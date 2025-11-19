@extends('layouts.app')

@section('title', 'Formulir Permohonan Kartu Induk Kesenian')

@section('content')
<div class="container mt-5">
    <h3 class="fw-bold mb-4">Formulir Permohonan Kartu Induk Kesenian</h3>

    <div class="row">
        <!-- Sidebar Tabs -->
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
                    <button class="list-group-item list-group-item-action d-flex align-items-center" data-target="#tab-selesai">
                        <i class="fas fa-check-circle me-3 text-success"></i> Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body" id="tab-content">

                     <!-- TAB 1: PERHATIAN -->
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

                            <!-- Tombol Kembali & Selanjutnya -->
                            <div class="d-flex justify-content-between mt-3">

                                <!-- Tombol Kembali ke Dashboard -->
                                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>

                                <div class="text-end">
                                    <button class="btn btn-primary next-tab" data-next="#tab-organisasi">
                                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                            </div>
                        </div>


                    <!-- TAB 2: ORGANISASI -->
                    <div id="tab-organisasi" class="tab-pane d-none">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>Isi informasi dasar mengenai organisasi kesenian Anda.</div>
                        </div>

                        @include('user.organisasi.create')

                        {{-- <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-secondary prev-tab" data-prev="#tab-perhatian">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                           {{-- <button class="btn btn-primary next-tab" data-next="#tab-anggota">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button> --}}
                        {{-- </div> --}}
                    </div>

                    <!-- TAB 3: ANGGOTA -->
                    <div id="tab-anggota" class="tab-pane d-none">
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="fas fa-users me-2"></i>
                            <div>Masukkan minimal 3 anggota dalam organisasi Anda.</div>
                        </div>

                        @include('user.anggota.index')

                        {{-- <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-secondary prev-tab" data-prev="#tab-organisasi">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                            <button class="btn btn-primary next-tab" data-next="#tab-inventaris">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div> --}}
                    </div>

                    <!-- TAB 4: INVENTARIS -->
                    <div id="tab-inventaris" class="tab-pane d-none">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-box-open me-2"></i>
                            <div>Isi data inventaris barang yang dimiliki oleh organisasi Anda.</div>
                        </div>

                        @include('user.inventaris.index')

                        {{-- <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-secondary prev-tab" data-prev="#tab-anggota">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                            <button class="btn btn-primary next-tab" data-next="#tab-dokumen">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div> --}}
                    </div>

                    <!-- TAB 5:  -->
                   <div id="tab-pendukung" class="tab-pane d-none">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-file-alt me-2"></i>
                            <div>Unggah dokumen pendukung seperti surat, foto kegiatan, atau dokumen lain yang relevan.</div>
                        </div>

                        @include('user.pendukung.index')

                        {{-- <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-secondary prev-tab" data-prev="#tab-inventaris">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </button>
                            <button class="btn btn-primary next-tab" data-next="#tab-review">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div> --}}
                    </div>

                   <!-- TAB 6: REVIEW -->
                <div id="tab-review" class="tab-pane d-none">
                    @include('user.review.index')
                </div>

                    <!-- TAB 7: SELESAI -->
                    <div id="tab-selesai" class="tab-pane d-none">
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div><strong>Selesai!</strong> Semua data telah dikirim dan disimpan dengan benar.</div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS --}}
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

/* Tambahkan ini */
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

{{-- SCRIPT TAB --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('#form-tabs button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    const sidebar = document.getElementById('form-tabs');

    function showTab(targetId) {
        // Sembunyikan semua tab
        tabPanes.forEach(tab => tab.classList.add('d-none'));

        // Tampilkan tab tujuan
        const targetTab = document.querySelector(targetId);
        if (targetTab) targetTab.classList.remove('d-none');

        // Reset semua tombol
        tabButtons.forEach(btn => btn.classList.remove('active'));

        // Aktivkan tombol yang sesuai
        const activeBtn = document.querySelector(`#form-tabs button[data-target="${targetId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');

            // Auto scroll ke tombol yang dipilih (biar selalu kelihatan)
            activeBtn.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    // Klik tombol sidebar
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.target));
    });

    // Tombol Next
    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.next));
    });

    // Tombol Prev
    document.querySelectorAll('.prev-tab').forEach(btn => {
        btn.addEventListener('click', () => showTab(btn.dataset.prev));
    });
});
</script>
@endsection
