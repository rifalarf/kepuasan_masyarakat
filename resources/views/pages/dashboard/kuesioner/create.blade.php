@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Kuesioner' => route('kuesioner.index'),
        'Tambah Kuesioner' => '#',
    ],
])
@section('title', 'Tambah Kuesioner')
@section('content')
    <x-card>
        <div class="p-5">
            <form action="{{ route('kuesioner.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="unsur_id" class="mb-2 block text-sm font-medium">Unsur Pelayanan</label>
                    <div class="flex items-center space-x-2">
                        <select name="unsur_id" id="unsur_id"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="" hidden>-- Pilih Unsur --</option>
                            @foreach ($unsurs as $unsur)
                                <option value="{{ $unsur->id }}">{{ $unsur->unsur }}</option>
                            @endforeach
                        </select>
                        <button type="button" data-modal-target="unsur-modal" data-modal-toggle="unsur-modal"
                            class="flex-shrink-0 rounded-lg bg-blue-700 p-2.5 text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M17.402 2.612a1.5 1.5 0 012.12.002l.002.002a1.5 1.5 0 010 2.12l-1.17 1.17-3.24-3.24 1.17-1.17.002-.002.002-.002zm-2.12 2.12l-9.54 9.54a1.5 1.5 0 01-.88.44l-3.5 1a.5.5 0 01-.62-.62l1-3.5a1.5 1.5 0 01.44-.88l9.54-9.54 3.24 3.24z" />
                            </svg>
                            <span class="sr-only">Kelola Unsur</span>
                        </button>
                    </div>
                </div>
                {{-- Tampilkan dropdown Satuan Kerja hanya untuk Admin Utama --}}
                @if (auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="village_id" class="mb-2 block text-sm font-medium">Satuan Kerja (Opsional)</label>
                        <select name="village_id"
                            class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <option value="">-- Kuesioner Global --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}">{{ $village->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Jika dikosongkan, kuesioner akan berlaku untuk semua satuan kerja.</p>
                    </div>
                @endif

                <div class="mb-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal Mulai (Opsional)
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin langsung aktif</p>
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal Berakhir (Opsional)
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu</p>
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kontainer untuk pertanyaan dinamis --}}
                <div id="questions-container" class="space-y-4">
                    <div class="question-item">
                        <label class="mb-2 block text-sm font-medium">Pertanyaan 1</label>
                        <div class="flex items-center space-x-2">
                            <textarea rows="2" name="question[]" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Tulis pertanyaan kuesioner..." required></textarea>
                            {{-- Tombol hapus tidak ditampilkan untuk item pertama --}}
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <button type="button" id="add-question-btn" class="rounded-lg border border-dashed border-gray-400 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-400 dark:hover:bg-gray-700">
                        (+) Tambah Pertanyaan
                    </button>
                    <x-button.submit text="Simpan Semua" />
                </div>
            </form>
        </div>
    </x-card>

    @include('pages.dashboard.kuesioner.partials.unsur-modal')
@endsection
