
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Kuesioner' => route('kuesioner.index'),
        'Impor' => '#',
    ],
])
@section('title', 'Impor Kuesioner')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <h2 class="mb-4 text-xl font-semibold">Impor Kuesioner dari Excel</h2>

            <div class="mb-6 rounded-lg border border-blue-300 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-800 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <p class="font-medium">Petunjuk:</p>
                <ul class="ml-4 mt-1.5 list-disc">
                    <li>Unduh template yang disediakan untuk memastikan format file benar.</li>
                    <li>Isi kolom `unsur` dan `pertanyaan`. Kolom ini wajib diisi.</li>
                    <li>Jika Anda adalah Admin Utama, Anda dapat mengisi kolom `satuan_kerja` untuk menetapkan kuesioner ke satuan kerja tertentu. Jika dibiarkan kosong, kuesioner akan menjadi global.</li>
                    <li>Jika Anda adalah Admin Satker, kolom `satuan_kerja` akan diabaikan dan kuesioner akan otomatis ditetapkan ke satuan kerja Anda.</li>
                </ul>
            </div>

            <form action="{{ route('kuesioner.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Unggah File Excel</label>
                    <input type="file" name="file" id="file" class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400" required>
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Impor Sekarang
                    </button>
                    <a href="{{ route('kuesioner.import.template') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700">
                        Unduh Template
                    </a>
                </div>
            </form>
        </div>
    </x-card>
@endsection