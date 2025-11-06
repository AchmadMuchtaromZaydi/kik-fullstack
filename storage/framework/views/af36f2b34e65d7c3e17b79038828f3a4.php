

<?php $__env->startSection('title', 'Data Organisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary"><i class="fas fa-building me-2"></i>Data Organisasi</h4>
        
        <?php if($organisasi->isEmpty()): ?>
            <a href="<?php echo e(route('user.organisasi.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Organisasi
            </a>
        <?php endif; ?>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php elseif(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php elseif(session('warning')): ?>
        <div class="alert alert-warning"><?php echo e(session('warning')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if($organisasi->isEmpty()): ?>
                <div class="alert alert-info mb-0">Belum ada data organisasi yang ditambahkan.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nama Organisasi</th>
                                <th>Jenis Kesenian</th>
                                <th>Sub Kesenian</th>
                                <th>Alamat</th>
                                <th>Jumlah Anggota</th>
                                <th>Tanggal Berdiri</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $organisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($i + 1); ?></td>
                                    <td><?php echo e($org->nama); ?></td>
                                    <td><?php echo e($org->jenisKesenianObj->nama ?? '-'); ?></td>
                                    <td><?php echo e($org->subKesenianObj->nama ?? '-'); ?></td>
                                    <td>
                                        <?php
                                            $alamat = $org->alamat_lengkap ?? '';
                                            $desa = $org->desaObj->nama ?? '';
                                            $kecamatan = $org->kecamatanObj->nama ?? '';
                                            $kabupaten = $org->kabupatenObj->nama ?? '';
                                        ?>
                                        <small class="text-muted">
                                            <?php echo e($alamat ? $alamat . ', ' : ''); ?>

                                            <?php echo e($desa ? 'Desa ' . $desa . ', ' : ''); ?>

                                            <?php echo e($kecamatan ? 'Kec. ' . $kecamatan . ', ' : ''); ?>

                                            <?php echo e($kabupaten ? $kabupaten : ''); ?>

                                        </small>
                                    </td>
                                    <td><?php echo e($org->jumlah_anggota); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($org->tanggal_berdiri)->format('d-m-Y')); ?></td>
                                    <td>
                                        <span class="badge
                                            <?php if($org->status == 'Request'): ?> bg-warning
                                            <?php elseif($org->status == 'Diterima'): ?> bg-success
                                            <?php elseif($org->status == 'Ditolak'): ?> bg-danger
                                            <?php else: ?> bg-secondary <?php endif; ?>">
                                            <?php echo e($org->status); ?>

                                        </span>
                                    </td>
                                    <td>
                                        
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/user/organisasi/index.blade.php ENDPATH**/ ?>