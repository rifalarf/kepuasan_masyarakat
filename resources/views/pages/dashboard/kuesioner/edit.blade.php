@extends('layouts.dashboard', [
  'breadcrumbs' => [
      'Kuesioner' => route('kuesioner.index'),
      'Edit Kuesioner' => '#'
  ],
])
@section('title', 'Edit Kuesioner')
@section('content')
  <x-card>
    <div class="relative overflow-x-auto sm:rounded-lg p-5">
      <form action="{{ route('kuesioner.update', $kuesioner->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="unsur_id" class="mb-2 block text-sm font-medium">Unsur Pelayanan</label>
                    <div class="flex items-center space-x-2">
                        <select name="unsur_id"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <option value="" hidden>-- Pilih Unsur --</option>
                            @foreach ($unsurs as $unsur)
                                <option value="{{ $unsur->id }}" {{ $kuesioner->unsur_id == $unsur->id ? 'selected' : '' }}>{{ $unsur->unsur }}</option>
                            @endforeach
                        </select>
                        <button type="button" data-modal-target="unsur-modal" data-modal-toggle="unsur-modal" class="flex-shrink-0 rounded-lg bg-blue-700 p-2.5 text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.402 2.612a1.5 1.5 0 012.12.002l.002.002a1.5 1.5 0 010 2.12l-1.17 1.17-3.24-3.24 1.17-1.17.002-.002.002-.002zm-2.12 2.12l-9.54 9.54a1.5 1.5 0 01-.88.44l-3.5 1a.5.5 0 01-.62-.62l1-3.5a1.5 1.5 0 01.44-.88l9.54-9.54 3.24 3.24z" />
                            </svg>
                            <span class="sr-only">Kelola Unsur</span>
                        </button>
                    </div>
                </div>
                {{-- Tampilkan dropdown Satuan Kerja hanya untuk Admin Utama --}}
                @if (auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <select name="village_id"
                            class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            required>
                            <option value="" hidden>-- Pilih Satuan Kerja --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" @if ($kuesioner->village_id == $village->id) selected @endif>
                                    {{ $village->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" id="start_date" 
                            value="{{ old('start_date', $kuesioner->start_date) }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal Berakhir
                        </label>
                        <input type="date" name="end_date" id="end_date" 
                            value="{{ old('end_date', $kuesioner->end_date) }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 w-full rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700">
                    <div class="flex items-center justify-between border-b px-3 py-2 dark:border-gray-600">
                        <div class="flex flex-wrap items-center divide-gray-200 dark:divide-gray-600 sm:divide-x">
                            <div class="flex items-center space-x-1 sm:pr-4">
                                <h3 class="font-bold text-gray-500">Pertanyaan</h3>
                            </div>
                        </div>
                        <button type="button" data-tooltip-target="tooltip-fullscreen"
                            class="p-2 text-gray-500 rounded cursor-pointer sm:ml-auto hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 19 19">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 1h5m0 0v5m0-5-5 5M1.979 6V1H7m0 16.042H1.979V12M18 12v5.042h-5M13 12l5 5M2 1l5 5m0 6-5 5" />
                            </svg>
                            <span class="sr-only">Full screen</span>
                        </button>
                        <div id="tooltip-fullscreen" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Show full screen
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                        <label for="editor" class="sr-only">Submit</label>
                        <textarea id="editor" rows="8"
                            name="question"
                            class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                            placeholder="Tulis pertanyaan kuesioner...">{{ $kuesioner->question }}</textarea>
                    </div>
                </div>
                <x-button.submit text="Simpan" />
            </form>
        </div>
    </x-card>
</div>

@include('pages.dashboard.kuesioner.partials.unsur-modal')

@endsection
