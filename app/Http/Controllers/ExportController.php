<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Responden;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

function getRespondenDataExport($request)
{
  $query = Responden::query();

  $query = Responden::query();

  if (!$request->has('start_date') || !$request->has('end_date')) {
    $oldestResponden = Responden::oldest('created_at')->first();
    $newestResponden = Responden::latest('created_at')->first();

    $dates = [
      'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
      'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
    ];

    return redirect()->route('responden.index', array_merge($request->all(), $dates));
  }

  $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
  $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

  $query->whereBetween('created_at', [$startDate, $endDate]);

  if ($request->has('search')) {
    $searchTerm = $request->search;
    $query->where(function ($subquery) use ($searchTerm) {
      $subquery->Where('gender', 'like', "%$searchTerm%")
        ->orWhere('age', 'like', "%$searchTerm%")
        ->orWhere('education', 'like', "%$searchTerm%")
        ->orWhere('job', 'like', "%$searchTerm%")
        ->orWhere('village_id', 'like', "%$searchTerm%")
        ->orWhere('domicile', 'like', "%$searchTerm%");
    });
  }

  if (isset($request->age)) {
    if ($request->age == '0-19') {
      $age = [0, 19];
    } elseif ($request->age == '20-29') {
      $age = [20, 29];
    } elseif ($request->age == '30-39') {
      $age = [30, 39];
    } elseif ($request->age == '40-49') {
      $age = [40, 49];
    } elseif ($request->age == '50-59') {
      $age = [50, 59];
    } elseif ($request->age == '60-69') {
      $age = [60, 69];
    } elseif ($request->age == '>70') {
      $age = [60, 200];
    }
    $query->whereBetween('age', $age);
  }

  if (isset($request->gender)) {
    $query->where('gender', $request->gender);
  }

  if (isset($request->education)) {
    $query->where('education', $request->education);
  }

  if (isset($request->job)) {
    $query->where('job', $request->job);
  }

  if (isset($request->village)) {
    $query->where('village_id', $request->village);
  }
  if (isset($request->domicile)) {
    $query->where('domicile', $request->domicile);
  }

  $respondens = $query->get();

  $chartJKConfig = '{
            "type": "bar",
            "data": {
              "labels": ["Laki-laki", "Perempuan"],
              "datasets": [{
                "label": "Jenis Kelamin",
                "data": [' . $respondens->where('gender', 'Laki-laki')->count() . ', ' . $respondens->where('gender', 'Perempuan')->count() . ']
              }]
            }
          }';

  $chartUmurConfig = '{
            "type": "bar",
            "data": {
              "labels": ["(0-19)", "(20-29)", "(30-39)", "(40-49)", "(50-59)", "(60-69)", "(>= 70)"],
              "datasets": [{
                "label": "Umur",
                "data": [' . $respondens->whereBetween('age', [0, 19])->count() . ', ' . $respondens->whereBetween('age', [20, 29])->count() . ', ' . $respondens->whereBetween('age', [30, 39])->count() . ', ' . $respondens->whereBetween('age', [40, 49])->count() . ', ' . $respondens->whereBetween('age', [50, 59])->count() . ', ' . $respondens->whereBetween('age', [60, 69])->count() . ', ' . $respondens->where('age', '>=', 70)->count() . ']
              }]
            }
          }';

  $chartPendidikanConfig = '{
            "type": "bar",
            "data": {
              "labels": ["SD", "SMP", "SMA", "D4", "D3", "S1", "S2", "S3"],
              "datasets": [{
                "label": "Pendidikan",
                "data": [
                    ' . $respondens->where('education', 'SD')->count() . ',
                    ' . $respondens->where('education', 'SMP')->count() . ',
                    ' . $respondens->where('education', 'SMA')->count() . ',
                    ' . $respondens->where('education', 'D4')->count() . ',
                    ' . $respondens->where('education', 'D3')->count() . ',
                    ' . $respondens->where('education', 'S1')->count() . ',
                    ' . $respondens->where('education', 'S2')->count() . ',
                    ' . $respondens->where('education', 'S3')->count() . ',
                ]
              }]
            }
          }';

  $chartPekerjaanConfig = '{
              "type": "bar",
              "data": {
                "labels": ["Pelajar/Mahasiswa", "PNS", "TNI", "Polisi", "Swasta", "Wirausaha", "Lainnya"],
                "datasets": [{
                  "label": "Pekerjaan",
                  "data": [
                    ' . $respondens->where('job', 'Pelajar/Mahasiswa')->count() . ',
                    ' . $respondens->where('job', 'PNS')->count() . ',
                    ' . $respondens->where('job', 'TNI')->count() . ',
                    ' . $respondens->where('job', 'Polisi')->count() . ',
                    ' . $respondens->where('job', 'Swasta')->count() . ',
                    ' . $respondens->where('job', 'Wirausaha')->count() . ',
                    ' . $respondens->where('job', 'Lainnya')->count() . ',
                  ]
                }]
              }
            }';

  $labels = [];
  $data = [];
  $villages = Village::all();
  foreach ($villages as $key => $village) {
    $labels[$key] = '"' . $village->village . '"';
    $data[$key] = $respondens->where('village_id', $village->id)->count();
  }

  $chartDesaConfig = '{
                "type": "bar",
                "data": {
                  "labels": [' . implode(', ', $labels) . '],
                  "datasets": [{
                    "label": "Desa",
                    "data": [' . implode(', ', $data) . ']
                  }]
                }
              }';
              
  $chartDomisiliConfig = '{
            "type": "bar",
            "data": {
              "labels": ["Garut", "Luar Garut"],
              "datasets": [{
                "label": "Domisili",
                "data": [' . $respondens->where('domicile', 'Garut')->count() . ', ' . $respondens->where('domicile', 'Luar Garut')->count() . ']
              }]
            }
          }';            

  return [
    'chartJKConfig' => $chartJKConfig,
    'chartUmurConfig' => $chartUmurConfig,
    'chartPendidikanConfig' => $chartPendidikanConfig,
    'chartPekerjaanConfig' => $chartPekerjaanConfig,
    'chartDesaConfig' => $chartDesaConfig,
    'chartDomisiliConfig' => $chartDomisiliConfig
  ];
}

class ExportController extends Controller
{
  public function responden_export(Request $request)
  {
    $respondens = Responden::all();
    if ($respondens->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kosong']]);
    }

    extract(getRespondenDataExport($request));

    $Pdf = Pdf::loadView('export.responden', compact('chartJKConfig', 'chartUmurConfig', 'chartPendidikanConfig', 'chartPekerjaanConfig', 'chartDesaConfig', 'chartDomisiliConfig'));

    return $Pdf->download('Laporan Responden.Pdf');
  }

  public function responden_preview(Request $request)
  {
    $respondens = Responden::all();
    if ($respondens->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kosong']]);
    }

    extract(getRespondenDataExport($request));

    $Pdf = Pdf::loadView('export.responden', compact('chartJKConfig', 'chartUmurConfig', 'chartPendidikanConfig', 'chartPekerjaanConfig', 'chartDesaConfig','chartDomisiliConfig'));
    return $Pdf->stream();
  }

  public function responden_export_table(Request $request)
  {
    $query = Responden::query();

    if (!$request->has('start_date') || !$request->has('end_date')) {
      $oldestResponden = Responden::oldest('created_at')->first();
      $newestResponden = Responden::latest('created_at')->first();

      $dates = [
        'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
        'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
      ];

      return redirect()->route('responden.index', array_merge($request->all(), $dates));
    }

    $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
    $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

    $query->whereBetween('created_at', [$startDate, $endDate]);

    if ($request->has('search')) {
      $searchTerm = $request->search;
      $query->where(function ($subquery) use ($searchTerm) {
        $subquery->Where('gender', 'like', "%$searchTerm%")
          ->orWhere('age', 'like', "%$searchTerm%")
          ->orWhere('education', 'like', "%$searchTerm%")
          ->orWhere('job', 'like', "%$searchTerm%")
          ->orWhere('village', 'like', "%$searchTerm%")
          ->orWhere('domicile', 'like', "%$searchTerm%");
      });
    }

    if (isset($request->age)) {
      if ($request->age == '0-19') {
        $age = [0, 19];
      } elseif ($request->age == '20-29') {
        $age = [20, 29];
      } elseif ($request->age == '30-39') {
        $age = [30, 39];
      } elseif ($request->age == '40-49') {
        $age = [40, 49];
      } elseif ($request->age == '50-59') {
        $age = [50, 59];
      } elseif ($request->age == '60-69') {
        $age = [60, 69];
      } elseif ($request->age == '>70') {
        $age = [70, 200];
      }
      $query->whereBetween('age', $age);
    }

    if (isset($request->gender)) {
      $query->where('gender', $request->gender);
    }

    if (isset($request->education)) {
      $query->where('education', $request->education);
    }

    if (isset($request->job)) {
      $query->where('job', $request->job);
    }

    if (isset($request->village)) {
      $query->where('village', $request->village);
    }

    if (isset($request->domicile)) {
      $query->where('domicile', $request->domicile);
    }


    $respondens = $query->latest()->paginate($request->per_page ?? 5);

    if ($respondens->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kosong']]);
    }

    $Pdf = Pdf::loadView('export.responden-table', compact('respondens'));

    return $Pdf->download('Laporan Tabel Responden.Pdf');
  }

  public function responden_preview_table(Request $request)
  {
    $query = Responden::query();

    if (!$request->has('start_date') || !$request->has('end_date')) {
      $oldestResponden = Responden::oldest('created_at')->first();
      $newestResponden = Responden::latest('created_at')->first();

      $dates = [
        'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
        'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
      ];

      return redirect()->route('responden.index', array_merge($request->all(), $dates));
    }

    $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
    $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

    $query->whereBetween('created_at', [$startDate, $endDate]);

    if ($request->has('search')) {
      $searchTerm = $request->search;
      $query->where(function ($subquery) use ($searchTerm) {
        $subquery->Where('gender', 'like', "%$searchTerm%")
          ->orWhere('age', 'like', "%$searchTerm%")
          ->orWhere('education', 'like', "%$searchTerm%")
          ->orWhere('job', 'like', "%$searchTerm%")
          ->orWhere('village', 'like', "%$searchTerm%")
          ->orWhere('domicile', 'like', "%$searchTerm%");
      });
    }

    if (isset($request->age)) {
      if ($request->age == '0-19') {
        $age = [0, 19];
      } elseif ($request->age == '20-29') {
        $age = [20, 29];
      } elseif ($request->age == '30-39') {
        $age = [30, 39];
      } elseif ($request->age == '40-49') {
        $age = [40, 49];
      } elseif ($request->age == '50-59') {
        $age = [50, 59];
      } elseif ($request->age == '60-69') {
        $age = [60, 69];
      } elseif ($request->age == '>70') {
        $age = [70, 200];
      }
      $query->whereBetween('age', $age);
    }

    if (isset($request->gender)) {
      $query->where('gender', $request->gender);
    }

    if (isset($request->education)) {
      $query->where('education', $request->education);
    }

    if (isset($request->job)) {
      $query->where('job', $request->job);
    }

    if (isset($request->village)) {
      $query->where('village', $request->village);
    }

    if (isset($request->domicile)) {
      $query->where('domicile', $request->domicile);
    }
    $respondens = $query->latest()->paginate($request->per_page ?? 5);

    if ($respondens->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kosong']]);
    }

    $Pdf = Pdf::loadView('export.responden-table', compact('respondens'));
    return $Pdf->stream();
  }

  public function feedback_preview_table(Request $request)
  {
    // Menggunakan logika query dan filter yang sama dengan fungsi export
    $query = Feedback::with(['responden.village', 'responden.answers.kuesioner.unsur']);

    if (auth()->user()->role === 'satker') {
        $query->whereHas('responden', function ($q) {
            $q->where('village_id', auth()->user()->village_id);
        });
    }
    if (auth()->user()->role === 'admin' && $request->filled('village_id')) {
        $query->whereHas('responden', function ($q) use ($request) {
            $q->where('village_id', $request->village_id);
        });
    }
    if ($request->filled('unsur_id')) {
        $query->whereHas('responden.answers.kuesioner', function ($q) use ($request) {
            $q->where('unsur_id', $request->unsur_id);
        });
    }

    $data = $query->latest()->get();

    if ($data->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kritik & Saran kosong untuk dipreview.']]);
    }

    // Menambahkan logika analisis kata kunci yang hilang
    $keywordsCount = [];
    $ignoredWords = [
        'saya', 'aku', 'kamu', 'dia', 'mereka', 'ini', 'itu', 'sini', 'situ', 'adalah', 'sebagai', 'oleh', 'pada', 'di',
        'dan', 'atau', 'tetapi', 'namun', 'sebab', 'karena', 'yang', 'apa', 'bagaimana', 'dimana', 'kapan', 'siapa',
        'dengan', 'ke', 'dari', 'menuju', 'kepada', 'menuju', 'sangat', 'tidak', 'bisa', 'agar', 'untuk', 'supaya',
        'lebih', 'kurang', 'baik', 'jelek', 'tolong', 'mohon', 'harap', 'sudah', 'belum', 'selalu', 'sering', 'perlu',
    ];

    foreach ($data as $feedback) {
        $feedbackText = strtolower($feedback->feedback);
        $words = array_diff(str_word_count($feedbackText, 1), $ignoredWords);
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $keywordsCount[$word] = ($keywordsCount[$word] ?? 0) + 1;
            }
        }
    }
    arsort($keywordsCount);
    $topKeywords = array_slice($keywordsCount, 0, 10);

    // Mengirim variabel $topKeywords ke view
    $Pdf = Pdf::loadView('export.feedback-table', compact('data', 'topKeywords'));
    return $Pdf->stream('Laporan Analisis Kritik dan Saran.pdf');
  }

  public function feedback_export_table(Request $request)
  {
    $query = Feedback::with(['responden.village', 'responden.answers.kuesioner.unsur']);

    // Terapkan filter yang sama dari halaman index
    if (auth()->user()->role === 'satker') {
        $query->whereHas('responden', function ($q) {
            $q->where('village_id', auth()->user()->village_id);
        });
    }

    if (auth()->user()->role === 'admin' && $request->filled('village_id')) {
        $query->whereHas('responden', function ($q) use ($request) {
            $q->where('village_id', $request->village_id);
        });
    }

    if ($request->filled('unsur_id')) {
        $query->whereHas('responden.answers.kuesioner', function ($q) use ($request) {
            $q->where('unsur_id', $request->unsur_id);
        });
    }

    $data = $query->latest()->get();

    if ($data->isEmpty()) {
      return redirect()->back()
        ->withErrors(['message' => ['Data Kritik & Saran kosong untuk diekspor.']]);
    }

    // --- ANALISIS KATA KUNCI ---
    $keywordsCount = [];
    $ignoredWords = [
        'saya', 'aku', 'kamu', 'dia', 'mereka', 'ini', 'itu', 'sini', 'situ', 'adalah', 'sebagai', 'oleh', 'pada', 'di',
        'dan', 'atau', 'tetapi', 'namun', 'sebab', 'karena', 'yang', 'apa', 'bagaimana', 'dimana', 'kapan', 'siapa',
        'dengan', 'ke', 'dari', 'menuju', 'kepada', 'menuju', 'sangat', 'tidak', 'bisa', 'agar', 'untuk', 'supaya',
        'lebih', 'kurang', 'baik', 'jelek', 'tolong', 'mohon', 'harap', 'sudah', 'belum', 'selalu', 'sering', 'perlu',
    ];

    foreach ($data as $feedback) {
        $feedbackText = strtolower($feedback->feedback);
        $words = array_diff(str_word_count($feedbackText, 1), $ignoredWords);
        foreach ($words as $word) {
            if (strlen($word) > 3) { // Hanya hitung kata dengan panjang > 3
                $keywordsCount[$word] = ($keywordsCount[$word] ?? 0) + 1;
            }
        }
    }
    arsort($keywordsCount);
    $topKeywords = array_slice($keywordsCount, 0, 10); // Ambil 10 kata kunci teratas
    // --- AKHIR ANALISIS ---

    $Pdf = Pdf::loadView('export.feedback-table', compact('data', 'topKeywords'));
    return $Pdf->download('Laporan Analisis Kritik dan Saran.pdf');
  }
}
