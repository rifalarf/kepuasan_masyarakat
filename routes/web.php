<?php

use App\Http\Controllers\AdminSatkerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RespondenController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UnsurController;
use Illuminate\Support\Facades\Route;

// --- RUTE PUBLIK LAMA (Bisa dihapus atau diabaikan) ---
// Route::get('/', [IndexController::class, 'index'])->name('index');
// Route::get('/kuesioner', [IndexController::class, 'kuesioner'])->name('kuesioner');
// Route::post('/result', [IndexController::class, 'store'])->name('result.store');

// --- RUTE PUBLIK BARU ---
Route::get('/', [IndexController::class, 'index'])->name('index');

// Alur Survei Baru
Route::get('/survei/mulai', [SurveyController::class, 'showStep1'])->name('survey.start');
Route::post('/survei/simpan-data-diri', [SurveyController::class, 'storeStep1'])->name('survey.store.personal_info'); // <-- UBAH NAMA DI SINI
Route::get('/survei/pilih-unit', [SurveyController::class, 'showStep2'])->name('survey.step2');
Route::get('/survei/tampilkan-kuesioner', [SurveyController::class, 'showStep3'])->name('survey.step3');
Route::post('/survei/simpan-jawaban', [SurveyController::class, 'storeSurvey'])->name('survey.store');


// Rute untuk OTP
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// --- Rute Autentikasi ---
// Ubah nama rute di sini agar sesuai dengan pemanggilan di view
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout'); // <-- UBAH NAMA DI SINI

Route::middleware('auth')->group(function () {
    Route::get('/dasbor', [DasborController::class, 'index'])->name('dasbor');
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

    // Tambahkan route resource untuk Unsur
    Route::resource('/dasbor/unsur', UnsurController::class);

    // Rute untuk Impor Kuesioner
    Route::get('/dasbor/kuesioner/import', [KuesionerController::class, 'showImportForm'])->name('kuesioner.import.form');
    Route::post('/dasbor/kuesioner/import', [KuesionerController::class, 'import'])->name('kuesioner.import');
    Route::get('/dasbor/kuesioner/import/template', [KuesionerController::class, 'downloadTemplate'])->name('kuesioner.import.template');

    // Rute yang hanya bisa diakses oleh Admin Utama
    Route::middleware('role:admin')->group(function () {
        Route::get('/village', [DasborController::class, 'village'])->name('village.index');
        Route::post('/village', [DasborController::class, 'village_add'])->name('village.add');
        Route::patch('/village/{uuid}', [DasborController::class, 'village_update'])->name('village.update');
        Route::delete('/village/{uuid}', [DasborController::class, 'village_destroy'])->name('village.destroy');
        Route::resource('admin-satker', AdminSatkerController::class);
    });
});
