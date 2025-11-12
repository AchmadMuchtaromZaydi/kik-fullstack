<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use App\Models\{
    Organisasi,
    Anggota,
    Inventaris,
    DataPendukung,
    Verifikasi,
    Wilayah,
    JenisKesenian
};

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* =========================================================
     =============== HALAMAN DETAIL VERIFIKASI ===============
     ========================================================= */
    public function show($id, Request $request)
    {
        set_time_limit(120);

        try {
            $organisasi = Organisasi::query()
                ->select([
                    'id','nama','nomor_induk','jenis_kesenian','nama_jenis_kesenian',
                    'sub_kesenian','nama_sub_kesenian','nama_ketua','no_telp_ketua',
                    'alamat','desa','kecamatan','nama_kecamatan','jumlah_anggota',
                    'status','tanggal_berdiri','tanggal_daftar','tanggal_expired'
                ])
                ->with([
                    'jenisKesenianObj:id,nama',
                    'subKesenianObj:id,nama',
                    'kecamatanWilayah:kode,nama',
                    'desaWilayah:kode,nama',
                    'ketua:id,organisasi_id,nama,telepon,whatsapp',
                    'anggota:id,organisasi_id,nik,nama,jabatan,telepon,whatsapp,tanggal_lahir,pekerjaan,alamat,foto,jenis_kelamin',
                    'inventaris:id,organisasi_id,nama,jumlah,pembelian_th,kondisi,keterangan,validasi',
                    'dataPendukung:id,organisasi_id,image,tipe,validasi',
                    'verifikasi'
                ])
                ->findOrFail($id);

            $verifikasiData = Cache::remember("verifikasi_data_{$id}", 300, function() use ($id) {
                return Verifikasi::select('id','organisasi_id','tipe','status','keterangan','catatan','userid_review','tanggal_review')
                    ->where('organisasi_id', $id)
                    ->get();
            });

            $jabatanOrder = [
                'Ketua' => 1,
                'Wakil Ketua' => 2,
                'Sekretaris' => 3,
                'Bendahara' => 4,
                'Anggota' => 5,
            ];

            $anggota_terurut = $organisasi->anggota->sortBy(function ($item) use ($jabatanOrder) {
                return $jabatanOrder[$item->jabatan] ?? 99;
            });

            $tabActive = $request->get('tab', 'general');

            return view('admin.verifikasi.show', compact(
                'organisasi',
                'verifikasiData',
                'tabActive',
                'anggota_terurut'
            ));
        } catch (\Throwable $e) {
            Log::error('VerifikasiController@show error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /* =========================================================
     ================== SIMPAN VERIFIKASI ====================
     ========================================================= */
    public function storeVerifikasi(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:data_organisasi,data_anggota,data_inventaris,data_pendukung',
            'status' => 'required|in:valid,tdk_valid',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        Verifikasi::updateOrCreate(
            ['organisasi_id' => $id, 'tipe' => $request->tipe],
            [
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'catatan' => $request->catatan,
                'userid_review' => auth()->id(),
                'tanggal_review' => now(),
            ]
        );

        Cache::forget("verifikasi_data_{$id}");

        return redirect()->route('admin.verifikasi.show', [
            'id' => $id,
            'tab' => $this->getNextTab($request->tipe)
        ])->with('success', 'Verifikasi berhasil disimpan.');
    }

    /* =========================================================
     ==================== APPROVE ORGANISASI =================
     ========================================================= */
    public function approve($id)
    {
        $organisasi = Organisasi::findOrFail($id);

        if (!$this->checkAllVerifikasiValid($id)) {
            return redirect()->route('admin.verifikasi.show', ['id'=>$id,'tab'=>'review'])
                ->with('error','Tidak dapat menyetujui karena ada data yang belum divalidasi atau tidak valid.');
        }

        // 1. FORMAT NOMOR INDUK (Sesuai backend API)
        if (empty($organisasi->nomor_induk)) {
            $carbonInstance = Carbon::now();
            $year = $carbonInstance->year;
            // Menggunakan format dari Api/ValidasiController.php
            $organisasi->nomor_induk = "430/" . $organisasi->id . '.' . $organisasi->jenis_kesenian . '.' . $organisasi->sub_kesenian . "/429.110/"  . $year;
        }

        try {
            DB::transaction(function() use ($organisasi, $id) {

                $organisasi->status = 'Allow';

                // 2. FORMAT TANGGAL EXPIRED (Sesuai backend API)
                // Diubah dari addYear() menjadi addYears(2)
                $organisasi->tanggal_expired = now()->addYears(2);

                $organisasi->save(); // Menyimpan perubahan nomor induk, status, dan expired

                Verifikasi::where('organisasi_id',$id)
                    ->where('status','valid')
                    ->update([
                        'verified_by' => auth()->id(),
                        'tanggal_verifikasi' => now()
                    ]);

                Anggota::where('organisasi_id',$id)->update(['validasi'=>1]);
                Inventaris::where('organisasi_id',$id)->update(['validasi'=>1]);
                DataPendukung::where('organisasi_id',$id)->update(['validasi'=>1]);
            });

            Cache::forget("verifikasi_data_{$id}");

            return redirect()->route('admin.verifikasi.show',['id'=>$id,'tab'=>'review'])
                ->with('success','Organisasi berhasil disetujui dan kartu induk telah dibuat.');

        } catch (\Throwable $e) {
            Log::error('VerifikasiController@approve error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /* =========================================================
     ===================== REJECT ORGANISASI =================
     ========================================================= */
    public function reject($id)
    {
        Organisasi::where('id',$id)->update(['status'=>'Denny']);
        Cache::forget("verifikasi_data_{$id}");
        return redirect()->route('admin.verifikasi.show',$id)
            ->with('success','Organisasi berhasil ditolak.');
    }

    /* =========================================================
     ================== GENERATE KARTU (PDF) =================
     ========================================================= */
    public function generateCard($id)
    {
        $organisasi = Organisasi::with(['jenisKesenianObj','subKesenianObj','kecamatanWilayah'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('admin.verifikasi.kartu', compact('organisasi'));
        $safe = str_replace(['/', '\\'], '-', $organisasi->nomor_induk);
        return $pdf->download("kartu_kesenian_{$safe}.pdf");
    }

    /* =========================================================
     ============== GENERATE KARTU (FORMAT PNG) ==============
     ========================================================= */
    public function generateImageCard($id)
    {
        try {
            $org = Organisasi::findOrFail($id);
            $folder = public_path("storage/uploads/organisasi/{$org->id}");
            if (!File::exists($folder)) File::makeDirectory($folder, 0775, true);

            $template = public_path('images/contoh-1.jpeg');
            $qr = public_path('images/qrcode.png');

            if (!File::exists($template)) return response()->json(['error'=>'Template kartu tidak ditemukan'],500);
            if (!File::exists($qr)) return response()->json(['error'=>'QR Code tidak ditemukan'],500);

            $img = Image::make($template);
            $qrImg = Image::make($qr)->resize(150, 150);
            $img->insert($qrImg, 'bottom-right', 60, 50);

            // Tambah teks ke kartu (posisi disesuaikan dengan contoh)
            $img->text($org->nama_ketua ?? '-', 450, 220, function ($font) {
                $font->file(public_path('fonts/OpenSans-Bold.ttf'));
                $font->size(42);
                $font->color('#0B2E83');
            });

            $img->text($org->nama ?? '-', 450, 270, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(28);
                $font->color('#C70000');
            });

            $img->text($org->nomor_induk ?? '-', 450, 320, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(30);
                $font->color('#222');
            });

            $alamat = "{$org->alamat}, {$org->desa}, {$org->nama_kecamatan}, Banyuwangi";
            $img->text($alamat, 450, 380, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(26);
                $font->color('#002C72');
            });

            $masa = "aktif sampai " . ($org->tanggal_expired ? Carbon::parse($org->tanggal_expired)->format('d.m.Y') : '12.12.2025');
            $img->text($masa, 860, 720, function ($font) {
                $font->file(public_path('fonts/OpenSans-Regular.ttf'));
                $font->size(22);
                $font->color('#000');
            });

            $file = "{$folder}/kartu_induk_{$org->id}.png";
            $img->save($file);

            return response()->json(['success'=>true,'path'=>"storage/uploads/organisasi/{$org->id}/kartu_induk_{$org->id}.png"]);
        } catch (\Throwable $e) {
            Log::error("Gagal generate kartu: ".$e->getMessage());
            return response()->json(['error'=>'Gagal membuat kartu.'],500);
        }
    }

    /* =========================================================
     ================== PREVIEW KARTU (PNG) ==================
     ========================================================= */
    public function previewKartu($id)
    {
        $org = Organisasi::findOrFail($id);
        $template = public_path('images/contoh-1.jpeg');
        $qr = public_path('images/qrcode.png');

        $img = Image::make($template);
        $qrImg = Image::make($qr)->resize(150, 150);
        $img->insert($qrImg, 'bottom-right', 60, 50);

        $img->text($org->nama_ketua ?? '-', 450, 220, function ($font) {
            $font->file(public_path('fonts/OpenSans-Bold.ttf'));
            $font->size(42);
            $font->color('#0B2E83');
        });

        $img->text($org->nama ?? '-', 450, 270, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(28);
            $font->color('#C70000');
        });

        $img->text($org->nomor_induk ?? '-', 450, 320, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(30);
            $font->color('#222');
        });

        $alamat = "{$org->alamat}, {$org->desa}, {$org->nama_kecamatan}, Banyuwangi";
        $img->text($alamat, 450, 380, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(26);
            $font->color('#002C72');
        });

        $masa = "aktif sampai " . ($org->tanggal_expired ? Carbon::parse($org->tanggal_expired)->format('d.m.Y') : '12.12.2025');
        $img->text($masa, 860, 720, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(22);
            $font->color('#000');
        });

        return $img->response('png');
    }

    /* =========================================================
     ==================== FUNGSI UTILITAS ====================
     ========================================================= */

    // Fungsi-fungsi private untuk format 'seq/kec/desa/tahun' telah dihapus
    // karena kita sekarang menggunakan format '430/...'

    private function getNextTab($current)
    {
        return [
            'general'=>'data_organisasi',
            'data_organisasi'=>'data_anggota',
            'data_anggota'=>'data_inventaris',
            'data_inventaris'=>'data_pendukung',
            'data_pendukung'=>'review'
        ][$current] ?? 'general';
    }

    private function checkAllVerifikasiValid($orgId)
    {
        $types = ['data_organisasi','data_anggota','data_inventaris','data_pendukung'];
        $verifikasi = Verifikasi::select('tipe','status')
            ->where('organisasi_id',$orgId)
            ->whereIn('tipe',$types)
            ->get();

        if ($verifikasi->count() < count($types)) return false;
        return $verifikasi->every(fn($v)=>$v->status==='valid');
    }
}
