
<?php $__env->startSection('title', 'Tambah Anggota'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah Anggota Organisasi</h5>
        </div>

        <div class="card-body">
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php elseif(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('user.anggota.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama')); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" value="<?php echo e(old('nik')); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Ketua" <?php echo e(old('jabatan') == 'Ketua' ? 'selected' : ''); ?>>Ketua</option>
                            <option value="Wakil Ketua" <?php echo e(old('jabatan') == 'Wakil Ketua' ? 'selected' : ''); ?>>Wakil Ketua</option>
                            <option value="Sekretaris" <?php echo e(old('jabatan') == 'Sekretaris' ? 'selected' : ''); ?>>Sekretaris</option>
                            <option value="Bendahara" <?php echo e(old('jabatan') == 'Bendahara' ? 'selected' : ''); ?>>Bendahara</option>
                            <option value="Anggota" <?php echo e(old('jabatan') == 'Anggota' ? 'selected' : ''); ?>>Anggota</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" <?php echo e(old('jenis_kelamin') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('jenis_kelamin') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo e(old('tanggal_lahir')); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="<?php echo e(old('pekerjaan')); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?php echo e(old('alamat')); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?php echo e(old('telepon')); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="<?php echo e(old('whatsapp')); ?>">
                        <small class="form-text text-muted">Contoh nomor wa : 081234421112</small>
                    </div>
                </div>

                <div class="text-end">
                    <a href="<?php echo e(route('user.anggota.index')); ?>" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/user/anggota/create.blade.php ENDPATH**/ ?>