@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Dasbor' => '#',
    ],
])
@section('title', 'Dasbor')
@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card.info title="Total Responden" :value="$total->responden" />
        <x-card.info title="Total Kuesioner" :value="$total->kuesioner" />
        <x-card.info title="Total Jawaban" :value="$total->jawaban" />
        <x-card.info title="Total Kritik & Saran" :value="$total->kritik" />
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-card>
                <h3 class="p-3 font-bold">Statistik Jawaban Harian (7 Hari Terakhir)</h3>
                <x-chart.line :answers="$answers" />
            </x-card>
        </div>
        <div>
            <x-card>
                <h3 class="p-3 font-bold">Statistik Jawaban Keseluruhan</h3>
                <x-chart.donut :answers="$answers" />
            </x-card>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Jenis Kelamin</h3>
            <x-chart.pie :data="$dataGrafikJenisKelamin" />
        </x-card>
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Umur</h3>
            <x-chart.pie :data="$dataGrafikUmur" />
        </x-card>
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Pendidikan</h3>
            <x-chart.pie :data="$dataGrafikPendidikan" />
        </x-card>
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Pekerjaan</h3>
            <x-chart.pie :data="$dataGrafikPekerjaan" />
        </x-card>
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Satuan Kerja</h3>
            <x-chart.pie :data="$dataGrafikDesa" />
        </x-card>
        <x-card>
            <h3 class="p-3 font-bold">Grafik Berdasarkan Domisili</h3>
            <x-chart.pie :data="$dataGrafikDomisili" />
        </x-card>
    </div>
@endsection
