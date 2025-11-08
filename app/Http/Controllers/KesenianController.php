<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\JenisKesenian;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KesenianExport;
use Throwable;

class KesenianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $organisasiTable = (new Organisasi)->getTable();

        $query = Organisasi::query()
            ->leftJoin('wilayah as kec', "$organisasiTable.kecamatan", '=', 'kec.kode')
            ->leftJoin('wilayah as des', "$organisasiTable.desa", '=', 'des.kode')
            ->select([
                "$organisasiTable.id", "$organisasiTable.nama", "$organisasiTable.nomor_induk",
                "$organisasiTable.nama_jenis_kesenian", "$organisasiTable.nama_sub_kesenian",
                "$organisasiTable.alamat", "$organisasiTable.nama_ketua", "$organisasiTable.no_telp_ketua",
                "$organisasiTable.tanggal_daftar", "$organisasiTable.tanggal_expired", "$organisasiTable.status",
                "$organisasiTable.kecamatan", "$organisasiTable.desa",
                'kec.nama as nama_kecamatan',
                'des.nama as nama_desa'
            ]);

        // FILTER
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

        if ($jenisKesenian = $request->get('jenis_kesenian')) {
            $query->where("$organisasiTable.nama_jenis_kesenian", $jenisKesenian);
        }

        if ($kecamatan = $request->get('kecamatan')) {
            $query->where('kec.nama', $kecamatan);
        }

        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // SORT
        if ($hasSearch) {
            $query->orderBy("$organisasiTable.id", 'desc');
        } else {
            $query->orderByRaw("
                CASE
                    WHEN $organisasiTable.status = 'Request' THEN 1
                    WHEN $organisasiTable.status = 'Denny' THEN 2
                    WHEN $organisasiTable.status = 'Allow' THEN 3
                    WHEN $organisasiTable.status = 'DataLama' THEN 4
                    ELSE 5
                END
            ")->orderBy("$organisasiTable.id", 'desc');
        }

        // PAGINATION SELALU DIGUNAKAN
        $perPage = $hasSearch ? 200 : 1000;
        $page = $request->get('page', 1);

        $cacheKey = "kesenian_index_{$page}_" . md5(json_encode($request->all()));

        $dataKesenian = Cache::remember($cacheKey, 300, function () use ($query, $perPage) {
            return $query->paginate($perPage);
        });

        $pagination = $dataKesenian;

        // DROPDOWN CACHE
        $jenisKesenian = Cache::remember('jenis_kesenian_dropdown', 3600, function () {
            return Organisasi::whereNotNull('nama_jenis_kesenian')
                ->where('nama_jenis_kesenian', '!=', '')
                ->select('nama_jenis_kesenian')
                ->distinct()
                ->orderBy('nama_jenis_kesenian')
                ->pluck('nama_jenis_kesenian')
                ->toArray();
        });

        $kecamatanList = Cache::remember('kecamatan_list_dropdown', 3600, function () {
            return Wilayah::where('kode', 'LIKE', '%.%.%')
                ->where('kode', 'NOT LIKE', '%.%.%.%')
                ->where('kode', '!=', '35.10')
                ->orderBy('nama')
                ->pluck('nama')
                ->toArray();
        });

        return view('admin.kesenian.index', compact(
            'dataKesenian',
            'jenisKesenian',
            'kecamatanList',
            'hasSearch',
            'pagination'
        ));
    }

    public function show($id)
    {
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

        return view('kesenian.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Organisasi::find($id);
        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $jenisKesenian = JenisKesenian::orderBy('nama')->pluck('nama')->toArray();

        $kecamatanList = Wilayah::where('kode', 'LIKE', '%.%.%')
            ->where('kode', 'NOT LIKE', '%.%.%.%')
            ->where('kode', '!=', '35.10')
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();

        return view('kesenian.edit', compact('item', 'jenisKesenian', 'kecamatanList'));
    }

    public function download(Request $request, $type)
    {
        $jenisKesenian = $request->get('jenis_kesenian');
        $kecamatan = $request->get('kecamatan');
        $q = $request->get('q');

        $organisasiTable = (new Organisasi)->getTable();

        $query = Organisasi::query()
            ->leftJoin('wilayah as kec', "$organisasiTable.kecamatan", '=', 'kec.kode')
            ->leftJoin('wilayah as des', "$organisasiTable.desa", '=', 'des.kode')
            ->select([
                "$organisasiTable.id", "$organisasiTable.nama", "$organisasiTable.nomor_induk",
                "$organisasiTable.nama_jenis_kesenian", "$organisasiTable.nama_sub_kesenian",
                "$organisasiTable.alamat", "$organisasiTable.nama_ketua", "$organisasiTable.no_telp_ketua",
                "$organisasiTable.tanggal_daftar", "$organisasiTable.tanggal_expired", "$organisasiTable.status",
                "$organisasiTable.jumlah_anggota", "$organisasiTable.kecamatan", "$organisasiTable.desa",
                'kec.nama as nama_kecamatan', 'des.nama as nama_desa'
            ]);

        if ($jenisKesenian) $query->where("$organisasiTable.nama_jenis_kesenian", $jenisKesenian);
        if ($kecamatan) $query->where('kec.nama', $kecamatan);
        if ($q) {
            $query->where(function ($qry) use ($q, $organisasiTable) {
                $qry->where("$organisasiTable.nama", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nomor_induk", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nama_jenis_kesenian", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.nama_ketua", 'like', "%{$q}%")
                    ->orWhere("$organisasiTable.alamat", 'like', "%{$q}%");
            });
        }

        $allData = $query->orderBy('kec.nama')->orderBy("$organisasiTable.nama")->limit(5000)->get();

        $filename = 'data_kesenian';
        if ($kecamatan) $filename .= '_' . str_replace(' ', '_', strtolower($kecamatan));
        if ($jenisKesenian) $filename .= '_' . str_replace(' ', '_', strtolower($jenisKesenian));
        if (!$kecamatan && !$jenisKesenian && !$q) $filename .= '_semua_kecamatan';

        return match ($type) {
            'pdf' => $this->generatePDF($allData, $filename),
            'excel' => $this->generateExcel($allData, $filename),
            default => back()->with('error', 'Format download tidak valid.')
        };
    }

    private function generatePDF($data, $filename)
    {
        set_time_limit(300);
        try {
            $dataByKecamatan = $data->groupBy(fn($item) => $item->nama_kecamatan ?? 'Tidak Terkategori');

            if ($data->count() > 1000) {
                return back()->with('error', 'Terlalu banyak data untuk PDF. Gunakan Excel.');
            }

            $pdf = Pdf::loadView('kesenian.export-pdf', [
                'dataByKecamatan' => $dataByKecamatan,
                'tanggalExport' => now()->format('d/m/Y H:i:s')
            ]);

            return $pdf->download($filename . '.pdf');
        } catch (Throwable $e) {
            Log::error('PDF Download Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    private function generateExcel($data, $filename)
    {
        return Excel::download(new KesenianExport($data), $filename . '.xlsx');
    }

    public function showImportForm()
    {
        return view('admin.kesenian.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName, 'public');

            $result = $this->processExcelFile(storage_path('app/public/' . $filePath));

            if ($result['success']) {
                $msg = "Data berhasil diimport! ({$result['stats']['success']} data)";
                if ($result['stats']['duplicate'] > 0)
                    $msg .= " ({$result['stats']['duplicate']} duplikat dilewati)";
                return back()->with('success', $msg);
            }

            $errorMsg = implode('<br>', $result['errors']);
            return back()->with('error', 'Gagal import sebagian:<br>' . $errorMsg);
        } catch (Throwable $e) {
            Log::error('Import error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    private function processExcelFile($filePath)
    {
        $errors = [];
        $success = $error = $dup = 0;
        $organisasiTable = (new Organisasi)->getTable();

        $wilayahCache = Wilayah::all()->keyBy(fn($w) => strtolower($w->nama));
        $jenisCache = JenisKesenian::all()->keyBy(fn($j) => strtolower($j->nama));
        $existCache = [];

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $rows = $spreadsheet->getActiveSheet()->toArray();
            array_shift($rows);

            DB::beginTransaction();

            foreach ($rows as $i => $row) {
                $no = $i + 2;

                $data = [
                    'nama' => trim($row[0] ?? ''),
                    'nomor_induk' => trim($row[1] ?? ''),
                    'nama_jenis_kesenian' => trim($row[2] ?? ''),
                    'nama_ketua' => trim($row[3] ?? ''),
                    'no_telp_ketua' => trim($row[4] ?? ''),
                    'alamat' => trim($row[5] ?? ''),
                    'desa' => trim($row[6] ?? ''),
                    'kecamatan' => trim($row[7] ?? ''),
                    'jumlah_anggota' => (int)($row[8] ?? 0),
                ];

                $validator = Validator::make($data, [
                    'nama' => 'required|string|max:255',
                    'nomor_induk' => "nullable|string|max:50|unique:$organisasiTable,nomor_induk",
                    'nama_jenis_kesenian' => 'required|string|max:255',
                    'nama_ketua' => 'required|string|max:200',
                    'no_telp_ketua' => 'required|string|max:20',
                    'alamat' => 'required|string',
                    'kecamatan' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    $error++;
                    $errors[] = "Baris $no: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                $key = strtolower($data['nama'] . '|' . $data['nama_jenis_kesenian']);
                if (!isset($existCache[$key])) {
                    $existCache[$key] = Organisasi::where('nama', $data['nama'])
                        ->where('nama_jenis_kesenian', $data['nama_jenis_kesenian'])
                        ->exists();
                }
                if ($existCache[$key]) {
                    $dup++;
                    continue;
                }

                $kec = $wilayahCache[strtolower($data['kecamatan'])] ?? null;
                if (!$kec) {
                    $error++;
                    $errors[] = "Baris $no: Kecamatan '{$data['kecamatan']}' tidak ditemukan.";
                    continue;
                }

                $desa = $wilayahCache[strtolower($data['desa'])] ?? null;
                $jenis = $jenisCache[strtolower($data['nama_jenis_kesenian'])] ?? null;

                Organisasi::create([
                    'nama' => $data['nama'],
                    'nomor_induk' => $data['nomor_induk'] ?: null,
                    'nama_ketua' => $data['nama_ketua'],
                    'no_telp_ketua' => $data['no_telp_ketua'],
                    'alamat' => $data['alamat'],
                    'desa' => $desa?->kode,
                    'kecamatan' => $kec->kode,
                    'nama_kecamatan' => $data['kecamatan'],
                    'jenis_kesenian' => $jenis?->id,
                    'nama_jenis_kesenian' => $data['nama_jenis_kesenian'],
                    'jumlah_anggota' => $data['jumlah_anggota'] ?? 0,
                    'tanggal_daftar' => now(),
                    'tanggal_expired' => now()->addYear(),
                    'status' => 'Allow',
                ]);

                $success++;
                $existCache[$key] = true;
            }

            DB::commit();

            return [
                'success' => $success > 0,
                'stats' => ['total' => count($rows), 'success' => $success, 'error' => $error, 'duplicate' => $dup],
                'errors' => $errors,
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
