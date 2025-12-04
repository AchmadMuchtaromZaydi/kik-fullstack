<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\JenisKesenian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganisasiController extends Controller
{
    /**
     * Tampilkan form create/edit organisasi
     */
    public function create()
    {
        $jenisKesenian = JenisKesenian::whereNull('parent')->get();
        $kabupaten = DB::table('wilayah')
            ->whereRaw('LENGTH(kode) = 5')
            ->get();

        // Ambil data organisasi user jika sudah ada
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        return view('user.organisasi.create', compact('jenisKesenian', 'kabupaten', 'organisasi'));
    }

    /**
     * Ambil sub kesenian berdasarkan parent
     */
    public function getSubKesenian($parent_id)
    {
        $subKesenian = JenisKesenian::where('parent', $parent_id)
            ->get(['id', 'nama']);

        return response()->json($subKesenian);
    }

    /**
     * Ambil kecamatan berdasarkan kabupaten
     */
    public function getKecamatan($kabupatenKode)
    {
        $kecamatan = DB::table('wilayah')
            ->where('kode', 'LIKE', $kabupatenKode . '.%')
            ->whereRaw('LENGTH(kode) = 8')
            ->get(['kode', 'nama']);

        return response()->json($kecamatan);
    }

    /**
     * Ambil desa berdasarkan kecamatan
     */
    public function getDesa($kecamatanKode)
    {
        $desa = DB::table('wilayah')
            ->where('kode', 'LIKE', $kecamatanKode . '.%')
            ->whereRaw('LENGTH(kode) = 13')
            ->get(['kode', 'nama']);

        return response()->json($desa);
    }

    /**
     * Simpan atau update data organisasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_berdiri' => 'required|date',
            'jenis_kesenian' => 'required|integer',
            'sub_kesenian' => 'required|integer',
            'jumlah_anggota' => 'required|integer|min:1',
            'kabupaten_kode' => 'required|string',
            'kecamatan_kode' => 'required|string',
            'desa_kode' => 'required|string',
            'alamat_lengkap' => 'required|string|max:500',
        ]);

        $jenis = JenisKesenian::find($request->jenis_kesenian);
        $sub = JenisKesenian::find($request->sub_kesenian);

        if (!$jenis || !$sub) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis atau Sub Jenis Kesenian tidak valid.'
            ], 422);
        }

        $kabupaten = DB::table('wilayah')->where('kode', $request->kabupaten_kode)->value('nama');
        $kecamatan = DB::table('wilayah')->where('kode', $request->kecamatan_kode)->value('nama');
        $desa = DB::table('wilayah')->where('kode', $request->desa_kode)->value('nama');

        // Simpan atau update data berdasarkan user_id
        $organisasi = Organisasi::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nama' => $request->nama,
                'tanggal_berdiri' => $request->tanggal_berdiri,
                'jenis_kesenian' => $jenis->id,
                'sub_kesenian' => $sub->id,
                'nama_jenis_kesenian' => $jenis->nama,
                'nama_sub_kesenian' => $sub->nama,
                'jumlah_anggota' => $request->jumlah_anggota,
                'kabupaten' => $kabupaten,
                'kecamatan' => $kecamatan,
                'desa' => $desa,
                'alamat' => $request->alamat_lengkap,
                'status' => 'Request',
            ]
        );

     return response()->json([
    'success_organisasi' => true,
    'message' => 'Data organisasi berhasil disimpan!',
    'jumlah_anggota' => $organisasi->jumlah_anggota
]);
}
}
