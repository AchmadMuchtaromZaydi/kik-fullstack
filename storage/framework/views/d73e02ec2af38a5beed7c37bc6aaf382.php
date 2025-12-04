<div class="container mt-4">

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h2 class="fw-bold mb-3">Review Data Sebelum Dikirim</h2>

            <p class="text-muted" style="font-size: 15px;">
                Pastikan seluruh data organisasi, anggota, inventaris, dan dokumen pendukung
                sudah lengkap dan benar sebelum mengirim pengajuan.
            </p>

            
             <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('user.daftar.submit')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="d-flex justify-content-between mt-4">

                    <button type="button" class="btn btn-secondary prev-tab"
                            data-prev="#tab-pendukung">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Data
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/user/review/index.blade.php ENDPATH**/ ?>