<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataAnggotaController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()->route('user.organisasi.index')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        $anggota = $organisasi->anggota()->get();
        $jumlahMaks = $organisasi->jumlah_anggota;
        $jumlahSaatIni = $anggota->count();

        return view('user.anggota.index', compact('organisasi', 'anggota', 'jumlahMaks', 'jumlahSaatIni'));
    }

    public function create()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();
        if (!$organisasi) {
            return redirect()->route('user.organisasi.index')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        // Jika anggota sudah penuh, larang penambahan
        if ($organisasi->anggota()->count() >= $organisasi->jumlah_anggota) {
            return redirect()->route('user.anggota.index')
                ->with('error', 'Jumlah anggota sudah mencapai batas yang ditentukan.');
        }

        return view('user.anggota.create', compact('organisasi'));
    }

   public function store(Request $request)
{
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

    $organisasi = Organisasi::where('user_id', Auth::id())->first();
    if (!$organisasi) {
        return back()->with('error', 'Organisasi tidak ditemukan.');
    }

    // 🚨 Tambahkan validasi peran jabatan agar tidak duplikat
    $jabatan = $request->jabatan;
    if (in_array($jabatan, ['Ketua', 'Sekretaris'])) {
        $sudahAda = Anggota::where('organisasi_id', $organisasi->id)
            ->where('jabatan', $jabatan)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', "Jabatan $jabatan sudah terisi, tidak boleh ganda.");
        }
    }

    // Simpan data anggota baru
    Anggota::create([
        'organisasi_id' => $organisasi->id,
        'nama' => $request->nama,
        'nik' => $request->nik,
        'jabatan' => $request->jabatan,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'pekerjaan' => $request->pekerjaan,
        'alamat' => $request->alamat,
        'telepon' => $request->telepon,
        'whatsapp' => $request->whatsapp,
    ]);

    // ✅ Cek apakah organisasi sudah punya Ketua dan Sekretaris
    $punyaKetua = Anggota::where('organisasi_id', $organisasi->id)
        ->where('jabatan', 'Ketua')
        ->exists();
    $punyaSekretaris = Anggota::where('organisasi_id', $organisasi->id)
        ->where('jabatan', 'Sekretaris')
        ->exists();

    if ($punyaKetua && $punyaSekretaris) {
        return redirect()->route('user.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan! Struktur organisasi lengkap (Ketua & Sekretaris ada).');
    } else {
        return redirect()->route('user.anggota.index')
            ->with('warning', 'Anggota berhasil ditambahkan, tapi pastikan sudah ada Ketua dan Sekretaris.');
    }
}

    public function edit($id)
{
    $anggota = Anggota::findOrFail($id);
    $organisasi = Organisasi::where('user_id', Auth::id())->first();

    // Cegah user mengedit anggota milik organisasi lain
    if ($anggota->organisasi_id != $organisasi->id) {
        return redirect()->route('user.anggota.index')->with('error', 'Anda tidak berhak mengedit data ini.');
    }

    return view('user.anggota.edit', compact('anggota', 'organisasi'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:20|unique:kik_anggota,nik,' . $id,
        'jabatan' => 'nullable|string|max:100',
        'jenis_kelamin' => 'required|in:L,P',
        'tanggal_lahir' => 'nullable|date',
        'pekerjaan' => 'nullable|string|max:255',
        'alamat' => 'nullable|string|max:255',
        'telepon' => 'nullable|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
    ]);

    $anggota = Anggota::findOrFail($id);
    $organisasi = Organisasi::where('user_id', Auth::id())->first();

    if ($anggota->organisasi_id != $organisasi->id) {
        return redirect()->route('user.anggota.index')->with('error', 'Tidak diizinkan mengedit data ini.');
    }

    // Simpan nilai lama jika tanggal lahir tidak diisi
    $data = $request->only([
        'nama', 'nik', 'jabatan', 'jenis_kelamin', 'tanggal_lahir',
        'pekerjaan', 'alamat', 'telepon', 'whatsapp'
    ]);

    if (empty($data['tanggal_lahir'])) {
        $data['tanggal_lahir'] = $anggota->tanggal_lahir; // pertahankan tanggal lama
    }

    $anggota->update($data);

    return redirect()->route('user.anggota.index')->with('success', 'Data anggota berhasil diperbarui!');
}


public function destroy($id)
{
    $anggota = Anggota::findOrFail($id);
    $organisasi = Organisasi::where('user_id', Auth::id())->first();

    if ($anggota->organisasi_id != $organisasi->id) {
        return redirect()->route('user.anggota.index')->with('error', 'Tidak diizinkan menghapus data ini.');
    }

    $anggota->delete();

    return redirect()->route('user.anggota.index')->with('success', 'Data anggota berhasil dihapus!');
}
}
