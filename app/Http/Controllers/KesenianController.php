<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi; // ganti jika model berbeda

class KesenianController extends Controller
{
    public function index(Request $request)
    {
        $query = Organisasi::query()
            ->select([
                'id',
                'nama',
                'nomor_induk',
                'nama_jenis_kesenian',
                'nama_sub_kesenian',
                'alamat',
                'nama_ketua',
                'no_telp_ketua',
                'tanggal_daftar',
                'tanggal_expired',
                'status'
            ]);

        if ($q = $request->get('q')) {
            $query->where(function($qry) use ($q) {
                $qry->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%");
            });
        }

        $dataKesenian = $query->orderBy('id','desc')->paginate(25)->withQueryString();

       return view('kesenian.index', compact('dataKesenian'));
    }

    public function showImportForm()
    {
        return view('kesenian.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $path = $request->file('file')->store('imports');

        // TODO: proses file
        return back()->with('success', 'File diunggah: ' . basename($path));
    }

    public function show($id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
        return view('kesenian.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
        return view('kesenian.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // contoh update sederhana — sesuaikan validasi dan field
        $item->update($request->only(['nama', 'nomor_induk', 'alamat']));
        return redirect()->route('kesenian.index')->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        $item = Organisasi::find($id);
        if ($item) {
            $item->delete();
            return back()->with('success', 'Data dihapus.');
        }
        return back()->with('error', 'Data tidak ditemukan.');
    }
}
