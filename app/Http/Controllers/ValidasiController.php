<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\Anggota;
use App\Models\Inventaris;
use App\Models\DataPendukung;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data organisasi milik user
        $organisasi = Organisasi::where('user_id', $user->id)->first();

        if (!$organisasi) {
            return redirect()->route('user.organisasi.create')
                ->with('warning', 'Silakan isi data organisasi terlebih dahulu.');
        }

        // Cek apakah semua data sudah lengkap
        $anggota = \App\Models\Anggota::where('organisasi_id', $organisasi->id)->count();
        $inventaris = \App\Models\Inventaris::where('organisasi_id', $organisasi->id)->count();
        $pendukung = \App\Models\DataPendukung::where('organisasi_id', $organisasi->id)->count();

        $lengkap = $anggota > 0 && $inventaris > 0 && $pendukung > 0;

        return view('user.validasi.index', compact('organisasi', 'lengkap'));
    }
}
