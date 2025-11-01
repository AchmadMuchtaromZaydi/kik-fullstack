@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard Admin</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
                        <p class="card-text">Anda login sebagai <strong>Administrator</strong>.</p>

                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5>Data Kesenian</h5>
                                        <p>Kelola data kesenian</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>Jenis Kesenian</h5>
                                        <p>Kelola jenis kesenian</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5>Data Users</h5>
                                        <p>Kelola pengguna sistem</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5>Laporan</h5>
                                        <p>Lihat laporan sistem</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
