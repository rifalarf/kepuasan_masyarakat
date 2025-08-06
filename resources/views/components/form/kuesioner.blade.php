{{-- filepath: resources/views/pages/public/survey/kuesioner.blade.php --}}
@extends('layouts.public')

@section('title', 'Formulir Survei')

@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:py-16">
            <x-form.kuesioner :kuesioners="$kuesioners" :village="$village" />
        </div>
    </section>
@endsection

<?php
{{-- filepath: resources/views/components/form/kuesioner.blade.php --}}
@props(['kuesioners', 'village'])

<div class="w-full rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800 md:p-8">
    <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">
        Penilaian: {{ $village->name }}
    </h2>
    <p class="mb-8 text-gray-500 dark:text-gray-400">
        Berikan penilaian Anda pada setiap pertanyaan di bawah ini.
    </p>

    <form action="{{ route('survey.store.answers') }}" method="POST">
        @csrf
        <div class="space-y-8">
            {{-- Loop through each questionnaire question --}}
            @foreach ($kuesioners as $kuesioner)
                <div class="kuesioner-item">
                    <p class="mb-3 font-semibold text-gray-800 dark:text-gray-200">
                        {{ $loop->iteration }}. {{ $kuesioner->question }}
                    </p>

                    {{-- Radio buttons with local SVG images as labels --}}
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        @for ($i = 1; $i <= 4; $i++)
                            <label for="answer-{{ $kuesioner->id }}-{{ $i }}"
                                class="flex cursor-pointer flex-col items-center rounded-lg border-2 border-gray-200 p-4 text-center transition-all hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:ring-2 has-[:checked]:ring-blue-600 dark:border-gray-700 dark:hover:border-blue-400 dark:has-[:checked]:border-blue-500">
                                <input type="radio" id="answer-{{ $kuesioner->id }}-{{ $i }}"
                                    name="answers[{{ $kuesioner->id }}]" value="{{ $i }}" class="peer sr-only"
                                    required>
                                <img src="{{ asset('assets/' . $i . '.svg') }}" alt="{{ rateLabel($i) }}"
                                    class="h-16 w-16 transition-transform duration-200 peer-hover:scale-110">
                                <span class="mt-2 font-medium text-gray-700 dark:text-gray-300">{{ rateLabel($i) }}</span>
                            </label>
                        @endfor
                    </div>
                    @error('answers.' . $kuesioner->id)
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
            <button type="submit"
                class="w-full rounded-lg bg-blue-700 px-8 py-3 text-center font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">
                Lanjutkan
            </button>
        </div>
    </form>
</div>
