<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\JenisKesenian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganisasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $organisasi = Organisasi::with(['jenisKesenianObj', 'subKesenianObj'])
            ->where('user_id', $user->id)
            ->get();

        foreach ($organisasi as $org) {
            $org->kabupaten_nama = DB::table('wilayah')->where('kode', $org->kabupaten_kode)->value('nama');
            $org->kecamatan_nama = DB::table('wilayah')->where('kode', $org->kecamatan_kode)->value('nama');
            $org->desa_nama = DB::table('wilayah')->where('kode', $org->desa_kode)->value('nama');
        }

        return view('user.organisasi.index', compact('organisasi'));
    }

    public function create()
    {
        $jenisKesenian = JenisKesenian::whereNull('parent')->get();
        $kabupaten = DB::table('wilayah')->whereRaw('LENGTH(kode) = 5')->get();

        return view('user.organisasi.create', compact('jenisKesenian', 'kabupaten'));
    }

    public function getSubKesenian($parent_id)
    {
        $subKesenian = JenisKesenian::where('parent', $parent_id)->get(['id', 'nama']);
        return response()->json($subKesenian);
    }

    public function getKecamatan($kabupatenKode)
    {
        $kecamatan = DB::table('wilayah')
            ->where('kode', 'LIKE', $kabupatenKode . '.%')
            ->whereRaw('LENGTH(kode) = 8')
            ->get(['kode', 'nama']);
        return response()->json($kecamatan);
    }

    public function getDesa($kecamatanKode)
    {
        $desa = DB::table('wilayah')
            ->where('kode', 'LIKE', $kecamatanKode . '.%')
            ->whereRaw('LENGTH(kode) = 13')
            ->get(['kode', 'nama']);
        return response()->json($desa);
    }

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
            return redirect()->back()->with('error', 'Jenis kesenian atau sub jenis tidak valid.');
        }

        if (Organisasi::where('user_id', Auth::id())->exists()) {
            return redirect()->route('user.organisasi.index')
                ->with('warning', 'Anda sudah memiliki organisasi.');
        }

        $kabupaten_nama = DB::table('wilayah')->where('kode', $request->kabupaten_kode)->value('nama');
        $kecamatan_nama = DB::table('wilayah')->where('kode', $request->kecamatan_kode)->value('nama');
        $desa_nama = DB::table('wilayah')->where('kode', $request->desa_kode)->value('nama');

        Organisasi::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'tanggal_berdiri' => $request->tanggal_berdiri,
            'jenis_kesenian' => $request->jenis_kesenian,
            'sub_kesenian' => $request->sub_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,
            'kabupaten_kode' => $request->kabupaten_kode,
            'kecamatan_kode' => $request->kecamatan_kode,
            'desa_kode' => $request->desa_kode,
            'kabupaten' => $kabupaten_nama,
            'kecamatan' => $kecamatan_nama,
            'desa' => $desa_nama,
            'alamat' => $request->alamat_lengkap,
            'status' => 'Request',
        ]);

        return redirect()->route('user.organisasi.index')
            ->with('success', 'Data organisasi berhasil disimpan!');
    }
}
