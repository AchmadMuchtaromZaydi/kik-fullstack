@if(!isset($dataPendukung))
@php
    $organisasi = \App\Models\Organisasi::where('user_id', Auth::id())->first();
    $dataPendukung = \App\Models\DataPendukung::where('organisasi_id', $organisasi->id)->get();
@endphp
@endif

<div class="p-4">
    <h4 class="fw-bold mb-4">Upload Data Pendukung</h4>
    <p class="text-muted mb-4">Unggah dokumen pendukung berikut (format: JPG, PNG, SVG)</p>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- ================= 1. KTP (SINGLE) ================= --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto KTP <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','KTP')->count()) has-file @endif"
                    data-tipe="KTP">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-KTP">
                        @foreach($dataPendukung->where('tipe','KTP') as $file)
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}" class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ================= 2. PAS FOTO (SINGLE) ================= --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Pas Foto <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','PAS_FOTO')->count()) has-file @endif"
                    data-tipe="PAS_FOTO">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-PAS_FOTO">
                        @foreach($dataPendukung->where('tipe','PAS_FOTO') as $file)
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}" class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ================= 3. BANNER (SINGLE) ================= --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Banner / Poster Organisasi <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','BANNER')->count()) has-file @endif"
                    data-tipe="BANNER">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>
                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">

                    <div class="mt-2" id="preview-BANNER">
                        @foreach($dataPendukung->where('tipe','BANNER') as $file)
                            <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}" class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ================= 4. FOTO KEGIATAN (GRID / MULTIPLE) ================= --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Kegiatan (bisa lebih dari satu)</label>

                {{-- Container Grid --}}
                <div class="row g-3" id="container-FOTO-KEGIATAN">

                    {{-- Loop Gambar yang Sudah Ada --}}
                    @foreach($dataPendukung->where('tipe','FOTO-KEGIATAN') as $file)
                        <div class="col-6 col-md-3" id="file-{{ $file->id }}">
                            <div class="photo-card position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white deleteFile shadow-sm" style="z-index: 10;"></button>
                                <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}" class="img-grid">
                            </div>
                        </div>
                    @endforeach

                    {{-- Tombol Tambah (Selalu Muncul di Akhir) --}}
                    <div class="col-6 col-md-3" id="btn-add-wrapper">
                        <div class="add-photo-btn" id="trigger-upload-kegiatan">
                            <i class="fas fa-plus-circle fa-2x text-muted mb-2"></i>
                            <span class="text-muted small fw-bold">Tambah Foto</span>
                        </div>
                        {{-- Input file tetap hidden --}}
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
</div>

{{-- CSS --}}
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
</style>

{{-- SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnNext = document.getElementById('btnNextPendukung');

    // 1. CEK STATUS TOMBOL NEXT
    function updateNextButton() {
        // Wajib: KTP, PAS_FOTO, BANNER
        const required = ['KTP', 'PAS_FOTO', 'BANNER'];
        const singleOk = required.every(t =>
            document.querySelectorAll(`#preview-${t} .position-relative`).length > 0
        );

        // Opsional: Jika Foto Kegiatan juga wajib, uncomment baris di bawah ini:
        // const kegiatanOk = document.querySelectorAll('#container-FOTO-KEGIATAN .photo-card').length > 0;
        // btnNext.disabled = !(singleOk && kegiatanOk);

        btnNext.disabled = !singleOk; // Default: hanya yg single yg wajib
    }

    updateNextButton();

    // 2. HANDLER UNTUK SINGLE UPLOAD (KTP, Pas Foto, Banner)
    document.querySelectorAll('.upload-box').forEach(box => {
        const input = box.querySelector('.uploader');
        const tipe = box.dataset.tipe;
        const container = document.getElementById('preview-' + tipe);

        if (container.children.length > 0) box.classList.add('has-file');

        box.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            [...input.files].forEach(file => uploadFile(file, tipe, container, box));
        });
    });

    // 3. HANDLER UNTUK GRID UPLOAD (Foto Kegiatan)
    const triggerKegiatan = document.getElementById('trigger-upload-kegiatan');
    const inputKegiatan = document.querySelector('.uploader-grid');
    const containerKegiatan = document.getElementById('container-FOTO-KEGIATAN');
    const btnWrapper = document.getElementById('btn-add-wrapper');

    if(triggerKegiatan) {
        triggerKegiatan.addEventListener('click', () => inputKegiatan.click());

        inputKegiatan.addEventListener('change', () => {
            [...inputKegiatan.files].forEach(file => {
                // Panggil upload, parameter box null karena ini grid
                uploadFile(file, 'FOTO-KEGIATAN', containerKegiatan, null);
            });
        });
    }

    // 4. FUNGSI UTAMA UPLOAD KE SERVER
    function uploadFile(file, tipe, container, box) {
        let formData = new FormData();
        formData.append('file', file);
        formData.append('tipe', tipe);
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('user.pendukung.store') }}", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {

                if (tipe === 'FOTO-KEGIATAN') {
                    // LOGIC GRID: Masukkan HTML gambar SEBELUM tombol tambah
                    const htmlGrid = `
                        <div class="col-6 col-md-3" id="file-${res.data.id}">
                            <div class="photo-card position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white deleteFile shadow-sm" style="z-index:10"></button>
                                <img src="${res.url}" class="img-grid">
                            </div>
                        </div>
                    `;
                    btnWrapper.insertAdjacentHTML('beforebegin', htmlGrid);

                } else {
                    // LOGIC SINGLE: Replace isi container
                    container.innerHTML = "";
                    container.insertAdjacentHTML('beforeend', `
                        <div class="position-relative d-inline-block me-2 mb-2" id="file-${res.data.id}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                            <img src="${res.url}" class="img-preview">
                        </div>
                    `);
                    if(box) box.classList.add('has-file');
                }
                updateNextButton();
            }
        });
    }

    // 5. HAPUS FILE (Generic untuk Single & Grid)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteFile')) {
            // Cek apakah tombol ini milik Grid atau Single
            const isGrid = e.target.closest('.photo-card');

            // Cari elemen wrapper utama (col-.. atau div position-relative)
            const wrapper = isGrid ? e.target.closest('.col-6') : e.target.closest('.position-relative');

            if (!wrapper) return;

            const id = wrapper.id.replace('file-', '');
            const boxSingle = wrapper.closest('.upload-box'); // Hanya ada di single upload

            fetch(`{{ url('user-kik/pendukung') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    wrapper.remove();

                    // Jika single upload jadi kosong, hilangkan class has-file agar teks muncul lagi
                    if (boxSingle && boxSingle.querySelector('[id^="preview-"]').children.length === 0) {
                        boxSingle.classList.remove('has-file');
                    }

                    updateNextButton();
                }
            });
        }
    });

});
</script>
@endpush
