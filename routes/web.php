<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RespondenController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/kuesioner', [IndexController::class, 'kuesioner'])->name('kuesioner');
Route::post('/result/store', [IndexController::class, 'store'])->name('result.store');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login'); 
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

Route::middleware('auth')->group(function () {
    Route::prefix('dasbor')->group(function () {
        Route::get('/', [DasborController::class, 'index'])->name('dasbor');
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::post('/change-password', [AuthController::class, 'change_password'])->name('change-password');
        Route::get('/responden', [RespondenController::class, 'index'])->name('responden.index');
        Route::get('/responden/{responden:uuid}', [RespondenController::class, 'show'])->name('responden.show');
        Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
        Route::get('/kuesioner/create', [KuesionerController::class, 'create'])->name('kuesioner.create');
        Route::post('/kuesioner', [KuesionerController::class, 'store'])->name('kuesioner.store');
        Route::get('/kuesioner/{kuesioner:uuid}/edit', [KuesionerController::class, 'edit'])->name('kuesioner.edit');
        Route::patch('/kuesioner/{kuesioner:uuid}', [KuesionerController::class, 'update'])->name('kuesioner.update');
        Route::delete('/kuesioner/{kuesioner:uuid}', [KuesionerController::class, 'destroy'])->name('kuesioner.destroy');
        Route::post('/kuesioner/checks', [KuesionerController::class, 'checks'])->name('kuesioner.checks');
        Route::get('/ikm', [DasborController::class, 'ikm'])->name('ikm.index');
        Route::get('/ikm/export', [DasborController::class, 'ikm_export'])->name('ikm.export');
        Route::get('/ikm/preview', [DasborController::class, 'ikm_preview'])->name('ikm.preview');
        Route::get('/ikm/export/table', [DasborController::class, 'ikm_export_table'])->name('ikm.export.table');
        Route::get('/ikm/preview/table', [DasborController::class, 'ikm_preview_table'])->name('ikm.preview.table');
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/laporan/responden/export', [ExportController::class, 'responden_export'])->name('responden.export');
        Route::get('/laporan/responden/preview', [ExportController::class, 'responden_preview'])->name('responden.preview');
        Route::get('/laporan/responden/export/table', [ExportController::class, 'responden_export_table'])->name('responden.export.table');
        Route::get('/laporan/responden/preview/table', [ExportController::class, 'responden_preview_table'])->name('responden.preview.table');
        Route::get('/laporan/feedback/export/table', [ExportController::class, 'feedback_export_table'])->name('feedback.export.table');
        Route::get('/laporan/feedback/preview/table', [ExportController::class, 'feedback_preview_table'])->name('feedback.preview.table');

        // Rute yang hanya bisa diakses oleh Admin Utama
        Route::middleware('role:admin')->group(function () {
            Route::get('/village', [DasborController::class, 'village'])->name('village.index');
            Route::post('/village', [DasborController::class, 'village_add'])->name('village.add');
            Route::patch('/village/{uuid}', [DasborController::class, 'village_update'])->name('village.update');
            Route::delete('/village/{uuid}', [DasborController::class, 'village_destroy'])->name('village.destroy');
            Route::resource('admin-satker', \App\Http\Controllers\AdminSatkerController::class);
        });
    });
});
