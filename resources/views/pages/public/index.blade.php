@extends('layouts.public')
@section('title', 'Indeks Kepuasan Masyarakat')
@section('content')
    <div class="container mx-auto px-4 py-8 md:py-16">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            {{-- Kolom Teks & CTA --}}
            <div class="text-center lg:col-span-7 lg:text-left">
                <h1 class="mb-4 max-w-2xl text-4xl font-extrabold leading-none tracking-tight dark:text-white md:text-5xl xl:text-6xl">
                    Survei Kepuasan Masyarakat
                </h1>
                <p class="mb-8 max-w-2xl font-light text-gray-500 dark:text-gray-400 md:text-lg lg:text-xl">
                    Bantu kami meningkatkan kualitas pelayanan publik. Partisipasi Anda sangat berarti untuk mewujudkan pelayanan yang lebih baik.
                </p>
                {{-- Ganti href ke rute 'survey.start' yang baru --}}
                <a href="{{ route('survey.start') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-8 py-4 text-center text-lg font-medium text-white shadow-lg transition-transform duration-200 hover:bg-blue-800 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Mulai Isi Survei
                    <svg class="ml-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            {{-- Kolom Grafik Donut --}}
            <div class="hidden lg:col-span-5 lg:flex">
                <div class="w-full rounded-xl bg-white p-4 shadow-2xl dark:bg-gray-800">
                    <x-chart.donut :answers="$answers" />
                </div>
            </div>
        </div>

        {{-- Bagian Statistik Hasil Survei --}}
        <div class="mt-24">
            <div class="mx-auto max-w-screen-md text-center">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">
                    Hasil Survei Terkini
                </h2>
                <p class="text-gray-500 sm:text-xl dark:text-gray-400">
                    Terima kasih atas setiap masukan yang Anda berikan. Berikut adalah ringkasan hasil survei kepuasan masyarakat secara keseluruhan.
                </p>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-4">
                <div class="rounded-xl border p-6 text-center shadow-lg dark:border-gray-700">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500">
                        <img src="{{ asset('assets/1.svg') }}" alt="Tidak Memuaskan" class="p-3">
                    </div>
                    <h3 class="mb-2 mt-4 text-lg font-semibold uppercase text-red-500">Tidak Memuaskan</h3>
                    <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ $answers->details[0]['total'] }}</p>
                </div>
                <div class="rounded-xl border p-6 text-center shadow-lg dark:border-gray-700">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-orange-500">
                        <img src="{{ asset('assets/2.svg') }}" alt="Kurang Memuaskan" class="p-3">
                    </div>
                    <h3 class="mb-2 mt-4 text-lg font-semibold uppercase text-orange-500">Kurang Memuaskan</h3>
                    <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ $answers->details[1]['total'] }}</p>
                </div>
                <div class="rounded-xl border p-6 text-center shadow-lg dark:border-gray-700">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 text-yellow-500">
                        <img src="{{ asset('assets/3.svg') }}" alt="Cukup Memuaskan" class="p-3">
                    </div>
                    <h3 class="mb-2 mt-4 text-lg font-semibold uppercase text-yellow-500">Cukup Memuaskan</h3>
                    <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ $answers->details[2]['total'] }}</p>
                </div>
                <div class="rounded-xl border p-6 text-center shadow-lg dark:border-gray-700">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-500">
                        <img src="{{ asset('assets/4.svg') }}" alt="Sangat Memuaskan" class="p-3">
                    </div>
                    <h3 class="mb-2 mt-4 text-lg font-semibold uppercase text-green-500">Sangat Memuaskan</h3>
                    <p class="text-5xl font-bold text-gray-900 dark:text-white">{{ $answers->details[3]['total'] }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection