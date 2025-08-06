<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use App\Models\Unsur;
use App\Models\Village; // <-- Tambahkan ini
use Illuminate\Http\Request;

class RespondenController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi untuk optimasi
        $query = Responden::with(['village', 'answers.kuesioner.unsur']);

        // Filter wajib untuk Admin Satker
        if (auth()->user()->role === 'satker') {
            $query->where('village_id', auth()->user()->village_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan unsur pelayanan
        if ($request->filled('unsur_id')) {
            $query->whereHas('answers.kuesioner', function ($q) use ($request) {
                $q->where('unsur_id', $request->unsur_id);
            });
        }

        // Filter berdasarkan Satuan Kerja (hanya untuk admin)
        if (auth()->user()->role === 'admin' && $request->filled('village_id')) {
            $query->where('village_id', $request->village_id);
        }

        $respondens = $query->latest()->paginate(10);
        $unsurs = Unsur::orderBy('unsur')->get();

        // Ambil data villages hanya jika user adalah admin
        $villages = [];
        if (auth()->user()->role === 'admin') {
            $villages = Village::orderBy('name')->get();
        }

        return view('pages.dashboard.responden.index', compact('respondens', 'unsurs', 'villages'));
    }

    public function show(Responden $responden)
    {
        // Eager load relasi untuk halaman detail
        $responden->load(['village', 'answers.kuesioner.unsur']);
        return view('pages.dashboard.responden.show', compact('responden'));
    }
}
