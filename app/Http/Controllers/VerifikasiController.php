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

        if (empty($organisasi->nomor_induk)) {
            $organisasi->nomor_induk = $this->generateUniqueNomorInduk($organisasi);
        }

        try {
            DB::transaction(function() use ($organisasi, $id) {
                $organisasi->update([
                    'status' => 'Allow',
                    'tanggal_expired' => now()->addYear()
                ]);

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
    private function generateNomorInduk($org)
    {
        $tahun = now()->year;
        $kodeKec = $this->getFormattedKodeWilayah($org->kecamatan);
        $kodeDesa = $this->getFormattedKodeWilayah($org->desa);
        $seq = $this->getNextSequence($tahun);
        return "{$seq}/{$kodeKec}/{$kodeDesa}/{$tahun}";
    }

    private function generateUniqueNomorInduk($org, $forceNew=false)
    {
        $attempt = 0;
        do {
            $nomor = $attempt===0 && !$forceNew
                ? $this->generateNomorInduk($org)
                : $this->generateNomorIndukWithCustomSequence($org, $attempt+1);
            $exists = Organisasi::where('nomor_induk',$nomor)->exists();
            $attempt++;
        } while ($exists && $attempt < 30);

        if ($exists) {
            $time = now()->format('His');
            $tahun = now()->year;
            $nomor = "{$time}/{$this->getFormattedKodeWilayah($org->kecamatan)}/{$this->getFormattedKodeWilayah($org->desa)}/{$tahun}";
        }

        return $nomor;
    }

    private function generateNomorIndukWithCustomSequence($org, $seq)
    {
        $tahun = now()->year;
        return "{$seq}/{$this->getFormattedKodeWilayah($org->kecamatan)}/{$this->getFormattedKodeWilayah($org->desa)}/{$tahun}";
    }

    private function getFormattedKodeWilayah($kode)
    {
        if (!$kode) return '00.00.00';

        return Cache::remember("formatted_wilayah_{$kode}", 3600, function() use ($kode) {
            $p = explode('.', $kode);
            if (count($p) >= 4) {
                $kab = intval($p[1]??0);
                $kec = intval($p[2]??0);
                $des = intval($p[3]??0);
                return "{$this->mapKabupatenCode($kab)}.{$kec}.{$this->mapDesaCode($des)}";
            }
            return '00.00.00';
        });
    }

    private function mapKabupatenCode($kode)
    {
        return [10 => 18][$kode] ?? $kode;
    }

    private function mapDesaCode($kode)
    {
        return ($kode >= 1000 && $kode <= 1999)
            ? $kode - 1000 + 31
            : $kode;
    }

    private function getNextSequence($tahun)
    {
        return Cache::remember("sequence_number_{$tahun}", 300, function() use ($tahun) {
            $last = Organisasi::where('nomor_induk','like',"%/{$tahun}")
                ->whereNotNull('nomor_induk')
                ->orderByDesc('nomor_induk')
                ->first();
            if ($last && $last->nomor_induk) {
                $p = explode('/', $last->nomor_induk);
                return intval($p[0] ?? 0) + 1;
            }
            return 1;
        });
    }

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
