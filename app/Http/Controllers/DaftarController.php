<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\JenisKesenian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DaftarController extends Controller
{
     public function index()
    {
        // Dropdown untuk form organisasi
        $jenisKesenian = JenisKesenian::whereNull('parent')->get();
        $kabupaten = DB::table('wilayah')->whereRaw('LENGTH(kode)=5')->get();

        // Ambil organisasi milik user yang sedang login
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        // Pastikan semua data aman meskipun organisasi belum ada
        $anggota = $organisasi ? $organisasi->anggota()->get() : collect();
        $inventaris = $organisasi && method_exists($organisasi, 'inventaris')
            ? $organisasi->inventaris()->get()
            : collect();
        $pendukung = $organisasi && method_exists($organisasi, 'pendukung')
            ? $organisasi->pendukung()->get()
            : collect();

        // Jumlah anggota yang seharusnya dan yang sudah ada
        $jumlahMaksAnggota = $organisasi->jumlah_anggota ?? 0;
        $jumlahSaatIni = $anggota->count();

        // Kirim semua data ke view
        return view('user.daftar.index', compact(
            'jenisKesenian',
            'kabupaten',
            'organisasi',
            'anggota',
            'jumlahMaksAnggota',
            'jumlahSaatIni',
            'inventaris',
            'pendukung'
        ));
    }
}
