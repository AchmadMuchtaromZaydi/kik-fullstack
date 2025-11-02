{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-primary text-white" onclick="loadStatDetail('total-kesenian')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Kesenian</h5>
                                <h2 class="mb-0">{{ $stats['total_kesenian'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-music fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-success text-white" onclick="loadStatDetail('kesenian-aktif')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Kesenian Aktif</h5>
                                <h2 class="mb-0">{{ $stats['kesenian_aktif'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-warning text-white" onclick="loadStatDetail('kesenian-tidak-aktif')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Kesenian Tidak Aktif</h5>
                                <h2 class="mb-0">{{ $stats['kesenian_tidak_aktif'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-times-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-info text-white" onclick="loadStatDetail('total-users')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Users</h5>
                                <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-success text-white" onclick="loadStatDetail('users-aktif')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">User Aktif</h5>
                                <h2 class="mb-0">{{ $stats['users_aktif'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card bg-danger text-white" onclick="loadStatDetail('users-tidak-aktif')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">User Tidak Aktif</h5>
                                <h2 class="mb-0">{{ $stats['users_tidak_aktif'] }}</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-times fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>Tambah User
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.kesenian.index') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-plus me-2"></i>Tambah Kesenian
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-user-friends me-2"></i>Kelola Anggota
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.laporan') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
