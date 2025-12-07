

<?php $__env->startSection('title', 'Perpanjangan Kartu Kesenian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">

                    
                    <h4 class="fw-bold mb-4 text-center">Check Kartu Anda</h4>

                    
                    <?php if($errors->has('not_found')): ?>
                        <div class="alert alert-danger text-center">
                            <?php echo e($errors->first('not_found')); ?>

                        </div>
                    <?php endif; ?>

                    
                    <form action="<?php echo e(route('user.perpanjang.check')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Masukkan Nomor Kartu Induk <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="nomor_kartu"
                                   class="form-control form-control-lg"
                                   placeholder="Nomor Kartu Induk Lama"
                                   value="<?php echo e(old('nomor_kartu')); ?>"
                                   required>
                        </div>

                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nama Ketua <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="nama_ketua"
                                   class="form-control form-control-lg"
                                   placeholder="Nama Ketua"
                                   value="<?php echo e(old('nama_ketua')); ?>"
                                   required>
                        </div>

                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-outline-secondary px-4">
                                CANCEL
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                CARI
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New Code\kik-fullstack\resources\views/user/perpanjang/index.blade.php ENDPATH**/ ?>