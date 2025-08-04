<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Feedback;
use App\Models\Kuesioner;
use App\Models\Responden;
use App\Models\SatkerType;
use App\Models\Village;
use App\Rules\NoRedundantTypeName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

function getIkmData($respondens, $kuesioners)
{
    $data = [];

    $bobotNilaiTertimbang = 1;
    if (count($kuesioners) > 0) {
        $bobotNilaiTertimbang = 1 / count($kuesioners);
    }

    $nilaiPersepsiPerUnit = [];
    foreach ($respondens as $keyResponden => $responden) {
        foreach ($responden->answers as $keyAnswer => $answer) {
            $nilaiPersepsiPerUnit[$keyResponden][$keyAnswer] = (object) [
                'question' => $answer->kuesioner->question,
                'answer' => $answer->answer
            ];
        }
    }

    $totalAnswer = [];
    foreach ($nilaiPersepsiPerUnit as $key => $array) {
        for ($i = 0; $i < count($array); $i++) {
            if (!isset($totalAnswer[$i])) {
                $totalAnswer[$i] = 0;
            }
            $totalAnswer[$i] += $array[$i]->answer;
        }
    }

    foreach ($totalAnswer as $key => $value) {
        $data[$key] = (object) [
            'question' => $kuesioners[$key]->question,
            'totalNilaiPersepsiPerUnit' => $value
        ];
    }

    foreach ($data as $key => $value) {
        $data[$key] = (object) [
            'question' => $value->question,
            'totalNilaiPersepsiPerUnit' => $value->totalNilaiPersepsiPerUnit,
            'PerUnsur' => $value->totalNilaiPersepsiPerUnit / count($respondens)
        ];
    }

    foreach ($data as $key => $value) {
        $data[$key] = (object) [
            'question' => $value->question,
            'totalNilaiPersepsiPerUnit' => $value->totalNilaiPersepsiPerUnit,
            'NRRPerUnsur' => $value->NRRPerUnsur,
            'NRRTertimbangUnsur' => $value->NRRPerUnsur * $bobotNilaiTertimbang
        ];
    }

    $IKM = 0;
    foreach ($data as $value) {
        $IKM += $value->NRRTertimbangUnsur;
    }

    $konversiIKM = $IKM * 25;

    $ikm = [
        'nilaiIkmTertimbang' => number_format($IKM, 2),
        'ikmUnit' => number_format($konversiIKM, 2),
        'mutu' => nilaPersepsi($konversiIKM)->mutu,
        'kinerja' => nilaPersepsi($konversiIKM)->kinerja,
        'responden' => (object) [
            'jumlah' => $respondens->count(),
            'laki' => $respondens->where('gender', 'Laki-laki')->count(),
            'perempuan' => $respondens->where('gender', 'Perempuan')->count(),
            'sd' => $respondens->where('education', 'SD')->count(),
            'smp' => $respondens->where('education', 'SMP')->count(),
            'sma' => $respondens->where('education', 'SMA')->count(),
            'D3' => $respondens->where('education', 'D3')->count(),
            's1' => $respondens->where('education', 'S1')->count(),
            's2' => $respondens->where('education', 'S2')->count(),
            's3' => $respondens->where('education', 'S3')->count(),
        ]
    ];

    return $ikm;
}

class DasborController extends Controller
{
    public function index(Request $request)
    {
        // --- MULAI FILTER ---
        $query = Responden::query();
        if (auth()->user()->role === 'satker') {
            $query->where('village_id', auth()->user()->village_id);
        }
        $dataRespondens = $query->get();
        // --- AKHIR FILTER ---

        // DEFINISIKAN $dataAnswers BERDASARKAN RESPONDEN YANG SUDAH DIFILTER
        $dataAnswers = Answer::whereIn('responden_id', $dataRespondens->pluck('id'))->get();

        // --- MULAI FILTER KUESIONER ---
        $kuesionerQuery = Kuesioner::query();
        if (auth()->user()->role === 'satker') {
            $kuesionerQuery->where('village_id', auth()->user()->village_id);
        }
        // --- AKHIR FILTER KUESIONER ---

        $total = (object) [
            'responden' => $dataRespondens->count(),
            'kuesioner' => $kuesionerQuery->count(), // Gunakan query yang sudah difilter
            'jawaban' => $dataAnswers->count(),
            'kritik' => Feedback::whereIn('responden_id', $dataRespondens->pluck('id'))->count(),
        ];

        $today = Carbon::now();
        $dateArray = [];

        for ($i = 0; $i < 7; $i++) {
            $dateArray[] = $today->copy()->subDays($i)->format('Y-m-d');
        }
        $dateArray = array_reverse($dateArray);

        $dailyAnswers = [];
        foreach ($dateArray as $key => $date) {
            $dailyAnswers[$date] = [
                (object) [
                    'label' => 0,
                    'total' => Answer::where('answer', 1)
                        ->whereHas('responden', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                            if (auth()->user()->role === 'satker') {
                                $query->where('village_id', auth()->user()->village_id);
                            }
                        })
                        ->count()
                ],
                (object) [
                    'label' => 1,
                    'total' => Answer::where('answer', 2)
                        ->whereHas('responden', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                            if (auth()->user()->role === 'satker') {
                                $query->where('village_id', auth()->user()->village_id);
                            }
                        })
                        ->count()
                ],
                (object) [
                    'label' => 2,
                    'total' => Answer::where('answer', 3)
                        ->whereHas('responden', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                            if (auth()->user()->role === 'satker') {
                                $query->where('village_id', auth()->user()->village_id);
                            }
                        })
                        ->count()
                ],
                (object) [
                    'label' => 3,
                    'total' => Answer::where('answer', 4)
                        ->whereHas('responden', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                            if (auth()->user()->role === 'satker') {
                                $query->where('village_id', auth()->user()->village_id);
                            }
                        })
                        ->count()
                ],
            ];
        }

        $answers = (object) [
            'total' => $total->jawaban, // <-- PERBAIKI INI
            'details' => [
                [
                    'label' => rateLabel(1),
                    'total' => $dataAnswers->where('answer', 1)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 1)->count(), $total->jawaban) // <-- PERBAIKI INI
                ],
                [
                    'label' => rateLabel(2),
                    'total' => $dataAnswers->where('answer', 2)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 2)->count(), $total->jawaban) // <-- PERBAIKI INI
                ],
                [
                    'label' => rateLabel(3),
                    'total' => $dataAnswers->where('answer', 3)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 3)->count(), $total->jawaban) // <-- PERBAIKI INI
                ],
                [
                    'label' => rateLabel(4),
                    'total' => $dataAnswers->where('answer', 4)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 4)->count(), $total->jawaban) // <-- PERBAIKI INI
                ],
            ],
            'daily' => $dailyAnswers
        ];

        $colors = (object) [
            'red' => '#F63326',
            'orange' => '#F07F00',
            'yellow' => '#ECBD00',
            'green' => '#4CD440',
            'blue' => '#5540F5',
            'purple' => '#A736FF',
            'pink' => '#FF6EC7',
            'brown' => '#8B572A',
            'gray' => '#757575',
            'teal' => '#00BFA5',
            'cyan' => '#00BCD4',
            'indigo' => '#3F51B5',
            'lime' => '#CDDC39',
            'amber' => '#FFC107'
        ];

        $dataGrafikJenisKelamin = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('gender', 'Laki-laki')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('gender', 'Perempuan')->count(), $dataRespondens->count()), 2)
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->blue]
        ];

        $dataGrafikUmur = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [0, 19])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [20, 29])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [30, 39])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [40, 49])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [50, 59])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->whereBetween('age', [60, 69])->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('age', '>=', 70)->count(), $dataRespondens->count()), 2)
            ],
            'labels' => ['(0-19)', '(20-29)', '(30-39)', '(40-49)', '(50-59)', '(60-69)', '(>= 70)'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green, $colors->blue, $colors->pink, $colors->brown]
        ];

        // Menghitung persentase responden berdasarkan pendidikan
        $dataGrafikPendidikan = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('education', 'SD')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'SMP')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'SMA')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'D4')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'D3')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'S1')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'S2')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('education', 'S3')->count(), $dataRespondens->count()), 2),
            ],
            'labels' => ['SD', 'SMP', 'SMA', 'D4', 'D3', 'S1', 'S2', 'S3'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green, $colors->blue, $colors->purple, $colors->pink, $colors->brown]
        ];

        // Menghitung persentase responden berdasarkan pekerjaan
        $dataGrafikPekerjaan = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('job', 'Pelajar/Mahasiswa')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'PNS')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'TNI')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'Polisi')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'Swasta')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'Wirausaha')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('job', 'Lainnya')->count(), $dataRespondens->count()), 2),
            ],
            'labels' => ['Pelajar/Mahasiswa', 'PNS', 'TNI', 'Polisi', 'Swasta', 'Wirausaha', 'Lainnya'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green, $colors->blue, $colors->purple, $colors->pink]
        ];

        // Menghitung persentase responden berdasarkan Satuan Kerja
        $villages = Village::all();
        $series = [];
        $labels = [];
        foreach ($villages as $key => $village) {
            $series[$key] = (int) number_format(getPercentage($dataRespondens->where('village_id', $village->id)->count(), $dataRespondens->count()), 2);
            $labels[$key] = $village->name;
        }
        $dataGrafikDesa = (object) [
            'series' => $series,
            'labels' => $labels,
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green, $colors->blue, $colors->purple, $colors->pink, $colors->brown, $colors->gray, $colors->teal, $colors->cyan, $colors->indigo, $colors->lime, $colors->amber]
        ];
        
        $dataGrafikDomisili = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('domicile', 'Garut')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('domicile', 'Luar Garut')->count(), $dataRespondens->count()), 2)
            ],
            'labels' => ['Garut', 'Luar Garut'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->blue]
        ];

        return view('pages.dashboard.index', compact('total', 'answers', 'dataGrafikJenisKelamin', 'dataGrafikUmur', 'dataGrafikPendidikan', 'dataGrafikPekerjaan', 'dataGrafikDesa', 'dataGrafikDomisili'));
    }

    public function ikm(Request $request)
    {
        // --- MULAI FILTER ---
        $query = Responden::query();
        if (auth()->user()->role === 'satker') {
            $query->where('village_id', auth()->user()->village_id);
        }
        // --- AKHIR FILTER ---

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
            $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $respondens = $query->get();

        // --- MULAI FILTER KUESIONER ---
        $kuesionerQuery = Kuesioner::query();
        if (auth()->user()->role === 'satker') {
            $kuesionerQuery->where('village_id', auth()->user()->village_id);
        }
        $kuesioners = $kuesionerQuery->get();
        // --- AKHIR FILTER KUESIONER ---

        $villages = Village::all();

        extract(getIKM($respondens, $kuesioners));

        return view('pages.dashboard.ikm.index', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang', 'villages'));
    }

    public function ikm_export(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuesioner::all();

        $ikm = getIkmData($respondens, $kuesioners);
        $data = getIkm($respondens, $kuesioners);

        if (count($data['data']) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $unsurSurvey = [];
        foreach ($data['data'] as $dataItem) {
            $filteredKuesioner = $kuesioners->where('question', $dataItem->question)->first();
            $unsur = $filteredKuesioner->unsur->unsur;

            if (!isset($unsurSurvey[$unsur])) {
                $unsurSurvey[$unsur] = [
                    'total' => 0,
                    'count' => 0,
                    'average' => 0,
                ];
            }

            $unsurSurvey[$unsur]['total'] += $dataItem->NRRPerUnsur;
            $unsurSurvey[$unsur]['count']++;
            $unsurSurvey[$unsur]['average'] = $unsurSurvey[$unsur]['total'] / $unsurSurvey[$unsur]['count'];
        }

        $pdf = Pdf::loadView('export.ikm', compact('ikm', 'data', 'unsurSurvey'));

        return $pdf->download('Laporan IKM.pdf');
    }

    public function ikm_preview(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuesioner::all();

        $ikm = getIkmData($respondens, $kuesioners);
        $data = getIkm($respondens, $kuesioners);

        if (count($data['data']) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $unsurSurvey = [];
        foreach ($data['data'] as $dataItem) {
            $filteredKuesioner = $kuesioners->where('question', $dataItem->question)->first();
            $unsur = $filteredKuesioner->unsur->unsur;

            if (!isset($unsurSurvey[$unsur])) {
                $unsurSurvey[$unsur] = [
                    'total' => 0,
                    'count' => 0,
                    'average' => 0,
                ];
            }

            $unsurSurvey[$unsur]['total'] += $dataItem->NRRPerUnsur;
            $unsurSurvey[$unsur]['count']++;
            $unsurSurvey[$unsur]['average'] = $unsurSurvey[$unsur]['total'] / $unsurSurvey[$unsur]['count'];
        }

        $pdf = Pdf::loadView('export.ikm', compact('ikm', 'data', 'unsurSurvey'));

        return $pdf->stream();
    }

    public function ikm_export_table(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuesioner::all();
        $villages = Village::all();

        extract(getIKM($respondens, $kuesioners));

        if (count($data) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $pdf = Pdf::loadView('export.ikm-table', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang'));

        return $pdf->download('Laporan IKM.pdf');
    }

    public function ikm_preview_table(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuesioner::all();

        extract(getIKM($respondens, $kuesioners));

        if (count($data) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $pdf = Pdf::loadView('export.ikm-table', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang'));

        return $pdf->stream();
    }

    public function village(Request $request)
    {
        $query = Village::with('satkerType');

        // Proses pencarian
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Proses filter
        if ($request->has('type') && $request->type) {
            $query->where('satker_type_id', $request->type);
        }

        $data = $query->latest()->paginate(10);
        $types = SatkerType::orderBy('name')->get();

        return view('pages.dashboard.village.index', compact('data', 'types'));
    }

    public function village_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'satker_type_id' => 'required|exists:satker_types,id',
            'name' => ['required', 'string', 'max:100', new NoRedundantTypeName],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Village::create($validator->validated());
            return redirect()
                ->route('village.index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
        }
    }

    public function village_update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'satker_type_id' => 'required|exists:satker_types,id',
            'name' => ['required', 'string', 'max:100', new NoRedundantTypeName],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $village = Village::where('uuid', $uuid)->firstOrFail();
            $village->update($validator->validated());
            return redirect()
                ->route('village.index')
                ->with('success', 'Data berhasil diedit!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengedit data!', $th->getMessage()]]);
        }
    }

    public function village_destroy($uuid)
    {
        try {
            $village = Village::where('uuid', $uuid)->first();
            if (!$village) {
                return redirect()->back()->withErrors(['message' => 'Data tidak ditemukan!']);
            }
            // if ($village->allowDelete == 0) {
            //     return redirect()->back()->withErrors(['message' => 'Data ini tidak bisa dihapus!']);
            // }
            Village::destroy($village->id);
            return redirect()->route('village.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => 'Terjadi kesalahan saat menghapus data! ' . $th->getMessage()]);
        }
    }
}
