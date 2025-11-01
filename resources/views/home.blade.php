@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <!-- Hero Section -->
        <div class="bg-primary text-white py-5" style="background-color: #1386b0 !important;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold">Kartu Induk Kesenian</h1>
                        <p class="lead">Sistem pendaftaran dan pengelolaan data kesenian terpadu</p>
                        <div class="mt-4">
                            <a href="{{ route('auth.login') }}" class="btn btn-light btn-lg me-3">Login</a>
                            <a href="{{ route('auth.register') }}" class="btn btn-outline-light btn-lg">Daftar</a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="{{ asset('assets/img/logo-white.png') }}" alt="Logo" class="img-fluid"
                            style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
