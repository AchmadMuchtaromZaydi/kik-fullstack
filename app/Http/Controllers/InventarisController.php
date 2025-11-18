<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    /**
     * Tampilkan daftar inventaris organisasi user.
     */
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()->route('user.organisasi.create')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        $inventaris = $organisasi->inventaris()->get();
        $jumlahSaatIni = $inventaris->count();

        return view('user.inventaris.index', compact('organisasi', 'inventaris', 'jumlahSaatIni'));
    }

    /**
     * Simpan data inventaris baru.
     */
    public function store(Request $request)
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'pembelian_th' => 'nullable|digits:4|integer',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Inventaris::create(array_merge($request->all(), [
            'organisasi_id' => $organisasi->id
        ]));

        return redirect()->back()
            ->with('success_inventaris', 'Data inventaris berhasil ditambahkan!')
            ->with('tab', 'inventaris');
    }

    /**
     * Perbarui data inventaris.
     */
    public function update(Request $request, $id)
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();
        $inventaris = Inventaris::where('id', $id)
            ->where('organisasi_id', $organisasi->id)
            ->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'pembelian_th' => 'nullable|digits:4|integer',
            'kondisi' => 'required|in:Baru,Bekas,Rusak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $inventaris->update($request->only([
            'nama', 'jumlah', 'tahun_pembelian', 'kondisi', 'keterangan'
        ]));

        return redirect()->back()
            ->with('success_inventaris', 'Data inventaris berhasil diperbarui!')
            ->with('tab', 'inventaris');
    }

    /**
     * Hapus data inventaris.
     */
    public function destroy($id)
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();
        $inventaris = Inventaris::where('id', $id)
            ->where('organisasi_id', $organisasi->id)
            ->firstOrFail();

        $inventaris->delete();

        return redirect()->back()
            ->with('success_inventaris', 'Data inventaris berhasil dihapus!')
            ->with('tab', 'inventaris');
    }
}
