<?php
// app/Http/Controllers/KesenianController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;

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
                'jenis_kesenian',
                'nama_sub_kesenian',
                'alamat',
                'desa',
                'kecamatan',
                'nama_kecamatan',
                'nama_ketua',
                'no_telp_ketua',
                'tanggal_daftar',
                'tanggal_expired',
                'status'
            ]);

        // Cek apakah ada pencarian atau filter
        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // Pencarian berdasarkan multiple parameter
        if ($q = $request->get('q')) {
            $query->where(function($qry) use ($q) {
                $qry->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhere('jenis_kesenian', 'like', "%{$q}%")
                    ->orWhere('nama_ketua', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhere('desa', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%")
                    ->orWhere('nama_kecamatan', 'like', "%{$q}%")
                    ->orWhere('no_telp_ketua', 'like', "%{$q}%");
            });
        }

        // Filter berdasarkan jenis kesenian
        if ($jenisKesenian = $request->get('jenis_kesenian')) {
            $query->where(function($q) use ($jenisKesenian) {
                $q->where('nama_jenis_kesenian', $jenisKesenian)
                  ->orWhere('jenis_kesenian', $jenisKesenian);
            });
        }

        // Filter berdasarkan kecamatan
        if ($kecamatan = $request->get('kecamatan')) {
            $query->where(function($q) use ($kecamatan) {
                $q->where('kecamatan', 'like', "%{$kecamatan}%")
                  ->orWhere('nama_kecamatan', 'like', "%{$kecamatan}%");
            });
        }

        // Urutkan: Jika ada pencarian/filter -> urut by ID desc, jika tidak -> urut by status
        if ($hasSearch) {
            $query->orderBy('id', 'desc');
        } else {
            // Urutkan berdasarkan status: Request -> Denny -> Allow -> DataLama
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

        // Tampilkan semua data tanpa pagination
        $dataKesenian = $query->get();

        // Data untuk dropdown filter
        $jenisKesenian = Organisasi::whereNotNull('nama_jenis_kesenian')
            ->orWhereNotNull('jenis_kesenian')
            ->selectRaw('COALESCE(nama_jenis_kesenian, jenis_kesenian) as jenis')
            ->distinct()
            ->pluck('jenis')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        $kecamatanList = Organisasi::whereNotNull('kecamatan')
            ->orWhereNotNull('nama_kecamatan')
            ->selectRaw('COALESCE(nama_kecamatan, kecamatan) as nama_kec')
            ->distinct()
            ->pluck('nama_kec')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        // Tambahkan kecamatan Banyuwangi jika belum ada
        $kecamatanBanyuwangi = [
            'Banyuwangi', 'Bangorejo', 'Cluring', 'Gambiran', 'Genteng', 'Giri',
            'Glagah', 'Glenmore', 'Kabat', 'Kalibaru', 'Kalipuro', 'Licin',
            'Muncar', 'Pesanggaran', 'Purwoharjo', 'Rogojampi', 'Sempu',
            'Singojuruh', 'Srono', 'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
        ];

        $kecamatanList = array_unique(array_merge($kecamatanList, $kecamatanBanyuwangi));
        sort($kecamatanList);

        return view('admin.kesenian.index', compact('dataKesenian', 'jenisKesenian', 'kecamatanList', 'hasSearch'));
    }

    // Method lainnya tetap sama...
    public function create()
    {
        // Data untuk dropdown di form create
        $jenisKesenian = Organisasi::whereNotNull('nama_jenis_kesenian')
            ->orWhereNotNull('jenis_kesenian')
            ->selectRaw('COALESCE(nama_jenis_kesenian, jenis_kesenian) as jenis')
            ->distinct()
            ->pluck('jenis')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        $kecamatanList = [
            'Banyuwangi', 'Bangorejo', 'Cluring', 'Gambiran', 'Genteng', 'Giri',
            'Glagah', 'Glenmore', 'Kabat', 'Kalibaru', 'Kalipuro', 'Licin',
            'Muncar', 'Pesanggaran', 'Purwoharjo', 'Rogojampi', 'Sempu',
            'Singojuruh', 'Srono', 'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
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
            'kecamatan' => 'required|string|max:255',
            'jenis_kesenian' => 'required|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:1',
        ]);

        // Generate nomor induk otomatis jika belum ada
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
            'jenis_kesenian' => $request->jenis_kesenian,
            'nama_jenis_kesenian' => $request->jenis_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,
            'tanggal_daftar' => now(),
            'tanggal_expired' => now()->addYears(1), // Expired 1 tahun dari sekarang
            'status' => 'Request',
        ]);

        return redirect()->route('admin.kesenian.index')
            ->with('success', 'Data kesenian berhasil ditambahkan.' . ($nomorInduk ? ' Nomor Induk: ' . $nomorInduk : ''));
    }

    public function show($id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
        return view('admin.kesenian.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Data untuk dropdown di form edit
        $jenisKesenian = Organisasi::whereNotNull('nama_jenis_kesenian')
            ->orWhereNotNull('jenis_kesenian')
            ->selectRaw('COALESCE(nama_jenis_kesenian, jenis_kesenian) as jenis')
            ->distinct()
            ->pluck('jenis')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        $kecamatanList = [
            'Banyuwangi', 'Bangorejo', 'Cluring', 'Gambiran', 'Genteng', 'Giri',
            'Glagah', 'Glenmore', 'Kabat', 'Kalibaru', 'Kalipuro', 'Licin',
            'Muncar', 'Pesanggaran', 'Purwoharjo', 'Rogojampi', 'Sempu',
            'Singojuruh', 'Srono', 'Tegaldlimo', 'Tegalsari', 'Wongsorejo'
        ];
        sort($kecamatanList);

        return view('admin.kesenian.edit', compact('item', 'jenisKesenian', 'kecamatanList'));
    }

    public function update(Request $request, $id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:200',
            'no_telp_ketua' => 'required|string|max:20',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'jenis_kesenian' => 'required|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:1',
            'status' => 'required|in:Request,Allow,Denny,DataLama',
            'tanggal_expired' => 'nullable|date',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'nama_ketua' => $request->nama_ketua,
            'no_telp_ketua' => $request->no_telp_ketua,
            'alamat' => $request->alamat,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'nama_kecamatan' => $request->kecamatan,
            'jenis_kesenian' => $request->jenis_kesenian,
            'nama_jenis_kesenian' => $request->jenis_kesenian,
            'jumlah_anggota' => $request->jumlah_anggota,
            'status' => $request->status,
            'tanggal_expired' => $request->tanggal_expired,
        ];

        // Update nomor induk hanya jika diisi
        if ($request->filled('nomor_induk')) {
            $updateData['nomor_induk'] = $request->nomor_induk;
        }

        $item->update($updateData);

        return redirect()->route('admin.kesenian.index')->with('success', 'Data diperbarui.');
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

        // TODO: proses file
        return back()->with('success', 'File diunggah: ' . basename($path));
    }
}
