<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class KesenianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $data;
    protected $kecamatan;

    public function __construct($data, $kecamatan = null)
    {
        $this->data = $data;
        $this->kecamatan = $kecamatan;
    }

    /**
     * Data utama untuk export
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'NO',
            'NAMA ORGANISASI',
            'NOMOR INDUK',
            'JENIS KESENIAN',
            'ALAMAT',
            'DESA',
            'KECAMATAN',
            'NAMA KETUA',
            'NO. TELP KETUA',
            'TANGGAL DAFTAR',
            'TANGGAL EXPIRED',
            'STATUS',
            'JUMLAH ANGGOTA',
        ];
    }

    /**
     * Mapping setiap baris
     */
    public function map($organisasi): array
    {
        return [
            $organisasi->id,
            $organisasi->nama,
            $organisasi->nomor_induk,
            $organisasi->nama_jenis_kesenian,
            $organisasi->alamat,
            $organisasi->desa,
            $organisasi->nama_kecamatan ?? $organisasi->kecamatan,
            $organisasi->nama_ketua,
            $organisasi->no_telp_ketua,
            $organisasi->tanggal_daftar
                ? Carbon::parse($organisasi->tanggal_daftar)->format('d/m/Y')
                : '-',
            $organisasi->tanggal_expired
                ? Carbon::parse($organisasi->tanggal_expired)->format('d/m/Y')
                : '-',
            $this->getStatusText($organisasi->status),
            $organisasi->jumlah_anggota ?? 0,
        ];
    }

    /**
     * Style default (header)
     * Note: styling yang bergantung pada range dinamis dilakukan di AfterSheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E86AB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Event setelah sheet dibuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Gunakan delegate sehingga kita bekerja dengan PhpSpreadsheet\Worksheet
                $worksheet = $event->sheet->getDelegate();

                // Buat judul utama di baris 1
                $worksheet->mergeCells('A1:M1');
                $worksheet->setCellValue('A1', 'DATA ORGANISASI KESENIAN');
                $worksheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $currentRow = 3;
                // Group by kecamatan (menggunakan nama_kecamatan jika ada)
                $grouped = $this->data->groupBy(function($item) {
                    return $item->nama_kecamatan ?? $item->kecamatan ?? '-';
                });

                foreach ($grouped as $kecamatan => $items) {
                    // Tambah judul kecamatan
                    $worksheet->setCellValue("A{$currentRow}", 'KECAMATAN: ' . ($kecamatan ?: '-'));
                    $worksheet->mergeCells("A{$currentRow}:M{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 13],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F4FD'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $currentRow++;

                    // Header kolom
                    $worksheet->fromArray($this->headings(), null, "A{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}:M{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4CAF50'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $currentRow++;

                    // Isi data untuk kecamatan ini
                    foreach ($items as $item) {
                        // Panggil map untuk dapatkan array nilai, lalu tulis ke sheet delegate
                        $rowData = $this->map($item);
                        $worksheet->fromArray($rowData, null, "A{$currentRow}");
                        $currentRow++;
                    }

                    // Spasi antar kecamatan
                    $currentRow++;
                }

                // Tambahkan border untuk seluruh data yang ada
                $highestRow = $worksheet->getHighestRow();
                $worksheet->getStyle("A1:M{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Auto size setiap kolom
                foreach (range('A', 'M') as $col) {
                    $worksheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    /**
     * Konversi status ke teks yang lebih jelas
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'Request' => 'Menunggu',
            'Allow' => 'Diterima',
            'Denny' => 'Ditolak',
            'DataLama' => 'Data Lama',
        ];

        return $statusTexts[$status] ?? $status;
    }
}
