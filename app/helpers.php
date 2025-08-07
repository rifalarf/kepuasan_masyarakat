<?php

if (!function_exists('rateLabel')) {
  function rateLabel($rating)
  {
    switch ($rating) {
      case 1:
        return "Tidak Baik";
        break;

      case 2:
        return "Kurang Baik";
        break;

      case 3:
        return "Baik";
        break;

      case 4:
        return "Sangat Baik";
        break;
    }
  }
}

if (!function_exists('nilaPersepsi')) {
  function nilaPersepsi($konversiIKM)
  {
    if ($konversiIKM >= 25.00 && $konversiIKM <= 43.75) {
      return (object) [
        'mutu' => 'D',
        'kinerja' => "Tidak Baik"
      ];
    } elseif ($konversiIKM >= 43.76 && $konversiIKM <= 62.50) {
      return (object) [
        'mutu' => 'C',
        'kinerja' => "Kurang Baik"
      ];
    } elseif ($konversiIKM >= 62.51 && $konversiIKM <= 81.25) {
      return (object) [
        'mutu' => 'B',
        'kinerja' => "Baik"
      ];
    } elseif ($konversiIKM >= 81.26 && $konversiIKM <= 100.00) {
      return (object) [
        'mutu' => 'A',
        'kinerja' => "Sangat Baik"
      ];
    } else {
      return (object) [
        'mutu' => 'X',
        'kinerja' => "Nilai Invalid"
      ];
    }
  }
}

if (!function_exists('getPercentage')) {
  function getPercentage($number, $total)
  {
    if ($total == 0) {
      return 0;
    }
    return $number * 100 / $total;
  }
}

if (!function_exists('getIKM')) {
  function getIKM($respondens, $kuesioners)
  {
    if ($respondens->isEmpty() || $kuesioners->isEmpty()) {
        return [
            'data' => [],
            'IKM' => 0,
            'konversiIKM' => 0,
            'bobotNilaiTertimbang' => 0,
        ];
    }

    // Eager load relasi untuk efisiensi
    $respondens->load('answers');
    $kuesioners->load('unsur');

    // Kelompokkan jawaban berdasarkan kuesioner_id untuk pencarian cepat
    $allAnswers = $respondens->pluck('answers')->flatten();
    $answersByKuesioner = $allAnswers->groupBy('kuesioner_id');

    $totalKuesioner = $kuesioners->count();
    $bobotNilaiTertimbang = round(1 / $totalKuesioner, 2);

    $data = [];
    foreach ($kuesioners as $kuesioner) {
        $answersForThisKuesioner = $answersByKuesioner->get($kuesioner->id, collect());
        $totalNilaiPerUnsur = $answersForThisKuesioner->sum('answer');
        
        // --- PERBAIKAN UTAMA DI SINI ---
        // Hitung jumlah responden yang benar-benar menjawab pertanyaan ini.
        $jumlahPenjawab = $answersForThisKuesioner->count();

        // Gunakan $jumlahPenjawab sebagai pembagi, bukan $totalResponden.
        $rataRataPerUnsur = $jumlahPenjawab > 0 ? $totalNilaiPerUnsur / $jumlahPenjawab : 0;
        // --- AKHIR PERBAIKAN ---

        $rataRataTertimbang = $rataRataPerUnsur * $bobotNilaiTertimbang;

        $data[] = (object) [
            'question' => $kuesioner->question,
            'unsur' => $kuesioner->unsur->unsur ?? 'N/A',
            'totalNilaiPersepsiPerUnit' => $totalNilaiPerUnsur,
            'NRRPerUnsur' => $rataRataPerUnsur,
            'NRRTertimbangUnsur' => $rataRataTertimbang,
        ];
    }

    $IKM = collect($data)->sum('NRRTertimbangUnsur');
    $konversiIKM = $IKM * 25;

    return [
        'data' => $data,
        'IKM' => $IKM,
        'konversiIKM' => $konversiIKM,
        'bobotNilaiTertimbang' => $bobotNilaiTertimbang,
    ];
  }
}

if (!function_exists('generateColorClass')) {
    function generateColorClass(string $seed)
    {
        $hash = crc32($seed);
        $colors = [
            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
        ];
        $index = abs($hash) % count($colors);
        return $colors[$index];
    }
}
