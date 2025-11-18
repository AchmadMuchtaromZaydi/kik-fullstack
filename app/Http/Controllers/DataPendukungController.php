<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendukung;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DataPendukungController extends Controller
{
    /**
     * Halaman utama upload data pendukung
     */
    public function index()
    {
        $organisasi = Organisasi::where('user_id', Auth::id())->first();

        if (!$organisasi) {
            return redirect()
                ->route('user.organisasi.create')
                ->with('error', 'Silakan isi data organisasi terlebih dahulu.');
        }

        // Ambil semua data pendukung untuk organisasi ini
        $dataPendukung = DataPendukung::where('organisasi_id', $organisasi->id)->get();

        return view('user.pendukung.index', compact('organisasi', 'dataPendukung'));
    }

    /**
     * Simpan file pendukung (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048',
            'tipe' => 'required|string|in:KTP,PAS_FOTO,BANNER,FOTO-KEGIATAN'
        ]);

        $organisasi = Organisasi::where('user_id', Auth::id())->firstOrFail();

        // Buat folder organisasi jika belum ada
        $folder = "uploads/organisasi/{$organisasi->id}";
        if (!Storage::exists("public/{$folder}")) {
            Storage::makeDirectory("public/{$folder}");
        }

        $file = $request->file('file');
        $filename = strtoupper($request->tipe) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Simpan file di storage
        $file->storeAs("public/{$folder}", $filename);

        // Simpan record ke database
        $data = DataPendukung::create([
            'tipe'          => strtoupper($request->tipe),
            'image'         => $filename,
            'organisasi_id' => $organisasi->id,
            'validasi'      => null
        ]);

        return response()->json([
            'success' => true,
            'data'    => $data,
            'url'     => asset("storage/{$folder}/{$filename}")
        ]);
    }

    /**
     * Hapus file pendukung (AJAX)
     */
    public function destroy($id)
    {
        $data = DataPendukung::findOrFail($id);

        // Pastikan user hanya bisa hapus file miliknya
        if ($data->organisasi->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $path = "public/uploads/organisasi/{$data->organisasi_id}/{$data->image}";

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $data->delete();

        return response()->json(['success' => true]);
    }
}
