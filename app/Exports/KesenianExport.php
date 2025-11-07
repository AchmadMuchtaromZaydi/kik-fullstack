<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class KesenianExport implements WithStyles, WithEvents
{
    protected $data;
    protected $kecamatan;

    public function __construct($data, $kecamatan = null)
    {
        $this->data = $data;
        $this->kecamatan = $kecamatan;
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
        ];
    }

    /**
     * Mapping setiap baris data
     */
    public function map($no, $organisasi): array
    {
        $nomorInduk = $organisasi->nomor_induk;
        if (empty($nomorInduk) || $nomorInduk == 'Belum ada') {
            $nomorInduk = '-';
        }

        return [
            $no,
            $organisasi->nama,
            $nomorInduk,
            $organisasi->nama_jenis_kesenian ?? '-',
            $organisasi->alamat,
            $organisasi->nama_desa ?? $organisasi->desa ?? '-',
            $organisasi->nama_kecamatan ?? $organisasi->kecamatan ?? '-',
            $organisasi->nama_ketua,
            $organisasi->no_telp_ketua,
            $organisasi->tanggal_daftar
                ? Carbon::parse($organisasi->tanggal_daftar)->format('d/m/Y')
                : '-',
            $organisasi->tanggal_expired
                ? Carbon::parse($organisasi->tanggal_expired)->format('d/m/Y')
                : '-',
            $this->getStatusText($organisasi->status),
        ];
    }

    /**
     * Style default untuk header utama
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
     * Event: membuat isi laporan Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();

                // 1️⃣ Judul utama
                $worksheet->mergeCells('A1:L1');
                $worksheet->setCellValue('A1', 'DATA ORGANISASI KESENIAN');
                $worksheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $currentRow = 3; // mulai dari baris 3

                // 2️⃣ Kelompokkan berdasarkan kecamatan
                $grouped = $this->data->groupBy(function ($item) {
                    return $item->nama_kecamatan ?? $item->kecamatan ?? 'Tidak Terkategori';
                });

                foreach ($grouped as $kecamatan => $items) {
                    // Judul kecamatan
                    $worksheet->setCellValue("A{$currentRow}", 'KECAMATAN: ' . strtoupper($kecamatan ?? '-'));
                    $worksheet->mergeCells("A{$currentRow}:L{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F4FD'],
                        ],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $currentRow++;

                    // Header kolom
                    $worksheet->fromArray($this->headings(), null, "A{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}:L{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4CAF50'],
                        ],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $currentRow++;

                    // Data isi — mulai nomor dari 1 per kecamatan
                    $no = 1;
                    foreach ($items as $item) {
                        $worksheet->fromArray($this->map($no, $item), null, "A{$currentRow}");
                        $currentRow++;
                        $no++;
                    }

                    // Spasi antar kecamatan
                    $currentRow++;
                }

                // 3️⃣ Tambahkan border
                $highestRow = $worksheet->getHighestRow();
                if ($highestRow > 1) {
                    $worksheet->getStyle("A1:L{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                }

                // 4️⃣ Auto size kolom
                foreach (range('A', 'L') as $col) {
                    $worksheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    /**
     * Ubah status ke teks deskriptif
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
