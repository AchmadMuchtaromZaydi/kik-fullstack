<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Organisasi</th>
            <th>Jenis Kesenian</th>
            <th>Kecamatan</th>
            <th>Desa</th>
            <th>Ketua</th>
            <th>Telepon</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $dataKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($item->nama); ?></td>
                <td><?php echo e($item->nama_jenis_kesenian); ?></td>
                <td><?php echo e($item->kecamatanWilayah->nama ?? '-'); ?></td>
                <td><?php echo e($item->desaWilayah->nama ?? '-'); ?></td>
                <td><?php echo e($item->ketua->nama ?? '-'); ?></td>
                <td><?php echo e($item->ketua->telepon ?? '-'); ?></td>
                <td><?php echo e(ucfirst($item->status)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/kesenian/pdf.blade.php ENDPATH**/ ?>