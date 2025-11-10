<h6 class="mb-3"><?php echo e($title); ?> (<?php echo e($data->count()); ?> data)</h6>

<?php if($data->isEmpty()): ?>
    <div class="alert alert-info">Tidak ada data</div>
<?php else: ?>
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover">
            <thead class="table-light sticky-top">
                <tr>
                    <th width="50">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($user->name ?? '-'); ?></td>
                        <td><?php echo e($user->email ?? '-'); ?></td>
                        <td><span class="badge bg-info"><?php echo e($user->role ?? '-'); ?></span></td>
                        <td>
                            <?php if($user->isActive): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/layouts/partials/stats/users-list.blade.php ENDPATH**/ ?>