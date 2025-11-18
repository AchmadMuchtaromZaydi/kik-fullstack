<div class="p-4">
    <h4 class="fw-bold mb-4">Upload Data Pendukung</h4>
    <p class="text-muted mb-4">Unggah dokumen pendukung berikut (format: JPG, PNG, SVG)</p>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- KTP --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto KTP <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','KTP')->count()) has-file @endif"
                    data-tipe="ktp">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>

                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">
                    <div class="mt-2" id="preview-ktp">
                        @if(isset($dataPendukung))
                            @foreach($dataPendukung->where('tipe', 'KTP') as $file)
                                <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                    <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                         class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pas Foto --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Pas Foto <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','PAS_FOTO')->count()) has-file @endif"
                    data-tipe="pas_foto">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>

                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">
                    <div class="mt-2" id="preview-pas_foto">
                        @if(isset($dataPendukung))
                            @foreach($dataPendukung->where('tipe', 'PAS_FOTO') as $file)
                                <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                    <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                         class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Banner --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Banner / Poster Organisasi <span class="text-danger">*</span></label>
                <div class="upload-box border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','BANNER')->count()) has-file @endif"
                    data-tipe="banner">

                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Klik/Seret file ke sini</p>

                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg">
                    <div class="mt-2" id="preview-banner">
                        @if(isset($dataPendukung))
                            @foreach($dataPendukung->where('tipe', 'BANNER') as $file)
                                <div class="position-relative d-inline-block me-2 mb-2" id="file-{{ $file->id }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                    <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                         class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Foto Kegiatan --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Kegiatan (bisa lebih dari satu)</label>
                <div class="upload-box-multiple border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','FOTO-KEGIATAN')->count()) has-file @endif"
                    data-tipe="kegiatan">

                    <i class="fas fa-plus-circle fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Tambah Foto</p>

                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg" multiple>

                    <div class="mt-2 row g-2" id="preview-kegiatan">
                        @if(isset($dataPendukung))
                            @foreach($dataPendukung->where('tipe', 'FOTO-KEGIATAN') as $file)
                                <div class="col-6 col-md-3 position-relative" id="file-{{ $file->id }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                                    <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                         class="img-thumbnail w-100">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">

    <!-- Tombol Kembali -->
    <button
        class="btn btn-secondary prev-tab"
        data-prev="#tab-inventaris"
    >
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </button>

    <!-- Tombol Selanjutnya -->
    <button
        id="btnNextPendukung"
        class="btn btn-success px-4 next-tab"
        data-next="#tab-review"
        disabled
    >
        Selanjutnya
    </button>

</div>

</div>

{{-- === CSS: sembunyikan icon jika ada file === --}}
<style>
.upload-box.has-file .upload-icon,
.upload-box.has-file .upload-text,
.upload-box-multiple.has-file .upload-icon,
.upload-box-multiple.has-file .upload-text {
    display: none !important;
}
</style>

{{-- === SCRIPT === --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnNext = document.getElementById('btnNextPendukung');

    // === CEK JUMLAH FILE REQUIRED ===
    function updateNextButton() {
        const requiredTypes = ['ktp','pas_foto','banner']; // hanya required field
        const allFilled = requiredTypes.every(t => document.querySelectorAll(`#preview-${t} .position-relative`).length > 0);
        btnNext.disabled = !allFilled;
    }

    updateNextButton();

    // === EVENT UPLOAD BOX ===
    document.querySelectorAll('.upload-box, .upload-box-multiple').forEach(box => {
        const input = box.querySelector('.uploader');
        const tipe = box.dataset.tipe;
        const container = document.getElementById('preview-' + tipe);

        // jika sudah ada preview lama
        if (container.children.length > 0) {
            box.classList.add('has-file');
        }

        box.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            [...input.files].forEach(file => uploadFile(file, tipe, container, box));
        });
    });

    // === UPLOAD FILE ===
    function uploadFile(file, tipe, container, box) {
        let formData = new FormData();
        formData.append('file', file);
        // convert tipe back ke format DB (misal KTP, PAS_FOTO, BANNER, FOTO-KEGIATAN)
        formData.append('tipe', tipe === 'kegiatan' ? 'FOTO-KEGIATAN' : tipe.toUpperCase());
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('user.pendukung.store') }}", { method: "POST", body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                // jika tipe tunggal, hapus semua preview lama
                if (!['kegiatan'].includes(tipe)) {
                    container.innerHTML = "";
                }

                container.insertAdjacentHTML('beforeend', `
                    <div class="position-relative d-inline-block me-2 mb-2" id="file-${res.data.id}">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                        <img src="${res.url}" class="img-thumbnail ${tipe === 'kegiatan' ? 'w-100' : ''}">
                    </div>
                `);

                box.classList.add('has-file');
                updateNextButton();
            }
        });
    }

    // === HAPUS FILE ===
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteFile')) {

            const fileDiv = e.target.closest('.position-relative');
            const id = fileDiv.id.replace('file-', '');
            const container = fileDiv.parentElement;
            const box = container.closest('.upload-box, .upload-box-multiple');

            fetch(`{{ url('user-kik/pendukung') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    fileDiv.remove();

                    if (container.children.length === 0) {
                        box.classList.remove('has-file');
                    }

                    updateNextButton();
                }
            });
        }
    });

});
</script>
@endpush

