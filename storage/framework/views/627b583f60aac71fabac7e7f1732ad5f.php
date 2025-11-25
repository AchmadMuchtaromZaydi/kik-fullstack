<div class="container py-4">

    
    <?php if(session('success_inventaris')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success_inventaris')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-1">Data Inventaris</h5>
            <p class="text-muted mb-3">
                Masukkan data inventaris organisasi Anda.
                <br>
                <strong>Minimal 5 inventaris</strong> diperlukan. <br>
                <?php if($inventaris->count() >= 5): ?>
                    <span class="text-success">(Batas minimal terpenuhi)</span>
                <?php else: ?>
                    <span class="text-danger">(Saat ini baru <?php echo e($inventaris->count()); ?>)</span>
                <?php endif; ?>
            </p>

            
            <?php if($inventaris->count() < 5): ?>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalInventaris">
                <i class="bi bi-plus-circle me-1"></i> Tambah Inventaris
            </button>
            <?php endif; ?>

            
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Tahun Pembelian</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php $__empty_1 = true; $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($inv->nama); ?></td>
                            <td><?php echo e($inv->jumlah); ?></td>
                            <td><?php echo e($inv->pembelian_th ?? '-'); ?></td>
                            <td><?php echo e($inv->kondisi); ?></td>
                            <td><?php echo e($inv->keterangan ?? '-'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditInventaris<?php echo e($inv->id); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteInventaris<?php echo e($inv->id); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">Belum ada data inventaris.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

               <div class="d-flex justify-content-between mt-3">

          <!-- Tombol Kembali -->
            <button class="btn btn-secondary prev-tab" data-prev="#tab-anggota">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
            
            <button
                id="btnNextInventaris"
                class="btn btn-success px-4 next-tab"
                data-next="#tab-pendukung"
                <?php if($inventaris->count() < 5): ?> disabled <?php endif; ?>
            >
                Selanjutnya
            </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalInventaris" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('user.inventaris.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>
                       <div class="col-md-3">
                            <label>Tahun Pembelian</label>
                            <select name="pembelian_th" class="form-select">
                                <option value="">Pilih Tahun</option>
                                <?php for($year = now()->year; $year >= 1990; $year--): ?>
                                    <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baru">Baru</option>
                                <option value="Bekas">Bekas</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: dalam perbaikan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <div class="modal fade" id="modalEditInventaris<?php echo e($inv->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Inventaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('user.inventaris.update', $inv->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Nama</label>
                                <input type="text" name="nama" value="<?php echo e($inv->nama); ?>" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" value="<?php echo e($inv->jumlah); ?>" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label>Tahun Pembelian</label>
                                <select name="pembelian_th" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    <?php for($year = now()->year; $year >= 1990; $year--): ?>
                                        <option value="<?php echo e($year); ?>" <?php echo e($inv->pembelian_th == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Kondisi</label>
                                <select name="kondisi" class="form-select" required>
                                    <option value="Baru" <?php echo e($inv->kondisi == 'Baru' ? 'selected' : ''); ?>>Baru</option>
                                    <option value="Bekas" <?php echo e($inv->kondisi == 'Bekas' ? 'selected' : ''); ?>>Bekas</option>
                                    <option value="Rusak" <?php echo e($inv->kondisi == 'Rusak' ? 'selected' : ''); ?>>Rusak</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" value="<?php echo e($inv->keterangan); ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modalDeleteInventaris<?php echo e($inv->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Inventaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('user.inventaris.destroy', $inv->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus inventaris <strong><?php echo e($inv->nama); ?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




<script>
document.addEventListener('DOMContentLoaded', function() {
    if(<?php echo json_encode(session('tab'), 15, 512) ?> === 'inventaris') {
        const tabButtons = document.querySelectorAll('#form-tabs button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        // Sembunyikan semua tab lain
        tabPanes.forEach(tab => tab.classList.add('d-none'));

        // Tampilkan tab inventaris
        document.querySelector('#tab-inventaris').classList.remove('d-none');

        // Atur tombol tab aktif
        tabButtons.forEach(btn => btn.classList.remove('active'));
        const btnInventaris = document.querySelector('#form-tabs button[data-target="#tab-inventaris"]');
        if(btnInventaris) btnInventaris.classList.add('active');
    }
});
</script>
<?php /**PATH D:\Main\kik-fullstack\resources\views/user/inventaris/index.blade.php ENDPATH**/ ?>