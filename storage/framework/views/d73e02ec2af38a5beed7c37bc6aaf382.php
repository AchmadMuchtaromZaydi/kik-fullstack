

<div class="container mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h2 class="fw-bold mb-3">Review Data Sebelum Dikirim</h2>

            <p class="text-muted" style="font-size: 15px;">
                Pastikan seluruh data organisasi, anggota, inventaris, dan dokumen pendukung
                sudah lengkap dan benar sebelum mengirim pengajuan. Data yang sudah dikirim
                tidak dapat diubah kecuali statusnya dikembalikan (revisi) oleh Admin.
            </p>

            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('user.daftar.submit')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="alert alert-info mt-3">
                    <strong>Info:</strong> Data akan dikirim untuk verifikasi Admin.
                    Anda akan diarahkan ke halaman status setelah menekan tombol kirim.
                </div>

                <div class="d-flex justify-content-between mt-5">
                    
                    <button type="button" class="btn btn-secondary prev-tab px-4"
                            data-prev="#tab-pendukung">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </button>

                    
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/user/review/index.blade.php ENDPATH**/ ?>