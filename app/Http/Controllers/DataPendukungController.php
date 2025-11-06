<?php

namespace App\Http\Controllers;

use App\Models\DataPendukung;
use App\Models\Inventaris;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataPendukungController extends Controller
{
    // ========================
    // PRIVATE HELPERS
    // ========================

    private function getOrganisasi()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()->route('user.organisasi.index')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        return $organisasi;
    }

    private function checkInventaris($organisasi)
    {
        $inventarisCount = Inventaris::where('organisasi_id', $organisasi->id)->count();

        if ($inventarisCount < $organisasi->jumlah_anggota) {
            return redirect()->route('user.inventaris.index')
                ->with('warning', 'Lengkapi semua data inventaris terlebih dahulu. Minimal ' . $organisasi->jumlah_anggota . ' item inventaris.');
        }

        return null;
    }

    // ========================
    // CONTROLLER METHODS
    // ========================

    public function index()
    {
        $organisasi = $this->getOrganisasi();
        if ($organisasi instanceof \Illuminate\Http\RedirectResponse) return $organisasi;

        if ($response = $this->checkInventaris($organisasi)) return $response;

        $dataPendukung = DataPendukung::where('organisasi_id', $organisasi->id)->get();

        return view('user.pendukung.index', compact('dataPendukung', 'organisasi'));
    }

    public function create()
    {
        $organisasi = $this->getOrganisasi();
        if ($organisasi instanceof \Illuminate\Http\RedirectResponse) return $organisasi;

        if ($response = $this->checkInventaris($organisasi)) return $response;

        return view('user.pendukung.create', compact('organisasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:ktp,photo,banner,poster,kegiatan',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tipe.required' => 'Jenis data pendukung harus dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format foto harus JPG atau PNG.',
            'image.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $organisasi = $this->getOrganisasi();
        if ($organisasi instanceof \Illuminate\Http\RedirectResponse) return $organisasi;

        if ($response = $this->checkInventaris($organisasi)) return $response;

        // Batasi hanya satu foto kegiatan
        if ($request->tipe === 'kegiatan') {
            $cekKegiatan = DataPendukung::where('organisasi_id', $organisasi->id)
                ->where('tipe', 'kegiatan')
                ->first();
            if ($cekKegiatan) {
                return back()->with('error', 'Foto kegiatan sudah diunggah sebelumnya.');
            }
        }

        // Simpan file
        $path = $request->file('image')->store('data_pendukung', 'public');

        DataPendukung::create([
            'organisasi_id' => $organisasi->id,
            'tipe' => $request->tipe,
            'image' => $path,
            'validasi' => 0,
        ]);

        return redirect()->route('user.pendukung.index')
            ->with('success', 'Data pendukung berhasil diunggah!');
    }

    public function destroy($id)
    {
        $data = DataPendukung::findOrFail($id);
        $organisasi = $this->getOrganisasi();
        if ($organisasi instanceof \Illuminate\Http\RedirectResponse) return $organisasi;

        if ($data->organisasi_id != $organisasi->id) {
            return redirect()->route('user.pendukung.index')
                ->with('error', 'Anda tidak berhak menghapus data ini.');
        }

        if ($data->image && Storage::disk('public')->exists($data->image)) {
            Storage::disk('public')->delete($data->image);
        }

        $data->delete();

        return redirect()->route('user.pendukung.index')->with('success', 'Data pendukung berhasil dihapus!');
    }
}
