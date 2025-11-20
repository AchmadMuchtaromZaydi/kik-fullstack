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

            {{-- ================= KTP ================= --}}
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
                               <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                    class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            {{-- ================= PAS FOTO ================= --}}
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
                               <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                 class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            {{-- ================= BANNER ================= --}}
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
                               <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                class="img-preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            {{-- ================= FOTO KEGIATAN MULTIPLE ================= --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Kegiatan (bisa lebih dari satu)</label>

                <div class="upload-box-multiple border border-2 rounded-3 p-4 text-center bg-light cursor-pointer
                    @if(isset($dataPendukung) && $dataPendukung->where('tipe','FOTO-KEGIATAN')->count()) has-file @endif"
                    data-tipe="FOTO-KEGIATAN">

                    <i class="fas fa-plus-circle fa-2x text-muted mb-2 upload-icon"></i>
                    <p class="text-muted upload-text">Tambah Foto</p>

                    <input type="file" class="d-none uploader" accept=".jpg,.png,.jpeg,.svg" multiple>

                    <div class="mt-2 row g-2" id="preview-FOTO-KEGIATAN">
                        @foreach($dataPendukung->where('tipe','FOTO-KEGIATAN') as $file)
                            <div class="col-6 col-md-3 position-relative" id="file-{{ $file->id }}">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                               <img src="{{ asset('storage/uploads/organisasi/'.$organisasi->id.'/'.$file->image) }}"
                                class="img-preview-full w-100">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- BUTTONS --}}
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-secondary prev-tab" data-prev="#tab-inventaris">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>

        <button id="btnNextPendukung" class="btn btn-primary px-4 next-tab" data-next="#tab-review" disabled>
            Selanjutnya
        </button>
    </div>
</div>


{{-- CSS --}}
<style>
.upload-box.has-file .upload-icon,
.upload-box.has-file .upload-text,
.upload-box-multiple.has-file .upload-icon,
.upload-box-multiple.has-file .upload-text {
    display: none !important;
}
.img-preview {
    width: 100%;
    max-width: 260px;   /* ukuran foto */
    max-height: 260px;
    object-fit: contain;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 4px;
}
.img-preview-full {   /* untuk foto kegiatan */
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
}
</style>


{{-- SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnNext = document.getElementById('btnNextPendukung');

    function updateNextButton() {
        const required = ['KTP', 'PAS_FOTO', 'BANNER'];
        const ok = required.every(t => document.querySelectorAll(`#preview-${t} .position-relative`).length > 0);
        btnNext.disabled = !ok;
    }

    updateNextButton();


    // ================= HANDLE UPLOAD =================
    document.querySelectorAll('.upload-box, .upload-box-multiple').forEach(box => {
        const input = box.querySelector('.uploader');
        const tipe = box.dataset.tipe;
        const container = document.getElementById('preview-' + tipe);

        if (container.children.length > 0) box.classList.add('has-file');

        box.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            [...input.files].forEach(file => uploadFile(file, tipe, container, box));
        });
    });


    function uploadFile(file, tipe, container, box) {
        let formData = new FormData();
        formData.append('file', file);
        formData.append('tipe', tipe); // LANGSUNG PAKAI FORMAT DB
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('user.pendukung.store') }}", { method: "POST", body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {

                if (tipe !== 'FOTO-KEGIATAN') container.innerHTML = "";

                container.insertAdjacentHTML('beforeend', `
                    <div class="position-relative d-inline-block me-2 mb-2" id="file-${res.data.id}">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-1 deleteFile"></button>
                        <img src="${res.url}" class="${tipe === 'FOTO-KEGIATAN' ? 'img-preview-full w-100' : 'img-preview'}">
                    </div>
                `);

                box.classList.add('has-file');
                updateNextButton();
            }
        });
    }


    // ================== DELETE FILE ==================
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteFile')) {

            const divFile = e.target.closest('.position-relative');
            const id = divFile.id.replace('file-', '');
            const container = divFile.parentElement;
            const box = container.closest('.upload-box, .upload-box-multiple');

            fetch(`{{ url('user-kik/pendukung') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    divFile.remove();

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
