{{-- resources/views/user/review/index.blade.php --}}

<div class="container mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h2 class="fw-bold mb-3">Review Data Sebelum Dikirim</h2>

            <p class="text-muted" style="font-size: 15px;">
                Pastikan seluruh data organisasi, anggota, inventaris, dan dokumen pendukung
                sudah lengkap dan benar sebelum mengirim pengajuan. Data yang sudah dikirim
                tidak dapat diubah kecuali statusnya dikembalikan (revisi) oleh Admin.
            </p>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- FORM SUBMIT --}}
            <form action="{{ route('user.daftar.submit') }}" method="POST">
                @csrf

                {{-- Preview singkat (Opsional) --}}
                <div class="alert alert-info mt-3">
                    <strong>Info:</strong> Data akan dikirim untuk verifikasi Admin.
                    Anda akan diarahkan ke halaman status setelah menekan tombol kirim.
                </div>

                <div class="d-flex justify-content-between mt-5">
                    {{-- Tombol Kembali ke Tab Pendukung --}}
                    <button type="button" class="btn btn-secondary prev-tab px-4"
                            data-prev="#tab-pendukung">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </button>

                    {{-- Tombol Final Kirim --}}
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
