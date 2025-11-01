<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    // Menampilkan halaman daftar wilayah
    public function index()
    {
        // contoh dummy data (sementara, nanti diganti ambil dari tabel 'wilayah')
        $wilayah = [
            ['id' => 1, 'nama' => 'Kecamatan Bandung Wetan'],
            ['id' => 2, 'nama' => 'Kecamatan Sukajadi'],
            ['id' => 3, 'nama' => 'Kecamatan Coblong'],
        ];

        return view('wilayah.index', compact('wilayah'));
    }

    // Ambil semua wilayah (untuk dropdown dsb)
    public function getWilayahAll()
    {
        // contoh dummy data
        $wilayah = [
            ['id' => 1, 'nama' => 'Kecamatan Bandung Wetan'],
            ['id' => 2, 'nama' => 'Kecamatan Sukajadi'],
            ['id' => 3, 'nama' => 'Kecamatan Coblong'],
        ];

        return response()->json($wilayah);
    }

    // Cari wilayah berdasarkan nama
    public function getWilayahNama(Request $request)
    {
        $nama = $request->input('nama');

        // contoh dummy filter
        $result = collect([
            ['id' => 1, 'nama' => 'Kecamatan Bandung Wetan'],
            ['id' => 2, 'nama' => 'Kecamatan Sukajadi'],
            ['id' => 3, 'nama' => 'Kecamatan Coblong'],
        ])->filter(fn($w) => stripos($w['nama'], $nama) !== false)->values();

        return response()->json($result);
    }
}
