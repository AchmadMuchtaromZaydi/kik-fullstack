


<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Statistik Cards -->
        <div class="row justify-content-center mb-4">
            <!-- Total Kesenian -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-primary text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('total-kesenian')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">Total Kesenian</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['total_kesenian']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-music fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kesenian Aktif -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-success text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('kesenian-aktif')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">Kesenian Aktif</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['kesenian_aktif']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kesenian Tidak Aktif -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-warning text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('kesenian-tidak-aktif')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">Kesenian Tidak Aktif</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['kesenian_tidak_aktif']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-times-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-info text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('total-users')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">Total Users</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['total_users']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Aktif -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-success text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('users-aktif')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">User Aktif</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['users_aktif']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Tidak Aktif -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card stat-card bg-danger text-white h-100" style="cursor: pointer;"
                    onclick="loadStatDetail('users-tidak-aktif')">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold">User Tidak Aktif</h5>
                                <h2 class="mb-0 fw-bold"><?php echo e($stats['users_tidak_aktif']); ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-times fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <small class="opacity-75">Klik untuk detail</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fw-semibold">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="<?php echo e(route('admin.users.create')); ?>"
                                    class="btn btn-outline-primary w-100 h-100 py-3">
                                    <i class="fas fa-user-plus me-2"></i>Tambah User
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="<?php echo e(route('admin.kesenian.create')); ?>"
                                    class="btn btn-outline-success w-100 h-100 py-3">
                                    <i class="fas fa-plus me-2"></i>Tambah Kesenian
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="<?php echo e(route('admin.anggota.index')); ?>" class="btn btn-outline-info w-100 h-100 py-3">
                                    <i class="fas fa-user-friends me-2"></i>Kelola Anggota
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="<?php echo e(route('admin.laporan')); ?>" class="btn btn-outline-warning w-100 h-100 py-3">
                                    <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Detail Statistik -->
    <div class="modal fade" id="statModal" tabindex="-1" aria-labelledby="statModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="statModalLabel">Detail Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="statContent">
                        <!-- Konten akan di-load via AJAX -->
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function loadStatDetail(type) {
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('statModal'));
            modal.show();

            // Load data via AJAX
            fetch(`/admin/dashboard/stats/${type}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('statModalLabel').textContent = data.title;
                    document.getElementById('statContent').innerHTML = data.content;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('statContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data: ${error.message}
                </div>
            `;
                });
        }
    </script>

    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 140px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-card h2 {
            font-weight: 700;
            font-size: 2.2rem;
        }

        /* Memastikan semua card memiliki tinggi yang sama */
        .h-100 {
            height: 100% !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 1.8rem;
            }

            .stat-card .fa-2x {
                font-size: 1.5rem;
            }
        }

        /* Button styling untuk konsistensi */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New Code\kik-fullstack\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>