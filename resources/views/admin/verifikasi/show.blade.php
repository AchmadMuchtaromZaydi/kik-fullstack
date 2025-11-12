{{-- resources/views/admin/verifikasi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Verifikasi - ' . $organisasi->nama)
@section('page-title', 'Verifikasi Permohonan')

@section('content')
    <div class="container-fluid">
        {{-- ====== ALERTS ====== --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger shadow-sm d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            {{-- ===== SIDEBAR (Progress + Info) - STICKY ===== --}}
            <div class="col-md-3">
                <div class="sticky-sidebar">
                    {{-- Back to Main Menu --}}
                    <div class="back-navigation mb-4">
                        <a href="{{ route('admin.kesenian.index') }}"
                            class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Data Kesenian
                        </a>
                    </div>

                    {{-- Progress Card --}}
                    <div class="card border-0 shadow-sm mb-4 progress-card">
                        <div class="card-header bg-gradient-primary text-white">
                            <div class="d-flex align-items-center">
                                <div class="progress-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">Progress Verifikasi</h6>
                                    <small class="opacity-80">Lengkapi semua tahapan</small>
                                </div>
                            </div>
                            @php
                                $completedSteps = 0;
                                $totalSteps = 5;
                                $steps = [
                                    'data_organisasi',
                                    'data_anggota',
                                    'data_inventaris',
                                    'data_pendukung',
                                    'review',
                                ];
                                foreach ($steps as $step) {
                                    if ($verifikasiData->where('tipe', $step)->first()?->status == 'valid') {
                                        $completedSteps++;
                                    }
                                }
                                $progressPercentage = ($completedSteps / $totalSteps) * 100;
                            @endphp
                            <div class="progress-overall mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="fw-semibold">Progress</small>
                                    <small class="fw-bold">{{ $completedSteps }}/{{ $totalSteps }}</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-white" role="progressbar"
                                        style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="progress-steps">
                                {{-- Step 1: Perhatian --}}
                                <div
                                    class="progress-step {{ in_array($tabActive, ['general', 'data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">1</div>
                                        <div class="step-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Perhatian</div>
                                        <div class="step-description">Panduan verifikasi</div>
                                    </div>
                                    <div class="step-status">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>

                                {{-- Step 2: Data Organisasi --}}
                                <div
                                    class="progress-step {{ in_array($tabActive, ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">2</div>
                                        <div class="step-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Data Organisasi</div>
                                        <div class="step-description">Informasi lembaga</div>
                                    </div>
                                    <div class="step-status">
                                        @if ($verifikasi = $verifikasiData->where('tipe', 'data_organisasi')->first())
                                            @if ($verifikasi->status == 'valid')
                                                <div class="status-badge success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            @else
                                                <div class="status-badge danger">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="status-badge pending">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 3: Data Anggota --}}
                                <div
                                    class="progress-step {{ in_array($tabActive, ['data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">3</div>
                                        <div class="step-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Data Anggota</div>
                                        <div class="step-description">Struktur kepengurusan</div>
                                    </div>
                                    <div class="step-status">
                                        @if ($verifikasi = $verifikasiData->where('tipe', 'data_anggota')->first())
                                            @if ($verifikasi->status == 'valid')
                                                <div class="status-badge success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            @else
                                                <div class="status-badge danger">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="status-badge pending">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 4: Inventaris Barang --}}
                                <div
                                    class="progress-step {{ in_array($tabActive, ['data_inventaris', 'data_pendukung', 'review']) ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">4</div>
                                        <div class="step-icon">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Inventaris Barang</div>
                                        <div class="step-description">Aset organisasi</div>
                                    </div>
                                    <div class="step-status">
                                        @if ($verifikasi = $verifikasiData->where('tipe', 'data_inventaris')->first())
                                            @if ($verifikasi->status == 'valid')
                                                <div class="status-badge success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            @else
                                                <div class="status-badge danger">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="status-badge pending">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 5: Dokumen Pendukung --}}
                                <div
                                    class="progress-step {{ in_array($tabActive, ['data_pendukung', 'review']) ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">5</div>
                                        <div class="step-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Dokumen Pendukung</div>
                                        <div class="step-description">File & dokumentasi</div>
                                    </div>
                                    <div class="step-status">
                                        @if ($verifikasi = $verifikasiData->where('tipe', 'data_pendukung')->first())
                                            @if ($verifikasi->status == 'valid')
                                                <div class="status-badge success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            @else
                                                <div class="status-badge danger">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="status-badge pending">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 6: Review Akhir --}}
                                <div class="progress-step {{ $tabActive == 'review' ? 'active' : '' }}">
                                    <div class="step-indicator">
                                        <div class="step-number">6</div>
                                        <div class="step-icon">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                    </div>
                                    <div class="step-content">
                                        <div class="step-title">Review Akhir</div>
                                        <div class="step-description">Konfirmasi & selesai</div>
                                    </div>
                                    <div class="step-status">
                                        <i class="fas fa-flag-checkered text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Organisasi --}}
                    <div class="card border-0 shadow-sm info-card">
                        <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h6 class="mb-0 fw-bold ms-2">Informasi Organisasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-building me-2"></i>
                                    Nama Organisasi
                                </div>
                                <div class="info-value">{{ $organisasi->nama }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user me-2"></i>
                                    Ketua
                                </div>
                                <div class="info-value">{{ $organisasi->nama_ketua }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Kecamatan
                                </div>
                                <div class="info-value">{{ $organisasi->nama_kecamatan ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tag me-2"></i>
                                    Status
                                </div>
                                <div class="info-value">
                                    <span
                                        class="status-badge {{ $organisasi->status == 'Request' ? 'warning' : ($organisasi->status == 'Allow' ? 'success' : 'danger') }}">
                                        {{ $organisasi->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="col-md-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 main-content-area">
                        {{-- Konten tab akan diisi di sini --}}
                        @if ($tabActive == 'general')
                            @include('admin.verifikasi.tabs.general')
                        @elseif($tabActive == 'data_organisasi')
                            @include('admin.verifikasi.tabs.data_organisasi')
                        @elseif($tabActive == 'data_anggota')
                            @include('admin.verifikasi.tabs.data_anggota')
                        @elseif($tabActive == 'data_inventaris')
                            @include('admin.verifikasi.tabs.data_inventaris')
                        @elseif($tabActive == 'data_pendukung')
                            @include('admin.verifikasi.tabs.data_pendukung')
                        @elseif($tabActive == 'review')
                            @include('admin.verifikasi.tabs.review')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STYLE ===== --}}
    <style>
        /* ====== STICKY SIDEBAR ====== */
        .sticky-sidebar {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        /* ====== BACK NAVIGATION ====== */
        .back-navigation .btn {
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid #4e73df;
        }

        .back-navigation .btn:hover {
            background-color: #4e73df;
            color: white;
            transform: translateX(-3px);
        }

        /* ====== PROGRESS CARD ====== */
        .progress-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-card .card-header {
            padding: 1.25rem;
            border-bottom: none;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%) !important;
        }

        .progress-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .progress-overall {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        /* ====== PROGRESS STEPS ====== */
        .progress-steps {
            padding: 0;
        }

        .progress-step {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .progress-step:last-child {
            border-bottom: none;
        }

        .progress-step:hover {
            background-color: #f8f9ff;
        }

        .progress-step.active {
            background: linear-gradient(90deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
            border-left: 4px solid #4e73df;
        }

        .step-indicator {
            position: relative;
            margin-right: 12px;
        }

        .step-number {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #4e73df;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 2;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            background: #f8f9ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4e73df;
            transition: all 0.3s ease;
        }

        .progress-step.active .step-icon {
            background: #4e73df;
            color: white;
            transform: scale(1.1);
        }

        .step-content {
            flex-grow: 1;
        }

        .step-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2e384d;
            margin-bottom: 2px;
        }

        .step-description {
            font-size: 0.75rem;
            color: #8c94a0;
        }

        .step-status {
            margin-left: auto;
        }

        .status-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.pending {
            background: #e2e3e5;
            color: #6c757d;
        }

        /* ====== INFO CARD ====== */
        .info-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .info-card .card-header {
            padding: 1rem 1.25rem;
            border-bottom: none;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-card .card-body {
            padding: 1.25rem;
        }

        .info-item {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            flex: 1;
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            flex: 1;
            text-align: right;
            font-size: 0.85rem;
            font-weight: 600;
            color: #2e384d;
        }

        /* ====== RESPONSIVE ADJUSTMENTS ====== */
        @media (max-width: 768px) {
            .sticky-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 2rem;
            }

            .col-md-3 {
                margin-bottom: 1rem;
            }

            .main-content-area {
                padding: 1rem !important;
            }

            .progress-step {
                padding: 0.75rem 1rem;
            }

            .step-icon {
                width: 35px;
                height: 35px;
            }
        }

        /* ====== SMOOTH ANIMATIONS ====== */
        .progress-step {
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- ===== JAVASCRIPT UNTUK INTERAKTIF ===== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth navigation
            document.querySelectorAll('.progress-step').forEach(step => {
                step.addEventListener('click', function() {
                    const stepTitle = this.querySelector('.step-title').textContent;
                    // Add navigation logic here if needed
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });

            // Back button hover effect
            const backBtn = document.querySelector('.back-navigation .btn');
            if (backBtn) {
                backBtn.addEventListener('mouseenter', function() {
                    this.querySelector('i').style.transform = 'translateX(-3px)';
                });

                backBtn.addEventListener('mouseleave', function() {
                    this.querySelector('i').style.transform = 'translateX(0)';
                });
            }

            // Progress bar animation
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                setTimeout(() => {
                    progressBar.style.transition = 'width 1s ease-in-out';
                }, 500);
            }
        });
    </script>
@endsection
