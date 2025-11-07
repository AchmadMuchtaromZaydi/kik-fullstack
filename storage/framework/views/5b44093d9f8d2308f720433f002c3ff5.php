
<?php $__env->startSection('title', 'Data Inventaris'); ?>

<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Inventaris Barang</h5>

        
        <?php if($inventaris->count() < 5): ?>
            <a href="<?php echo e(route('user.inventaris.create')); ?>" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle"></i> Tambah Inventaris
            </a>
        <?php else: ?>
            <span class="badge bg-warning text-dark">Kuota inventaris sudah penuh</span>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php elseif(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        
        <?php if($inventaris->count() < 5): ?>
            <div class="alert alert-info">Silakan tambahkan data inventaris minimal sampai 5 item.</div>
        <?php elseif($inventaris->count() >= 5): ?>
            <div class="alert alert-danger">Jumlah inventaris sudah mencapai batas maksimal 5 item.</div>
        <?php endif; ?>

        <?php if($inventaris->isEmpty()): ?>
            <div class="alert alert-info">Belum ada data inventaris barang.</div>
        <?php else: ?>
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Tahun Pembelian</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($inv->nama); ?></td>
                        <td class="text-center"><?php echo e($inv->jumlah); ?></td>
                        <td class="text-center"><?php echo e($inv->pembelian_th ?? '-'); ?></td>
                        <td class="text-center">
                            <?php if($inv->kondisi == 'Baru'): ?>
                                <span class="badge bg-success"><?php echo e($inv->kondisi); ?></span>
                            <?php elseif($inv->kondisi == 'Bekas'): ?>
                                <span class="badge bg-warning text-dark"><?php echo e($inv->kondisi); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?php echo e($inv->kondisi); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($inv->keterangan ?? '-'); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('user.inventaris.edit', $inv->id)); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>

                            <form action="<?php echo e(route('user.inventaris.destroy', $inv->id)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus inventaris ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/user/inventaris/index.blade.php ENDPATH**/ ?>