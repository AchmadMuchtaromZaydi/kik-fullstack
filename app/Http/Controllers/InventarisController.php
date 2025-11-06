<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();
        if (!$organisasi) {
            return redirect()->route('user.organisasi.index')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

         $anggotaCount = $organisasi->anggota()->count();
    if ($anggotaCount < $organisasi->jumlah_anggota) {
        return redirect()->route('user.anggota.index')
            ->with('warning', 'Lengkapi data anggota terlebih dahulu. Minimal ' . $organisasi->jumlah_anggota . ' anggota.');
    }

        $inventaris = Inventaris::where('organisasi_id', $organisasi->id)->get();

        return view('user.inventaris.index', compact('organisasi', 'inventaris'));
    }

    public function create()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();
        if (!$organisasi) {
            return redirect()->route('user.organisasi.index')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        // Cek jumlah anggota
    $anggotaCount = $organisasi->anggota()->count();
    if ($anggotaCount < $organisasi->jumlah_anggota) {
        return redirect()->route('user.anggota.index')
            ->with('error', 'Lengkapi data anggota terlebih dahulu. Minimal ' . $organisasi->jumlah_anggota . ' anggota.');
    }

        return view('user.inventaris.create', compact('organisasi'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:500',
        'jumlah' => 'required|integer|min:1',
        'pembelian_th' => 'nullable|digits:4',
        'kondisi' => 'required|string|max:100',
        'keterangan' => 'nullable|string',
    ]);

    $organisasi = Organisasi::where('user_id', Auth::id())->first();
    if (!$organisasi) {
        return back()->with('error', 'Organisasi tidak ditemukan.');
    }

    $inventarisCount = Inventaris::where('organisasi_id', $organisasi->id)->count();
    if ($inventarisCount >= 5) {
        return back()->with('error', 'Jumlah inventaris sudah maksimal (5 item).');
    }

    Inventaris::create([
        'organisasi_id' => $organisasi->id,
        'nama' => $request->nama,
        'jumlah' => $request->jumlah,
        'pembelian_th' => $request->pembelian_th,
        'kondisi' => $request->kondisi,
        'keterangan' => $request->keterangan,
        'validasi' => 0,
    ]);

    return redirect()->route('user.inventaris.index')->with('success', 'Data inventaris berhasil ditambahkan!');
}
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return view('user.inventaris.edit', compact('inventaris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:500',
            'jumlah' => 'required|integer|min:1',
            'pembelian_th' => 'nullable|digits:4',
            'kondisi' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $inventaris = Inventaris::findOrFail($id);
        $inventaris->update($request->all());

        return redirect()->route('user.inventaris.index')->with('success', 'Data inventaris berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();

        return redirect()->route('user.inventaris.index')->with('success', 'Data inventaris berhasil dihapus!');
    }
}
