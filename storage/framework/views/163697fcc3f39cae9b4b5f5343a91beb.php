
<?php $__env->startSection('title', 'Verifikasi Data'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Proses Verifikasi Data</h5>
        </div>
        <div class="card-body text-center py-5">

            <?php if($lengkap): ?>
                <h4 class="mb-3 text-success fw-bold">Data Anda Sudah Lengkap!</h4>
                <p class="mb-4">Semua data telah berhasil diunggah. Data Anda akan segera diverifikasi oleh admin.</p>
                <div class="alert alert-info mx-auto w-75">
                    Mohon menunggu proses validasi. Anda akan menerima pemberitahuan jika data sudah disetujui.
                </div>
            <?php else: ?>
                <h4 class="mb-3 text-warning fw-bold">Data Belum Lengkap!</h4>
                <p class="mb-4">Silakan lengkapi semua data terlebih dahulu sebelum proses verifikasi.</p>

                <div class="alert alert-danger mx-auto w-75 text-start">
                    <ul class="mb-0">
                        <?php if($organisasi): ?>
                            <li>✅ Data organisasi sudah diisi</li>
                        <?php else: ?>
                            <li>❌ Data organisasi belum diisi</li>
                        <?php endif; ?>

                        <li><?php echo e($lengkap ? '✅ Data anggota lengkap' : '❌ Data anggota belum lengkap'); ?></li>
                        <li><?php echo e($lengkap ? '✅ Data inventaris lengkap' : '❌ Data inventaris belum lengkap'); ?></li>
                        <li><?php echo e($lengkap ? '✅ Data pendukung lengkap' : '❌ Data pendukung belum lengkap'); ?></li>
                    </ul>
                </div>
            <?php endif; ?>

            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary mt-4">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Project\kik-fullstack-main (2)\Project\kik-fullstack\resources\views/user/validasi/index.blade.php ENDPATH**/ ?>