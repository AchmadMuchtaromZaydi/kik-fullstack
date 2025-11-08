{{-- resources/views/admin/verifikasi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Verifikasi - ' . $organisasi->nama)
@section('page-title', 'Verifikasi Permohonan')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <!-- Progress Steps -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>Progress Verifikasi
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item {{ in_array($tabActive, ['general', 'data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : '' }}">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Perhatian</strong>
                            </li>
                            <li
                                class="list-group-item {{ in_array($tabActive, ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : '' }}">
                                <i class="fas fa-building me-2"></i>
                                <strong>Data Organisasi</strong>
                                @if ($verifikasi = $verifikasiData->where('tipe', 'data_organisasi')->first())
                                    <span
                                        class="badge bg-{{ $verifikasi->status == 'valid' ? 'success' : 'danger' }} float-end">
                                        {{ $verifikasi->status == 'valid' ? '✓' : '✗' }}
                                    </span>
                                @endif
                            </li>
                            <li
                                class="list-group-item {{ in_array($tabActive, ['data_anggota', 'data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : '' }}">
                                <i class="fas fa-users me-2"></i>
                                <strong>Data Anggota</strong>
                                @if ($verifikasi = $verifikasiData->where('tipe', 'data_anggota')->first())
                                    <span
                                        class="badge bg-{{ $verifikasi->status == 'valid' ? 'success' : 'danger' }} float-end">
                                        {{ $verifikasi->status == 'valid' ? '✓' : '✗' }}
                                    </span>
                                @endif
                            </li>
                            <li
                                class="list-group-item {{ in_array($tabActive, ['data_inventaris', 'data_pendukung', 'review']) ? 'active-step' : '' }}">
                                <i class="fas fa-boxes me-2"></i>
                                <strong>Inventaris Barang</strong>
                                @if ($verifikasi = $verifikasiData->where('tipe', 'data_inventaris')->first())
                                    <span
                                        class="badge bg-{{ $verifikasi->status == 'valid' ? 'success' : 'danger' }} float-end">
                                        {{ $verifikasi->status == 'valid' ? '✓' : '✗' }}
                                    </span>
                                @endif
                            </li>
                            <li
                                class="list-group-item {{ in_array($tabActive, ['data_pendukung', 'review']) ? 'active-step' : '' }}">
                                <i class="fas fa-file-alt me-2"></i>
                                <strong>Dokumen Pendukung</strong>
                                @if ($verifikasi = $verifikasiData->where('tipe', 'data_pendukung')->first())
                                    <span
                                        class="badge bg-{{ $verifikasi->status == 'valid' ? 'success' : 'danger' }} float-end">
                                        {{ $verifikasi->status == 'valid' ? '✓' : '✗' }}
                                    </span>
                                @endif
                            </li>
                            <li class="list-group-item {{ $tabActive == 'review' ? 'active-step' : '' }}">
                                <i class="fas fa-clipboard-check me-2"></i>
                                <strong>Review Akhir</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Info Organisasi -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $organisasi->nama }}</p>
                        <p><strong>Ketua:</strong> {{ $organisasi->nama_ketua }}</p>
                        <p><strong>Kecamatan:</strong> {{ $organisasi->nama_kecamatan ?? '-' }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge bg-{{ $organisasi->status == 'Request' ? 'warning' : ($organisasi->status == 'Allow' ? 'success' : 'danger') }}">
                                {{ $organisasi->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Content berdasarkan tab active -->
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

    <style>
        .active-step {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .list-group-item {
            border: none;
            padding: 15px 20px;
        }
    </style>
@endsection
