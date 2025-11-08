
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-info-circle me-2"></i>Perhatian
        </h5>
    </div>
    <div class="card-body">
        <h3>Halo Admin</h3>
        <p class="mb-3">
            Berikut adalah data user baru yang mengajukan Permohonan kartu
            induk kesenian. Silahkan lakukan verifikasi untuk tindak lanjut
            dari permohonan ini.
        </p>
        <p>Klik tombol selanjutnya di bagian bawah :</p>

        <div class="text-end mt-4">
            <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi'])); ?>"
                class="btn btn-primary">
                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/general.blade.php ENDPATH**/ ?>