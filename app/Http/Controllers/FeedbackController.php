<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        // Memulai query dengan eager loading untuk optimasi
        $query = Feedback::with(['responden.village', 'responden.answers.kuesioner.unsur']);

        // --- FILTER ---
        // Filter berdasarkan peran
        if (auth()->user()->role === 'satker') {
            $query->whereHas('responden', function ($q) {
                $q->where('village_id', auth()->user()->village_id);
            });
        }

        // Filter berdasarkan Satuan Kerja (hanya untuk admin)
        if (auth()->user()->role === 'admin' && $request->filled('village_id')) {
            $query->whereHas('responden', function ($q) use ($request) {
                $q->where('village_id', $request->village_id);
            });
        }

        // Filter berdasarkan Unsur Pelayanan
        if ($request->filled('unsur_id')) {
            $query->whereHas('responden.answers.kuesioner', function ($q) use ($request) {
                $q->where('unsur_id', $request->unsur_id);
            });
        }
        // --- END FILTER ---

        // Ambil data untuk ditampilkan di tabel dengan paginasi
        $data = $query->clone()->latest()->paginate(10);

        // Ambil semua data yang sudah difilter untuk analisis keyword
        $feedbacks = $query->get();
        $keywordsCount = [];
        $ignoredWords = [
            'saya', 'aku', 'kamu', 'dia', 'mereka',
            'ini', 'itu', 'sini', 'situ',
            'adalah', 'sebagai', 'oleh', 'pada', 'di',
            'dan', 'atau', 'tetapi', 'namun', 'sebab', 'karena',
            'yang', 'apa', 'bagaimana', 'dimana', 'kapan', 'siapa',
            'dengan', 'ke', 'dari', 'menuju', 'kepada', 'menuju',
            'ini', 'itu', 'tersebut', 'sangat', 'benar', 'tidak',
            'bisa', 'boleh', 'mungkin', 'harus', 'seharusnya', 'perlu',
            'akan', 'telah', 'sudah', 'sedang', 'masih',
            'yang', 'apa', 'bagaimana', 'dimana', 'kapan', 'siapa',
            'bagus', 'buruk', 'baik', 'jelek', 'tidak', 'ya', 'tidak',
            'mereka', 'kami', 'kita', 'anda', 'diri', 'kalian',
        ];

        foreach ($feedbacks as $feedback) {
            $feedbackText = strtolower($feedback->feedback);

            $words = array_diff(str_word_count($feedbackText, 1), $ignoredWords);

            $wordCount = array_count_values($words);

            foreach ($wordCount as $word => $count) {
                if (!isset($keywordsCount[$word])) {
                    $keywordsCount[$word] = 0;
                }
                $keywordsCount[$word] += $count;
            }
        }

        arsort($keywordsCount);

        $topKeywords = array_slice($keywordsCount, 0, 10);

        if (!request()->has('pg') || request()->pg == 1) {
            $itemsPerPage = 5;
            $currentPage = 1;
            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $topKeywords = array_slice($topKeywords, $startIndex, $itemsPerPage);
        }

        if (request()->has('pg') && request()->pg == 2) {
            $itemsPerPage = 5;
            $currentPage = 2;
            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $topKeywords = array_slice($topKeywords, $startIndex, $itemsPerPage);
        }

        // Data untuk dropdown filter
        $unsurs = Unsur::orderBy('unsur')->get();
        $villages = [];
        if (auth()->user()->role === 'admin') {
            $villages = Village::orderBy('name')->get();
        }

        return view('pages.dashboard.feedback.index', compact('data', 'topKeywords', 'unsurs', 'villages'));
    }
}
