<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailClass;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\InventarisController;
use App\Http\Controllers\Api\JenisKesenianController;
use App\Http\Controllers\Api\DataPendukungController;
use App\Http\Controllers\Api\ValidasiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KesenianController;

/*
|--------------------------------------------------------------------------~
| Web Routes (Fullstack)
|--------------------------------------------------------------------------
|
| Web routes act as fullstack entry. API remains under /api/v1 but not used.
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/generate-word', [WordController::class, 'generateWordBackup']);

Route::get('/send-test-email', function () {
    $emailData = [
        'subject' => 'Ini adalah contoh subject',
        'recipient' => 'est23.edi@gmail.com',
        'recipient_name' => 'John Doe',
        'message' => 'isi message',
        'code' => "123456"
    ];
    dispatch(new SendEmailJob(new SendMailClass($emailData)));
    return 'Test email sent successfully!';
});

// Public web routes (fullstack)
Route::get('/login', function () {
    if (view()->exists('auth.login')) return view('auth.login');
    return response()->json(['status'=>'info','message'=>'Create resources/views/auth/login.blade.php for web login'], 200);
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public read-only data
Route::get('/wilayah', [WilayahController::class, 'index']);
Route::get('/wilayah-all', [WilayahController::class, 'getWilayahAll']);
Route::post('/get-wilayah-by-nama', [WilayahController::class, 'getWilayahNama']);
Route::get('/jenis-kesenian-all', [JenisKesenianController::class, 'jenisKesenianAll']);
Route::get('/get-documents/{id}', [DataPendukungController::class, 'getDocuments']);

// Utility
Route::get('/generate-word', [WordController::class, 'generateWordBackup']);
Route::get('/send-test-email', function () {
    $emailData = [
        'subject' => 'Ini adalah contoh subject',
        'recipient' => 'est23.edi@gmail.com',
        'recipient_name' => 'John Doe',
        'message' => 'isi message',
        'code' => "123456"
    ];
    dispatch(new SendEmailJob(new SendMailClass($emailData)));
    return 'Test email sent successfully!';
});

// Protected web routes (session auth)
// Jika ingin menggunakan guard berbeda, ganti middleware 'auth' dengan yang sesuai.
Route::middleware(['auth'])->group(function () {

    // User profile / account
    Route::get('/me', [UsersController::class, 'me']);
    Route::post('/profile', [UsersController::class, 'updateProfile']);
    Route::post('/change-password', [UsersController::class, 'changePassword']);

    // Resources (CRUD via web controllers)
    Route::resource('/users', UsersController::class);
    Route::resource('/organisasi', OrganisasiController::class);
    Route::get('/get-organisasi-user/{userid}', [OrganisasiController::class, 'get_organisasi_user']);
    Route::post('/save-organisasi-user', [OrganisasiController::class, 'save_organisasi_user']);
    Route::post('/check-organisasi', [OrganisasiController::class, 'checkOrganisasi']);
    Route::post('/import-data', [OrganisasiController::class, 'importData']);
    Route::post('/get-image', [OrganisasiController::class, 'getImage']);
    Route::delete('/delete-organisasi-user/{id}', [OrganisasiController::class, 'destroy']);

    Route::resource('/anggota', AnggotaController::class);
    Route::post('/anggota-lain', [AnggotaController::class, 'tambahAnggotaOrgLain']);

    Route::resource('/inventaris', InventarisController::class);

    Route::resource('/jenis-kesenian', JenisKesenianController::class);

    // Documents
    Route::post('/upload-document', [DataPendukungController::class, 'uploadDocument']);
    Route::delete('/delete-document/{id}', [DataPendukungController::class, 'deleteDocument']);

    // Verifikasi / validasi
    Route::resource('/verifikasi', ValidasiController::class);
    Route::post('/final-verifikasi', [ValidasiController::class, 'updateStatus']);
    Route::post('/send-notification', [ValidasiController::class, 'sendNotification']);
});

// Import form dan submit handler
Route::get('/kesenian/import', [KesenianController::class, 'showImportForm'])->name('kesenian.import.form');
Route::post('/kesenian/import', [KesenianController::class, 'import'])->name('kesenian.import');

// Resource routes untuk kesenian (mencakup show, edit, destroy, index, store, update dll.)
Route::resource('kesenian', KesenianController::class);

// SPA catch-all: arahkan semua route web ke SPA view jika ada.
// Pastikan ini diletakkan di akhir file routes/web.php
Route::get('/{any}', function () {
    if (view()->exists('app')) {
        return view('app');
    }
    return response()->json([
        'status' => 'error',
        'message' => 'Page not found'
    ], 404);
})->where('any', '.*');
