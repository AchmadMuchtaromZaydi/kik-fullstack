<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\JenisKesenian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganisasiController extends Controller
{
    /**
     * Menampilkan daftar organisasi milik user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data organisasi, sudah termasuk nama jenis kesenian (via eager loading)
        // Kita juga tambahkan select() untuk efisiensi, hanya mengambil kolom yang perlu.
        $organisasi = Organisasi::with([
                'jenisKesenianObj:id,nama',
                'subKesenianObj:id,nama'
            ])
            ->select(
                'id', 'nama', 'status', 'jenis_kesenian', 'sub_kesenian',
                'kabupaten', 'nama_kecamatan', 'nama_desa', // <-- Nama wilayah sudah ada di sini
                'nomor_induk', 'tanggal_expired' // <-- Kolom tambahan untuk view (opsional)
            )
            ->where('user_id', $user->id)
            ->get();

        // ===================================================================
        // PERBAIKAN N+1 PROBLEM:
        // Seluruh foreach loop dihapus dari sini.
        // Data nama wilayah (kabupaten, nama_kecamatan, nama_desa)
        // sudah diambil langsung dari tabel 'kik_organisasi' di query atas.
        // Pastikan view Anda memanggil $org->kabupaten, $org->nama_kecamatan, $org->nama_desa
        // ===================================================================

        return view('user.organisasi.index', compact('organisasi'));
    }

    /**
     * Menampilkan form untuk membuat organisasi baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Query ini sudah efisien
        $jenisKesenian = JenisKesenian::whereNull('parent')->get();
        $kabupaten = DB::table('wilayah')->whereRaw('LENGTH(kode) = 5')->get();

        return view('user.organisasi.create', compact('jenisKesenian', 'kabupaten'));
    }

    /**
     * Mengambil data Sub Kesenian berdasarkan parent (AJAX).
     *
     * @param  int  $parent_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubKesenian($parent_id)
    {
        $subKesenian = JenisKesenian::where('parent', $parent_id)->get(['id', 'nama']);
        return response()->json($subKesenian);
    }

    /**
     * Mengambil data Kecamatan berdasarkan kabupaten (AJAX).
     *
     * @param  string  $kabupatenKode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKecamatan($kabupatenKode)
    {
        // Query ini sudah efisien
        $kecamatan = DB::table('wilayah')
            ->where('kode', 'LIKE', $kabupatenKode . '.%')
            ->whereRaw('LENGTH(kode) = 8')
            ->get(['kode', 'nama']);
        return response()->json($kecamatan);
    }

    /**
     * Mengambil data Desa berdasarkan kecamatan (AJAX).
     *
     * @param  string  $kecamatanKode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesa($kecamatanKode)
    {
        // Query ini sudah efisien
        $desa = DB::table('wilayah')
            ->where('kode', 'LIKE', $kecamatanKode . '.%')
            ->whereRaw('LENGTH(kode) = 13')
            ->get(['kode', 'nama']);
        return response()->json($desa);
    }

    /**
     * Menyimpan organisasi baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        // Ambil nama wilayah (3 query ini tidak masalah, hanya terjadi sekali saat 'store')
        $kabupaten_nama = DB::table('wilayah')->where('kode', $request->kabupaten_kode)->value('nama');
        $kecamatan_nama = DB::table('wilayah')->where('kode', $request->kecamatan_kode)->value('nama');
        $desa_nama = DB::table('wilayah')->where('kode', $request->desa_kode)->value('nama');

        // ===================================================================
        // PERBAIKAN LOGIKA PENYIMPANAN:
        // Menyesuaikan data yang disimpan dengan struktur tabel 'kik_organisasi'
        // yang Anda tunjukkan di screenshot.
        // ===================================================================
        Organisasi::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'tanggal_berdiri' => $request->tanggal_berdiri,
            'jenis_kesenian' => $request->jenis_kesenian,
            'sub_kesenian' => $request->sub_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,

            // Menyimpan KODE Wilayah
            'kecamatan' => $request->kecamatan_kode, // 'kecamatan' diisi KODE
            'desa' => $request->desa_kode,           // 'desa' diisi KODE

            // Menyimpan NAMA Wilayah (Denormalisasi untuk kecepatan read)
            'kabupaten' => $kabupaten_nama,         // 'kabupaten' diisi NAMA
            'nama_kecamatan' => $kecamatan_nama,    // 'nama_kecamatan' diisi NAMA
            'nama_desa' => $desa_nama,            // 'nama_desa' diisi NAMA

            'alamat' => $request->alamat_lengkap,
            'status' => 'Request',
            'tanggal_daftar' => now(), // Menambahkan tanggal daftar saat create
        ]);

        return redirect()->route('user.organisasi.index')
            ->with('success', 'Data organisasi berhasil disimpan!');
    }
}
