<?php
// app/Http/Controllers/JenisKesenianController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKesenian;

class JenisKesenianController extends Controller
{
    public function index()
    {
        $dataJenisKesenian = JenisKesenian::with('sub')
            ->jenisUtama()
            ->orderBy('nama')
            ->get();

        $parentJenisKesenian = JenisKesenian::jenisUtama()->orderBy('nama')->get();

        return view('admin.jenis-kesenian.index', compact('dataJenisKesenian', 'parentJenisKesenian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'parent' => 'nullable|exists:kik_jeniskesenian,id'
        ]);

        JenisKesenian::create([
            'nama' => $request->nama,
            'parent' => $request->parent ?: null
        ]);

        return redirect()->route('admin.jenis-kesenian')
            ->with('success', 'Jenis kesenian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'parent' => 'nullable|exists:kik_jeniskesenian,id'
        ]);

        $jenisKesenian = JenisKesenian::findOrFail($id);

        // Cegar circular reference
        if ($request->parent == $id) {
            return redirect()->route('admin.jenis-kesenian')
                ->with('error', 'Tidak dapat memilih diri sendiri sebagai parent.');
        }

        $jenisKesenian->update([
            'nama' => $request->nama,
            'parent' => $request->parent ?: null
        ]);

        return redirect()->route('admin.jenis-kesenian')
            ->with('success', 'Jenis kesenian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisKesenian = JenisKesenian::findOrFail($id);

        // Cek jika memiliki sub jenis
        if ($jenisKesenian->sub()->count() > 0) {
            return redirect()->route('admin.jenis-kesenian')
                ->with('error', 'Tidak dapat menghapus jenis kesenian yang memiliki sub jenis.');
        }

        $jenisKesenian->delete();

        return redirect()->route('admin.jenis-kesenian')
            ->with('success', 'Jenis kesenian berhasil dihapus.');
    }
}
