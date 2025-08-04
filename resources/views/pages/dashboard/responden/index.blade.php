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
			<form id="form-action" method="GET" action="{{ route('responden.index') }}">
					<div id="advanced-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
						<div class="relative max-h-full w-full max-w-md">
							<div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
								<button type="button" class="absolute right-2.5 top-3 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="advanced-modal">
									<svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
										<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
									</svg>
									<span class="sr-only">Close modal</span>
								</button>
								<div class="px-6 py-6 lg:px-8">
									<h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Filter</h3>
									<div class="grid grid-cols-1">
										<a href="{{ route('responden.index', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">Hari ini</a>
										<a href="{{ route('responden.index', ['start_date' => now()->startOfWeek()->format('Y-m-d'),'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">Minggu ini</a>
										<a href="{{ route('responden.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'),'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">Bulan ini</a>
										<a href="{{ route('responden.index', ['start_date' => now()->startOfYear()->format('Y-m-d'),'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" class="mb-2 me-2 rounded-lg border border-blue-700 px-5 py-2.5 text-center text-sm font-medium text-blue-700 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">Tahun ini</a>
									</div>
								</div>
							</div>
						</div>
					</div>
			</form>
		</div>
	<x-card>
			<table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
				<thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
					<tr>
						<th scope="col" class="p-4">
							#
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
						<th scope="col" class="px-6 py-3">
							Satuan Kerja
						<emp>
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
							<td colspan="8" class="py-5 text-center italic text-red-500">Data Kosong</td>
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
								<td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
									{{ $responden->village->village }}
								</td>
								<td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
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
@endsection
