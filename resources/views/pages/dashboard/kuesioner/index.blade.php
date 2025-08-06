
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Kuesioner' => '#',
    ],
])
@section('title', 'Kuesioner')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            {{-- Form untuk Aksi Massal (Hapus) --}}
            <form action="{{ route('kuesioner.checks') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?');">
                @csrf
                <input type="hidden" name="action" value="delete">

                <div class="mb-5 flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('kuesioner.create') }}" class="flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Tambah Kuesioner
                        </a>
                        <a href="{{ route('kuesioner.import.form') }}" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700">
                            <svg class="mr-2 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.33-2.33 3 3 0 013.75 5.75M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                            Impor dari Excel
                        </a>
                    </div>
                    <div class="flex items-center space-x-2">
                        {{-- Tombol Hapus Massal --}}
                        <button type="submit" id="deleteMany" class="hidden items-center justify-center rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700">
                            <svg class="mr-2 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Hapus yang Dipilih
                        </button>
                    </div>
                </div>

                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-table-all" type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                                    <label for="checkbox-table-all" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Unsur
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Pertanyaan
                            </th>
                            @if (auth()->user()->role === 'admin')
                                <th scope="col" class="px-6 py-3">
                                    Satuan Kerja
                                </th>
                            @endif
                            <th scope="col" class="px-6 py-3">
                                Dibuat pada
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($kuesioner) == 0)
                            <tr>
                                <td colspan="8" class="py-5 text-center italic text-red-500">Data Kosong</td>
                            </tr>
                        @else
                            @foreach ($kuesioner as $item)
                                <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-{{ $item->uuid }}" type="checkbox" name="checks[]" value="{{ $item->uuid }}" onchange="updateButtonVisibility()" class="checkbox-item h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                                            <label for="checkbox-table-{{ $item->uuid }}" class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
                                        {{ $item->unsur->unsur }}
                                    </td>
                                    <td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
                                        {{ $item->question }}
                                    </td>
                                    @if (auth()->user()->role === 'admin')
                                        <td class="px-6 py-4">
                                            {{ $item->village->name ?? 'Global' }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        {{ $item->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('kuesioner.edit', $item->uuid) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="mt-5">
                    {{ $kuesioner->links() }}
                </div>
            </form>
        </div>
    </x-card>

    <script>
        const checkAll = document.getElementById('checkbox-table-all')
        const checkboxes = document.querySelectorAll(".checkbox-item")
        checkAll.addEventListener('change', (e) => {
            checkboxes.forEach(checkbox => checkbox.checked = e.target.checked)
            updateButtonVisibility()
        })

        const updateButtonVisibility = () => {
            const deleteMany = document.getElementById("deleteMany")
            let checked = false;

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    checked = true;
                    break;
                }
            }

            if (checked) {
                deleteMany.classList.add('inline-flex')
                deleteMany.classList.remove('hidden')
            } else {
                deleteMany.classList.add('hidden')
                deleteMany.classList.remove('inline-flex')
            }
        }
    </script>
@endsection
