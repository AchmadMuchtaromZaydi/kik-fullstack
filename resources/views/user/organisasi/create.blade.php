<div class="p-4">
    {{-- FLASH MESSAGE OTOMATIS (dari sessionStorage) --}}
    <div id="flash-area"></div>

    <h4 class="fw-bold mb-4">Data Organisasi</h4>
    <p class="text-muted mb-4">Isi data organisasi anda:</p>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form id="formOrganisasi" action="{{ route('user.organisasi.store') }}" method="POST">
                @csrf

                {{-- Nama & Tanggal Berdiri --}}
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Organisasi</label>
                        <input type="text" name="nama" class="form-control" required value="{{ $organisasi->nama ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Berdiri</label>
                        <input type="date" name="tanggal_berdiri" class="form-control" required value="{{ $organisasi->tanggal_berdiri ?? '' }}">
                    </div>
                </div>

                {{-- Jenis & Sub --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis Kesenian</label>
                        <select name="jenis_kesenian" id="jenis_kesenian" class="form-select" required>
                            <option value="">-- Pilih Jenis Kesenian --</option>
                            @foreach($jenisKesenian as $jenis)
                                <option value="{{ $jenis->id }}" @selected(isset($organisasi) && $organisasi->jenis_kesenian == $jenis->id)>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sub Jenis</label>
                        <select name="sub_kesenian" id="sub_kesenian" class="form-select" required>
                            <option value="">-- Pilih Sub Jenis --</option>
                            @if(isset($organisasi))
                                @foreach(\App\Models\JenisKesenian::where('parent', $organisasi->jenis_kesenian)->get() as $sub)
                                    <option value="{{ $sub->id }}" @selected($organisasi->sub_kesenian == $sub->id)>
                                        {{ $sub->nama }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jumlah Anggota</label>
                        <input type="number" name="jumlah_anggota" class="form-control" required value="{{ $organisasi->jumlah_anggota ?? '' }}">
                    </div>
                </div>

                <hr>

                {{-- Alamat --}}
                <h6 class="fw-bold mb-3">Alamat Sekretariat Organisasi</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kabupaten</label>
                        <select name="kabupaten_kode" id="kabupaten" class="form-select" required>
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach($kabupaten as $k)
                                <option value="{{ $k->kode }}" @selected(isset($organisasi) && $organisasi->kabupaten == $k->nama)>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <select name="kecamatan_kode" id="kecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Desa</label>
                        <select name="desa_kode" id="desa" class="form-select" required>
                            <option value="">-- Pilih Desa --</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ $organisasi->alamat ?? '' }}</textarea>
                </div>

                <div class="text-end">
                    <button type="button" id="btnSimpan" class="btn btn-primary px-4" disabled> Simpan Data </button>
                </div>
            </form>

            {{-- Tombol Navigasi --}}
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-secondary prev-tab" data-prev="#tab-perhatian">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </button>
                <button id="btnNextOrganisasi" class="btn btn-success px-4 next-tab" data-next="#tab-anggota" @disabled(!$organisasi)>
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const flashArea = document.getElementById("flash-area");
    const form = document.getElementById('formOrganisasi');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnNext = document.getElementById('btnNextOrganisasi');

    /* ============================== FLASH MESSAGE OTOMATIS ============================== */
    const flashMsg = sessionStorage.getItem("flash_organisasi");
    if (flashMsg) {
        flashArea.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${flashMsg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        sessionStorage.removeItem("flash_organisasi");
    }

    /* ============================== VALIDASI FORM WAJIB ============================== */
    function checkForm() {
        const required = form.querySelectorAll('[required]');
        let valid = true;
        required.forEach(f => { if (!f.value.trim()) valid = false; });
        btnSimpan.disabled = !valid;
    }
    form.addEventListener('input', checkForm);
    checkForm();

    /* ============================== SIMPAN DATA AJAX TANPA RELOAD ============================== */
    btnSimpan.addEventListener('click', function () {
        const formData = new FormData(form);
        btnSimpan.disabled = true;
        btnSimpan.innerText = "Menyimpan...";

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success_organisasi) {
                // Flash message
                flashArea.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                btnNext.disabled = false; // Tombol Selanjutnya aktif
            } else if (data.error) {
                flashArea.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${data.error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            }
        })
        .catch(err => alert("Terjadi kesalahan saat menyimpan data"))
        .finally(() => btnSimpan.innerText = "Simpan Data");
    });

    /* ============================== DROPDOWN DINAMIS ============================== */
    const jenis = document.getElementById('jenis_kesenian');
    const sub = document.getElementById('sub_kesenian');
    const kab = document.getElementById('kabupaten');
    const kec = document.getElementById('kecamatan');
    const desa = document.getElementById('desa');

    // Sub jenis kesenian
    jenis.addEventListener('change', function () {
        fetch(`/user-kik/organisasi/sub/${this.value}`)
        .then(res => res.json())
        .then(data => {
            sub.innerHTML = `<option value="">-- Pilih Sub --</option>`;
            data.forEach(i => sub.innerHTML += `<option value="${i.id}">${i.nama}</option>`);
        });
    });

    // Kecamatan
    kab.addEventListener('change', function () {
        fetch(`/user-kik/organisasi/kecamatan/${this.value}`)
        .then(res => res.json())
        .then(data => {
            kec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(i => kec.innerHTML += `<option value="${i.kode}">${i.nama}</option>`);

            // Pilih kecamatan lama jika ada
            @if(isset($organisasi) && $organisasi->kecamatan)
            const oldKec = "{{ $organisasi->kecamatan }}";
            [...kec.options].find(o => o.text === oldKec)?.setAttribute('selected', 'selected');
            @endif

            // Trigger change untuk load desa
            kec.dispatchEvent(new Event("change"));
        });
    });

    // Desa
    kec.addEventListener('change', function () {
        fetch(`/user-kik/organisasi/desa/${this.value}`)
        .then(res => res.json())
        .then(data => {
            desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
            data.forEach(i => desa.innerHTML += `<option value="${i.kode}">${i.nama}</option>`);

            // Pilih desa lama jika ada
            @if(isset($organisasi) && $organisasi->desa)
            const oldDesa = "{{ $organisasi->desa }}";
            [...desa.options].find(o => o.text === oldDesa)?.setAttribute('selected', 'selected');
            @endif
        });
    });

    // Trigger awal untuk populate kecamatan & desa jika ada data lama
    @if(isset($organisasi) && $organisasi->kabupaten)
    kab.dispatchEvent(new Event("change"));
    @endif

});
</script>
@endpush

