@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard User</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
                        <p class="card-text">Anda login sebagai <strong>User KIK</strong>.</p>

                        <!-- Konten dashboard lainnya -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
