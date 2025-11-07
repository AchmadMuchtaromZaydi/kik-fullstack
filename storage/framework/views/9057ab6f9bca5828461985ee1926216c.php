
<?php $__env->startSection('title', 'Data Pendukung'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Data Pendukung Organisasi</h5>
            <a href="<?php echo e(route('user.pendukung.create')); ?>" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle"></i> Upload Baru
            </a>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php elseif(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <?php if($dataPendukung->isEmpty()): ?>
                <div class="alert alert-info">Belum ada data pendukung diunggah.</div>
            <?php else: ?>
                <div class="row">
                    <?php $__currentLoopData = $dataPendukung; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo e(asset('storage/' . $data->image)); ?>"
                                     class="card-img-top" alt="Foto <?php echo e($data->tipe); ?>">
                                <div class="card-body">
                                    <h6 class="card-title text-capitalize"><?php echo e($data->tipe); ?></h6>
                                    <p class="text-muted small mb-1">
                                        Status:
                                        <?php if($data->validasi == 1): ?>
                                            <span class="text-success">Tervalidasi</span>
                                        <?php else: ?>
                                            <span class="text-warning">Menunggu Validasi</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <form action="<?php echo e(route('user.pendukung.destroy', $data->id)); ?>"
                                          method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/user/pendukung/index.blade.php ENDPATH**/ ?>