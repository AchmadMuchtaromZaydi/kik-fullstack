<?php $__env->startSection('title', 'Data Kesenian'); ?>
<?php $__env->startSection('page-title', 'Data Kesenian'); ?>

<?php $__env->startSection('content'); ?>
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
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Organisasi Kesenian</h5>
                    <!-- Button Import Data -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-2"></i>Import Data
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Form Pencarian dan Filter -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="<?php echo e(route('admin.kesenian.index')); ?>" class="row g-3">
                            <!-- Pencarian -->
                            <div class="col-md-6">
                                <label for="q" class="form-label">Pencarian</label>
                                <input type="text" class="form-control" id="q" name="q"
                                    placeholder="Cari berdasarkan Nama, Nomor Induk, Jenis Kesenian, Ketua, Alamat, Desa, Kecamatan, No. Telp..."
                                    value="<?php echo e(request('q')); ?>">
                            </div>

                            <!-- Filter Jenis Kesenian -->
                            <div class="col-md-3">
                                <label for="jenis_kesenian" class="form-label">Filter Jenis Kesenian</label>
                                <select class="form-select" id="jenis_kesenian" name="jenis_kesenian">
                                    <option value="">Semua Jenis</option>
                                    <?php $__currentLoopData = $jenisKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($jenis); ?>"
                                            <?php echo e(request('jenis_kesenian') == $jenis ? 'selected' : ''); ?>>
                                            <?php echo e($jenis); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Filter Kecamatan -->
                            <div class="col-md-3">
                                <label for="kecamatan" class="form-label">Filter Kecamatan</label>
                                <select class="form-select" id="kecamatan" name="kecamatan">
                                    <option value="">Semua Kecamatan</option>
                                    <?php $__currentLoopData = $kecamatanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kecamatan); ?>"
                                            <?php echo e(request('kecamatan') == $kecamatan ? 'selected' : ''); ?>>
                                            <?php echo e($kecamatan); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="col-md-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>Cari & Filter
                                        </button>
                                        <a href="<?php echo e(route('admin.kesenian.index')); ?>" class="btn btn-secondary">
                                            <i class="fas fa-refresh me-2"></i>Reset
                                        </a>
                                    </div>

                                    <!-- Dropdown Download -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-2"></i>Download
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button type="button" id="btnDownloadPdf" class="dropdown-item">
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>Download PDF
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" id="btnDownloadExcel" class="dropdown-item">
                                                    <i class="fas fa-file-excel text-success me-2"></i>Download Excel
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Jumlah Data dan Urutan -->
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan <strong><?php echo e($dataKesenian->count()); ?></strong> data organisasi kesenian
                    <?php if($hasSearch): ?>
                        <span class="badge bg-warning ms-2">Mode Pencarian: Diurutkan berdasarkan terbaru</span>
                    <?php else: ?>
                        <span class="badge bg-success ms-2">Mode Normal</span>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Kesenian</th>
                                <th>Nomor Induk</th>
                                <th>Jenis Kesenian</th>
                                <th>Alamat</th>
                                <th>Ketua</th>
                                <th>Tgl Daftar</th>
                                <th>Tgl Expired</th>
                                <th>Status</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $dataKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($item->nama ?? '-'); ?></td>
                                    <td>
                                        <?php if($item->nomor_induk): ?>
                                            <span class="fw-bold text-primary"><?php echo e($item->nomor_induk); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->nama_jenis_kesenian ?? ($item->jenis_kesenian ?? '-')); ?></td>
                                    <td class="table-alamat">
                                        <!-- TAMPILKAN LANGSUNG ALAMAT DARI DATABASE -->
                                        <small><?php echo e($item->alamat ?? '-'); ?></small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo e($item->nama_ketua ?? '-'); ?></strong>
                                            <?php if($item->no_telp_ketua): ?>
                                                <br>
                                                <small class="text-muted"><?php echo e($item->no_telp_ketua); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($item->tanggal_daftar): ?>
                                            <span
                                                class="small"><?php echo e(\Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y')); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($item->tanggal_expired): ?>
                                            <?php if(\Carbon\Carbon::parse($item->tanggal_expired)->isPast()): ?>
                                                <span class="badge bg-danger small">
                                                    <?php echo e(\Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y')); ?>

                                                </span>
                                            <?php elseif(\Carbon\Carbon::parse($item->tanggal_expired)->diffInDays(now()) <= 30): ?>
                                                <span class="badge bg-warning text-dark small">
                                                    <?php echo e(\Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="small"><?php echo e(\Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y')); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $statusColors = [
                                                'Request' => 'warning',
                                                'Allow' => 'success',
                                                'Denny' => 'danger',
                                                'DataLama' => 'info',
                                            ];
                                            $color = $statusColors[$item->status] ?? 'secondary';
                                            $statusTexts = [
                                                'Request' => 'Menunggu',
                                                'Allow' => 'Diterima',
                                                'Denny' => 'Ditolak',
                                                'DataLama' => 'Data Lama',
                                            ];
                                            $text = $statusTexts[$item->status] ?? $item->status;
                                        ?>
                                        <span class="badge bg-<?php echo e($color); ?>">
                                            <?php echo e($text); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo e(route('admin.kesenian.edit', $item->id)); ?>" class="btn btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.kesenian.destroy', $item->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Hapus data?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <br>
                                            Tidak ada data kesenian
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-responsive {
            /* max-height: 80vh;
                            overflow-y: auto; */
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #212529;
            z-index: 10;
        }

        .card-body {
            padding: 1.5rem;
        }
    </style>
    <!-- Modal Import Data -->
    
    
    
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Kesenian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                
                
                <form action="<?php echo e(route('admin.kesenian.import.post')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file Excel (XLSX / XLS)</label>
                            
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xlsx, .xls, .csv">
                        </div>
                        <div class="alert alert-warning small">
                            <strong>Perhatian:</strong> Pastikan file Excel Anda memiliki kolom dengan urutan:
                            <br>
                            `Nama Organisasi`, `Nomor Induk` (opsional), `Jenis Kesenian`, `Nama Ketua`, `No. Telp`,
                            `Alamat`, `Desa`, `Kecamatan`, `Jumlah Anggota`.
                        </div>
                    </div>
                    <div class="modal-footer">
                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Upload dan Import</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    
    


<?php $__env->stopSection(); ?> 

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatan = document.getElementById('kecamatan').value;
            const jenis = document.getElementById('jenis_kesenian').value;
            const q = document.getElementById('q').value;

            // Tombol PDF
            document.getElementById('btnDownloadPdf').addEventListener('click', function() {
                const url =
                    `<?php echo e(route('admin.kesenian.download', 'pdf')); ?>?kecamatan=${encodeURIComponent(kecamatan)}&jenis_kesenian=${encodeURIComponent(jenis)}&q=${encodeURIComponent(q)}`;
                window.open(url, '_blank');
            });

            // Tombol Excel
            document.getElementById('btnDownloadExcel').addEventListener('click', function() {
                const url =
                    `<?php echo e(route('admin.kesenian.download', 'excel')); ?>?kecamatan=${encodeURIComponent(kecamatan)}&jenis_kesenian=${encodeURIComponent(jenis)}&q=${encodeURIComponent(q)}`;
                window.open(url, '_blank');
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/kesenian/index.blade.php ENDPATH**/ ?>