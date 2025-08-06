@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Indek Kepuasan Masyarakat' => '#',
    ],
])
@section('title', 'Indek Kepuasan Masyarakat')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <div class="mb-5 flex items-center justify-between">
                {{-- Tombol Filter --}}
                <button type="button" data-modal-target="advanced-modal" data-modal-toggle="advanced-modal"
                    class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="mr-2 h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
                    </svg>
                    Filter
                </button>

                {{-- Modal Filter --}}
                <div id="advanced-modal" tabindex="-1" aria-hidden="true"
                    class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
                    <div class="relative max-h-full w-full max-w-md">
                        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                            <button type="button"
                                class="absolute right-2.5 top-3 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white"
                                data-modal-hide="advanced-modal">
                                <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <div class="px-6 py-6 lg:px-8">
                                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Filter IKM</h3>
                                <form action="{{ route('ikm.index') }}" method="GET">
                                    @if (auth()->user()->role === 'admin')
                                        <div class="mb-4">
                                            <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan
                                                Kerja</label>
                                            <select name="village_id" id="village_id"
                                                class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Semua Satuan Kerja</option>
                                                @foreach ($villages as $village)
                                                    <option value="{{ $village->id }}" {{ request('village_id') == $village->id ? 'selected' : '' }}>
                                                        {{ $village->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="mb-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="start_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                Mulai</label>
                                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="end_date" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                Selesai</label>
                                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">Terapkan
                                        Filter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Laporan --}}
                <div class="relative">
                    <button id="dropdownLaporanTabelButton" data-dropdown-toggle="dropdownLaporanTabel"
                        class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        Laporan
                        <svg class="ms-2.5 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <div id="dropdownLaporanTabel"
                        class="z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLaporanTabelButton">
                            <li>
                                <a href="{{ route('ikm.preview.table', request()->all()) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                    Preview
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <table class="w-full border text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-blue-100 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Pertanyaan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah Nilai/Unsur
                        </th>
                        <th scope="col" class="px-6 py-3">
                            NRR/Unsur
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Bobot Nilai Tertimbang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            NRR Tertimbang/Unsur
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($data))
                        <tr>
                            <td colspan="5" class="py-5 text-center italic text-red-500">Data Kosong</td>
                        </tr>
                    @else
                        @foreach ($data as $item)
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $item->question }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ number_format($item->totalNilaiPersepsiPerUnit, 2) }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ number_format($item->NRRPerUnsur, 2) }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ number_format($bobotNilaiTertimbang, 2) }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ number_format($item->NRRTertimbangUnsur, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="border-b bg-gray-50 font-bold">
                            <td scope="row" colspan="4" class="border-r px-6 py-4 text-gray-900 dark:text-white">
                                IKM
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ number_format($IKM, 2) }}
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50 font-bold">
                            <td scope="row" colspan="4" class="border-r px-6 py-4 text-gray-900 dark:text-white">
                                Konversi IKM
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ number_format($konversiIKM, 2) }}
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50 font-bold">
                            <td scope="row" colspan="4" class="border-r px-6 py-4 text-gray-900 dark:text-white">
                                Mutu Pelayanan (Hasil Penilaian)
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ nilaPersepsi($konversiIKM)->mutu }}
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-50 font-bold">
                            <td scope="row" colspan="4" class="border-r px-6 py-4 text-gray-900 dark:text-white">
                                Kinerja Unit Pelayanan
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ nilaPersepsi($konversiIKM)->kinerja }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-card>
@endsection
