<?php $__env->startSection('title', 'Data Kesenian'); ?>
<?php $__env->startSection('page-title', 'Data Kesenian'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger" role="alert"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Organisasi Kesenian</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-2"></i>Import Data
                </button>
            </div>

            <div class="card-body">
                
                <form method="GET" action="<?php echo e(route('admin.kesenian.index')); ?>" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="q" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="q" name="q"
                            placeholder="Cari nama, jenis, ketua, alamat..." value="<?php echo e(request('q')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="jenis_kesenian" class="form-label">Filter Jenis Kesenian</label>
                        <select class="form-select" id="jenis_kesenian" name="jenis_kesenian">
                            <option value="">Semua Jenis</option>
                            <?php $__currentLoopData = $jenisKesenianList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($jenis); ?>"
                                    <?php echo e(request('jenis_kesenian') == $jenis ? 'selected' : ''); ?>>
                                    <?php echo e($jenis); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="kecamatan" class="form-label">Filter Kecamatan</label>
                        <select class="form-select" id="kecamatan" name="kecamatan">
                            <option value="">Semua Kecamatan</option>
                            <?php $__currentLoopData = $kecamatanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kec); ?>" <?php echo e(request('kecamatan') == $kec ? 'selected' : ''); ?>>
                                    <?php echo e($kec); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-12 d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cari & Filter
                            </button>
                            <a href="<?php echo e(route('admin.kesenian.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-2"></i>Download
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="button" id="btnDownloadPdf" class="dropdown-item">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF</button></li>
                                <li><button type="button" id="btnDownloadExcel" class="dropdown-item">
                                        <i class="fas fa-file-excel text-success me-2"></i>Excel</button></li>
                            </ul>
                        </div>
                    </div>
                </form>

                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan <strong><?php echo e($dataKesenian->count()); ?></strong> dari total
                    <strong><?php echo e($dataKesenian->total()); ?></strong> data organisasi kesenian.
                </div>

                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Kesenian</th>
                                <th>Nomor Induk</th>
                                <th>Jenis</th>
                                <th>Alamat</th>
                                <th>Ketua</th>
                                <th>Daftar</th>
                                <th>Expired</th>
                                <th>Status</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $dataKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($dataKesenian->firstItem() + $i); ?></td>
                                    <td><?php echo e($item->nama ?? '-'); ?></td>
                                    <td><strong class="text-primary"><?php echo e($item->nomor_induk ?? 'Belum ada'); ?></strong></td>
                                    <td><?php echo e($item->jenis_kesenian_nama); ?></td>
                                    <td><small><?php echo e($item->alamat ?? '-'); ?></small></td>
                                    <td>
                                        <strong><?php echo e($item->ketua->nama ?? '-'); ?></strong><br>
                                        <small class="text-muted"><?php echo e($item->ketua->no_telp ?? ''); ?></small>
                                    </td>
                                    <td><?php echo e($item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y') : '-'); ?>

                                    </td>
                                    <td>
                                        <?php if($item->tanggal_expired): ?>
                                            <?php $exp = \Carbon\Carbon::parse($item->tanggal_expired); ?>
                                            <?php if($exp->isPast()): ?>
                                                <span class="badge bg-danger small"><?php echo e($exp->format('d/m/Y')); ?></span>
                                            <?php elseif($exp->diffInDays(now()) <= 30): ?>
                                                <span
                                                    class="badge bg-warning text-dark small"><?php echo e($exp->format('d/m/Y')); ?></span>
                                            <?php else: ?>
                                                <span class="small"><?php echo e($exp->format('d/m/Y')); ?></span>
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
                                            $statusTexts = [
                                                'Request' => 'Menunggu',
                                                'Allow' => 'Diterima',
                                                'Denny' => 'Ditolak',
                                                'DataLama' => 'Data Lama',
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$item->status] ?? 'secondary'); ?>">
                                            <?php echo e($statusTexts[$item->status] ?? $item->status); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.kesenian.edit', $item->id)); ?>"
                                                class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($item->status == 'Request'): ?>
                                                <a href="<?php echo e(route('admin.verifikasi.show', $item->id)); ?>"
                                                    class="btn btn-info">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                            <form action="<?php echo e(route('admin.kesenian.destroy', $item->id)); ?>" method="POST"
                                                class="d-inline" onsubmit="return confirm('Hapus data?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php echo e($dataKesenian->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>

    
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('admin.kesenian.import.post')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Import Data Kesenian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file Excel</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xlsx,.xls,.csv">
                        </div>
                        <div class="alert alert-warning small">
                            Pastikan urutan kolom sesuai format import.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const q = document.getElementById('q').value;
                const jenis = document.getElementById('jenis_kesenian').value;
                const kecamatan = document.getElementById('kecamatan').value;

                document.getElementById('btnDownloadPdf').addEventListener('click', () => {
                    const url =
                        `<?php echo e(route('admin.kesenian.download.pdf')); ?>?q=${encodeURIComponent(q)}&jenis_kesenian=${encodeURIComponent(jenis)}&kecamatan=${encodeURIComponent(kecamatan)}`;
                    window.open(url, '_blank');
                });

                document.getElementById('btnDownloadExcel').addEventListener('click', () => {
                    const url =
                        `<?php echo e(route('admin.kesenian.download.excel')); ?>?q=${encodeURIComponent(q)}&jenis_kesenian=${encodeURIComponent(jenis)}&kecamatan=${encodeURIComponent(kecamatan)}`;
                    window.open(url, '_blank');
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/kesenian/index.blade.php ENDPATH**/ ?>