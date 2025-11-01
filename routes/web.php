<?php

use App\Jobs\SendEmailJob;
use App\Mail\SendMailClass;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KesenianController;
use App\Http\Controllers\JenisKesenianController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/*
|--------------------------------------------------------------------------
| Web Routes - Fullstack Laravel (KIK Project)
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------
//  PUBLIC PAGES
// ------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

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

// ------------------------------------------------------
//  PROTECTED ROUTES (Harus login)
// ------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/jenis-kesenian', [JenisKesenianController::class, 'index'])->name('admin.jenis-kesenian');

        // Users Management Routes using AuthController
        Route::get('/users', [AuthController::class, 'usersIndex'])->name('admin.users');
        Route::get('/users/create', [AuthController::class, 'usersCreate'])->name('admin.users.create');
        Route::post('/users', [AuthController::class, 'usersStore'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AuthController::class, 'usersEdit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AuthController::class, 'usersUpdate'])->name('admin.users.update');
        Route::delete('/users/{user}', [AuthController::class, 'usersDestroy'])->name('admin.users.destroy');
        Route::post('/users/{user}/status', [AuthController::class, 'usersUpdateStatus'])->name('admin.users.status');
        Route::post('/users/{user}/reset-verification', [AuthController::class, 'usersResetVerification'])->name('admin.users.reset-verification');
    });

    // Admin Users Management Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/users', [AuthController::class, 'usersIndex'])->name('admin.users');
    Route::get('/users/create', [AuthController::class, 'usersCreate'])->name('admin.users.create');
    Route::post('/users', [AuthController::class, 'usersStore'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AuthController::class, 'usersEdit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AuthController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/users/{user}', [AuthController::class, 'usersDestroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/status', [AuthController::class, 'usersUpdateStatus'])->name('admin.users.status');
    Route::post('/users/{user}/reset-verification', [AuthController::class, 'usersResetVerification'])->name('admin.users.reset-verification');
});

    // User Routes
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

// ------------------------------------------------------
//  UTILITY & DEV TOOLS
// ------------------------------------------------------
Route::get('/test-email', function () {
    try {
        Log::info("Testing email configuration...");

        // Buat DSN dari konfigurasi .env
        $dsn = sprintf(
            'smtp://%s:%s@%s:%s?encryption=%s',
            urlencode(config('mail.mailers.smtp.username')),
            urlencode(config('mail.mailers.smtp.password')),
            config('mail.mailers.smtp.host'),
            config('mail.mailers.smtp.port'),
            config('mail.mailers.smtp.encryption')
        );

        // Buat transport dan mailer Symfony
        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);

        // Data untuk pengujian email
        $emailData = [
            'subject' => 'Test Email - KIK',
            'recipient' => 'est23.edi@gmail.com',
            'recipient_name' => 'Test User',
            'message' => 'Ini adalah email test dari sistem KIK.',
            'code' => "123456"
        ];

        // Kirim email menggunakan mailable
        Mail::to($emailData['recipient'])->send(new SendMailClass($emailData));

        Log::info("✅ Test email sent successfully");
        return '✅ Email test berhasil dikirim!';
    } catch (\Exception $e) {
        Log::error("❌ Email test failed: " . $e->getMessage());
        return '❌ Error: ' . $e->getMessage() .
               '<br>Host: ' . config('mail.mailers.smtp.host') .
               '<br>Port: ' . config('mail.mailers.smtp.port') .
               '<br>Username: ' . config('mail.mailers.smtp.username');
    }
});

// ------------------------------------------------------
//  FALLBACK PAGE
// ------------------------------------------------------
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
