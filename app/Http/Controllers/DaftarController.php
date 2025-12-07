<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\JenisKesenian;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DaftarController extends Controller
{
    /**
     * Halaman Formulir Pendaftaran (Multi-step)
     */
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        // 1. CEK STATUS UNTUK REDIRECT
        if ($organisasi) {
            $verifikasi = Verifikasi::where('organisasi_id', $organisasi->id)
                                    ->where('tipe', 'pengajuan')
                                    ->latest()
                                    ->first();

            $statusOrg = $organisasi->status;

            $isMenunggu = $verifikasi && $verifikasi->status == 'Menunggu Verifikasi';
            $isApproved = $statusOrg == 'Allow';
            $isDitolak  = $statusOrg == 'Denny'; // Tambahan logika agar tidak loop saat ditolak

            // Redirect HANYA JIKA Approved ATAU (Menunggu DAN TIDAK Ditolak)
            if ($isApproved || ($isMenunggu && !$isDitolak)) {
                return redirect()->route('user.selesai.index');
            }
        }

        // 2. DATA UNTUK FORM
        $jenisKesenian = JenisKesenian::whereNull('parent')->get();
        $kabupaten = DB::table('wilayah')->whereRaw('LENGTH(kode)=5')->get();

        $anggota = $organisasi ? $organisasi->anggota()->get() : collect();
        $inventaris = $organisasi ? $organisasi->inventaris()->get() : collect();

        // PERBAIKAN DI SINI: Ubah 'pendukung()' menjadi 'dataPendukung()'
        $pendukung = $organisasi ? $organisasi->dataPendukung()->get() : collect();

        $jumlahMaksAnggota = $organisasi->jumlah_anggota ?? 0;
        $jumlahSaatIni = $anggota->count();

        return view('user.daftar.index', compact(
            'jenisKesenian', 'kabupaten', 'organisasi', 'anggota',
            'jumlahMaksAnggota', 'jumlahSaatIni', 'inventaris', 'pendukung'
        ));
    }

    /**
     * Proses Kirim Data ke Admin (Submit)
     */
    public function submit(Request $request)
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

        // 1. Validasi Dokumen Wajib
        $dokumenWajib = ['KTP', 'PAS_FOTO', 'BANNER'];

        // PERBAIKAN DI SINI: Ubah 'pendukung()' menjadi 'dataPendukung()'
        $uploadedDocs = $organisasi->dataPendukung()->pluck('tipe')->toArray();

        $kurang = array_diff($dokumenWajib, $uploadedDocs);

        if (!empty($kurang)) {
            return back()->with('error', 'Dokumen belum lengkap. Harap upload: ' . implode(', ', $kurang))
                         ->with('tab', 'pendukung');
        }

        // Jika status sebelumnya 'Denny' (Ditolak), reset jadi NULL agar Admin tahu ini pengajuan revisi baru
        if ($organisasi->status == 'Denny') {
            $organisasi->update(['status' => null]);
        }

        // 2. SIMPAN KE TABEL VERIFIKASI
        Verifikasi::updateOrCreate(
            ['organisasi_id' => $organisasi->id, 'tipe' => 'pengajuan'],
            [
                'status' => 'Menunggu Verifikasi',
                'catatan' => null,
                'tanggal_review' => now()
            ]
        );

        return redirect()->route('user.selesai.index')->with('success', 'Data berhasil dikirim ke Admin!');
    }

    /**
     * Halaman Status (Selesai/Menunggu/Ditolak)
     */
    public function selesai()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()->route('user.daftar.index');
        }

        // Ambil record pengajuan
        $verifikasi = Verifikasi::where('organisasi_id', $organisasi->id)
                                ->where('tipe', 'pengajuan')
                                ->latest()
                                ->first();

        // MAPPING STATUS ADMIN KE VIEW USER
        if ($organisasi->status == 'Allow') {
            if (!$verifikasi) $verifikasi = new Verifikasi();
            $verifikasi->status = 'Approved';

        } elseif ($organisasi->status == 'Denny') {
            if (!$verifikasi) $verifikasi = new Verifikasi();
            $verifikasi->status = 'Ditolak';

            // Ambil catatan penolakan dari item yang tidak valid
            $itemRevisi = Verifikasi::where('organisasi_id', $organisasi->id)
                        ->where('status', 'tdk_valid')
                        ->latest()
                        ->first();

            if ($itemRevisi) {
                $verifikasi->catatan = "Perbaikan diperlukan pada " . $itemRevisi->tipe_formatted . ": " . $itemRevisi->catatan;
            } else {
                $verifikasi->catatan = "Data Anda ditolak oleh admin. Silakan cek kelengkapan data.";
            }
        }

        return view('user.selesai.index', compact('verifikasi'));
    }
}
