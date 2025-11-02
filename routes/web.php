<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KesenianController;
use App\Http\Controllers\JenisKesenianController;
use App\Http\Controllers\AnggotaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Fullstack Laravel (KIK Project)
|--------------------------------------------------------------------------
*/

// Root Route - Redirect to login
Route::get('/', function () {
    return redirect()->route('auth.login');
});

// Public Routes
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('auth.verify');
Route::get('/resend-code', [AuthController::class, 'showResendForm'])->name('auth.resend.form');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('auth.verify.post');
Route::post('/resend-code', [AuthController::class, 'resendCode'])->name('auth.resend.code');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/stats/{type}', [AdminController::class, 'getStatDetail'])->name('admin.dashboard.stats');
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

        // Users Management
        Route::get('/users', [AuthController::class, 'usersIndex'])->name('admin.users');
        Route::get('/users/create', [AuthController::class, 'usersCreate'])->name('admin.users.create');
        Route::post('/users', [AuthController::class, 'usersStore'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AuthController::class, 'usersEdit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AuthController::class, 'usersUpdate'])->name('admin.users.update');
        Route::delete('/users/{user}', [AuthController::class, 'usersDestroy'])->name('admin.users.destroy');
        Route::post('/users/{user}/status', [AuthController::class, 'usersUpdateStatus'])->name('admin.users.status');
        Route::post('/users/{user}/reset-verification', [AuthController::class, 'usersResetVerification'])->name('admin.users.reset-verification');

        // Kesenian Management
        Route::get('/kesenian', [KesenianController::class, 'index'])->name('admin.kesenian.index');
        Route::get('/kesenian/create', [KesenianController::class, 'create'])->name('admin.kesenian.create');
        Route::post('/kesenian', [KesenianController::class, 'store'])->name('admin.kesenian.store');
        Route::get('/kesenian/{id}', [KesenianController::class, 'show'])->name('admin.kesenian.show');
        Route::get('/kesenian/{id}/edit', [KesenianController::class, 'edit'])->name('admin.kesenian.edit');
        Route::put('/kesenian/{id}', [KesenianController::class, 'update'])->name('admin.kesenian.update');
        Route::delete('/kesenian/{id}', [KesenianController::class, 'destroy'])->name('admin.kesenian.destroy');
        Route::get('/kesenian/import', [KesenianController::class, 'showImportForm'])->name('admin.kesenian.import');
        Route::post('/kesenian/import', [KesenianController::class, 'import'])->name('admin.kesenian.import.post');

        // Jenis Kesenian
        Route::get('/jenis-kesenian', [JenisKesenianController::class, 'index'])->name('admin.jenis-kesenian');
        Route::post('/jenis-kesenian', [JenisKesenianController::class, 'store'])->name('admin.jenis-kesenian.store');
        Route::put('/jenis-kesenian/{id}', [JenisKesenianController::class, 'update'])->name('admin.jenis-kesenian.update');
        Route::delete('/jenis-kesenian/{id}', [JenisKesenianController::class, 'destroy'])->name('admin.jenis-kesenian.destroy');

        // Anggota Management
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
        Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('admin.anggota.create');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('admin.anggota.store');
        Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
        Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');
    });
});

// Test Email Route
Route::get('/test-email', function () {
    // ... kode test email yang sudah ada
});

// Fallback
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
