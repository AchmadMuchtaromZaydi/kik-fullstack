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
        $query = Organisasi::query()->select(['id', 'nama', 'nomor_induk', 'nama_jenis_kesenian', 'nama_sub_kesenian', 'alamat', 'nama_ketua', 'no_telp_ketua', 'tanggal_daftar', 'tanggal_expired', 'status']);

        // Cek apakah ada pencarian atau filter
        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // Pencarian berdasarkan multiple parameter
        if ($q = $request->get('q')) {
            $query->where(function ($qry) use ($q) {
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
            $query
                ->orderByRaw(
                    "
                CASE
                    WHEN status = 'Request' THEN 1
                    WHEN status = 'Denny' THEN 2
                    WHEN status = 'Allow' THEN 3
                    WHEN status = 'DataLama' THEN 4
                    ELSE 5
                END
            ",
                )
                ->orderBy('id', 'desc');
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

        return view('admin.kesenian.index', compact('dataKesenian', 'jenisKesenian', 'kecamatanList', 'hasSearch'));
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
        $kecamatan = $request->get('kecamatan');

        // Ambil semua data kesenian
        $query = Organisasi::query()
            ->select([
                'id', 'nama', 'nomor_induk', 'nama_jenis_kesenian',
                'nama_sub_kesenian', 'alamat', 'nama_ketua', 'no_telp_ketua',
                'tanggal_daftar', 'tanggal_expired', 'status', 'desa', 'kecamatan', 'jumlah_anggota'
            ]);

        // Filter opsional berdasarkan jenis kesenian
        if ($jenisKesenian) {
            $query->where('nama_jenis_kesenian', $jenisKesenian);
        }

        // Filter opsional berdasarkan kecamatan (jika dipilih)
        if ($kecamatan) {
            $query->where('kecamatan', $kecamatan);
        }

        $allData = $query->orderBy('kecamatan')->orderBy('nama')->get();

        // Validasi untuk Excel - pastikan semua data memiliki nomor induk
        if ($type === 'excel') {
            $dataWithoutNomorInduk = $allData->filter(function ($item) {
                return empty($item->nomor_induk) || $item->nomor_induk == 'Belum ada';
            });

            if ($dataWithoutNomorInduk->count() > 0) {
                return back()->with('error', 
                    'Tidak dapat mengexport ke Excel karena terdapat ' . $dataWithoutNomorInduk->count() . 
                    ' data tanpa nomor induk. Silakan periksa data terlebih dahulu.'
                );
            }
        }

        $filename = 'data_kesenian';
        
        // Tambahkan info filter ke filename
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
        // Kelompokkan data berdasarkan kecamatan
        $dataByKecamatan = $data->groupBy('kecamatan');

        $pdf = Pdf::loadView('admin.kesenian.export-pdf', [
            'dataByKecamatan' => $dataByKecamatan,
            'tanggalExport' => now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->download($filename . '.pdf');
    }

    private function generateExcel($data, $filename)
    {
        return Excel::download(new KesenianExport($data), $filename . '.xlsx');
    }

    public function update(Request $request, $id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Validasi kecamatan berdasarkan data dari model Wilayah
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
            'desa' => 'nullable|string|max:255',
            'kecamatan' => $kecamatanRule,
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

            // Process Excel file
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
        $existingDataCache = []; // Cache untuk data yang sudah ada

        try {
            // Load Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/' . $filePath));
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena kita skipped header dan index starts dari 0

                try {
                    // Validate row data
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
                        'nomor_induk' => 'nullable|string|max:50|unique:organisasi,nomor_induk',
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

                    // CEK DUPLIKASI: Nama Organisasi + Jenis Kesenian
                    $namaOrganisasi = trim($data['nama']);
                    $jenisKesenian = trim($data['nama_jenis_kesenian']);

                    // Gunakan cache untuk mengurangi query ke database
                    $cacheKey = $namaOrganisasi . '|' . $jenisKesenian;
                    if (!isset($existingDataCache[$cacheKey])) {
                        $existingDataCache[$cacheKey] = Organisasi::where('nama', $namaOrganisasi)
                            ->where('nama_jenis_kesenian', $jenisKesenian)
                            ->exists();
                    }

                    if ($existingDataCache[$cacheKey]) {
                        $duplicateCount++;
                        $errors[] = "Baris $rowNumber: Data duplikat - Organisasi '$namaOrganisasi' dengan jenis kesenian '$jenisKesenian' sudah ada di database";
                        continue;
                    }

                    // CEK DUPLIKASI: Nomor Induk (jika ada)
                    if (!empty($data['nomor_induk'])) {
                        $nomorIndukExists = Organisasi::where('nomor_induk', $data['nomor_induk'])->exists();
                        if ($nomorIndukExists) {
                            $duplicateCount++;
                            $errors[] = "Baris $rowNumber: Nomor induk '{$data['nomor_induk']}' sudah digunakan";
                            continue;
                        }
                    }

                    // Check if jenis kesenian exists
                    $jenisKesenianModel = JenisKesenian::where('nama', $data['nama_jenis_kesenian'])->first();
                    $jenisKesenianId = $jenisKesenianModel ? $jenisKesenianModel->id : null;

                    // Generate nomor induk if empty
                    $nomorInduk = $data['nomor_induk'];
                    if (empty($nomorInduk)) {
                        $lastOrganisasi = Organisasi::orderBy('id', 'desc')->first();
                        $nextId = $lastOrganisasi ? $lastOrganisasi->id + 1 : 1;
                        $nomorInduk = 'KS' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                    }

                    // Create organisasi
                    Organisasi::create([
                        'nama' => $data['nama'],
                        'nomor_induk' => $nomorInduk,
                        'nama_ketua' => $data['nama_ketua'],
                        'no_telp_ketua' => $data['no_telp_ketua'],
                        'alamat' => $data['alamat'],
                        'desa' => $data['desa'],
                        'kecamatan' => $data['kecamatan'],
                        'nama_kecamatan' => $data['kecamatan'],
                        'jenis_kesenian' => $jenisKesenianId,
                        'nama_jenis_kesenian' => $data['nama_jenis_kesenian'],
                        'jumlah_anggota' => $data['jumlah_anggota'],
                        'tanggal_daftar' => now(),
                        'tanggal_expired' => now()->addYears(1),
                        'status' => 'Allow',
                    ]);

                    $successCount++;

                    // Tambahkan ke cache setelah berhasil create
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