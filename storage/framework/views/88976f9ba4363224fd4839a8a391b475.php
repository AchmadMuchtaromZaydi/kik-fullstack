<?php $__env->startSection('title', 'Dashboard User'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-3">
            <i class="fas fa-theater-masks me-2"></i>Kartu Identitas Kesenian (KIK)
        </h2>
        <p class="text-muted">Selamat datang di sistem pendaftaran Kartu Identitas Kesenian.</p>
    </div>



    <div class="row justify-content-center">

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Daftar Sekarang</h5>
                        <p class="text-muted small mb-4">
                            Ajukan pendaftaran baru untuk mendapatkan Kartu Kesenian.
                        </p>
                        <a href="<?php echo e(route('user.daftar.index')); ?>" class="btn btn-success w-100">
                            <i class="fas fa-pencil-alt me-2"></i>Mulai Daftar
                        </a>
                    </div>
                </div>
            </div>


        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <i class="fas fa-sync-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Perpanjangan Kartu</h5>
                    <p class="text-muted small mb-4">Perpanjang masa berlaku Kartu Identitas Kesenian Anda.</p>
                      <a href="<?php echo e(route('user.perpanjang.index')); ?>" class="btn btn-primary w-100">
                        <i class="fas fa-redo me-2"></i>Perpanjang Sekarang
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Main\kik-fullstack\resources\views/dashboard.blade.php ENDPATH**/ ?>