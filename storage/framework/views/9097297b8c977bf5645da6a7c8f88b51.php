<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Data Users</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Daftar Pengguna</h5>
                            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
                        </div>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>WhatsApp</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($user->name); ?></td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td><?php echo e($user->whatsapp ?? '-'); ?></td>
                                            
                                            <td>
                                                <span class="badge bg-<?php echo e($user->role == 'admin' ? 'primary' : 'success'); ?>">
                                                    <?php echo e($user->role); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($user->isActive ? 'success' : 'danger'); ?>">
                                                    <?php echo e($user->isActive ? 'Aktif' : 'Non-Aktif'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($user->role === 'admin'): ?>
                                                    <span class="badge bg-info">Auto Verified</span>
                                                <?php else: ?>
                                                    <span
                                                        class="badge bg-<?php echo e($user->code_verified == 1 ? 'success' : 'warning'); ?>">
                                                        <?php echo e($user->code_verified == 1 ? 'Terverifikasi' : 'Belum Verifikasi'); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST"
                                                        class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <?php if($user->code_verified != 1): ?>
                                                        <form action="<?php echo e(route('admin.users.reset-verification', $user)); ?>"
                                                            method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-info"
                                                                title="Reset Verifikasi">
                                                                <i class="fas fa-sync"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/users/index.blade.php ENDPATH**/ ?>