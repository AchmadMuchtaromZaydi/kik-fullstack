{{-- resources/views/admin/jenis-kesenian/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Jenis Kesenian')
@section('page-title', 'Tambah Jenis Kesenian')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tambah Jenis Kesenian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.jenis-kesenian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="nama" class="form-label">
                                    Jenis Kesenian <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                    value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="parent" class="form-label">Sub Kesenian</label>
                                <select class="form-control" id="parent" name="parent">
                                    <option value="">Parent</option>
                                    @foreach ($parentJenisKesenian as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.jenis-kesenian') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
