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
    return Kuesioner::where('village_id', $village_id)
        ->active() // Filter hanya yang aktif
        ->get();
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
    $relevantUnsurIds = Kuesioner::where('village_id', $village_id)
        ->active() // TAMBAHKAN BARIS INI
        ->select('unsur_id')
        ->distinct()
        ->pluck('unsur_id');

    return Unsur::whereIn('id', $relevantUnsurIds)
        ->orderBy('unsur')
        ->get();
});
