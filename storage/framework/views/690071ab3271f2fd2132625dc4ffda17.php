
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-building me-2"></i>Data Organisasi
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Nama Organisasi</th>
                        <td><?php echo e($organisasi->nama ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Nomor Induk</th>
                        <td>
                            <?php if(!empty($organisasi->nomor_induk)): ?>
                                <span class="badge bg-success"><?php echo e($organisasi->nomor_induk); ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning">Belum ada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Berdiri</th>
                        <td>
                            <?php if($organisasi->tanggal_berdiri): ?>
                                <?php echo e(\Carbon\Carbon::parse($organisasi->tanggal_berdiri)->format('d/m/Y')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Kesenian</th>
                        <td>
                            <strong><?php echo e($organisasi->jenis_kesenian_nama ?? '-'); ?></strong>
                            <?php if(!empty($organisasi->sub_kesenian_nama) && $organisasi->sub_kesenian_nama != 'Tidak ada sub jenis'): ?>
                                <br><small class="text-muted">Sub: <?php echo e($organisasi->sub_kesenian_nama); ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Jumlah Anggota</th>
                        <td><?php echo e($organisasi->jumlah_anggota ?? 0); ?> orang</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo e($organisasi->alamat ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td><?php echo e($organisasi->nama_kecamatan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td><?php echo e($organisasi->nama_desa ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $organisasi->status_badge ?? '<span class="badge bg-secondary">-</span>'; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Data Ketua -->
        <div class="mt-4">
            <h6>Data Ketua Organisasi</h6>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Nama Ketua</th>
                    <td><?php echo e($organisasi->nama_ketua ?? '-'); ?></td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td><?php echo e($organisasi->no_telp_ketua ?? '-'); ?></td>
                </tr>
            </table>

            <?php if(empty($organisasi->nama_ketua) || $organisasi->nama_ketua == '-'): ?>
                <div class="alert alert-warning mt-2">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Data ketua belum ditemukan. Pastikan ada anggota dengan jabatan "Ketua".
                    </small>
                </div>
            <?php endif; ?>
        </div>

        <hr>

        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_organisasi">

            <h5>Verifikasi Data Organisasi</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_organisasi')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                                Valid</option>
                            <option value="tdk_valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_organisasi')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin"><?php echo e($verifikasiData->where('tipe', 'data_organisasi')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar"><?php echo e($verifikasiData->where('tipe', 'data_organisasi')->first()->keterangan ?? ''); ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'general'])); ?>"
                    class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan & Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_organisasi.blade.php ENDPATH**/ ?>