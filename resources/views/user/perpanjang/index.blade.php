@extends('layouts.app')

@section('title', 'Perpanjangan Kartu Kesenian')

@section('content')
<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- CARD --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">

                    {{-- TITLE --}}
                    <h4 class="fw-bold mb-4 text-center">Check Kartu Anda</h4>

                    {{-- ERROR MESSAGE --}}
                    @if($errors->has('not_found'))
                        <div class="alert alert-danger text-center">
                            {{ $errors->first('not_found') }}
                        </div>
                    @endif

                    {{-- FORM CEK PERPANJANG --}}
                    <form action="{{ route('user.perpanjang.check') }}" method="POST">
                        @csrf

                        {{-- Nomor Kartu --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Masukkan Nomor Kartu Induk <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="nomor_kartu"
                                   class="form-control form-control-lg"
                                   placeholder="Nomor Kartu Induk Lama"
                                   value="{{ old('nomor_kartu') }}"
                                   required>
                        </div>

                        {{-- Nama Ketua --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nama Ketua <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="nama_ketua"
                                   class="form-control form-control-lg"
                                   placeholder="Nama Ketua"
                                   value="{{ old('nama_ketua') }}"
                                   required>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary px-4">
                                CANCEL
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                CARI
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            {{-- END CARD --}}

        </div>
    </div>

</div>
@endsection
