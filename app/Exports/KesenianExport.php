<?php

namespace App\Exports;

// PERBAIKAN: Hapus 'FromCollection', 'WithHeadings', 'WithMapping' dari 'use'
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

// PERBAIKAN KUNCI: Hapus implements FromCollection, WithHeadings, WithMapping
// Kita hanya perlu WithStyles (untuk style default A1) dan WithEvents (untuk membuat laporan)
class KesenianExport implements WithStyles, WithEvents
{
    protected $data;
    protected $kecamatan;

    public function __construct($data, $kecamatan = null)
    {
        $this->data = $data;
        $this->kecamatan = $kecamatan;
    }

    // Metode collection() tidak diperlukan lagi karena kita tidak meng-implementasi FromCollection

    /**
     * Header kolom (Dipanggil manual dari event)
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
     * Mapping setiap baris (Dipanggil manual dari event)
     */
    public function map($organisasi): array
    {
        // Cek nomor induk dan beri nilai default jika kosong
        $nomorInduk = $organisasi->nomor_induk;
        if (empty($nomorInduk) || $nomorInduk == 'Belum ada') {
            $nomorInduk = '-';
        }

        return [
            $organisasi->id,
            $organisasi->nama,
            $nomorInduk,
            $organisasi->nama_jenis_kesenian,
            $organisasi->alamat,
            // Gunakan nama_desa, fallback ke kode jika null
            $organisasi->nama_desa ?? $organisasi->desa ?? '-', 
            // Gunakan nama_kecamatan, fallback ke kode jika null
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
            $organisasi->jumlah_anggota ?? 0,
        ];
    }

    /**
     * Style default (Ini hanya berlaku untuk A1 sebelum event berjalan)
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
     * Di sinilah semua keajaiban terjadi.
     * Karena FromCollection dihapus, event ini akan berjalan pada sheet KOSONG.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();

                // 1. Buat judul utama di baris 1
                $worksheet->mergeCells('A1:M1');
                $worksheet->setCellValue('A1', 'DATA ORGANISASI KESENIAN');
                $worksheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // 2. Mulai dari baris 3 (membiarkan baris 2 kosong untuk spasi)
                $currentRow = 3; 
                
                $grouped = $this->data->groupBy(function($item) {
                    return $item->nama_kecamatan ?? $item->kecamatan ?? 'Tidak Terkategori';
                });

                foreach ($grouped as $kecamatan => $items) {
                    // 3. Tambah judul kecamatan
                    $worksheet->setCellValue("A{$currentRow}", 'KECAMATAN: ' . (strtoupper($kecamatan) ?: '-'));
                    $worksheet->mergeCells("A{$currentRow}:M{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 13],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F4FD'], // Biru muda
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $currentRow++;

                    // 4. Header kolom untuk grup ini
                    $worksheet->fromArray($this->headings(), null, "A{$currentRow}");
                    $worksheet->getStyle("A{$currentRow}:M{$currentRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4CAF50'], // Hijau
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                    $currentRow++;

                    // 5. Isi data untuk kecamatan ini
                    foreach ($items as $item) {
                        $rowData = $this->map($item);
                        $worksheet->fromArray($rowData, null, "A{$currentRow}");
                        $currentRow++;
                    }

                    // 6. Spasi antar kecamatan
                    $currentRow++;
                }

                // 7. Tambahkan border untuk seluruh data yang ada
                $highestRow = $worksheet->getHighestRow();
                if ($highestRow > 1) { // Hanya tambahkan border jika ada data
                    $worksheet->getStyle("A1:M{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                }

                // 8. Auto size setiap kolom
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