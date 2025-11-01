@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard Admin</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Anggota</h5>
                    <p class="card-text display-4">{{ $totalAnggota }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Organisasi</h5>
                    <p class="card-text display-4">{{ $totalOrganisasi }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Kesenian</h5>
                    <p class="card-text display-4">{{ $totalKesenian }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($aktivitasTerbaru as $aktivitas)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $aktivitas['nama'] }}</strong> - {{ $aktivitas['aksi'] }}
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $aktivitas['tanggal'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
