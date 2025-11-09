<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Kesenian by Kecamatan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <?php $__currentLoopData = $groupedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h2>Kecamatan: <?php echo e($kecamatan); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Organisasi</th>
                    <th>Jenis Kesenian</th>
                    <th>Sub Kesenian</th>
                    <th>Ketua</th>
                    <th>No. Telp Ketua</th>
                    <th>Desa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($item->nama ?? '-'); ?></td>
                        <td><?php echo e($item->jenisKesenianObj->nama ?? ($item->nama_jenis_kesenian ?? '-')); ?></td>
                        <td><?php echo e($item->subKesenianObj->nama ?? ($item->sub_kesenian_nama ?? '-')); ?></td>
                        <td><?php echo e($item->ketua->nama ?? ($item->nama_ketua ?? '-')); ?></td>
                        <td><?php echo e($item->ketua->telepon ?? ($item->ketua->whatsapp ?? ($item->no_telp_ketua ?? '-'))); ?>

                        </td>
                        <td><?php echo e($item->desaWilayah->nama ?? ($item->nama_desa ?? '-')); ?></td>
                        <td><?php echo e(ucfirst($item->status ?? '-')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php if(!$loop->last): ?>
            <div class="page-break"></div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>

</html>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/kesenian/pdf_grouped.blade.php ENDPATH**/ ?>