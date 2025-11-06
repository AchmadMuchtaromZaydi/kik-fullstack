
<?php $__env->startSection('title', 'Data Anggota'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Anggota Organisasi</h5>

            <?php if($jumlahSaatIni < $jumlahMaks): ?>
                <a href="<?php echo e(route('user.anggota.create')); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Anggota
                </a>
            <?php else: ?>
                <span class="badge bg-warning text-dark">Kuota anggota sudah penuh</span>
            <?php endif; ?>
        </div>

        <div class="card-body">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php elseif(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php elseif(session('warning')): ?>
                <div class="alert alert-warning"><?php echo e(session('warning')); ?></div>
            <?php endif; ?>

            
            <div class="mb-3">
                <strong>Jumlah anggota maksimal:</strong> <?php echo e($jumlahMaks); ?><br>
                <strong>Sudah terdaftar:</strong> <?php echo e($jumlahSaatIni); ?>

            </div>

            
            <?php
                $punyaKetua = $anggota->where('jabatan', 'Ketua')->count() > 0;
                $punyaSekretaris = $anggota->where('jabatan', 'Sekretaris')->count() > 0;
            ?>

            <?php if(!$punyaKetua || !$punyaSekretaris): ?>
                <div class="alert alert-warning">
                    Struktur organisasi belum lengkap.
                    <?php if(!$punyaKetua): ?> <strong>Ketua</strong> belum diisi. <?php endif; ?>
                    <?php if(!$punyaSekretaris): ?> <strong>Sekretaris</strong> belum diisi. <?php endif; ?>
                </div>
            <?php endif; ?>

            
            <?php if($anggota->isEmpty()): ?>
                <div class="alert alert-info">Belum ada anggota terdaftar.</div>
            <?php else: ?>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Umur</th>
                            <th>Pekerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($a->nama); ?></td>
                            <td><?php echo e($a->nik); ?></td>
                            <td><?php echo e($a->jabatan); ?></td>
                            <td class="text-center"><?php echo e($a->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                            <td class="text-center">
                                <?php if($a->tanggal_lahir): ?>
                                    <?php echo e(\Carbon\Carbon::parse($a->tanggal_lahir)->age); ?> Tahun
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($a->pekerjaan ?? '-'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('user.anggota.edit', $a->id)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('user.anggota.destroy', $a->id)); ?>" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Project\kik-fullstack-main (2)\Project\kik-fullstack\resources\views/user/anggota/index.blade.php ENDPATH**/ ?>