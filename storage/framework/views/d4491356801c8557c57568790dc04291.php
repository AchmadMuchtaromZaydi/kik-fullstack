

<?php $__env->startSection('title', 'Formulir Permohonan Kartu Induk Kesenian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h3 class="fw-bold mb-4">Formulir Permohonan Kartu Induk Kesenian</h3>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="list-group list-group-flush" id="form-tabs">
                        <button class="list-group-item list-group-item-action active d-flex align-items-center" data-target="#tab-perhatian">
                            <i class="fas fa-info-circle me-3 text-primary"></i> Perhatian
                        </button>
                        <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-organisasi">
                            <i class="fas fa-building me-3 text-secondary"></i> Data Organisasi
                        </button>
                        <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-anggota">
                            <i class="fas fa-users me-3 text-secondary"></i> Data Anggota
                        </button>
                        <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-inventaris">
                            <i class="fas fa-box-open me-3 text-secondary"></i> Inventaris
                        </button>
                        <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-pendukung">
                            <i class="fas fa-file-alt me-3 text-secondary"></i> Dokumen Pendukung
                        </button>
                        <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-review">
                            <i class="fas fa-clipboard-check me-3 text-secondary"></i> Review Akhir
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body" id="tab-content">

                        <div id="tab-perhatian" class="tab-pane active">
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    Isi seluruh data dengan lengkap dan benar sebelum melanjutkan ke tahap berikutnya.
                                </div>
                            </div>

                            <h5 class="fw-bold text-primary">Perhatian</h5>
                            <p>
                                Anda akan melakukan pendaftaran <strong>Kartu Induk Kesenian Banyuwangi</strong>.
                                Mohon isi data dengan benar dan sesuai dengan jenis kesenian Anda.
                            </p>

                            <div class="d-flex justify-content-between mt-3">
                                <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                                <div class="text-end">
                                    <button class="btn btn-primary next-tab" data-next="#tab-organisasi">
                                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="tab-organisasi" class="tab-pane d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>Isi informasi dasar mengenai organisasi kesenian Anda.</div>
                            </div>
                            <?php echo $__env->make('user.organisasi.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div id="tab-anggota" class="tab-pane d-none">
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fas fa-users me-2"></i>
                                <div>Masukkan minimal 3 anggota dalam organisasi Anda.</div>
                            </div>
                            <?php echo $__env->make('user.anggota.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div id="tab-inventaris" class="tab-pane d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-box-open me-2"></i>
                                <div>Isi data inventaris barang yang dimiliki oleh organisasi Anda.</div>
                            </div>
                            <?php echo $__env->make('user.inventaris.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div id="tab-pendukung" class="tab-pane d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-file-alt me-2"></i>
                                <div>Unggah dokumen pendukung seperti surat, foto kegiatan, atau dokumen lain yang relevan.</div>
                            </div>
                            <?php echo $__env->make('user.pendukung.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div id="tab-review" class="tab-pane d-none">
                            <?php echo $__env->make('user.review.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>


</div>


<style>
#form-tabs button {
    border: none;
    border-left: 4px solid transparent;
    text-align: left;
    padding: 12px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
}
#form-tabs button.active {
    background-color: #e9f3ff;
    border-left: 4px solid #0d6efd;
    color: #0d6efd;
}
#form-tabs button:hover {
    background-color: #f8f9fa;
}
#form-tabs {
    max-height: 80vh;
    overflow-y: auto;
}
.tab-pane {
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
#form-tabs button i {
    transition: color 0.2s ease;
}
#form-tabs button.active i {
    color: #0d6efd !important;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah elemen ada (karena jika status 'Menunggu', tab tidak dirender)
    const formTabs = document.getElementById('form-tabs');
    if (!formTabs) return;

    const tabButtons = document.querySelectorAll('#form-tabs button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    function showTab(targetId) {
        tabPanes.forEach(tab => tab.classList.add('d-none'));
        const targetTab = document.querySelector(targetId);
        if (targetTab) targetTab.classList.remove('d-none');

        tabButtons.forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.querySelector(`#form-tabs button[data-target="${targetId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.target));
    });

    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.next));
    });

    document.querySelectorAll('.prev-tab').forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.prev));
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New Code\kik-fullstack\resources\views/user/daftar/index.blade.php ENDPATH**/ ?>