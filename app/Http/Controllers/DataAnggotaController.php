<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataAnggotaController extends Controller
{
    // Tampilkan daftar anggota
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()->route('user.organisasi.create')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        $anggota = $organisasi->anggota()->get();
        $jumlahMaks = $organisasi->jumlah_anggota;
        $jumlahSaatIni = $anggota->count();
        $sisa = $jumlahMaks - $jumlahSaatIni;

        $ketua = $anggota->where('jabatan', 'Ketua')->first();
        $sekretaris = $anggota->where('jabatan', 'Sekretaris')->first();

        return view('user.anggota.index', compact(
            'organisasi', 'anggota', 'jumlahMaks', 'jumlahSaatIni', 'sisa','ketua',
    'sekretaris'
        ));
    }

    // Simpan anggota baru
    public function store(Request $request)
{
    $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

    if ($organisasi->anggota()->count() >= $organisasi->jumlah_anggota) {
        return back()->with('error', 'Jumlah anggota sudah mencapai batas: ' . $organisasi->jumlah_anggota)
                     ->with('tab', 'anggota');
    }

    $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:20|unique:kik_anggota,nik',
        'jabatan' => 'required|string|max:100',
        'jenis_kelamin' => 'required|in:L,P',
        'tanggal_lahir' => 'nullable|date',
        'pekerjaan' => 'nullable|string|max:255',
        'alamat' => 'nullable|string|max:255',
        'telepon' => 'nullable|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
    ]);

    if (in_array($request->jabatan, ['Ketua', 'Sekretaris']) &&
        Anggota::where('organisasi_id', $organisasi->id)
               ->where('jabatan', $request->jabatan)
               ->exists()) {
        return back()->with('error', "Jabatan {$request->jabatan} sudah terisi.")
                     ->with('tab', 'anggota');
    }

    Anggota::create(array_merge($request->all(), ['organisasi_id' => $organisasi->id]));

    return back()->with('success', 'Anggota berhasil ditambahkan!')->with('tab', 'anggota');
}

public function update(Request $request, $id)
{
    $anggota = Anggota::findOrFail($id);
    $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

    if ($anggota->organisasi_id != $organisasi->id) {
        return back()->with('error', 'Tidak diizinkan mengedit data ini.')->with('tab', 'anggota');
    }

    $request->validate([
        'nama','nik','jabatan','jenis_kelamin','tanggal_lahir','pekerjaan','alamat','telepon','whatsapp'
    ]);

    if (in_array($request->jabatan, ['Ketua', 'Sekretaris']) &&
        Anggota::where('organisasi_id', $organisasi->id)
               ->where('jabatan', $request->jabatan)
               ->where('id', '!=', $anggota->id)
               ->exists()) {
        return back()->with('error', "Jabatan {$request->jabatan} sudah terisi.")->with('tab', 'anggota');
    }

    $anggota->update($request->only([
        'nama','nik','jabatan','jenis_kelamin','tanggal_lahir',
        'pekerjaan','alamat','telepon','whatsapp'
    ]));

    return back()->with('success', 'Data anggota berhasil diperbarui!')->with('tab', 'anggota');
}

public function destroy($id)
{
    $anggota = Anggota::findOrFail($id);
    $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

    if ($anggota->organisasi_id != $organisasi->id) {
        return back()->with('error', 'Tidak diizinkan menghapus data ini.')->with('tab', 'anggota');
    }

    $anggota->delete();

    return back()->with('success', 'Data anggota berhasil dihapus!')->with('tab', 'anggota');
}
}
