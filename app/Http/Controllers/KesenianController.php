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
        // ✅ Query utama dengan eager loading untuk mencegah N+1
        $query = Organisasi::query()
            ->with(['kecamatanWilayah:id,kode,nama', 'desaWilayah:id,kode,nama']);

        // 🔍 FILTER PENCARIAN
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

        // 🎭 FILTER BERDASARKAN JENIS KESENIAN
        if ($jenisKesenian = $request->get('jenis_kesenian')) {
            $query->where('nama_jenis_kesenian', $jenisKesenian);
        }

        // 🏙️ FILTER BERDASARKAN KECAMATAN
        if ($kecamatan = $request->get('kecamatan')) {
            $query->whereHas('kecamatanWilayah', function ($q) use ($kecamatan) {
                $q->where('nama', $kecamatan);
            });
        }

        // Apakah ada pencarian atau filter aktif?
        $hasSearch = $request->filled('q') || $request->filled('jenis_kesenian') || $request->filled('kecamatan');

        // 🔢 URUTAN
        if ($hasSearch) {
            $query->orderByDesc('id');
        } else {
            $query->orderByRaw("
                CASE
                    WHEN status = 'Request' THEN 1
                    WHEN status = 'Denny' THEN 2
                    WHEN status = 'Allow' THEN 3
                    WHEN status = 'DataLama' THEN 4
                    ELSE 5
                END
            ")->orderByDesc('id');
        }

        // 📄 Pagination + Cache
        $perPage = $hasSearch ? 200 : 1000;
        $page = $request->get('page', 1);
        $cacheKey = "kesenian_index_{$page}_" . md5(json_encode($request->all()));

        $dataKesenian = Cache::remember($cacheKey, 300, fn() => $query->paginate($perPage));
        $pagination = $dataKesenian;

        // 🎭 Dropdown Jenis Kesenian (Cache 1 jam)
        $jenisKesenianList = Cache::remember('jenis_kesenian_dropdown', 3600, function () {
            return Organisasi::select('nama_jenis_kesenian')
                ->whereNotNull('nama_jenis_kesenian')
                ->where('nama_jenis_kesenian', '!=', '')
                ->distinct()
                ->orderBy('nama_jenis_kesenian')
                ->pluck('nama_jenis_kesenian')
                ->toArray();
        });

        // 🏙️ Dropdown Kecamatan (Cache 1 jam)
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
            'jenisKesenianList',
            'kecamatanList',
            'hasSearch',
            'pagination'
        ));
    }

    // 📄 Detail data kesenian
    public function show($id)
    {
        $item = Organisasi::with(['kecamatanWilayah', 'desaWilayah'])->find($id);

        if (!$item) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        return view('kesenian.show', compact('item'));
    }

    // ✏️ Edit data kesenian
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

    // 📥 Download (PDF / Excel)
    public function download(Request $request, $type)
    {
        $jenisKesenian = $request->get('jenis_kesenian');
        $kecamatan = $request->get('kecamatan');
        $q = $request->get('q');

        $query = Organisasi::query()
            ->with(['kecamatanWilayah:id,kode,nama', 'desaWilayah:id,kode,nama']);

        if ($jenisKesenian) {
            $query->where('nama_jenis_kesenian', $jenisKesenian);
        }

        if ($kecamatan) {
            $query->whereHas('kecamatanWilayah', fn($q2) => $q2->where('nama', $kecamatan));
        }

        if ($q) {
            $query->where(function ($qry) use ($q) {
                $qry->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhere('nama_ketua', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        $allData = $query->orderBy('id')->limit(5000)->get();

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

    // 🧾 Generate PDF
    private function generatePDF($data, $filename)
    {
        set_time_limit(300);
        try {
            if ($data->count() > 1000) {
                return back()->with('error', 'Terlalu banyak data untuk PDF. Gunakan Excel.');
            }

            $dataByKecamatan = $data->groupBy(fn($item) => $item->kecamatanWilayah->nama ?? 'Tidak Terkategori');

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

    // 📊 Generate Excel
    private function generateExcel($data, $filename)
    {
        try {
            return Excel::download(new KesenianExport($data), $filename . '.xlsx');
        } catch (Throwable $e) {
            Log::error('Excel Download Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat file Excel: ' . $e->getMessage());
        }
    }
}
