<?php
use App\Models\Kuesioner;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kuesioner/{village_id}', function ($village_id) {
    return Kuesioner::where('village_id', $village_id)->get();
});

Route::get('/villages-by-type/{satker_type_id}', function ($satker_type_id) {
    return Village::where('satker_type_id', $satker_type_id)
        ->withCount('kuesioners')
        ->having('kuesioners_count', '>', 0)
        ->orderBy('name')
        ->get();
});

// GANTI LOGIKA LAMA DENGAN YANG BARU DAN LEBIH ANDAL INI
Route::get('/unsurs-by-village/{village_id}', function ($village_id) {
    // Langkah 1: Ambil semua ID unsur yang memiliki kuesioner untuk village_id yang dipilih.
    // Ini akan mengambil ID dari unsur global maupun unsur spesifik.
    $relevantUnsurIds = Kuesioner::where('village_id', $village_id)
        ->select('unsur_id')
        ->distinct()
        ->pluck('unsur_id');

    // Langkah 2: Ambil data lengkap dari model Unsur berdasarkan ID yang sudah kita kumpulkan.
    // Ini memastikan kita mendapatkan data yang benar, tidak peduli apakah village_id-nya NULL atau tidak.
    return Unsur::whereIn('id', $relevantUnsurIds)
        ->orderBy('unsur')
        ->get();
});
