<?php

use App\Jobs\SendEmailJob;
use App\Mail\SendMailClass;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KesenianController;

/*
|--------------------------------------------------------------------------
| Web Routes - Fullstack Laravel (KIK Project)
|--------------------------------------------------------------------------
| Semua route di sini menggunakan Blade views, bukan SPA atau API lagi.
| Struktur disesuaikan dengan halaman yang ada di senicards frontend.
*/

// ------------------------------------------------------
//  PUBLIC PAGES
// ------------------------------------------------------

// Homepage (home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Registrasi
Route::get('/registrasi', [KesenianController::class, 'showRegistrasiForm'])->name('registrasi');
Route::post('/registrasi', [KesenianController::class, 'submitRegistrasi'])->name('registrasi.submit');

// Pembaruan kartu
Route::get('/pembaruan-kartu', [KesenianController::class, 'showPembaruanForm'])->name('pembaruan');
Route::post('/pembaruan-kartu', [KesenianController::class, 'submitPembaruan'])->name('pembaruan.submit');

// Data wilayah (public utility)
Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah');
Route::post('/get-wilayah-by-nama', [WilayahController::class, 'getWilayahNama'])->name('wilayah.nama');

// ------------------------------------------------------
//  AUTHENTICATION
// ------------------------------------------------------

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------------------------
//  ADMIN AREA (Protected by auth middleware)
// ------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
});

// ------------------------------------------------------
//  UTILITY & DEV TOOLS
// ------------------------------------------------------

Route::get('/send-test-email', function () {
    $emailData = [
        'subject' => 'Contoh Email',
        'recipient' => 'est23.edi@gmail.com',
        'recipient_name' => 'John Doe',
        'message' => 'Ini adalah pesan uji kirim email',
        'code' => "123456"
    ];
    dispatch(new SendEmailJob(new SendMailClass($emailData)));
    return 'Email test berhasil dikirim!';
});

// ------------------------------------------------------
//  DEFAULT 404
// ------------------------------------------------------

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
