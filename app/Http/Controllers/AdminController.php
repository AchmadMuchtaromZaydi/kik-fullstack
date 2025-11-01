<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalAnggota' => 128,
            'totalOrganisasi' => 42,
            'totalKesenian' => 63,
            'menungguValidasi' => 9,
            'aktivitasTerbaru' => [
                [
                    'nama' => 'Paguyuban Tari Anggrek',
                    'aksi' => 'Menambahkan data kesenian baru',
                    'tanggal' => '1 November 2025',
                ],
                [
                    'nama' => 'Organisasi Karawitan Budaya',
                    'aksi' => 'Upload dokumen pendukung',
                    'tanggal' => '30 Oktober 2025',
                ],
                [
                    'nama' => 'Admin Dinas',
                    'aksi' => 'Memvalidasi 2 data baru',
                    'tanggal' => '29 Oktober 2025',
                ],
            ]
        ]);
    }
}
