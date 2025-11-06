
<?php $__env->startSection('title', 'Upload Data Pendukung'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-image me-2"></i>Upload Data Pendukung</h5>
        </div>
        <div class="card-body">
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <form action="<?php echo e(route('user.pendukung.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Jenis Data</label>
                    <select name="tipe" class="form-select" required>
                        <option value="">-- Pilih Jenis Data --</option>
                        <option value="ktp">Foto KTP</option>
                        <option value="photo">Pas Photo 3x4</option>
                        <option value="banner">Banner Organisasi</option>
                        <option value="poster">Poster Organisasi</option>
                        <option value="kegiatan">Foto Kegiatan</option>
                    </select>
                    <?php $__errorArgs = ['tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png" required>
                    <small class="text-muted">
                        Format gambar: JPG/PNG — Maksimal 2MB
                    </small>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

              <div class="alert alert-warning">
                    Pastikan foto sesuai dengan tipe yang dipilih!
                    Misalnya:
                    <ul class="mb-0">
                        <li>KTP: foto identitas jelas</li>
                        <li>Pas foto: tampak wajah 3x4</li>
                        <li>Banner / Poster: mengandung logo atau nama organisasi</li>
                        <li>Kegiatan: hanya satu foto kegiatan</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload
                </button>
                <a href="<?php echo e(route('user.pendukung.index')); ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Project\kik-fullstack-main (2)\Project\kik-fullstack\resources\views/user/pendukung/create.blade.php ENDPATH**/ ?>