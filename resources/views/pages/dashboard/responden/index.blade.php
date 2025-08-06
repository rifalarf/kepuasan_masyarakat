@php
	$genders = [
	    (object) [
	        'value' => 'Laki-laki',
	        'label' => 'Laki-laki',
	    ],
	    (object) [
	        'value' => 'Perempuan',
	        'label' => 'Perempuan',
	    ],
	];

	$ages = [
	    (object) [
	        'value' => '0-19',
	        'label' => '0-19',
	    ],
	    (object) [
	        'value' => '20-29',
	        'label' => '20-29',
	    ],
	    (object) [
	        'value' => '30-39',
	        'label' => '30-39',
	    ],
	    (object) [
	        'value' => '40-49',
	        'label' => '40-49',
	    ],
	    (object) [
	        'value' => '50-59',
	        'label' => '50-59',
	    ],
	    (object) [
	        'value' => '60-69',
	        'label' => '60-69',
	    ],
	    (object) [
	        'value' => '>70',
	        'label' => '>70',
	    ],
	];

	$educations = [
	    (object) [
	        'value' => 'SD',
	        'label' => 'Sekolah Dasar (SD)',
	    ],
	    (object) [
	        'value' => 'SMP',
	        'label' => 'Sekolah Menengah Pertama (SMP)',
	    ],
	    (object) [
	        'value' => 'SMA',
	        'label' => 'Sekolah Menengah Atas (SMA)',
	    ],
	    (object) [
	        'value' => 'D4',
	        'label' => 'Diploma Empat (D4)',
	    ],
	    (object) [
	        'value' => 'D3',
	        'label' => 'Diploma Tiga (D3)',
	    ],
	    (object) [
	        'value' => 'S1',
	        'label' => 'Sarjana (S1)',
	    ],
	    (object) [
	        'value' => 'S2',
	        'label' => 'Magister (S2)',
	    ],
	    (object) [
	        'value' => 'S3',
	        'label' => 'Doktor (S3)',
	    ],
	];

	$jobs = [
	    (object) [
	        'value' => 'Pelajar/Mahasiswa',
	        'label' => 'Pelajar/Mahasiswa',
	    ],
	    (object) [
	        'value' => 'PNS',
	        'label' => 'PNS',
	    ],
	    (object) [
	        'value' => 'TNI',
	        'label' => 'TNI',
	    ],
	    (object) [
	        'value' => 'Polisi',
	        'label' => 'Polisi',
	    ],
	    (object) [
	        'value' => 'Swasta',
	        'label' => 'Swasta',
	    ],
	    (object) [
	        'value' => 'Wirausaha',
	        'label' => 'Wirausaha',
	    ],
	    (object) [
	        'value' => 'Lainnya',
	        'label' => 'Lainnya',
	    ],
	];
@endphp
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Responden' => '#',
    ],
])
@section('title', 'Responden')
@section('content')
    <div class="mb-5 flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">
        {{-- Tombol Filter Lanjutan --}}
        <button type="button" data-modal-target="advanced-modal" data-modal-toggle="advanced-modal" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
            </svg>
            Filter
        </button>
        {{-- Modal Filter Lanjutan --}}
        <div id="advanced-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
            <div class="relative max-h-full w-full max-w-md">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                    <button type="button" class="absolute right-2.5 top-3 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="advanced-modal">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Filter</h3>
                        <form action="{{ route('responden.index') }}" method="GET">
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
                        <hr class="my-4">
                        <div class="grid grid-cols-1">
                            <div class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">
                                <a href="{{ route('responden.index', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}">Hari ini</a>
                            </div>
                            <div class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">
                                <a href="{{ route('responden.index', ['start_date' => now()->startOfWeek()->format('Y-m-d'),'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}">Minggu ini</a>
                            </div>
                            <div class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">
                                <a href="{{ route('responden.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'),'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}">Bulan ini</a>
                            </div>
                            <div class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">
                                <a href="{{ route('responden.index', ['start_date' => now()->startOfYear()->format('Y-m-d'),'end_date' => now()->endOfYear()->format('Y-m-d')]) }}">Tahun ini</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-card>
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Jenis Kelamin
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Umur
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Pendidikan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Pekerjaan
                    </th>
                    @if (auth()->user()->role === 'admin')
                        <th scope="col" class="px-6 py-3">
                            Satuan Kerja
                        </th>
                    @endif
                    <th scope="col" class="px-6 py-3">
                        Unsur Pelayanan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tempat Tinggal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (count($respondens) == 0)
                    <tr>
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? '9' : '8' }}" class="py-5 text-center italic text-red-500">Data Kosong</td>
                    </tr>
                    </tr>
                @else
                    @foreach ($respondens as $responden)
                        <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $respondens->firstItem() + $loop->index }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $responden->gender }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $responden->age }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $responden->education }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $responden->job }}
                            </td>
                            @if (auth()->user()->role === 'admin')
                                <td class="px-6 py-4">
                                    {{ $responden->village->name ?? 'N/A' }}
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                {{-- Mengambil unsur dari jawaban pertama --}}
                                {{ $responden->answers->first()?->kuesioner?->unsur?->unsur ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $responden->domicile }}
                            </td>
                            <td class="flex space-x-3 whitespace-nowrap px-6 py-4">
                                <a href="{{ route('responden.show', $responden->uuid) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="mt-5">
            {{ $respondens->onEachSide(1)->appends([
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'unsur_id' => request('unsur_id'),
                    'village_id' => request('village_id'), // <-- Tambahkan ini
                    'gender' => request('gender'),
                    'age' => request('age'),
                    'education' => request('education'),
                    'job' => request('job'),
                    'village' => request('village'),
					'domicile' => request('domicile'),
                    'search' => request('search'),
                    'per_page' => request('per_page'),
                    'filter' => request('filter'),
                    'filter_by' => request('filter_by'),
                ])->links() }}
        </div>
    </x-card>
    <div id="dropdownLaporanTabel" class="z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLaporanTabelButton">
            <li>
                <a href="{{ route('feedback.preview.table', request()->query()) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    Preview
                </a>
            </li>
        </ul>
    </div>
@endsection
