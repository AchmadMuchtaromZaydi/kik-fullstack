<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
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

    /**
     * Show detail verifikasi
     * Optimized: limited selects, eager loads, no N+1
     */
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
                    'anggota:id,organisasi_id,nik,nama,jabatan,telepon,whatsapp',
                    'inventaris:id,organisasi_id,nama,jumlah,pembelian_th,kondisi,keterangan,validasi',
                    // FIX: removed 'deskripsi' (DB doesn't have that column)
                    'dataPendukung:id,organisasi_id,image,tipe,validasi',
                ])
                ->findOrFail($id);

            // cache verifikasi result (small TTL)
            $verifikasiData = Cache::remember("verifikasi_data_{$id}", 300, function() use ($id) {
                return Verifikasi::select('id','organisasi_id','tipe','status','keterangan','catatan')
                    ->where('organisasi_id', $id)
                    ->get();
            });

            // build debug info for view to avoid undefined variable
            $debugInfo = [
                'storage_base_path' => storage_path('app'),
                'storage_public_path' => storage_path('app/public'),
                'public_path' => public_path(),
                'organisasi_uploads_dir' => storage_path('app/public/uploads/organisasi/' . $id),
                'dokumen_counts' => [
                    'ktp' => $organisasi->dokumen_ktp ? 1 : 0,
                    'pas_foto' => $organisasi->dokumen_pas_foto ? 1 : 0,
                    'banner' => $organisasi->dokumen_banner ? 1 : 0,
                    'kegiatan' => $organisasi->dokumen_kegiatan ? $organisasi->dokumen_kegiatan->count() : 0,
                ],
                'timestamp' => now()->toDateTimeString(),
            ];

            $tabActive = $request->get('tab', 'general');

            return view('admin.verifikasi.show', compact('organisasi','verifikasiData','tabActive','debugInfo'));

        } catch (\Throwable $e) {
            Log::error('VerifikasiController@show error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Preview card (for modal)
     */
    public function previewCard($id)
    {
        try {
            $organisasi = Organisasi::select([
                    'id','nama','nomor_induk','nama','nama_ketua',
                    'alamat','kecamatan','tanggal_expired','tanggal_daftar'
                ])
                ->with(['kecamatanWilayah:kode,nama'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $organisasi->id,
                    'nomor_induk' => $organisasi->nomor_induk,
                    'nama' => $organisasi->nama,
                    'nama_jenis_kesenian' => $organisasi->nama_jenis_kesenian,
                    'nama_ketua' => $organisasi->nama_ketua,
                    'alamat' => $organisasi->alamat,
                    'nama_kecamatan' => $organisasi->kecamatanWilayah->nama ?? '-',
                    'tanggal_expired' => optional($organisasi->tanggal_expired)->format('Y-m-d'),
                    'tanggal_daftar' => optional($organisasi->tanggal_daftar)->format('Y-m-d'),
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('previewCard error: '.$e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store / update verifikasi
     */
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

        // clear cache
        Cache::forget("verifikasi_data_{$id}");

        return redirect()->route('admin.verifikasi.show', [
            'id' => $id,
            'tab' => $this->getNextTab($request->tipe)
        ])->with('success', 'Verifikasi berhasil disimpan.');
    }

    /**
     * Approve organisasi if all valid
     */
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

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $organisasi->nomor_induk = $this->generateUniqueNomorInduk($organisasi, true);
                $organisasi->save();

                return redirect()->route('admin.verifikasi.show',['id'=>$id,'tab'=>'review'])
                    ->with('success','Organisasi berhasil disetujui dengan nomor induk baru.');
            }
            throw $e;
        }
    }

    public function reject($id)
    {
        Organisasi::where('id',$id)->update(['status'=>'Denny']);
        Cache::forget("verifikasi_data_{$id}");
        return redirect()->route('admin.verifikasi.show',$id)
            ->with('success','Organisasi berhasil ditolak.');
    }

    public function generateCard($id)
    {
        $organisasi = Organisasi::with(['jenisKesenianObj','subKesenianObj','kecamatanWilayah'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('admin.verifikasi.kartu', compact('organisasi'));
        $safe = str_replace(['/', '\\'], '-', $organisasi->nomor_induk);
        return $pdf->download("kartu_kesenian_{$safe}.pdf");
    }

    /* ===== UTILITIES ===== */

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

    public function getVerifikasiStatus($orgId)
    {
        $verifikasi = Verifikasi::select('tipe','status')->where('organisasi_id',$orgId)->get();
        $status = array_fill_keys(['data_organisasi','data_anggota','data_inventaris','data_pendukung'],'belum_divalidasi');
        foreach ($verifikasi as $v) $status[$v->tipe] = $v->status;
        return $status;
    }

    public function getJenisKesenian()
    {
        return Cache::remember('jenis_kesenian_all',3600,fn()=>
            JenisKesenian::jenisUtama()->select('id','nama')->orderBy('nama')->get()
        );
    }

    public function getSubKesenian($parent)
    {
        return Cache::remember("sub_kesenian_{$parent}",3600,fn()=>
            JenisKesenian::where('parent',$parent)->select('id','nama')->orderBy('nama')->get()
        );
    }

    /**
     * Fix file paths for organisasi
     */
    public function fixFilePaths($id)
    {
        $org = Organisasi::with('dataPendukung')->findOrFail($id);
        $count=0;
        foreach ($org->dataPendukung as $dok) {
            $old = $dok->image;
            $new = "uploads/organisasi/{$org->id}/".basename($old);
            if (Storage::disk('public')->exists($old)) {
                Storage::disk('public')->move($old,$new);
                $dok->update(['image'=>$new]);
                $count++;
            }
        }
        return response()->json(['message'=>"Fixed {$count} file paths",'organisasi_id'=>$id]);
    }

    public function fixAnggotaOrganisasi($id)
    {
        $org = Organisasi::findOrFail($id);
        $c=0;
        $pot = Anggota::whereNull('organisasi_id')->orWhere('organisasi_id',0)->get();
        foreach ($pot as $a) {
            if ($this->isAnggotaMatchOrganisasi($a,$org)) {
                $a->update(['organisasi_id'=>$id]);
                $c++;
            }
        }
        return back()->with('success',"Berhasil match {$c} anggota ke organisasi {$org->nama}");
    }

    private function isAnggotaMatchOrganisasi($a,$org)
    {
        $orgName = strtolower($org->nama);
        $search = strtolower(($a->nama ?? '').' '.($a->alamat ?? ''));
        $keywords = array_filter(explode(' ',$orgName),fn($w)=>strlen($w)>3);
        $match = 0;
        foreach ($keywords as $word) if (strpos($search,$word)!==false) $match++;
        return $match >= 1;
    }

    /**
     * API: status check for storage (used by JS checkStorage())
     */
    public function status($id)
    {
        $organisasi = Organisasi::with('dataPendukung')->findOrFail($id);

        $result = [
            'ktp' => null,
            'pas_foto' => null,
            'banner' => null,
            'kegiatan' => [],
            'checked_at' => now()->toDateTimeString(),
        ];

        if ($organisasi->dokumen_ktp) {
            $result['ktp'] = [
                'original' => $organisasi->dokumen_ktp->image,
                'resolved' => $organisasi->getFilePath($organisasi->dokumen_ktp),
                'exists' => $organisasi->getFileExists($organisasi->dokumen_ktp),
                'url' => $organisasi->getFileUrl($organisasi->dokumen_ktp),
            ];
        }

        if ($organisasi->dokumen_pas_foto) {
            $result['pas_foto'] = [
                'original' => $organisasi->dokumen_pas_foto->image,
                'resolved' => $organisasi->getFilePath($organisasi->dokumen_pas_foto),
                'exists' => $organisasi->getFileExists($organisasi->dokumen_pas_foto),
                'url' => $organisasi->getFileUrl($organisasi->dokumen_pas_foto),
            ];
        }

        if ($organisasi->dokumen_banner) {
            $result['banner'] = [
                'original' => $organisasi->dokumen_banner->image,
                'resolved' => $organisasi->getFilePath($organisasi->dokumen_banner),
                'exists' => $organisasi->getFileExists($organisasi->dokumen_banner),
                'url' => $organisasi->getFileUrl($organisasi->dokumen_banner),
            ];
        }

        foreach ($organisasi->dokumen_kegiatan as $foto) {
            $result['kegiatan'][] = [
                'original' => $foto->image,
                'resolved' => $organisasi->getFilePath($foto),
                'exists' => $organisasi->getFileExists($foto),
                'url' => $organisasi->getFileUrl($foto),
            ];
        }

        return response()->json($result);
    }
}
