<div class="container mt-4">

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h2 class="fw-bold mb-3">Review Data Sebelum Dikirim</h2>

            <p class="text-muted" style="font-size: 15px;">
                Pastikan seluruh data organisasi, anggota, inventaris, dan dokumen pendukung
                sudah lengkap dan benar sebelum mengirim pengajuan.
            </p>

            {{-- Pesan sukses / error --}}
             @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
{{--
            <form action="{{ route('user.review.submit') }}" method="POST"> --}}
                @csrf

                <div class="d-flex justify-content-between mt-4">

                    <!-- Tombol Kembali ke tab-pendukung -->
                    <button class="btn btn-secondary prev-tab"
                            data-prev="#tab-pendukung">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </button>

                    <!-- Tombol Selanjutnya ke tab-selesai -->
                    <button class="btn btn-primary next-tab"
                            data-next="#tab-selesai">
                        Kirim Data →
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
