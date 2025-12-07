 

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">

                    
                    
                    
                    <?php if($verifikasi && $verifikasi->status == 'Menunggu Verifikasi'): ?>

                        <div class="mb-4 text-warning">
                            <i class="fas fa-clock fa-5x"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Sedang Dalam Proses Verifikasi</h3>
                        <p class="text-muted mt-3">
                            Data organisasi Anda telah berhasil kami terima. <br>
                            Saat ini Admin sedang melakukan peninjauan data.
                        </p>
                        <div class="alert alert-warning mt-4 d-inline-block text-start">
                            <small><i class="fas fa-info-circle me-1"></i> <strong>Note:</strong> Proses ini biasanya memakan waktu 1x24 jam kerja. Silakan cek halaman ini secara berkala.</small>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                            </a>
                        </div>


                    
                    
                    
                    <?php elseif($verifikasi && $verifikasi->status == 'Ditolak'): ?>

                        <div class="mb-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-5x"></i>
                        </div>
                        <h3 class="fw-bold text-danger">Pengajuan Perlu Revisi</h3>
                        <p class="text-muted mt-3">
                            Mohon maaf, data Anda belum dapat disetujui karena terdapat kekurangan atau kesalahan.
                        </p>

                        
                        <?php if($verifikasi->catatan): ?>
                            <div class="alert alert-danger mt-3 text-start">
                                <strong><i class="fas fa-clipboard-list me-2"></i> Catatan Admin:</strong><br>
                                <?php echo e($verifikasi->catatan); ?>

                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            
                            <a href="<?php echo e(route('user.daftar.index')); ?>" class="btn btn-warning px-4 fw-bold">
                                <i class="fas fa-edit me-2"></i> Perbaiki Data Sekarang
                            </a>
                        </div>


                    
                    
                    
                    <?php elseif($verifikasi && $verifikasi->status == 'Approved'): ?>

                        <div class="mb-4 text-success">
                            <i class="fas fa-check-circle fa-5x"></i>
                        </div>
                        <h3 class="fw-bold text-success">Verifikasi Berhasil!</h3>
                        <p class="text-muted mt-3">
                            Selamat! Organisasi Anda telah resmi terdaftar dan terverifikasi. <br>
                            Anda sekarang dapat mengakses fitur penuh dan mencetak Kartu Induk Kesenian.
                        </p>

                        <div class="mt-4 d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-primary px-4">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                            
                            
                        </div>


                    
                    
                    
                    <?php else: ?>

                        <div class="mb-4 text-primary">
                            <i class="fas fa-file-alt fa-5x"></i>
                        </div>
                        <h3 class="fw-bold">Belum Ada Pengajuan</h3>
                        <p class="text-muted mt-3">
                            Sepertinya Anda belum mengirimkan data pengajuan. <br>
                            Silakan lengkapi form pendaftaran terlebih dahulu.
                        </p>
                        <div class="mt-4">
                            <a href="<?php echo e(route('user.daftar.index')); ?>" class="btn btn-primary px-4">
                                <i class="fas fa-pen me-2"></i> Isi Formulir
                            </a>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New Code\kik-fullstack\resources\views/user/selesai/index.blade.php ENDPATH**/ ?>