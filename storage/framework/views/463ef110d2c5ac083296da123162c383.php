

<?php $__env->startSection('title', 'Tambah Data Organisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Data Organisasi</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('user.organisasi.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Organisasi</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama organisasi" required>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Berdiri</label>
                    <input type="date" name="tanggal_berdiri" class="form-control" required>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kesenian</label>
                    <select name="jenis_kesenian" id="jenis_kesenian" class="form-select" required>
                        <option value="">-- Pilih Jenis Kesenian --</option>
                        <?php $__currentLoopData = $jenisKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jenis->id); ?>"><?php echo e($jenis->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sub Jenis Kesenian</label>
                    <select name="sub_kesenian" id="sub_kesenian" class="form-select" required>
                        <option value="">-- Pilih Sub Jenis Kesenian --</option>
                    </select>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Anggota</label>
                    <input type="number" name="jumlah_anggota" class="form-control" placeholder="Masukkan jumlah anggota" required>
                </div>

                
                <hr>
                <h6 class="fw-bold mb-3">Alamat Sekretariat Organisasi</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kabupaten</label>
                    <select name="kabupaten_kode" id="kabupaten" class="form-select" required>
                        <option value="">-- Pilih Kabupaten --</option>
                        <?php $__currentLoopData = $kabupaten; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->kode); ?>"><?php echo e($k->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kecamatan</label>
                    <select name="kecamatan_kode" id="kecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Desa</label>
                    <select name="desa_kode" id="desa" class="form-select" required>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Tulis alamat sekretariat lengkap..." required></textarea>
                </div>

                
                <div class="text-end">
                    <a href="<?php echo e(route('user.organisasi.index')); ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
const subUrl = "<?php echo e(route('user.organisasi.subkesenian', ':id')); ?>";
const kecUrl = "<?php echo e(route('user.organisasi.kecamatan', ':kode')); ?>";
const desaUrl = "<?php echo e(route('user.organisasi.desa', ':kode')); ?>";

// Sub Jenis
document.getElementById('jenis_kesenian').addEventListener('change', function() {
    const parentId = this.value;
    const subSelect = document.getElementById('sub_kesenian');
    subSelect.innerHTML = '<option>Memuat...</option>';

    fetch(subUrl.replace(':id', parentId))
        .then(res => res.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">-- Pilih Sub Jenis Kesenian --</option>';
            data.forEach(sub => {
                subSelect.innerHTML += `<option value="${sub.id}">${sub.nama}</option>`;
            });
        })
        .catch(() => subSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});

// Kecamatan (berdasarkan kabupaten)
document.getElementById('kabupaten').addEventListener('change', function() {
    const kode = this.value;
    const kecSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');
    kecSelect.innerHTML = '<option>Memuat...</option>';
    desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

    fetch(kecUrl.replace(':kode', kode))
        .then(res => res.json())
        .then(data => {
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(kec => {
                kecSelect.innerHTML += `<option value="${kec.kode}">${kec.nama}</option>`;
            });
        })
        .catch(() => kecSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});

// Desa (berdasarkan kecamatan)
document.getElementById('kecamatan').addEventListener('change', function() {
    const kode = this.value;
    const desaSelect = document.getElementById('desa');
    desaSelect.innerHTML = '<option>Memuat...</option>';

    fetch(desaUrl.replace(':kode', kode))
        .then(res => res.json())
        .then(data => {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            data.forEach(d => {
                desaSelect.innerHTML += `<option value="${d.kode}">${d.nama}</option>`;
            });
        })
        .catch(() => desaSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/user/organisasi/create.blade.php ENDPATH**/ ?>