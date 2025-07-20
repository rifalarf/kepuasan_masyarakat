@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Satuan' => '#',
    ],
])
@section('title', 'Satuan')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <div class="flex flex-col items-center justify-between space-y-4 pb-4 md:flex-row md:space-y-0">
                <div>
                    {{-- Tombol Tambah --}}
                    <button data-modal-target="add-village-modal" data-modal-toggle="add-village-modal"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        <svg class="-ml-1 mr-2 h-3.5 w-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Satuan Kerja
                    </button>
                    {{-- Modal Tambah --}}
                    <div id="add-village-modal" tabindex="-1" aria-hidden="true"
                        class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
                        <div class="relative max-h-full w-full max-w-md">
                            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                                <button type="button"
                                    class="absolute right-2.5 top-3 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="add-village-modal">
                                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="px-6 py-6 lg:px-8">
                                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Tambah Satuan Kerja
                                    </h3>
                                    <form action="{{ route('village.add') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="satker_type_id"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Jenis
                                                Satuan Kerja</label>
                                            <select name="satker_type_id" id="satker_type_id"
                                                class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                                required>
                                                <option value="" hidden>- Pilih Jenis -</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="village"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                                                Satuan
                                                Kerja</label>
                                            <input type="text" name="name" id="village"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                placeholder="Contoh: Dinas Pendidikan" required>
                                        </div>
                                        <button type="submit"
                                            class="mt-3 w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Form Pencarian dan Filter --}}
                <form method="GET" action="{{ route('village.index') }}" class="flex items-center space-x-2">
                    <select name="type" onchange="this.form.submit()"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Jenis</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}</option>
                        @endforeach
                    </select>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Cari nama satker...">
                    </div>
                    <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm text-white hover:bg-blue-800">Cari</button>
                </form>
            </div>
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jenis Satuan Kerja
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Satuan Kerja
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr
                            class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $data->firstItem() + $loop->index }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $item->satkerType->name ?? 'N/A' }}
                            </td>
                            <td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ $item->name }}
                            </td>
                            <td scope="row" class="flex px-6 py-4 text-gray-900 dark:text-white space-x-3">
                                <button type="button" data-modal-target="edit-village-modal-{{ $item->uuid }}"
                                    data-modal-toggle="edit-village-modal-{{ $item->uuid }}"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</button>
                                <div id="edit-village-modal-{{ $item->uuid }}" tabindex="-1" aria-hidden="true"
                                    class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
                                    <div class="relative max-h-full w-full max-w-md">
                                        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                                            <button type="button"
                                                class="absolute right-2.5 top-3 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                                                data-modal-hide="edit-village-modal-{{ $item->uuid }}">
                                                <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                            <div class="px-6 py-6 lg:px-8">
                                                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Edit
                                                    Satuan Kerja</h3>
                                                <form action="{{ route('village.update', $item->uuid) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="mb-4">
                                                        <label for="edit_satker_type_id_{{ $item->uuid }}"
                                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Jenis
                                                            Satuan Kerja</label>
                                                        <select name="satker_type_id"
                                                            id="edit_satker_type_id_{{ $item->uuid }}"
                                                            class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                                            required>
                                                            @foreach ($types as $type)
                                                                <option value="{{ $type->id }}"
                                                                    {{ $item->satker_type_id == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="edit_village_{{ $item->uuid }}"
                                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                                                            Satuan
                                                            Kerja</label>
                                                        <input type="text" name="name"
                                                            id="edit_village_{{ $item->uuid }}"
                                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                                            placeholder="Nama Satuan" value="{{ $item->name }}"
                                                            required>
                                                    </div>
                                                    <button type="submit"
                                                        class="mt-3 w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Submit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('village.destroy', $item->uuid) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-600 hover:underline dark:text-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-5">
                {{ $data->appends(request()->query())->links() }}
            </div>
        </div>
    </x-card>
@endsection
