<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Feedback;
use App\Models\Kuesioner;
use App\Models\Responden;
use App\Models\SatkerType;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Langkah 1: Menampilkan halaman formulir data diri.
     */
    public function showStep1()
    {
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

        return view('pages.public.survey-start', compact('genders', 'educations', 'jobs', 'domiciles'));
    }

    /**
     * Memproses data diri dari Langkah 1, simpan ke session, dan arahkan ke Langkah 2.
     */
    public function storeStep1(Request $request)
    {
        // Hapus validasi untuk 'name' dan 'phone'
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'gender' => 'required|string',
            'age' => 'required|integer|min:1|max:99', // <-- UBAH ATURAN VALIDASI DI SINI
            'education' => 'required|string',
            'job' => 'required|string',
            'domicile' => 'required|string',
            'otp' => 'required|array|min:6|max:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $otp = implode('', $request->otp);
        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            return redirect()->back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.'])->withInput();
        }
        Cache::forget('otp_' . $request->email);

        session(['survey_personal_data' => $validator->validated()]);
        return redirect()->route('survey.step2');
    }

    /**
     * Langkah 2: Menampilkan halaman pemilihan unit layanan.
     */
    public function showStep2()
    {
        // Pastikan data diri sudah diisi
        if (!session()->has('survey_personal_data')) {
            return redirect()->route('survey.start')->withErrors(['message' => 'Silakan isi data diri terlebih dahulu.']);
        }

        // Langsung ambil semua Satuan Kerja (Village) yang memiliki kuesioner.
        $villages = Village::whereHas('kuesioners')->orderBy('name')->get();

        // Kirim data villages ke view, bukan satkerTypes
        return view('pages.public.survey-step2', compact('villages'));
    }

    /**
     * Langkah 3: Menampilkan halaman kuesioner berdasarkan unit dan unsur yang dipilih.
     */
    public function showStep3(Request $request)
    {
        $request->validate([
            'village_id' => 'required|exists:villages,id',
            'unsur_id' => 'required|exists:unsurs,id',
        ]);

        if (!session()->has('survey_personal_data')) {
            return redirect()->route('survey.start')->withErrors(['message' => 'Sesi Anda telah berakhir. Silakan mulai kembali.']);
        }

        $village = Village::findOrFail($request->village_id);
        
        // Filter kuesioner berdasarkan village_id, unsur_id, DAN status aktif
        $kuesioners = Kuesioner::where('village_id', $village->id)
            ->where('unsur_id', $request->unsur_id)
            ->active() // Gunakan scope active
            ->orderBy('id')
            ->get();

        if ($kuesioners->isEmpty()) {
            return redirect()->route('survey.step2')->withErrors(['message' => 'Maaf, kuesioner untuk unit dan unsur ini belum tersedia atau sudah berakhir.']);
        }

        return view('pages.public.survey-step3', compact('village', 'kuesioners'));
    }

    /**
     * Menyimpan semua data survei (responden, jawaban, feedback).
     */
    public function storeSurvey(Request $request)
    {
        if (!session()->has('survey_personal_data')) {
            return redirect()->route('survey.start')->withErrors(['message' => 'Sesi Anda telah berakhir. Silakan mulai kembali.']);
        }

        // --- PERBAIKAN LOGIKA VALIDASI ---
        // 1. Hitung berapa banyak kuesioner yang seharusnya ada di halaman.
        $expectedKuesionerCount = Kuesioner::where('village_id', $request->village_id)
            ->where('unsur_id', $request->unsur_id)
            ->count();

        // 2. Buat aturan validasi yang benar.
        $validator = Validator::make($request->all(), [
            'village_id' => 'required|exists:villages,id',
            'unsur_id' => 'required|exists:unsurs,id',
            'answers' => ['required', 'array', "size:{$expectedKuesionerCount}"],
            'answers.*.kuesioner_id' => 'required|exists:kuesioners,id',
            'answers.*.answer' => 'required|integer|min:1|max:4',
        ], [
            'answers.size' => 'Peringatan: Harap jawab semua pertanyaan sebelum melanjutkan.',
            'answers.required' => 'Peringatan: Harap jawab semua pertanyaan sebelum melanjutkan.',
            'answers.*.answer.required' => 'Setiap pertanyaan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // --- AKHIR PERBAIKAN ---

        $personalData = session('survey_personal_data');

        $responden = Responden::create([
            'email' => $personalData['email'],
            'gender' => $personalData['gender'],
            'age' => $personalData['age'],
            'education' => $personalData['education'],
            'job' => $personalData['job'],
            'domicile' => $personalData['domicile'],
            'village_id' => $request->village_id,
        ]);

        foreach ($request->answers as $answerData) {
            Answer::create(['responden_id' => $responden->id, 'kuesioner_id' => $answerData['kuesioner_id'], 'answer' => $answerData['answer']]);
        }

        if ($request->filled('feedback')) {
            Feedback::create(['responden_id' => $responden->id, 'feedback' => $request->feedback]);
        }

        session()->forget('survey_personal_data');
        return redirect()->route('index')->with('success', 'Terima kasih! Penilaian Anda telah berhasil dikirim.');
    }
}
