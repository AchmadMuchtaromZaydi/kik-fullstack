<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\JenisKesenian;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KesenianExport;

class KesenianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // PERBAIKAN: Dapatkan nama tabel asli dari Model ('kik_organisasi')
        $organisasiTable = (new Organisasi)->getTable();

        // PERBAIKAN: Hapus alias 'organisasi' dan gunakan nama tabel asli
        $query = Organisasi::query() // Ini akan otomatis menggunakan 'FROM kik_organisasi'
            ->leftJoin('wilayah as kec', "$organisasiTable.kecamatan", '=', 'kec.kode')
            ->leftJoin('wilayah as des', "$organisasiTable.desa", '=', 'des.kode')
            ->select([
                "$organisasiTable.id", "$organisasiTable.nama", "$organisasiTable.nomor_induk", 
                "$organisasiTable.nama_jenis_kesenian", "$organisasiTable.nama_sub_kesenian", 
                "$organisasiTable.alamat", "$organisasiTable.nama_ketua", "$organisasiTable.no_telp_ketua", 
                "$organisasiTable.tanggal_daftar", "$organisasiTable.tanggal_expired", "$organisasiTable.status",
                "$organisasiTable.kecamatan", // kode asli
                "$organisasiTable.desa", // kode asli
                'kec.nama as nama_kecamatan', // nama dari join
                'des.nama as nama_desa' // nama dari join
            ]);

        // Cek apakah ada pencarian atau filter
        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // Pencarian (menggunakan nama tabel asli)
        if ($q = $request->get('q')) {
            $query->where(function ($qry) use ($q, $organisasiTable) { 
                $qry->where("$organisasiTable.nama", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nomor_induk", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nama_jenis_kesenian", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nama_ketua", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.alamat", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.no_telp_ketua", 'like', "%{$q}%");
            });
        }

        // Filter jenis kesenian (menggunakan nama tabel asli)
        if ($jenisKesenian = $request->get('jenis_kesenian')) {
            $query->where("$organisasiTable.nama_jenis_kesenian", $jenisKesenian);
        }

        // Filter kecamatan (ini sudah benar, pakai alias join 'kec')
        if ($kecamatan = $request->get('kecamatan')) {
            $query->where('kec.nama', $kecamatan);
        }

        // Urutkan (menggunakan nama tabel asli)
        if ($hasSearch) {
            $query->orderBy("$organisasiTable.id", 'desc');
        } else {
            $query
                ->orderByRaw(
                    "
                CASE
                    WHEN $organisasiTable.status = 'Request' THEN 1
                    WHEN $organisasiTable.status = 'Denny' THEN 2
                    WHEN $organisasiTable.status = 'Allow' THEN 3
                    WHEN $organisasiTable.status = 'DataLama' THEN 4
                    ELSE 5
                END
            "
                )
                ->orderBy("$organisasiTable.id", 'desc');
        }

        $dataKesenian = $query->get();

        // Data untuk dropdown filter jenis kesenian
        $jenisKesenian = Organisasi::whereNotNull('nama_jenis_kesenian')->where('nama_jenis_kesenian', '!=', '')->select('nama_jenis_kesenian')->distinct()->orderBy('nama_jenis_kesenian')->pluck('nama_jenis_kesenian')->toArray();

        // Data untuk dropdown filter kecamatan - AMBIL DARI MODEL WILAYAH
        $kecamatanList = Wilayah::where('kode', 'LIKE', '%.%.%')
            ->where('kode', 'NOT LIKE', '%.%.%.%')
            ->where('kode', '!=', '35.10')
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();

        // Pastikan path view ini benar (sesuai file Anda)
        return view('admin.kesenian.index', compact('dataKesenian', 'jenisKesenian', 'kecamatanList', 'hasSearch'));
    }

    public function show($id)
    {
        // PERBAIKAN: Gunakan nama tabel asli di sini juga
        $organisasiTable = (new Organisasi)->getTable();
        
        $item = Organisasi::query()
            ->leftJoin('wilayah as kec', "$organisasiTable.kecamatan", '=', 'kec.kode')
            ->leftJoin('wilayah as des', "$organisasiTable.desa", '=', 'des.kode')
            ->select("$organisasiTable.*", 'kec.nama as nama_kecamatan', 'des.nama as nama_desa')
            ->where("$organisasiTable.id", $id)
            ->first();

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

        $jenisKesenian = JenisKesenian::orderBy('nama')->pluck('nama')->toArray();

        // Daftar kecamatan dari model Wilayah untuk form edit
        $kecamatanList = Wilayah::where('kode', 'LIKE', '%.%.%')
            ->where('kode', 'NOT LIKE', '%.%.%.%')
            ->where('kode', '!=', '35.10')
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();

        return view('admin.kesenian.edit', compact('item', 'jenisKesenian', 'kecamatanList'));
    }

    public function download(Request $request, $type)
    {
        $jenisKesenian = $request->get('jenis_kesenian');
        $kecamatan = $request->get('kecamatan'); // Ini adalah NAMA kecamatan dari filter

        // PERBAIKAN: Gunakan nama tabel asli di sini juga
        $organisasiTable = (new Organisasi)->getTable();

        $query = Organisasi::query()
            ->leftJoin('wilayah as kec', "$organisasiTable.kecamatan", '=', 'kec.kode')
            ->leftJoin('wilayah as des', "$organisasiTable.desa", '=', 'des.kode')
            ->select([
                "$organisasiTable.id", "$organisasiTable.nama", "$organisasiTable.nomor_induk", 
                "$organisasiTable.nama_jenis_kesenian", "$organisasiTable.nama_sub_kesenian", 
                "$organisasiTable.alamat", "$organisasiTable.nama_ketua", "$organisasiTable.no_telp_ketua", 
                "$organisasiTable.tanggal_daftar", "$organisasiTable.tanggal_expired", "$organisasiTable.status", 
                "$organisasiTable.jumlah_anggota",
                "$organisasiTable.kecamatan", // kode asli
                "$organisasiTable.desa", // kode asli
                'kec.nama as nama_kecamatan', // nama dari join
                'des.nama as nama_desa' // nama dari join
            ]);

        // Filter opsional berdasarkan jenis kesenian
        if ($jenisKesenian) {
            $query->where("$organisasiTable.nama_jenis_kesenian", $jenisKesenian);
        }

        // Filter opsional berdasarkan kecamatan (jika dipilih)
        if ($kecamatan) {
            $query->where('kec.nama', $kecamatan); // Filter by NAMA
        }

        $allData = $query->orderBy('kec.nama')->orderBy("$organisasiTable.nama")->get();

        $filename = 'data_kesenian';
        
        if ($kecamatan) {
            $filename .= '_' . str_replace(' ', '_', strtolower($kecamatan));
        }
        if ($jenisKesenian) {
            $filename .= '_' . str_replace(' ', '_', strtolower($jenisKesenian));
        }
        if (!$kecamatan && !$jenisKesenian) {
            $filename .= '_semua_kecamatan';
        }

        if ($type === 'pdf') {
            return $this->generatePDF($allData, $filename);
        } elseif ($type === 'excel') {
            return $this->generateExcel($allData, $filename);
        }

        return back()->with('error', 'Format download tidak valid.');
    }

    private function generatePDF($data, $filename)
    {
        $dataByKecamatan = $data->groupBy(function($item) {
            return $item->nama_kecamatan ?? 'Tidak Terkategori';
        });

        // Pastikan path view ini benar (sesuai file yang Anda berikan)
        $pdf = Pdf::loadView('kesenian.export-pdf', [ 
            'dataByKecamatan' => $dataByKecamatan,
            'tanggalExport' => now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->download($filename . '.pdf');
    }

    private function generateExcel($data, $filename)
    {
        return Excel::download(new KesenianExport($data), $filename . '.xlsx');
    }

    // --- FUNGSI UPDATE, DESTROY, IMPORT DI BAWAH INI TIDAK PERLU DIUBAH ---
    // --- KARENA MEREKA MENGGUNAKAN ELOQUENT ORM (find, create, update) ---

    public function update(Request $request, $id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $kecamatanList = Wilayah::where('kode', 'LIKE', '%.%.%')
            ->where('kode', 'NOT LIKE', '%.%.%.%')
            ->where('kode', '!=', '35.10')
            ->pluck('nama')
            ->toArray();
            
        $kecamatanRule = 'required|string|max:255|in:' . implode(',', $kecamatanList);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:200',
            'no_telp_ketua' => 'required|string|max:20',
            'alamat' => 'required|string',
            'desa' => 'nullable|string|max:255', // Ini adalah NAMA desa
            'kecamatan' => $kecamatanRule, // Ini adalah NAMA kecamatan
            'jenis_kesenian' => 'required|string|max:255',
            'jumlah_anggota' => 'nullable|integer|min:1',
            'status' => 'required|in:Request,Allow,Denny,DataLama',
            'tanggal_expired' => 'nullable|date',
        ]);

        $wilayahKec = Wilayah::where('nama', $request->kecamatan)->first();
        $wilayahDes = Wilayah::where('nama', $request->desa)->first(); 

        $jenisKesenianModel = JenisKesenian::where('nama', $request->jenis_kesenian)->first();
        $jenisKesenianId = $jenisKesenianModel ? $jenisKesenianModel->id : null;

        $updateData = [
            'nama' => $request->nama,
            'nama_ketua' => $request->nama_ketua,
            'no_telp_ketua' => $request->no_telp_ketua,
            'alamat' => $request->alamat,
            
            'desa' => $wilayahDes ? $wilayahDes->kode : $request->desa, // Simpan KODE
            'kecamatan' => $wilayahKec ? $wilayahKec->kode : $request->kecamatan, // Simpan KODE
            'nama_kecamatan' => $request->kecamatan, // Simpan NAMA
            
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
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // max 10MB
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName);

            $importResult = $this->processExcelFile($filePath);

            if ($importResult['success']) {
                $message = 'Data berhasil diimport!';
                if ($importResult['stats']['duplicate'] > 0) {
                    $message .= " ({$importResult['stats']['duplicate']} data duplikat dilewati)";
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $importResult['stats']
                ]);
            } else {
                $message = 'Tidak ada data yang berhasil diimport.';
                if ($importResult['stats']['duplicate'] > 0) {
                    $message .= " ({$importResult['stats']['duplicate']} data duplikat ditemukan)";
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $importResult['errors'],
                    'stats' => $importResult['stats']
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processExcelFile($filePath)
    {
        $errors = [];
        $successCount = 0;
        $errorCount = 0;
        $duplicateCount = 0;
        $existingDataCache = []; 
        
        $organisasiTable = (new Organisasi)->getTable();
        
        $wilayahCache = Wilayah::all()->keyBy(function($item) {
            return strtolower($item->nama); // Key by lowercase name
        });

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/' . $filePath));
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            array_shift($rows); // Skip header

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; 

                try {
                    $validator = Validator::make([
                        'nama' => $row[0] ?? null,
                        'nomor_induk' => $row[1] ?? null,
                        'nama_jenis_kesenian' => $row[2] ?? null,
                        'nama_ketua' => $row[3] ?? null,
                        'no_telp_ketua' => $row[4] ?? null,
                        'alamat' => $row[5] ?? null,
                        'desa' => $row[6] ?? null,
                        'kecamatan' => $row[7] ?? null,
                        'jumlah_anggota' => $row[8] ?? null,
                    ], [
                        'nama' => 'required|string|max:255',
                        'nomor_induk' => "nullable|string|max:50|unique:$organisasiTable,nomor_induk", // pastikan nama tabel benar
                        'nama_jenis_kesenian' => 'required|string|max:255',
                        'nama_ketua' => 'required|string|max:200',
                        'no_telp_ketua' => 'required|string|max:20',
                        'alamat' => 'required|string',
                        'desa' => 'nullable|string|max:255',
                        'kecamatan' => 'required|string|max:255',
                        'jumlah_anggota' => 'nullable|integer|min:1',
                    ]);

                    if ($validator->fails()) {
                        $errorCount++;
                        $errors[] = "Baris $rowNumber: " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    $data = $validator->validated();

                    $namaOrganisasi = trim($data['nama']);
                    $jenisKesenian = trim($data['nama_jenis_kesenian']);

                    $cacheKey = $namaOrganisasi . '|' . $jenisKesenian;
                    if (!isset($existingDataCache[$cacheKey])) {
                        $existingDataCache[$cacheKey] = Organisasi::where('nama', $namaOrganisasi)
                            ->where('nama_jenis_kesenian', $jenisKesenian)
                            ->exists();
                    }

                    if ($existingDataCache[$cacheKey]) {
                        $duplicateCount++;
                        $errors[] = "Baris $rowNumber: Data duplikat - Organisasi '$namaOrganisasi' dengan jenis kesenian '$jenisKesenian' sudah ada";
                        continue;
                    }

                    if (!empty($data['nomor_induk'])) {
                        $nomorIndukExists = Organisasi::where('nomor_induk', $data['nomor_induk'])->exists();
                        if ($nomorIndukExists) {
                            $duplicateCount++;
                            $errors[] = "Baris $rowNumber: Nomor induk '{$data['nomor_induk']}' sudah digunakan";
                            continue;
                        }
                    }

                    $jenisKesenianModel = JenisKesenian::where('nama', $data['nama_jenis_kesenian'])->first();
                    $jenisKesenianId = $jenisKesenianModel ? $jenisKesenianModel->id : null;

                    $nomorInduk = $data['nomor_induk'];
                    if (empty($nomorInduk)) {
                        $lastOrganisasi = Organisasi::orderBy('id', 'desc')->first();
                        $nextId = $lastOrganisasi ? $lastOrganisasi->id + 1 : 1;
                        $nomorInduk = 'KS' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                    }
                    
                    $namaKecamatan = trim($data['kecamatan']);
                    $wilayahKec = $wilayahCache[strtolower($namaKecamatan)] ?? null; 
                    
                    $namaDesa = trim($data['desa']);
                    $wilayahDes = $wilayahCache[strtolower($namaDesa)] ?? null; 

                    if (empty($wilayahKec)) {
                         $errorCount++;
                         $errors[] = "Baris $rowNumber: Nama Kecamatan '{$namaKecamatan}' tidak ditemukan di database.";
                         continue;
                    }
                    
                    $kodeDesa = $wilayahDes ? $wilayahDes->kode : null;

                    Organisasi::create([
                        'nama' => $data['nama'],
                        'nomor_induk' => $nomorInduk,
                        'nama_ketua' => $data['nama_ketua'],
                        'no_telp_ketua' => $data['no_telp_ketua'],
                        'alamat' => $data['alamat'],
                        'desa' => $kodeDesa, // Simpan KODE
                        'kecamatan' => $wilayahKec->kode, // Simpan KODE
                        'nama_kecamatan' => $namaKecamatan, // Simpan NAMA
                        'jenis_kesenian' => $jenisKesenianId,
                        'nama_jenis_kesenian' => $data['nama_jenis_kesenian'],
                        'jumlah_anggota' => $data['jumlah_anggota'],
                        'tanggal_daftar' => now(),
                        'tanggal_expired' => now()->addYears(1),
                        'status' => 'Allow',
                    ]);

                    $successCount++;
                    $existingDataCache[$cacheKey] = true;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris $rowNumber: " . $e->getMessage();
                    continue;
                }
            }

            DB::commit();

            return [
                'success' => $successCount > 0,
                'stats' => [
                    'total' => count($rows),
                    'success' => $successCount,
                    'error' => $errorCount,
                    'duplicate' => $duplicateCount
                ],
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}