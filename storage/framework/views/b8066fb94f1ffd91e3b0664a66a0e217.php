<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit User</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo e(old('name', $user->name)); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo e(old('email', $user->email)); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                                            value="<?php echo e(old('whatsapp', $user->whatsapp)); ?>" placeholder="628123456789">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="user-kik"
                                                <?php echo e(old('role', $user->role) == 'user-kik' ? 'selected' : ''); ?>>User KIK
                                            </option>
                                            <option value="admin"
                                                <?php echo e(old('role', $user->role) == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="isActive" class="form-label">Status</label>
                                        <select class="form-control" id="isActive" name="isActive" required>
                                            <option value="1"
                                                <?php echo e(old('isActive', $user->isActive) == '1' ? 'selected' : ''); ?>>Aktif
                                            </option>
                                            <option value="0"
                                                <?php echo e(old('isActive', $user->isActive) == '0' ? 'selected' : ''); ?>>Non-Aktif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>