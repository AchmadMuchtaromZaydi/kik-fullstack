<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\JenisKesenian;
use Illuminate\Support\Facades\Log;

class KesenianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Organisasi::query()
            ->select([
                'id', 'nama', 'nomor_induk', 'nama_jenis_kesenian',
                'nama_sub_kesenian', 'alamat', 'nama_ketua', 'no_telp_ketua',
                'tanggal_daftar', 'tanggal_expired', 'status'
            ]);

        // Cek apakah ada pencarian atau filter
        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // Pencarian berdasarkan multiple parameter
        if ($q = $request->get('q')) {
            $query->where(function($qry) use ($q) {
                $qry->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhere('nama_ketua', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhere('no_telp_ketua', 'like', "%{$q}%");
            });
        }

        // Filter berdasarkan jenis kesenian
        if ($jenisKesenian = $request->get('jenis_kesenian')) {
            $query->where('nama_jenis_kesenian', $jenisKesenian);
        }

        // Filter berdasarkan kecamatan
        if ($kecamatan = $request->get('kecamatan')) {
            $query->where('nama_kecamatan', $kecamatan);
        }

        // Urutkan
        if ($hasSearch) {
            $query->orderBy('id', 'desc');
        } else {
            $query->orderByRaw("
                CASE
                    WHEN status = 'Request' THEN 1
                    WHEN status = 'Denny' THEN 2
                    WHEN status = 'Allow' THEN 3
                    WHEN status = 'DataLama' THEN 4
                    ELSE 5
                END
            ")->orderBy('id', 'desc');
        }

        $dataKesenian = $query->get();

        // Data untuk dropdown filter jenis kesenian
        $jenisKesenian = Organisasi::whereNotNull('nama_jenis_kesenian')
            ->where('nama_jenis_kesenian', '!=', '')
            ->select('nama_jenis_kesenian')
            ->distinct()
            ->orderBy('nama_jenis_kesenian')
            ->pluck('nama_jenis_kesenian')
            ->toArray();

        // Data untuk dropdown filter kecamatan - 25 KECAMATAN LENGKAP
        $kecamatanList = [
            'Banyuwangi', 'Bangorejo', 'Blimbingsari', 'Cluring', 'Gambiran',
            'Genteng', 'Giri', 'Glagah', 'Glenmore', 'Kabat', 'Kalibaru',
            'Kalipuro', 'Licin', 'Muncar', 'Pesanggaran', 'Purwoharjo',
            'Rogojampi', 'Sempu', 'Singojuruh', 'Songgon', 'Srono',
            'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
        ];

        // Gabungkan dengan kecamatan yang sudah ada di database
        $kecamatanFromDB = Organisasi::whereNotNull('nama_kecamatan')
            ->where('nama_kecamatan', '!=', '')
            ->select('nama_kecamatan')
            ->distinct()
            ->orderBy('nama_kecamatan')
            ->pluck('nama_kecamatan')
            ->toArray();

        $kecamatanList = array_unique(array_merge($kecamatanFromDB, $kecamatanList));
        sort($kecamatanList);

        return view('admin.kesenian.index', compact('dataKesenian', 'jenisKesenian', 'kecamatanList', 'hasSearch'));
    }

    public function create()
    {
        $jenisKesenian = JenisKesenian::orderBy('nama')->pluck('nama')->toArray();

        // Daftar kecamatan lengkap 25 untuk form create
        $kecamatanList = [
            'Banyuwangi', 'Bangorejo', 'Blimbingsari', 'Cluring', 'Gambiran',
            'Genteng', 'Giri', 'Glagah', 'Glenmore', 'Kabat', 'Kalibaru',
            'Kalipuro', 'Licin', 'Muncar', 'Pesanggaran', 'Purwoharjo',
            'Rogojampi', 'Sempu', 'Singojuruh', 'Songgon', 'Srono',
            'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
        ];
        sort($kecamatanList);

        return view('admin.kesenian.create', compact('jenisKesenian', 'kecamatanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:200',
            'no_telp_ketua' => 'required|string|max:20',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'required|string|max:255|in:Banyuwangi,Bangorejo,Blimbingsari,Cluring,Gambiran,Genteng,Giri,Glagah,Glenmore,Kabat,Kalibaru,Kalipuro,Licin,Muncar,Pesanggaran,Purwoharjo,Rogojampi,Sempu,Singojuruh,Songgon,Srono,Tegaldlimo,Tegalsari,Wongsorejo',
            'jenis_kesenian' => 'required|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:1',
        ]);

        $jenisKesenianModel = JenisKesenian::where('nama', $request->jenis_kesenian)->first();
        $jenisKesenianId = $jenisKesenianModel ? $jenisKesenianModel->id : null;

        if (!$jenisKesenianId) {
            Log::warning("Jenis Kesenian '{$request->jenis_kesenian}' tidak ditemukan di master saat create.");
        }

        $nomorInduk = $request->nomor_induk;
        if (empty($nomorInduk)) {
            $lastOrganisasi = Organisasi::orderBy('id', 'desc')->first();
            $nextId = $lastOrganisasi ? $lastOrganisasi->id + 1 : 1;
            $nomorInduk = 'KS' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }

        Organisasi::create([
            'nama' => $request->nama,
            'nomor_induk' => $nomorInduk,
            'nama_ketua' => $request->nama_ketua,
            'no_telp_ketua' => $request->no_telp_ketua,
            'alamat' => $request->alamat,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'nama_kecamatan' => $request->kecamatan,
            'jenis_kesenian' => $jenisKesenianId,
            'nama_jenis_kesenian' => $request->jenis_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,
            'tanggal_daftar' => now(),
            'tanggal_expired' => now()->addYears(1),
            'status' => 'Request',
        ]);

        return redirect()->route('admin.kesenian.index')
            ->with('success', 'Data kesenian berhasil ditambahkan.' . ($nomorInduk ? ' Nomor Induk: ' . $nomorInduk : ''));
    }

    public function show($id)
    {
        $item = Organisasi::findOrFail($id);
        return view('admin.kesenian.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Organisasi::findOrFail($id);

        $jenisKesenian = JenisKesenian::orderBy('nama')->pluck('nama')->toArray();

        // Daftar kecamatan lengkap 25 untuk form edit
        $kecamatanList = [
            'Banyuwangi', 'Bangorejo', 'Blimbingsari', 'Cluring', 'Gambiran',
            'Genteng', 'Giri', 'Glagah', 'Glenmore', 'Kabat', 'Kalibaru',
            'Kalipuro', 'Licin', 'Muncar', 'Pesanggaran', 'Purwoharjo',
            'Rogojampi', 'Sempu', 'Singojuruh', 'Songgon', 'Srono',
            'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
        ];
        sort($kecamatanList);

        return view('admin.kesenian.edit', compact('item', 'jenisKesenian', 'kecamatanList'));
    }

    public function update(Request $request, $id)
    {
        $item = Organisasi::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:200',
            'no_telp_ketua' => 'required|string|max:20',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'required|string|max:255|in:Banyuwangi,Bangorejo,Blimbingsari,Cluring,Gambiran,Genteng,Giri,Glagah,Glenmore,Kabat,Kalibaru,Kalipuro,Licin,Muncar,Pesanggaran,Purwoharjo,Rogojampi,Sempu,Singojuruh,Songgon,Srono,Tegaldlimo,Tegalsari,Wongsorejo',
            'jenis_kesenian' => 'required|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:1',
            'status' => 'required|in:Request,Allow,Denny,DataLama',
            'tanggal_expired' => 'nullable|date',
        ]);

        $jenisKesenianModel = JenisKesenian::where('nama', $request->jenis_kesenian)->first();
        $jenisKesenianId = $jenisKesenianModel ? $jenisKesenianModel->id : null;

        $updateData = [
            'nama' => $request->nama,
            'nama_ketua' => $request->nama_ketua,
            'no_telp_ketua' => $request->no_telp_ketua,
            'alamat' => $request->alamat,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'nama_kecamatan' => $request->kecamatan,
            'jenis_kesenian' => $jenisKesenianId,
            'nama_jenis_kesenian' => $request->jenis_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,
            'status' => $request->status,
            'tanggal_expired' => $request->tanggal_expired,
        ];

        if ($request->filled('nomor_induk')) {
            $updateData['nomor_induk'] = $request->nomor_induk;
        }

        $item->update($updateData);

        return redirect()->route('admin.kesenian.index')->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        $item = Organisasi::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Data dihapus.');
    }

    public function showImportForm()
    {
        return view('admin.kesenian.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $path = $request->file('file')->store('imports');
        return back()->with('success', 'File diunggah: ' . basename($path));
    }
}
