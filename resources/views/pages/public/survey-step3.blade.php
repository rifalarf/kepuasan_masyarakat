{{-- filepath: resources/views/pages/public/survey-step3.blade.php --}}
@extends('layouts.public')
@section('title', 'Langkah 3: Isi Kuesioner')

{{-- Menambahkan script Alpine.js ke dalam layout --}}
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:py-16">
            <div class="w-full rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800 md:p-8">
                <div class="mb-8">
                    <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Penilaian: {{ $village->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">Berikan penilaian Anda pada setiap pertanyaan di bawah ini.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('survey.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="village_id" value="{{ $village->id }}">
                    {{-- TAMBAHKAN BARIS INI untuk membawa unsur_id --}}
                    <input type="hidden" name="unsur_id" value="{{ request('unsur_id') }}">

                    <div class="space-y-8">
                        @foreach ($kuesioners as $index => $kuesioner)
                            {{-- Setiap pertanyaan menjadi komponen Alpine.js yang mandiri --}}
                            <div x-data="{ selectedAnswer: '{{ old('answers.' . $index . '.answer') }}' }"
                                class="kuesioner-item relative rounded-lg border p-4 transition-colors"
                                :class="selectedAnswer ? 'border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30' : 'dark:border-gray-700'">

                                {{-- Ikon centang ini akan muncul jika pertanyaan sudah dijawab --}}
                                <div x-show="selectedAnswer"
                                    class="absolute right-4 top-4 flex h-6 w-6 items-center justify-center rounded-full bg-green-500 text-white"
                                    style="display: none;">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>

                                <p class="mb-3 font-semibold text-gray-800 dark:text-gray-200">{{ $index + 1 }}.
                                    {{ $kuesioner->question }}</p>
                                
                                {{-- Input tersembunyi ini akan menyimpan dan mengirimkan nilai jawaban --}}
                                <input type="hidden" name="answers[{{ $index }}][kuesioner_id]" value="{{ $kuesioner->id }}">
                                <input type="hidden" x-model="selectedAnswer" name="answers[{{ $index }}][answer]" required>

                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    @for ($i = 1; $i <= 4; $i++)
                                        {{-- Opsi jawaban yang dapat diklik dengan umpan balik visual dinamis --}}
                                        <div @click="selectedAnswer = '{{ $i }}'"
                                            class="flex cursor-pointer flex-col items-center rounded-lg border-2 p-4 text-center transition-all hover:border-blue-500"
                                            :class="{
                                                'border-blue-600': selectedAnswer == '{{ $i }}',
                                                'border-gray-200 dark:border-gray-700': selectedAnswer != '{{ $i }}'
                                            }">
                                            <img src="{{ asset('assets/' . $i . '.svg') }}" alt="{{ rateLabel($i) }}"
                                                class="h-16 w-16 transition-transform duration-200">
                                            <span
                                                class="mt-2 font-medium text-gray-700 dark:text-gray-300">{{ rateLabel($i) }}</span>
                                        </div>
                                    @endfor
                                </div>
                                @error('answers.' . $index . '.answer')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Bagian Kritik dan Saran --}}
                    <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <label for="feedback" class="mb-2 block text-lg font-medium text-gray-900 dark:text-white">Kritik
                            dan Saran (Opsional)</label>
                        <textarea id="feedback" name="feedback" rows="4"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                            placeholder="Tuliskan kritik atau saran Anda untuk perbaikan layanan kami..."></textarea>
                    </div>

                    <div class="mt-8 text-right">
                        <x-button.submit text="Kirim Penilaian" />
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection