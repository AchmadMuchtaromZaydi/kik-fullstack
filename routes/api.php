<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
|
| Versioned API routes. API tetap ada tetapi dipisah ke /api/v1 sehingga
| web (fullstack) tidak terganggu.
|
*/

Route::prefix('v1')->group(function () {

    Route::get('/login', function (Request $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Not Authentication'
        ], 401);
    })->name("login");

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/social/login', [AuthController::class, 'socialLogin']);

    Route::get('wilayah', [App\Http\Controllers\Api\WilayahController::class, "index"]);
    Route::get('wilayah-all', [App\Http\Controllers\Api\WilayahController::class, "getWilayahAll"]);
    Route::post('get-wilayah-by-nama',  [App\Http\Controllers\Api\WilayahController::class, "getWilayahNama"]);

    Route::get("/jenis-kesenian-all", [App\Http\Controllers\Api\JenisKesenianController::class, 'jenisKesenianAll']);
    Route::get("/get-documents/{id}", [App\Http\Controllers\Api\DataPendukungController::class, "getDocuments"]);

    Route::post('/send-whatsapp', [App\Http\Controllers\Api\SendWhatsappController::class, "sendWhatsAppMessage"]);
    Route::post('/upload-document', [App\Http\Controllers\Api\DataPendukungController::class, 'uploadDocument']);
    Route::post('/generate-word', [App\Http\Controllers\WordController::class, 'generateWord']);

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('/me', [App\Http\Controllers\Api\UsersController::class, 'me']);
        Route::post('/profile', [App\Http\Controllers\Api\UsersController::class, 'updateProfile']);
        Route::post('/change-password', [App\Http\Controllers\Api\UsersController::class, 'changePassword']);

        Route::resource('/users', App\Http\Controllers\Api\UsersController::class);

        // organisasi
        Route::get('/get-organisasi-users', [App\Http\Controllers\Api\OrganisasiController::class, 'index']);
        Route::get('/get-organisasi-user/{userid}', [App\Http\Controllers\Api\OrganisasiController::class, 'get_organisasi_user']);
        Route::post('/save-organisasi-user', [App\Http\Controllers\Api\OrganisasiController::class, 'save_organisasi_user']);
        Route::post("/check-organisasi", [App\Http\Controllers\Api\OrganisasiController::class, 'checkOrganisasi']);
        Route::post('/import-data', [App\Http\Controllers\Api\OrganisasiController::class, 'importData']);
        Route::post('/get-image', [App\Http\Controllers\Api\OrganisasiController::class, 'getImage']);
        Route::get('/get-organisasi-detail/{id}', [App\Http\Controllers\Api\OrganisasiController::class, 'show']);
        Route::delete('/delete-organisasi-user/{id}', [App\Http\Controllers\Api\OrganisasiController::class, 'destroy']);

        Route::resource('/anggota', App\Http\Controllers\Api\AnggotaController::class);
        Route::post('/anggota-lain', [App\Http\Controllers\Api\AnggotaController::class, 'tambahAnggotaOrgLain']);
        Route::resource('/inventaris', App\Http\Controllers\Api\InventarisController::class);

        Route::resource('/jenis-kesenian', App\Http\Controllers\Api\JenisKesenianController::class);

        Route::delete("/delete-document/{id}", [App\Http\Controllers\Api\DataPendukungController::class, "deleteDocument"]);

        Route::resource('/verifikasi', App\Http\Controllers\Api\ValidasiController::class);
        Route::post('/final-verifikasi', [App\Http\Controllers\Api\ValidasiController::class, "updateStatus"]);
        Route::post('/send-notification', [App\Http\Controllers\Api\ValidasiController::class, "sendNotification"]);
    });
});

Route::fallback(function(){
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found. Check /api/v1/*'
    ], 404);
});
