<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KesenianExport;
use Maatwebsite\Excel\Facades\Excel;

class KesenianController extends Controller
{
    /**
     * 📋 Menampilkan data kesenian dengan filter dan pagination
     */
    public function index(Request $request)
    {
        $query = Organisasi::query()
            ->with([
                'kecamatanWilayah:id,kode,nama',
                'desaWilayah:id,kode,nama',
                'ketua:id,organisasi_id,nama,telepon,whatsapp',
            ])
            ->withCount(['anggota', 'inventaris', 'dataPendukung']);

        // 🔍 Filter umum
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($q1) use ($q) {
                $q1->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhereHas('ketua', fn($q2) => $q2->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('desaWilayah', fn($q3) => $q3->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('kecamatanWilayah', fn($q4) => $q4->where('nama', 'like', "%{$q}%"));
            });
        }

        // 🎭 Filter jenis kesenian
        if ($request->filled('jenis_kesenian')) {
            $query->where('nama_jenis_kesenian', $request->jenis_kesenian);
        }

        // 🏙️ Filter kecamatan
        if ($request->filled('kecamatan')) {
            $query->whereHas('kecamatanWilayah', function ($q) use ($request) {
                $q->where('nama', $request->kecamatan);
            });
        }

        // ⚙️ Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🩵 Urutan prioritas
        $query->orderByRaw("
            CASE
                WHEN status = 'request' THEN 1
                ELSE 2
            END
        ")->orderByDesc('created_at');

        // 📄 Pagination
        $dataKesenian = $query->paginate(100)->withQueryString();

        // 🔽 Dropdown filter
        $jenisKesenianList = Organisasi::select('nama_jenis_kesenian')
            ->whereNotNull('nama_jenis_kesenian')
            ->distinct()
            ->pluck('nama_jenis_kesenian');

        $kecamatanList = Wilayah::where('kode', 'LIKE', '%.%.%')
            ->where('kode', 'NOT LIKE', '%.%.%.%')
            ->orderBy('nama')
            ->pluck('nama');

        return view('admin.kesenian.index', compact(
            'dataKesenian',
            'jenisKesenianList',
            'kecamatanList'
        ));
    }

    /**
     * 📥 Download PDF - Group by Kecamatan
     */
    public function download(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');

        $query = Organisasi::query()
            ->with([
                'kecamatanWilayah:id,kode,nama',
                'desaWilayah:id,kode,nama',
                'ketua:id,organisasi_id,nama,telepon,whatsapp,jabatan',
                'jenisKesenianObj:id,nama',
                'subKesenianObj:id,nama',
                'anggota:id,organisasi_id',
                'inventaris:id,organisasi_id',
                'dataPendukung:id,organisasi_id,image,tipe,validasi',
            ])
            ->withCount(['anggota', 'inventaris', 'dataPendukung']);

        // Filter opsional
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhereHas('ketua', fn($q2) => $q2->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('desaWilayah', fn($q3) => $q3->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('kecamatanWilayah', fn($q4) => $q4->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil semua data
        $dataKesenian = $query->get();

        // Group by kecamatan
        $groupedData = $dataKesenian->groupBy(function ($item) {
            return optional($item->kecamatanWilayah)->nama ?? $item->nama_kecamatan ?? 'Tanpa Kecamatan';
        })->sortKeys();

        // Generate PDF
        $pdf = Pdf::loadView('admin.kesenian.pdf_grouped', [
            'groupedData' => $groupedData,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('data_kesenian_by_kecamatan.pdf');
    }

    /**
     * 📊 Download Excel - Tetap XLSX, bukan PDF
     */
    public function downloadExcel(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1024M');

        $query = Organisasi::query()
            ->with([
                'kecamatanWilayah:id,kode,nama',
                'desaWilayah:id,kode,nama',
                'ketua:id,organisasi_id,nama,telepon,whatsapp',
            ])
            ->withCount(['anggota', 'inventaris', 'dataPendukung']);

        // Filter opsional
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nomor_induk', 'like', "%{$q}%")
                    ->orWhere('nama_jenis_kesenian', 'like', "%{$q}%")
                    ->orWhereHas('kecamatanWilayah', fn($q4) => $q4->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('desaWilayah', fn($q5) => $q5->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('ketua', fn($q6) => $q6->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kecamatan')) {
            $query->whereHas('kecamatanWilayah', function ($q) use ($request) {
                $q->where('nama', $request->kecamatan);
            });
        }

        // Ambil data lengkap
        $data = $query->get();

        $fileName = 'Data_Kesenian_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new KesenianExport($data), $fileName);
    }
}
