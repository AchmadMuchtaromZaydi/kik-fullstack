<?php $__env->startSection('title', 'Verifikasi Organisasi Kesenian'); ?>
<?php $__env->startSection('page-title', 'Verifikasi Organisasi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Organisasi Menunggu Verifikasi</h5>
                    <div class="d-flex">
                        <form method="GET" action="<?php echo e(route('admin.verifikasi.index')); ?>" class="me-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" placeholder="Cari organisasi..."
                                    value="<?php echo e(request('q')); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Organisasi</th>
                                <th>Jenis Kesenian</th>
                                <th>Ketua</th>
                                <th>Kecamatan</th>
                                <th>Tanggal Daftar</th>
                                <th>Jumlah Anggota</th>
                                <th width="200" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $organisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo e(($organisasi->currentPage() - 1) * $organisasi->perPage() + $index + 1); ?></td>
                                    <td>
                                        <strong><?php echo e($item->nama); ?></strong>
                                        <?php if($item->nomor_induk): ?>
                                            <br><small class="text-muted">No. Induk: <?php echo e($item->nomor_induk); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->nama_jenis_kesenian ?? '-'); ?></td>
                                    <td>
                                        <div>
                                            <strong><?php echo e($item->nama_ketua ?? '-'); ?></strong>
                                            <?php if($item->no_telp_ketua): ?>
                                                <br><small class="text-muted"><?php echo e($item->no_telp_ketua); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo e($item->nama_kecamatan ?? '-'); ?></td>
                                    <td><?php echo e($item->tanggal_daftar ? $item->tanggal_daftar->format('d/m/Y') : '-'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?php echo e($item->anggota->count()); ?> Anggota</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo e(route('admin.verifikasi.show', $item->id)); ?>" class="btn btn-info"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.verifikasi.approve', $item->id)); ?>"
                                                method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success" title="Setujui"
                                                    onclick="return confirm('Setujui organisasi ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.verifikasi.reject', $item->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger" title="Tolak"
                                                    onclick="return confirm('Tolak organisasi ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-2x mb-3"></i>
                                            <br>
                                            Tidak ada organisasi yang menunggu verifikasi
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php echo e($organisasi->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>     

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/index.blade.php ENDPATH**/ ?>