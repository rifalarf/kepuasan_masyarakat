@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Kritik & Saran' => '#',
    ],
])
@section('title', 'Kritik & Saran')
@section('content')
    <div class="mb-5 flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">
        {{-- Tombol Filter --}}
        <button type="button" data-modal-target="filter-modal" data-modal-toggle="filter-modal" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
            </svg>
            Filter
        </button>
        {{-- Modal Filter --}}
        <div id="filter-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
            <div class="relative max-h-full w-full max-w-md">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                    <button type="button" class="absolute right-2.5 top-3 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="filter-modal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Filter Kritik & Saran</h3>
                        <form action="{{ route('feedback.index') }}" method="GET">
                            @if (auth()->user()->role === 'admin')
                                <div class="mb-4">
                                    <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan Kerja</label>
                                    <select name="village_id" id="village_id" class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Semua Satuan Kerja</option>
                                        @foreach ($villages as $village)
                                            <option value="{{ $village->id }}" {{ request('village_id') == $village->id ? 'selected' : '' }}>
                                                {{ $village->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="mb-4">
                                <label for="unsur_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Unsur Pelayanan</label>
                                <select name="unsur_id" id="unsur_id" class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Unsur</option>
                                    @foreach ($unsurs as $unsur)
                                        <option value="{{ $unsur->id }}" {{ request('unsur_id') == $unsur->id ? 'selected' : '' }}>
                                            {{ $unsur->unsur }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">Terapkan Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <div class="flex items-center justify-between pb-4">
                <button id="dropdownLaporanTabelButton" data-dropdown-toggle="dropdownLaporanTabel" class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    Laporan Tabel
                    <svg class="ml-2.5 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <div id="dropdownLaporanTabel" class="z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLaporanTabelButton">
                        <li>
                            <a href="{{ route('feedback.preview.table') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                Preview
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        @if (auth()->user()->role === 'admin')
                            <th scope="col" class="px-6 py-3">
                                Satuan Kerja
                            </th>
                        @endif
                        <th scope="col" class="px-6 py-3">
                            Unsur
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kritik & Saran
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) == 0)
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '4' : '3' }}" class="py-5 text-center italic text-red-500">Data Kosong</td>
                        </tr>
                    @else
                        @foreach ($data as $item)
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $data->firstItem() + $loop->index }}
                                </td>
                                @if (auth()->user()->role === 'admin')
                                    <td class="px-6 py-4">
                                        {{ $item->responden->village->name ?? 'N/A' }}
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    {{ $item->responden->answers->first()?->kuesioner?->unsur?->unsur ?? 'N/A' }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $item->feedback }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="mt-5">
                {{ $data->appends(request()->query())->links() }}
            </div>
        </div>
    </x-card>

    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Keyword
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($topKeywords) == 0)
                        <tr>
                            <td colspan="8" class="py-5 text-center italic text-red-500">Data Kosong</td>
                        </tr>
                    @else
                        @foreach ($topKeywords as $word => $count)
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $loop->iteration }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $word }}
                                </td>
                                <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                    {{ $count }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mx-5 my-5 flex items-center justify-between pb-5">
            <p class="text-sm leading-5 text-gray-700">
                Showing
                <span class="font-medium">1</span>
                to
                <span class="font-medium">5</span>
                of
                <span class="font-medium">10</span>
                results
            </p>
            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    <a href="{{ route('feedback.index', ['pg' => '1']) }}" rel="next" class="relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-500" aria-label="Next &amp;raquo;">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="{{ route('feedback.index', ['pg' => '1']) }}" class="relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-700" aria-label="Go to page 1">
                        1
                    </a>
                    <a href="{{ route('feedback.index', ['pg' => '2']) }}" class="relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-700" aria-label="Go to page 2">
                        2
                    </a>
                    <a href="{{ route('feedback.index', ['pg' => '2']) }}" rel="next" class="relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none focus:ring active:bg-gray-100 active:text-gray-500" aria-label="Next &amp;raquo;">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </span>
            </div>
        </div>
    </x-card>
@endsection
