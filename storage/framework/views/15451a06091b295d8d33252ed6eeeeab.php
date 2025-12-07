<h6 class="mb-3"><?php echo e($title); ?> (<?php echo e($data->count()); ?> data)</h6>

<?php if($data->isEmpty()): ?>
    <div class="alert alert-info">Tidak ada data</div>
<?php else: ?>
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover">
            <thead class="table-light sticky-top">
                <tr>
                    <th width="50">No</th>
                    <th>Nama Organisasi</th>
                    <th>Nomor Induk</th>
                    <th>Jenis Kesenian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $statusClass = [
                            'Request' => 'bg-warning',
                            'Allow' => 'bg-success',
                            'Denny' => 'bg-danger',
                            'DataLama' => 'bg-secondary',
                        ];
                    ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($item->nama ?? '-'); ?></td>
                        <td><?php echo e($item->nomor_induk ?? '-'); ?></td>
                        <td><?php echo e($item->nama_jenis_kesenian ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo e($statusClass[$item->status] ?? 'bg-secondary'); ?>">
                                <?php echo e($item->status ?? '-'); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/layouts/partials/stats/kesenian-list.blade.php ENDPATH**/ ?>