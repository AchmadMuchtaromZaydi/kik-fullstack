<?php
// app/Http/Controllers/AnggotaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Organisasi;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with('organisasi')->latest()->get();
        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        $organisasi = Organisasi::all();
        return view('admin.anggota.create', compact('organisasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'organisasi_id' => 'required|exists:kik_organisasi,id',
            'whatsapp' => 'nullable|string',
        ]);

        Anggota::create($request->all());

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $organisasi = Organisasi::all();
        return view('admin.anggota.edit', compact('anggota', 'organisasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'organisasi_id' => 'required|exists:kik_organisasi,id',
            'whatsapp' => 'nullable|string',
        ]);

        $anggota = Anggota::findOrFail($id);
        $anggota->update($request->all());

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }

     public function anggota()
    {
        return $this->hasMany(Anggota::class, 'organisasi_id');
    }
}
