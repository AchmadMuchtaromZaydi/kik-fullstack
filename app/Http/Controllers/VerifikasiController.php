<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Organisasi;
use App\Models\Anggota;
use App\Models\Inventaris;
use App\Models\DataPendukung;
use App\Models\Verifikasi;
use App\Models\Wilayah;
use App\Models\JenisKesenian;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id, Request $request)
    {
        // ✅ OPTIMASI: Eager loading dengan relasi yang benar
        $organisasi = Organisasi::with([
            'jenisKesenianObj',
            'subKesenianObj',
            'anggota' => function($query) {
                $query->orderBy('jabatan', 'desc');
            },
            'kecamatan_relasi',  // ✅ Relasi yang sudah diperbaiki
            'desa_relasi',       // ✅ Relasi yang sudah diperbaiki
            'ketua',             // ✅ Relasi khusus ketua
            'inventaris',
            'dataPendukung'
        ])->findOrFail($id);

        // Enhance data organisasi (sekarang tanpa query tambahan)
        $this->enhanceOrganisasiData($organisasi);

        // Data sudah di-load via eager loading, tidak perlu query terpisah
        $inventaris = $organisasi->inventaris;
        $dataPendukung = $organisasi->dataPendukung;

        // Get existing verifikasi data
        $verifikasiData = Verifikasi::where('organisasi_id', $id)->get();

        $tabActive = $request->get('tab', 'general');

        return view('admin.verifikasi.show', compact(
            'organisasi',
            'inventaris',
            'dataPendukung',
            'verifikasiData',
            'tabActive'
        ));
    }

    /**
     * Enhance organisasi data dengan informasi tambahan - OPTIMIZED
     */
    private function enhanceOrganisasiData($organisasi)
    {
        // ✅ OPTIMASI: Gunakan data yang sudah di-load via eager loading
        $organisasi->nama_kecamatan = $organisasi->kecamatan_relasi->nama ?? '-';
        $organisasi->nama_desa = $organisasi->desa_relasi->nama ?? '-';

        // ✅ OPTIMASI: Ambil ketua dari data anggota yang sudah di-load
        $ketua = $organisasi->anggota->first(function($anggota) {
            return $anggota->jabatan === 'Ketua';
        });

        $organisasi->nama_ketua = $ketua ? $ketua->nama : '-';
        $organisasi->no_telp_ketua = $ketua ? ($ketua->telepon ?? $ketua->whatsapp ?? '-') : '-';

        // Data jenis/sub kesenian sudah ada di eager loading
        $organisasi->jenis_kesenian_nama = $organisasi->jenisKesenianObj->nama ?? '-';
        $organisasi->sub_kesenian_nama = $organisasi->subKesenianObj->nama ?? '-';

        // Status badge
        $organisasi->status_badge = $this->getStatusBadge($organisasi->status);
    }

    /**
     * Generate status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'Request' => '<span class="badge bg-warning">Menunggu</span>',
            'Allow' => '<span class="badge bg-success">Diterima</span>',
            'Denny' => '<span class="badge bg-danger">Ditolak</span>',
            'Pending' => '<span class="badge bg-info">Proses</span>',
            'DataLama' => '<span class="badge bg-secondary">Data Lama</span>'
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Preview Kartu untuk Modal - METHOD BARU
     */
    public function previewCard($id)
    {
        // ✅ OPTIMASI: Eager loading yang sama seperti show method
        $organisasi = Organisasi::with([
            'jenisKesenianObj',
            'subKesenianObj',
            'kecamatan_relasi',
            'desa_relasi',
            'ketua'
        ])->findOrFail($id);

        // Enhance data untuk kartu
        $this->enhanceOrganisasiData($organisasi);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $organisasi->id,
                'nomor_induk' => $organisasi->nomor_induk,
                'nama' => $organisasi->nama,
                'nama_jenis_kesenian' => $organisasi->nama_jenis_kesenian,
                'nama_ketua' => $organisasi->nama_ketua,
                'alamat' => $organisasi->alamat,
                'nama_kecamatan' => $organisasi->nama_kecamatan,
                'tanggal_expired' => $organisasi->tanggal_expired ? $organisasi->tanggal_expired->format('Y-m-d') : null,
                'tanggal_daftar' => $organisasi->tanggal_daftar ? $organisasi->tanggal_daftar->format('Y-m-d') : null,
            ]
        ]);
    }

    public function storeVerifikasi(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:data_organisasi,data_anggota,data_inventaris,data_pendukung',
            'status' => 'required|in:valid,tdk_valid',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string'
        ]);

        $verifikasi = Verifikasi::updateOrCreate(
            [
                'organisasi_id' => $id,
                'tipe' => $request->tipe
            ],
            [
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'catatan' => $request->catatan,
                'userid_review' => auth()->id(),
                'tanggal_review' => now()
            ]
        );

        return redirect()->route('admin.verifikasi.show', [
            'id' => $id,
            'tab' => $this->getNextTab($request->tipe)
        ])->with('success', 'Verifikasi berhasil disimpan.');
    }

    public function approve($id)
    {
        $organisasi = Organisasi::findOrFail($id);

        // Check if all verifikasi steps are valid
        $allValid = $this->checkAllVerifikasiValid($id);

        if (!$allValid) {
            return redirect()->route('admin.verifikasi.show', ['id' => $id, 'tab' => 'review'])
                ->with('error', 'Tidak dapat menyetujui karena ada data yang belum divalidasi atau tidak valid.');
        }

        // Generate nomor induk jika belum ada
        if (empty($organisasi->nomor_induk)) {
            $organisasi->nomor_induk = $this->generateUniqueNomorInduk($organisasi);
        }

        try {
            $organisasi->status = 'Allow';
            $organisasi->tanggal_expired = now()->addYears(1);
            $organisasi->save();

            // Update verified_by dan tanggal_verifikasi untuk semua verifikasi yang valid
            Verifikasi::where('organisasi_id', $id)
                ->where('status', 'valid')
                ->update([
                    'verified_by' => auth()->id(),
                    'tanggal_verifikasi' => now()
                ]);

            // Auto-validate data terkait
            Anggota::where('organisasi_id', $id)->update(['validasi' => 1]);
            Inventaris::where('organisasi_id', $id)->update(['validasi' => 1]);
            DataPendukung::where('organisasi_id', $id)->update(['validasi' => 1]);

            return redirect()->route('admin.verifikasi.show', [
                'id' => $id,
                'tab' => 'review'
            ])->with('success', 'Organisasi berhasil disetujui dan kartu induk telah dibuat.');

        } catch (QueryException $e) {
            // Jika terjadi duplicate entry, generate nomor induk baru
            if ($e->getCode() == 23000) {
                $organisasi->nomor_induk = $this->generateUniqueNomorInduk($organisasi, true);
                $organisasi->save();

                return redirect()->route('admin.verifikasi.show', [
                    'id' => $id,
                    'tab' => 'review'
                ])->with('success', 'Organisasi berhasil disetujui dengan nomor induk baru.');
            }
            throw $e;
        }
    }

    public function reject($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->status = 'Denny';
        $organisasi->save();

        return redirect()->route('admin.verifikasi.show', $id)
            ->with('success', 'Organisasi berhasil ditolak.');
    }

    public function generateCard($id)
    {
        $organisasi = Organisasi::with(['jenisKesenianObj', 'subKesenianObj', 'kecamatan'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.verifikasi.kartu', compact('organisasi'));

        // Ganti karakter "/" dengan "-" dalam nama file
        $safeNomorInduk = str_replace(['/', '\\'], '-', $organisasi->nomor_induk);
        $filename = 'kartu_kesenian_' . $safeNomorInduk . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate nomor induk dengan format: 430/18.5.31/429.110/2024
     * Format: [sequence]/[kode_kecamatan]/[kode_desa]/[tahun]
     */
    private function generateNomorInduk($organisasi)
    {
        $tahun = now()->format('Y');

        // ✅ OPTIMASI: Cache data wilayah
        $kodeKecamatan = $this->getFormattedKodeWilayah($organisasi->kecamatan);
        $kodeDesa = $this->getFormattedKodeWilayah($organisasi->desa);

        // ✅ OPTIMASI: Cache sequence number
        $sequence = $this->getNextSequence($tahun);

        return "{$sequence}/{$kodeKecamatan}/{$kodeDesa}/{$tahun}";
    }

    /**
     * Generate nomor induk yang unik
     */
    private function generateUniqueNomorInduk($organisasi, $forceNew = false)
    {
        $maxAttempts = 50;
        $attempt = 0;

        do {
            if ($attempt === 0 && !$forceNew) {
                $nomorInduk = $this->generateNomorInduk($organisasi);
            } else {
                // Untuk attempt selanjutnya, gunakan sequence yang berbeda
                $nomorInduk = $this->generateNomorIndukWithCustomSequence($organisasi, $attempt + 1);
            }

            $exists = Organisasi::where('nomor_induk', $nomorInduk)->exists();
            $attempt++;

        } while ($exists && $attempt < $maxAttempts);

        // Jika masih duplicate setelah max attempts, tambahkan timestamp
        if ($exists) {
            $timestamp = now()->format('His');
            $tahun = now()->format('Y');
            $nomorInduk = "{$timestamp}/{$this->getFormattedKodeWilayah($organisasi->kecamatan)}/{$this->getFormattedKodeWilayah($organisasi->desa)}/{$tahun}";
        }

        return $nomorInduk;
    }

    /**
     * Generate nomor induk dengan sequence custom
     */
    private function generateNomorIndukWithCustomSequence($organisasi, $sequence)
    {
        $tahun = now()->format('Y');
        $kodeKecamatan = $this->getFormattedKodeWilayah($organisasi->kecamatan);
        $kodeDesa = $this->getFormattedKodeWilayah($organisasi->desa);

        return "{$sequence}/{$kodeKecamatan}/{$kodeDesa}/{$tahun}";
    }

    /**
     * Format kode wilayah dari format database ke format nomor induk
     * ✅ OPTIMASI: Ditambahkan caching
     */
    private function getFormattedKodeWilayah($kodeWilayah)
    {
        if (!$kodeWilayah) {
            return '00.00.00';
        }

        // ✅ OPTIMASI: Cache hasil formatting
        return Cache::remember("formatted_wilayah_{$kodeWilayah}", 3600, function() use ($kodeWilayah) {
            // Split kode wilayah
            $parts = explode('.', $kodeWilayah);

            if (count($parts) >= 4) {
                // Format asli: 35.10.01.1001
                // Format yang diinginkan: 18.5.31
                $kabupaten = intval($parts[1] ?? 0); // 10
                $kecamatan = intval($parts[2] ?? 0); // 01 -> 1
                $desa = intval($parts[3] ?? 0); // 1001 -> 1001

                // Konversi ke format yang diinginkan
                $formattedKabupaten = $this->mapKabupatenCode($kabupaten);
                $formattedKecamatan = $kecamatan;
                $formattedDesa = $this->mapDesaCode($desa);

                return "{$formattedKabupaten}.{$formattedKecamatan}.{$formattedDesa}";
            }

            return '00.00.00';
        });
    }

    /**
     * Mapping kode kabupaten
     */
    private function mapKabupatenCode($kode)
    {
        $mapping = [
            10 => 18,  // Banyuwangi -> 18
            // Tambahkan mapping lainnya sesuai kebutuhan
        ];

        return $mapping[$kode] ?? $kode;
    }

    /**
     * Mapping kode desa
     */
    private function mapDesaCode($kode)
    {
        // Contoh: 1001 -> 31, 1002 -> 32, dst
        if ($kode >= 1000 && $kode <= 1999) {
            return $kode - 1000 + 31;
        }

        return $kode;
    }

    /**
     * Get next sequence number for the year
     * ✅ OPTIMASI: Ditambahkan caching
     */
    private function getNextSequence($tahun)
    {
        $cacheKey = "sequence_number_{$tahun}";

        // ✅ OPTIMASI: Cache sequence number untuk mengurangi query
        return Cache::remember($cacheKey, 300, function() use ($tahun) {
            // Cari nomor induk tertinggi untuk tahun ini
            $lastNomorInduk = Organisasi::where('nomor_induk', 'like', "%/{$tahun}")
                ->whereNotNull('nomor_induk')
                ->orderBy('nomor_induk', 'desc')
                ->first();

            if ($lastNomorInduk && $lastNomorInduk->nomor_induk) {
                // Extract sequence dari nomor induk: "430/18.5.31/429.110/2024" -> 430
                $parts = explode('/', $lastNomorInduk->nomor_induk);
                if (count($parts) >= 4) {
                    $lastSequence = intval($parts[0]);
                    return $lastSequence + 1;
                }
            }

            // Jika tidak ada data sebelumnya, mulai dari 1
            return 1;
        });
    }

    private function getNextTab($currentTab)
    {
        $tabs = [
            'general' => 'data_organisasi',
            'data_organisasi' => 'data_anggota',
            'data_anggota' => 'data_inventaris',
            'data_inventaris' => 'data_pendukung',
            'data_pendukung' => 'review'
        ];

        return $tabs[$currentTab] ?? 'general';
    }

    private function checkAllVerifikasiValid($organisasiId)
    {
        $requiredTabs = ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung'];

        $verifikasi = Verifikasi::where('organisasi_id', $organisasiId)
            ->whereIn('tipe', $requiredTabs)
            ->get();

        // Check if all required tabs have been verified
        if ($verifikasi->count() < count($requiredTabs)) {
            return false;
        }

        // Check if all verifications are valid
        return $verifikasi->every(function ($item) {
            return $item->status === 'valid';
        });
    }

    public function getVerifikasiStatus($organisasiId)
    {
        $verifikasiData = Verifikasi::where('organisasi_id', $organisasiId)->get();

        $status = [
            'data_organisasi' => 'belum_divalidasi',
            'data_anggota' => 'belum_divalidasi',
            'data_inventaris' => 'belum_divalidasi',
            'data_pendukung' => 'belum_divalidasi'
        ];

        foreach ($verifikasiData as $verifikasi) {
            $status[$verifikasi->tipe] = $verifikasi->status;
        }

        return $status;
    }

    /**
     * Get jenis kesenian untuk dropdown
     */
    public function getJenisKesenian()
    {
        // ✅ OPTIMASI: Cache data jenis kesenian
        return Cache::remember('jenis_kesenian_list', 3600, function() {
            return JenisKesenian::jenisUtama()->orderBy('nama')->get();
        });
    }

    /**
     * Get sub jenis kesenian berdasarkan parent
     */
    public function getSubKesenian($parentId)
    {
        // ✅ OPTIMASI: Cache data sub kesenian
        return Cache::remember("sub_kesenian_{$parentId}", 3600, function() use ($parentId) {
            return JenisKesenian::where('parent', $parentId)->orderBy('nama')->get();
        });
    }

    /**
     * Fix file paths for organisasi
     */
    public function fixFilePaths($id)
    {
        $organisasi = Organisasi::with('dataPendukung')->findOrFail($id);
        $fixedCount = 0;

        foreach ($organisasi->dataPendukung as $dokumen) {
            $oldPath = $dokumen->image;
            $newPath = 'uploads/organisasi/' . $organisasi->id . '/' . basename($oldPath);

            // Coba pindahkan file ke struktur yang benar
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $dokumen->image = $newPath;
                $dokumen->save();
                $fixedCount++;
            }
        }

        return response()->json([
            'message' => 'Fixed ' . $fixedCount . ' file paths',
            'organisasi_id' => $id
        ]);
    }

    /**
     * Fix data anggota untuk organisasi
     */
    public function fixAnggotaOrganisasi($id)
    {
        $organisasi = Organisasi::findOrFail($id);

        // Cari anggota yang mungkin terkait
        $potentialAnggota = Anggota::whereNull('organisasi_id')
            ->orWhere('organisasi_id', 0)
            ->get();

        $matchedCount = 0;

        foreach ($potentialAnggota as $anggota) {
            if ($this->isAnggotaMatchOrganisasi($anggota, $organisasi)) {
                $anggota->organisasi_id = $id;
                $anggota->save();
                $matchedCount++;
            }
        }

        return redirect()->back()
            ->with('success', "Berhasil match {$matchedCount} anggota ke organisasi {$organisasi->nama}");
    }

    /**
     * Logic matching antara anggota dan organisasi
     */
    private function isAnggotaMatchOrganisasi($anggota, $organisasi)
    {
        $organisasiName = strtolower($organisasi->nama);
        $searchIn = strtolower($anggota->nama . ' ' . $anggota->alamat);

        // Kata kunci dari nama organisasi
        $keywords = array_filter(explode(' ', $organisasiName), function($word) {
            return strlen($word) > 3;
        });

        $matchCount = 0;
        foreach ($keywords as $keyword) {
            if (strpos($searchIn, $keyword) !== false) {
                $matchCount++;
            }
        }

        // Jika minimal 1 keyword match, anggap terkait
        return $matchCount >= 1;
    }
    // Di VerifikasiController.php - tambahkan method ini
    public function previewKartu($id)
    {
        $organisasi = Organisasi::with([
            'jenisKesenianObj',
            'subKesenianObj',
            'kecamatan_relasi',
            'desa_relasi',
            'ketua'
        ])->findOrFail($id);

        $this->enhanceOrganisasiData($organisasi);

        return view('admin.verifikasi.preview-kartu', compact('organisasi'));
    }
}
