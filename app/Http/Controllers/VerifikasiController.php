<?php
// app/Http/Controllers/VerifikasiController.php

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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id, Request $request)
    {
        $organisasi = Organisasi::with([
            'jenisKesenianObj',
            'subKesenianObj',
            'anggota' => function($query) {
                $query->orderBy('jabatan', 'desc');
            },
            'kecamatan',
            'desa',
            'inventaris',
            'dataPendukung'
        ])->findOrFail($id);

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

        // Get kode wilayah untuk kecamatan dan desa
        $kodeKecamatan = $this->getFormattedKodeWilayah($organisasi->kecamatan);
        $kodeDesa = $this->getFormattedKodeWilayah($organisasi->desa);

        // Generate sequence berdasarkan tahun
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
     * Contoh: "35.10.01.1001" -> "18.5.31"
     */
    private function getFormattedKodeWilayah($kodeWilayah)
    {
        if (!$kodeWilayah) {
            return '00.00.00';
        }

        // Split kode wilayah
        $parts = explode('.', $kodeWilayah);

        if (count($parts) >= 4) {
            // Format asli: 35.10.01.1001
            // Format yang diinginkan: 18.5.31
            $kabupaten = intval($parts[1] ?? 0); // 10
            $kecamatan = intval($parts[2] ?? 0); // 01 -> 1
            $desa = intval($parts[3] ?? 0); // 1001 -> 1001

            // Konversi ke format yang diinginkan
            // Anda bisa menyesuaikan mapping ini sesuai kebutuhan
            $formattedKabupaten = $this->mapKabupatenCode($kabupaten);
            $formattedKecamatan = $kecamatan; // Tetap sama
            $formattedDesa = $this->mapDesaCode($desa);

            return "{$formattedKabupaten}.{$formattedKecamatan}.{$formattedDesa}";
        }

        return '00.00.00';
    }

    /**
     * Mapping kode kabupaten
     * Sesuaikan dengan mapping yang sesuai untuk wilayah Anda
     */
    private function mapKabupatenCode($kode)
    {
        // Contoh mapping, sesuaikan dengan kebutuhan
        $mapping = [
            10 => 18,  // Banyuwangi -> 18
            // Tambahkan mapping lainnya sesuai kebutuhan
        ];

        return $mapping[$kode] ?? $kode;
    }

    /**
     * Mapping kode desa
     * Untuk menyederhanakan kode desa yang panjang
     */
    private function mapDesaCode($kode)
    {
        // Contoh: 1001 -> 31, 1002 -> 32, dst
        // Ini contoh sederhana, sesuaikan dengan kebutuhan
        if ($kode >= 1000 && $kode <= 1999) {
            return $kode - 1000 + 31;
        }

        return $kode;
    }

    /**
     * Get next sequence number for the year
     */
    private function getNextSequence($tahun)
    {
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
        return JenisKesenian::jenisUtama()->orderBy('nama')->get();
    }

    /**
     * Get sub jenis kesenian berdasarkan parent
     */
    public function getSubKesenian($parentId)
    {
        return JenisKesenian::where('parent', $parentId)->orderBy('nama')->get();
    }

    public function checkStorage($id)
{
    $organisasi = Organisasi::findOrFail($id);

    $storageInfo = [
        'base_path' => storage_path('app'),
        'public_path' => public_path(),
        'storage_url' => Storage::url('test'),
    ];

    $fileInfo = [];

    // Check KTP
    if ($organisasi->dokumen_ktp) {
        $fileInfo['ktp'] = [
            'original_path' => $organisasi->dokumen_ktp->image,
            'resolved_path' => $organisasi->getFilePath($organisasi->dokumen_ktp),
            'exists' => $organisasi->getFileExists($organisasi->dokumen_ktp),
            'url' => $organisasi->getFileUrl($organisasi->dokumen_ktp),
        ];
    }

    // Check other files similarly...

    return response()->json([
        'storage' => $storageInfo,
        'files' => $fileInfo
    ]);
}
}
