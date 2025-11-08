<?php $__env->startSection('title', 'Verifikasi - ' . $organisasi->nama); ?>
<?php $__env->startSection('page-title', 'Verifikasi Permohonan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-3">
                <!-- Progress Steps -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>Progress Verifikasi
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item <?php echo e(in_array($tabActive, ['general', 'data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : ''); ?>">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Perhatian</strong>
                            </li>
                            <li
                                class="list-group-item <?php echo e(in_array($tabActive, ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : ''); ?>">
                                <i class="fas fa-building me-2"></i>
                                <strong>Data Organisasi</strong>
                                <?php if($verifikasi = $verifikasiData->where('tipe', 'data_organisasi')->first()): ?>
                                    <span
                                        class="badge bg-<?php echo e($verifikasi->status == 'valid' ? 'success' : 'danger'); ?> float-end">
                                        <?php echo e($verifikasi->status == 'valid' ? '✓' : '✗'); ?>

                                    </span>
                                <?php endif; ?>
                            </li>
                            <li
                                class="list-group-item <?php echo e(in_array($tabActive, ['data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : ''); ?>">
                                <i class="fas fa-users me-2"></i>
                                <strong>Data Anggota</strong>
                                <?php if($verifikasi = $verifikasiData->where('tipe', 'data_anggota')->first()): ?>
                                    <span
                                        class="badge bg-<?php echo e($verifikasi->status == 'valid' ? 'success' : 'danger'); ?> float-end">
                                        <?php echo e($verifikasi->status == 'valid' ? '✓' : '✗'); ?>

                                    </span>
                                <?php endif; ?>
                            </li>
                            <li
                                class="list-group-item <?php echo e(in_array($tabActive, ['data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : ''); ?>">
                                <i class="fas fa-boxes me-2"></i>
                                <strong>Inventaris Barang</strong>
                                <?php if($verifikasi = $verifikasiData->where('tipe', 'data_inventaris')->first()): ?>
                                    <span
                                        class="badge bg-<?php echo e($verifikasi->status == 'valid' ? 'success' : 'danger'); ?> float-end">
                                        <?php echo e($verifikasi->status == 'valid' ? '✓' : '✗'); ?>

                                    </span>
                                <?php endif; ?>
                            </li>
                            <li
                                class="list-group-item <?php echo e(in_array($tabActive, ['data_pendukung', 'review']) ? 'active-step' : ''); ?>">
                                <i class="fas fa-file-alt me-2"></i>
                                <strong>Dokumen Pendukung</strong>
                                <?php if($verifikasi = $verifikasiData->where('tipe', 'data_pendukung')->first()): ?>
                                    <span
                                        class="badge bg-<?php echo e($verifikasi->status == 'valid' ? 'success' : 'danger'); ?> float-end">
                                        <?php echo e($verifikasi->status == 'valid' ? '✓' : '✗'); ?>

                                    </span>
                                <?php endif; ?>
                            </li>
                            <li class="list-group-item <?php echo e($tabActive == 'review' ? 'active-step' : ''); ?>">
                                <i class="fas fa-clipboard-check me-2"></i>
                                <strong>Review Akhir</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Info Organisasi -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> <?php echo e($organisasi->nama); ?></p>
                        <p><strong>Ketua:</strong> <?php echo e($organisasi->nama_ketua); ?></p>
                        <p><strong>Kecamatan:</strong> <?php echo e($organisasi->nama_kecamatan ?? '-'); ?></p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge bg-<?php echo e($organisasi->status == 'Request' ? 'warning' : ($organisasi->status == 'Allow' ? 'success' : 'danger')); ?>">
                                <?php echo e($organisasi->status); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Content berdasarkan tab active -->
                <?php if($tabActive == 'general'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.general', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($tabActive == 'data_organisasi'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.data_organisasi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($tabActive == 'data_anggota'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.data_anggota', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($tabActive == 'data_inventaris'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.data_inventaris', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($tabActive == 'data_pendukung'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.data_pendukung', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($tabActive == 'review'): ?>
                    <?php echo $__env->make('admin.verifikasi.tabs.review', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .active-step {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .list-group-item {
            border: none;
            padding: 15px 20px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/show.blade.php ENDPATH**/ ?>