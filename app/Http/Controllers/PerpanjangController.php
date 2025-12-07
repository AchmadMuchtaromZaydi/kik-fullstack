<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Auth;

class PerpanjangController extends Controller
{
    /**
     * Tampilkan halaman form cek kartu
     */
    public function index()
    {
        // Langsung tampilkan view tanpa cek apakah user sudah punya organisasi
        return view('user.perpanjang.index');
    }

    /**
     * Proses pencarian dan klaim data lama
     */
    public function check(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nomor_kartu' => 'required|string',
            'nama_ketua'  => 'required|string',
        ]);

        // 2. Cek apakah user saat ini sudah memiliki organisasi?
        // Logic ini tetap disimpan untuk keamanan saat tombol 'CARI' ditekan,
        // agar data user yang sudah ada tidak tertimpa/konflik.
        $currentUserOrg = Organisasi::where('user_id', Auth::id())->first();
        if ($currentUserOrg) {
            return redirect()->route('user.daftar.index')
                ->with('error', 'Akun Anda sudah terhubung dengan data organisasi lain.');
        }

        // 3. Cari Data Organisasi Lama (Berdasarkan No Induk & Nama Ketua)
        // Menggunakan 'LIKE' agar pencarian nama tidak case-sensitive
        $organisasi = Organisasi::where('nomor_induk', $request->nomor_kartu)
                                ->where('nama_ketua', 'LIKE', $request->nama_ketua)
                                ->first();

        // 4. Jika Data Tidak Ditemukan
        if (!$organisasi) {
            return back()
                ->withErrors(['not_found' => 'Data tidak ditemukan. Pastikan Nomor Kartu dan Nama Ketua sesuai dengan data lama.'])
                ->withInput();
        }

        // 5. Cek Validasi Kepemilikan (Apakah sudah diklaim orang lain?)
        if ($organisasi->user_id && $organisasi->user_id != Auth::id()) {
            return back()
                ->withErrors(['not_found' => 'Kartu ini sudah diklaim/terdaftar pada akun pengguna lain.'])
                ->withInput();
        }

        // 6. PROSES KLAIM (Hubungkan Data Lama ke User Saat Ini)
        try {
            $organisasi->user_id = Auth::id();

            // Opsional: Reset status agar masuk flow verifikasi ulang jika diperlukan
            // $organisasi->status = null;

            $organisasi->save();

            // Redirect ke halaman pendaftaran agar user bisa melihat/mengedit data yang baru ditemukan
            return redirect()->route('user.daftar.index')
                ->with('success', 'Data berhasil ditemukan! Silakan lengkapi data terbaru untuk proses perpanjangan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
