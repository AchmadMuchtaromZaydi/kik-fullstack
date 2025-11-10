<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Tambah User Baru</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo e(old('name')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo e(old('email')); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                                            value="<?php echo e(old('whatsapp')); ?>" placeholder="628123456789">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="user-kik" <?php echo e(old('role') == 'user-kik' ? 'selected' : ''); ?>>User
                                                KIK</option>
                                            <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            
                            <?php if(old('role') == 'admin'): ?>
                                <div class="alert alert-info mt-2">
                                    <small><i class="fas fa-info-circle me-1"></i>Admin akan langsung aktif tanpa perlu
                                        verifikasi email.</small>
                                </div>
                            <?php elseif(old('role') == 'user-kik'): ?>
                                <div class="alert alert-warning mt-2">
                                    <small><i class="fas fa-exclamation-triangle me-1"></i>User perlu verifikasi email
                                        sebelum bisa login.</small>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="isActive" class="form-label">Status</label>
                                        <select class="form-control" id="isActive" name="isActive" required>
                                            <option value="1" <?php echo e(old('isActive') == '1' ? 'selected' : ''); ?>>Aktif
                                            </option>
                                            <option value="0" <?php echo e(old('isActive') == '0' ? 'selected' : ''); ?>>Non-Aktif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/users/create.blade.php ENDPATH**/ ?>