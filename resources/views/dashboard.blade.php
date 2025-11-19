@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-3">
            <i class="fas fa-theater-masks me-2"></i>Kartu Identitas Kesenian (KIK)
        </h2>
        <p class="text-muted">Selamat datang di sistem pendaftaran Kartu Identitas Kesenian.</p>
    </div>

    {{-- =========================
         STATUS ORGANISASI
     ========================== --}}
    @if(isset($organisasi))

        {{-- STATUS PENDING --}}
        @if($organisasi->status === 'Pending')
            <div class="alert alert-warning text-center fw-bold">
                <i class="fas fa-clock me-2"></i>
                Data anda sedang dalam proses verifikasi oleh admin.
            </div>
        @endif

        {{-- STATUS DITOLAK --}}
        @if($organisasi->status === 'Ditolak')
            <div class="alert alert-danger text-center fw-bold">
                <i class="fas fa-times-circle me-2"></i>
                Data anda ditolak. Silakan perbaiki data anda.
            </div>

            <div class="text-center mb-4">
                <a href="{{ route('user.daftar.index') }}" class="btn btn-danger">
                    <i class="fas fa-edit me-2"></i>Perbaiki Data
                </a>
            </div>
        @endif

        {{-- STATUS DISETUJUI --}}
        @if($organisasi->status === 'Disetujui')
            <div class="alert alert-success text-center fw-bold">
                <i class="fas fa-check-circle me-2"></i>
                Selamat! Data anda telah disetujui.
            </div>
        @endif

    @endif

    <div class="row justify-content-center">

        {{-- =========================
             TAMPILKAN TOMBOL DAFTAR
             HANYA JIKA BELUM PERNAH DAFTAR
           ========================== --}}
        @if(!isset($organisasi))
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Daftar Sekarang</h5>
                        <p class="text-muted small mb-4">
                            Ajukan pendaftaran baru untuk mendapatkan Kartu Kesenian.
                        </p>
                        <a href="{{ route('user.daftar.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-pencil-alt me-2"></i>Mulai Daftar
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol Perpanjangan (selalu ada) --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <i class="fas fa-sync-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Perpanjangan Kartu</h5>
                    <p class="text-muted small mb-4">Perpanjang masa berlaku Kartu Identitas Kesenian Anda.</p>
                      <a href="{{ route('user.perpanjang.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-redo me-2"></i>Perpanjang Sekarang
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
