<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Data Kesenian Semua Kecamatan</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 2px 0;
            font-size: 9px;
        }

        .kecamatan-header {
            background-color: #e8f4fd;
            padding: 6px;
            margin-top: 12px;
            margin-bottom: 4px;
            border-left: 4px solid #2E86AB;
            font-weight: bold;
            font-size: 11px;

            /* PERBAIKAN: Gunakan 'auto' agar tidak ada page break sebelum item pertama */
            page-break-before: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;

            /* PERBAIKAN: Izinkan tabel terpotong antar halaman */
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 3px 5px;
            word-wrap: break-word;
            vertical-align: top;
        }

        th {
            background-color: #2E86AB;
            color: #fff;
            font-size: 9px;
            text-align: center;
        }

        /* PERBAIKAN: Ulangi header tabel (thead) di setiap halaman baru */
        thead {
            display: table-header-group;
        }

        /* PERBAIKAN: Usahakan agar baris (tr) tidak terpotong di tengah */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        td {
            font-size: 8.5px;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            display: inline-block;
        }

        .bg-success {
            background-color: #d4edda;
            color: #155724;
        }

        .bg-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .bg-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .bg-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .footer {
            margin-top: 10px;
            text-align: right;
            font-size: 8.5px;
            color: #666;
            border-top: 1px solid #aaa;
            padding-top: 4px;
        }

        /* HAPUS: Kita tidak perlu kelas .page-break lagi */
        /* .page-break { ... } */
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA ORGANISASI KESENIAN SEMUA KECAMATAN</h1>
        <p>Tanggal Export: <?php echo e($tanggalExport); ?></p>
    </div>

    <?php $__currentLoopData = $dataByKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan => $dataKesenian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="kecamatan-header" style="<?php if(!$loop->first): ?> page-break-before: always; <?php endif; ?>">
            KECAMATAN: <?php echo e($kecamatan ?? 'Tidak Terkategori'); ?>

        </div>

        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="17%">Nama Organisasi</th>
                    <th width="10%">Nomor Induk</th>
                    <th width="13%">Jenis Kesenian</th>
                    <th width="23%">Alamat</th>
                    <th width="13%">Ketua</th>
                    <th width="8%">Tgl Daftar</th>
                    <th width="8%">Tgl Expired</th>
                    <th width="7%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $dataKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($item->nama); ?></td>
                        <td><?php echo e($item->nomor_induk ?? '-'); ?></td>
                        <td><?php echo e($item->nama_jenis_kesenian ?? '-'); ?></td>
                        <td>
                            <?php echo e($item->alamat); ?>

                            <?php if($item->nama_desa || $item->desa): ?>
                                <br><small>Desa <?php echo e($item->nama_desa ?? $item->desa); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($item->nama_ketua); ?>

                            <?php if($item->no_telp_ketua): ?>
                                <br><small><?php echo e($item->no_telp_ketua); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php echo e($item->tanggal_daftar && $item->tanggal_daftar != '0000-00-00'
                                ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y')
                                : '-'); ?>

                        </td>
                        <td class="text-center">
                            <?php echo e($item->tanggal_expired && $item->tanggal_expired != '0000-00-00'
                                ? \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y')
                                : '-'); ?>

                        </td>
                        <td class="text-center">
                            <?php
                                $statusColors = [
                                    'Request' => 'bg-warning',
                                    'Allow' => 'bg-success',
                                    'Denny' => 'bg-danger',
                                    'DataLama' => 'bg-info',
                                ];
                                $statusTexts = [
                                    'Request' => 'Menunggu',
                                    'Allow' => 'Diterima',
                                    'Denny' => 'Ditolak',
                                    'DataLama' => 'Data Lama',
                                ];
                            ?>
                            <span class="badge <?php echo e($statusColors[$item->status] ?? ''); ?>">
                                <?php echo e($statusTexts[$item->status] ?? $item->status); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="footer">
        Total Data: <?php echo e($dataByKecamatan->flatten()->count()); ?> Organisasi |
        Total Kecamatan: <?php echo e($dataByKecamatan->count()); ?><br>
        Dicetak pada: <?php echo e($tanggalExport); ?>

    </div>
</body>

</html>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/kesenian/export-pdf.blade.php ENDPATH**/ ?>