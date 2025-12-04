<?php
    // Cek variabel dari Controller, jika tidak ada cari manual
    if (!isset($dataPendukung)) {
        $organisasi = \App\Models\Organisasi::where('user_id', Auth::id())->first();

        // PERBAIKAN: Cek apakah organisasi ada sebelum mengambil data pendukung
        if ($organisasi) {
            $dataPendukung = \App\Models\DataPendukung::where('organisasi_id', $organisasi->id)->get();
        } else {
            // Koleksi kosong agar tidak error di foreach
            $dataPendukung = collect();
        }
    }
?>

<div class="p-4">
    <h4 class="fw-bold mb-4">Upload Data Pendukung</h4>
    <p class="text-muted mb-4">Unggah dokumen pendukung berikut (format: JPG, PNG, SVG)</p>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto KTP <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    <?php if(isset($dataPendukung) && $dataPendukung->where('tipe','KTP')->count()): ?> has-file <?php endif; ?>"
                    data-tipe="KTP">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-KTP">
                        <?php $__currentLoopData = $dataPendukung->where('tipe','KTP'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-<?php echo e($file->id); ?>">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="<?php echo e(asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image)); ?>" class="img-preview">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Pas Foto <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    <?php if(isset($dataPendukung) && $dataPendukung->where('tipe','PAS_FOTO')->count()): ?> has-file <?php endif; ?>"
                    data-tipe="PAS_FOTO">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-PAS_FOTO">
                        <?php $__currentLoopData = $dataPendukung->where('tipe','PAS_FOTO'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-<?php echo e($file->id); ?>">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="<?php echo e(asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image)); ?>" class="img-preview">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Banner / Poster Organisasi <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    <?php if(isset($dataPendukung) && $dataPendukung->where('tipe','BANNER')->count()): ?> has-file <?php endif; ?>"
                    data-tipe="BANNER">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-BANNER">
                        <?php $__currentLoopData = $dataPendukung->where('tipe','BANNER'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-<?php echo e($file->id); ?>">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="<?php echo e(asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image)); ?>" class="img-preview">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Kegiatan (bisa lebih dari satu)</label>

                
                <div class="row g-3" id="container-FOTO-KEGIATAN">

                    
                    <?php $__currentLoopData = $dataPendukung->where('tipe','FOTO-KEGIATAN'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-3" id="file-<?php echo e($file->id); ?>">
                            <div class="photo-card position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white deleteFile shadow-sm" style="z-index: 10;"></button>
                                <img src="<?php echo e(asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image)); ?>" class="img-grid">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <div class="col-6 col-md-3" id="btn-add-wrapper">
                        <div class="add-photo-btn" id="trigger-upload-kegiatan">
                            <i class="fas fa-plus-circle fa-2x text-muted mb-2"></i>
                            <span class="text-muted small fw-bold">Tambah Foto</span>
                        </div>
                        
                        <input type="file" class="d-none uploader-grid" data-tipe="FOTO-KEGIATAN" accept=".jpg,.png,.jpeg,.svg" multiple>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-secondary prev-tab" data-prev="#tab-inventaris">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>

        <button id="btnNextPendukung" class="btn btn-primary px-4 next-tab"
            data-next="#tab-review" disabled>
            Selanjutnya
        </button>
    </div>
    <!-- Modal Zoom -->
<div class="modal fade" id="zoomModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <img id="zoomImage" src="" class="w-100 rounded-3">
    </div>
  </div>
</div>
</div>


<style>
/* --- STYLE SINGLE UPLOAD (KTP, BANNER) --- */
.upload-box.has-file .upload-icon,
.upload-box.has-file .upload-text {
    display: none !important;
}

.img-preview {
    width: 100%;
    max-width: 260px;
    max-height: 260px;
    object-fit: contain;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 4px;
}

/* --- STYLE GRID PHOTO (KEGIATAN) --- */
.photo-card {
    height: 150px; /* Tinggi tetap agar rapi */
    width: 100%;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #dee2e6;
    background-color: #fff;
}

.img-grid {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Agar gambar mengisi kotak full */
    transition: transform 0.3s;
}

.img-grid:hover {
    transform: scale(1.05);
}

/* Tombol Tambah (Kotak Putus-putus) */
.add-photo-btn {
    height: 150px;
    width: 100%;
    border: 2px dashed #ccc;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background-color: #f8f9fa;
    transition: 0.3s;
}

.add-photo-btn:hover {
    background-color: #e9ecef;
    border-color: #aaa;
}
#zoomModal img {
    max-height: 90vh;
    object-fit: contain;
}
</style>


<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnNext = document.getElementById('btnNextPendukung');

    // 1. CEK STATUS TOMBOL NEXT
    function updateNextButton() {
        const required = ['KTP', 'PAS_FOTO', 'BANNER'];
        const singleOk = required.every(t =>
            document.querySelectorAll(`#preview-${t} .position-relative`).length > 0
        );

        btnNext.disabled = !singleOk;
    }

    updateNextButton();

    // 2. SINGLE UPLOAD HANDLER
    document.querySelectorAll('.upload-box').forEach(box => {
    const input = box.querySelector('.uploader');
    const tipe = box.dataset.tipe;
    const container = document.getElementById('preview-' + tipe);

    if (container.children.length > 0) box.classList.add('has-file');

    // FIX: cegah file dialog saat klik gambar
    box.addEventListener('click', (e) => {
        if (e.target.tagName === 'IMG') {
            return; // buka zoom, bukan upload
        }
        input.click(); // area kosong → upload
    });

    input.addEventListener('change', () => {
        [...input.files].forEach(file => uploadFile(file, tipe, container, box));
    });
});

    // 3. GRID UPLOAD HANDLER
    const inputKegiatan = document.querySelector('.uploader-grid');
    const containerKegiatan = document.getElementById('container-FOTO-KEGIATAN');
    const triggerKegiatan = document.getElementById('trigger-upload-kegiatan');
    const btnWrapper = document.getElementById('btn-add-wrapper');

    triggerKegiatan.addEventListener('click', () => inputKegiatan.click());

    inputKegiatan.addEventListener('change', () => {
        [...inputKegiatan.files].forEach(file => {
            uploadFile(file, 'FOTO-KEGIATAN', containerKegiatan, null);
        });
    });

    // 4. FUNGSI UPLOAD
    function uploadFile(file, tipe, container, box) {
        let formData = new FormData();
        formData.append('file', file);
        formData.append('tipe', tipe);
        formData.append('_token', "<?php echo e(csrf_token()); ?>");

        fetch("<?php echo e(route('user.pendukung.store')); ?>", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {

                if (tipe === 'FOTO-KEGIATAN') {
                    const htmlGrid = `
                        <div class="col-6 col-md-3" id="file-${res.data.id}">
                            <div class="photo-card position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white deleteFile shadow-sm"></button>
                                <img src="${res.url}" class="img-grid">
                            </div>
                        </div>
                    `;
                    btnWrapper.insertAdjacentHTML('beforebegin', htmlGrid);

                } else {
                    container.innerHTML = `
                        <div class="position-relative d-inline-block me-2 mb-2" id="file-${res.data.id}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                            <img src="${res.url}" class="img-preview">
                        </div>
                    `;
                    box.classList.add('has-file');
                }

                updateNextButton();
            }
        });
    }

    // 5. DELETE FILE
    document.addEventListener('click', function(e) {

        if (e.target.classList.contains('deleteFile')) {

            const wrapper = e.target.closest('[id^="file-"]');
            const id = wrapper.id.replace('file-', '');
            const boxSingle = wrapper.closest('.upload-box');

            fetch(`<?php echo e(url('user-kik/pendukung')); ?>/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    wrapper.remove();

                    if (boxSingle) {
                        const preview = boxSingle.querySelector('[id^="preview-"]');
                        if (preview.children.length === 0) {
                            boxSingle.classList.remove('has-file');
                        }
                    }

                    updateNextButton();
                }
            });
        }
    });

    // 6. ZOOM GAMBAR (HARUS DI LUAR DELETE FILE)
    document.addEventListener("click", function (e) {

        // zoom single
        if (e.target.classList.contains("img-preview")) {
            document.getElementById("zoomImage").src = e.target.src;
            new bootstrap.Modal(document.getElementById("zoomModal")).show();
        }

        // zoom grid
        if (e.target.classList.contains("img-grid")) {
            document.getElementById("zoomImage").src = e.target.src;
            new bootstrap.Modal(document.getElementById("zoomModal")).show();
        }

    });

});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/user/pendukung/index.blade.php ENDPATH**/ ?>