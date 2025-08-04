@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Responden' => route('responden.index'),
        'Detail Responden' => '#',
    ],
])
@section('title', 'Detail Responden')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <dl class="w-full divide-y divide-gray-200 text-gray-900 dark:divide-gray-700 dark:text-white mb-5">
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Jenis Kelamin</dt>
                    <dd class="text-lg font-semibold">{{ $responden->gender }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Umur</dt>
                    <dd class="text-lg font-semibold">{{ $responden->age }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Pendidikan</dt>
                    <dd class="text-lg font-semibold">{{ $responden->education }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Pekerjaan</dt>
                    <dd class="text-lg font-semibold">{{ $responden->job }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Tanggal/Waktu Pengisian Kuesioner</dt>
                    <dd class="text-lg font-semibold">
                        {{ \Carbon\Carbon::parse($responden->created_at)->timezone('Asia/Jakarta')->format('d-m-Y / H:i:s') }}
                        WIB</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Tempat Tinggal</dt>
                    <dd class="text-lg font-semibold">{{ $responden->domicile }}</dd>
                </div>
                <div class="flex flex-col py-3">
                    <dt class="mb-1 text-gray-500 dark:text-gray-400 md:text-lg">Email</dt>
                    <dd class="text-lg font-semibold">{{ $responden->email ?? 'Tidak ada' }}</dd>
                </div>
            </dl>
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pertanyaan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jawaban
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responden->answers as $answer)
                        <tr
                            class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $loop->iteration }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $answer->kuesioner->question }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ rateLabel($answer->answer) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@endsection
