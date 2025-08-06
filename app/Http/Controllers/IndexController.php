<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Feedback;
use App\Models\Kuesioner;
use App\Models\Responden;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        $datakuesioners = Kuesioner::all();
        $dataAnswers = Answer::all();
        $dataRespondens = Responden::all();
        $dataFeedbacks = Feedback::all();

        $total = (object) [
            'kuesioner' => $datakuesioners->count(),
            'answer' => $dataAnswers->count(),
            'responden' => $dataRespondens->count(),
            'feedback' => $dataFeedbacks->count()
        ];

        $today = Carbon::now();
        $dateArray = [];
        for ($i = 0; $i <= 7; $i++) {
            $dateArray[] = $today->subDays($i)->toDateString();
        }
        $dateArray = array_reverse($dateArray);

        $dailyAnswers = [];
        foreach ($dateArray as $key => $date) {
            $dailyAnswers[$date] = [
                (object) [
                    'label' => 0,
                    'total' => Answer::where('answer', 1)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 1,
                    'total' => Answer::where('answer', 2)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 2,
                    'total' => Answer::where('answer', 3)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 3,
                    'total' => Answer::where('answer', 4)->whereDate('created_at', $date)->count()
                ],
            ];
        }

        $answers = (object) [
            'total' => $total->answer,
            'details' => [
                [
                    'label' => rateLabel(1),
                    'total' => $dataAnswers->where('answer', 1)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 1)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(2),
                    'total' => $dataAnswers->where('answer', 2)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 2)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(3),
                    'total' => $dataAnswers->where('answer', 3)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 3)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(4),
                    'total' => $dataAnswers->where('answer', 4)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 4)->count(), $total->answer)
                ],
            ],
            'daily' => $dailyAnswers
        ];

        return view('pages.public.index', compact('total', 'answers'));
    }

    public function kuesioner(Request $request)
    {
        try {
            $step = $request->get('step', 1);
            $question = $request->get('question');

            $kuesioner = Kuesioner::all();
            $totalKuesioner = count($kuesioner);

            if (count($kuesioner) === 0) {
                return redirect()->route('index')->withErrors(['message' => 'Maaf, kuesioner belum tersedia.']);
            }

            if ($step == 1) {
                $villages = Village::orderBy('name')->get();
                $genders = [
                    (object) ['value' => 'Laki-laki', 'label' => 'Laki-laki'],
                    (object) ['value' => 'Perempuan', 'label' => 'Perempuan'],
                ];
                $educations = [
                    (object) ['value' => 'SD', 'label' => 'Sekolah Dasar (SD)'],
                    (object) ['value' => 'SMP', 'label' => 'Sekolah Menengah Pertama (SMP)'],
                    (object) ['value' => 'SMA', 'label' => 'Sekolah Menengah Atas (SMA)'],
                    (object) ['value' => 'D3', 'label' => 'Diploma Tiga (D3)'],
                    (object) ['value' => 'D4', 'label' => 'Diploma Empat (D4)'],
                    (object) ['value' => 'S1', 'label' => 'Sarjana (S1)'],
                    (object) ['value' => 'S2', 'label' => 'Magister (S2)'],
                    (object) ['value' => 'S3', 'label' => 'Doktor (S3)'],
                ];
                $jobs = [
                    (object) ['value' => 'Pelajar/Mahasiswa', 'label' => 'Pelajar/Mahasiswa'],
                    (object) ['value' => 'PNS', 'label' => 'PNS'],
                    (object) ['value' => 'TNI', 'label' => 'TNI'],
                    (object) ['value' => 'Polisi', 'label' => 'Polisi'],
                    (object) ['value' => 'Swasta', 'label' => 'Swasta'],
                    (object) ['value' => 'Wirausaha', 'label' => 'Wirausaha'],
                    (object) ['value' => 'Lainnya', 'label' => 'Lainnya'],
                ];
                $domiciles = [
                    (object) ['value' => 'Garut', 'label' => 'Garut'],
                    (object) ['value' => 'Luar Garut', 'label' => 'Luar Garut'],
                ];

                return view('pages.public.kuesioner', compact('step', 'totalKuesioner', 'villages', 'genders', 'educations', 'jobs', 'domiciles'));
            }

            if ($step == 2) {
                $data = $request->all();

                $kuesioner = Kuesioner::where('village_id', $data['village'])->get();
                $totalKuesioner = $kuesioner->count();

                if ($totalKuesioner == 0) {
                    return redirect()->route('index')->withErrors(['message' => 'Mohon maaf, kuesioner untuk Satuan Kerja yang Anda pilih belum tersedia.']);
                }

                $question = (int) $request->question;

                $validator = Validator::make($data, [
                    'step' => 'required',
                    'question' => 'required',
                    'gender' => 'required',
                    'age' => 'required|numeric|min:1|max:99',
                    'education' => 'required',
                    'job' => 'required',
                    'village' => 'required',
                    'domicile' => 'required',
                    'name' => 'required',
                    'phone' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors($validator);
                }

                if ($question > $totalKuesioner) {
                    unset($data['question']);
                    return redirect(url('/kuesioner?step=3&' . http_build_query($data)));
                }

                $kuesioner = $kuesioner[$question - 1];

                // Logic for Previous Button
                $prevData = $data;
                $prevData['question'] = $question - 1;
                $previous = $question == 1 ? '#' : url('/kuesioner?' . http_build_query($prevData));

                // Logic for Next Button/Action
                $nextData = $data;
                if ($question < $totalKuesioner) {
                    $nextData['question'] = $question + 1;
                    $next = url('/kuesioner?' . http_build_query($nextData));
                } else {
                    unset($nextData['question']);
                    $nextData['step'] = 3;
                    $next = url('/kuesioner?' . http_build_query($nextData));
                }

                return view('pages.public.kuesioner', compact('kuesioner', 'totalKuesioner', 'step', 'next', 'previous', 'question', 'data'));
            }

            if ($step == 3) {
                $data = $request->data;
                $step = $request->step;

                return view('pages.public.kuesioner', compact('kuesioner', 'data', 'step'));
            }

            return redirect('/kuesioner?step=1');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan!', $th->getMessage()]]);
        }
    }

    public function store(Request $request)
    {
        try {
            $responden = Responden::create([
                'gender' => $request->gender,
                'age' => $request->age,
                'education' => $request->education,
                'job' => $request->job,
                'village_id' => $request->village,
                'domicile' => $request->domicile,
                'email' => $request->email, // Tambahkan baris ini
            ]);

            if($request->feedback) {
                Feedback::create([
                    'responden_id' => (int) $responden->id,
                    'feedback' => $request->feedback
                ]);
            }

            foreach ($request->answers as $answer) {
                $answerData = json_decode($answer, true);
                Answer::create([
                    'kuesioner_id' => (int) $answerData['idKuesioner'],
                    'responden_id' => (int) $responden->id,
                    'answer' => (int) $answerData['kuesionerAnswer']
                ]);
            }

            return redirect()
                ->route('index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan!', $th->getMessage()]]);
        }
    }
}

