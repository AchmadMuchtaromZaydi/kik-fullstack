@extends('layouts.app')
@section('title', 'Data Pendukung')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Data Pendukung Organisasi</h5>
            <a href="{{ route('user.pendukung.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle"></i> Upload Baru
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($dataPendukung->isEmpty())
                <div class="alert alert-info">Belum ada data pendukung diunggah.</div>
            @else
                <div class="row">
                    @foreach ($dataPendukung as $data)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('storage/' . $data->image) }}"
                                     class="card-img-top" alt="Foto {{ $data->tipe }}">
                                <div class="card-body">
                                    <h6 class="card-title text-capitalize">{{ $data->tipe }}</h6>
                                    <p class="text-muted small mb-1">
                                        Status:
                                        @if($data->validasi == 1)
                                            <span class="text-success">Tervalidasi</span>
                                        @else
                                            <span class="text-warning">Menunggu Validasi</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <form action="{{ route('user.pendukung.destroy', $data->id) }}"
                                          method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
