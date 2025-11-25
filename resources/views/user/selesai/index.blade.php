<div class="container mt-4">

    {{-- KASUS 1: MENUNGGU VERIFIKASI (Pending) --}}
    @if($verifikasi && $verifikasi->status == 'Menunggu Verifikasi')
        <div class="alert alert-warning d-flex align-items-center shadow-sm">
            <i class="fas fa-clock fa-2x me-3"></i>
            <div>
                <h4 class="alert-heading fw-bold">Sedang Dalam Proses Verifikasi</h4>
                <p class="mb-0">
                    Data Anda telah berhasil dikirim dan saat ini sedang ditinjau oleh Admin.
                    Mohon menunggu 1x24 jam atau cek secara berkala.
                </p>
            </div>
        </div>

    {{-- KASUS 2: DITOLAK (Rejected) --}}
    @elseif($verifikasi && $verifikasi->status == 'Ditolak')
        <div class="alert alert-danger shadow-sm">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <h4 class="alert-heading fw-bold">Pengajuan Perlu Revisi</h4>
                    <p class="mb-0">Mohon maaf, data Anda belum dapat disetujui. Silakan perbaiki data sesuai catatan di bawah ini.</p>
                </div>
            </div>

            {{-- Menampilkan Catatan dari Admin --}}
            @if($verifikasi->catatan)
                <hr>
                <p class="mb-0 fw-bold">Catatan Admin:</p>
                <p class="mb-0 text-danger bg-white p-2 rounded border border-danger mt-1">
                    {{ $verifikasi->catatan }}
                </p>
            @endif
        </div>

        {{-- Tombol untuk kembali memperbaiki data --}}
        <div class="text-center mt-3 mb-3">
            <a href="{{ route('user.daftar.index') }}" class="btn btn-warning px-4 text-dark fw-bold">
                <i class="fas fa-edit me-2"></i> Perbaiki Data
            </a>
        </div>

    {{-- KASUS 3: DISETUJUI (Approved) --}}
    @elseif($verifikasi && $verifikasi->status == 'Approved')
        <div class="alert alert-success d-flex align-items-center shadow-sm">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div>
                <h4 class="alert-heading fw-bold">Verifikasi Berhasil!</h4>
                <p class="mb-0">
                    Selamat, organisasi Anda telah terverifikasi. Anda sekarang dapat mencetak Kartu Induk Kesenian.
                </p>
            </div>
        </div>

    {{-- KASUS 4: BARU SUBMIT (Default fallback) --}}
    @else
        <div class="alert alert-info d-flex align-items-center shadow-sm">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>Berhasil!</strong> Data Anda telah kami terima. Status verifikasi akan muncul di sini setelah diproses.
            </div>
        </div>
    @endif

    {{-- Tombol Navigasi Bawah --}}
    <div class="text-center mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary px-4">
            <i class="fas fa-home me-2"></i> Kembali ke Dashboard
        </a>
    </div>

</div>
