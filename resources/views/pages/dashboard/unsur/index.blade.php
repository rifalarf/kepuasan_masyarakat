
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Manajemen Unsur' => '#',
    ],
])
@section('title', 'Manajemen Unsur')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <div class="flex flex-col items-center justify-between space-y-4 pb-4 md:flex-row md:space-y-0">
                <div>
                    <x-button.create text="Tambah Unsur" :href="route('unsur.create')" />
                </div>
            </div>
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Nama Unsur</th>
                        @if (auth()->user()->role === 'admin')
                            <th scope="col" class="px-6 py-3">Kepemilikan</th>
                        @endif
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unsurs as $unsur)
                        <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $loop->iteration + $unsurs->firstItem() - 1 }}</td>
                            <td class="px-6 py-4">{{ $unsur->unsur }}</td>
                            @if (auth()->user()->role === 'admin')
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded px-2 py-1 text-xs font-semibold {{ $unsur->village ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $unsur->village->name ?? 'Global' }}
                                    </span>
                                </td>
                            @endif
                            <td class="flex items-center space-x-3 px-6 py-4">
                                {{-- Admin Satker tidak bisa edit/hapus unsur global --}}
                                @if (is_null($unsur->village_id) && auth()->user()->role === 'satker')
                                    <span class="font-medium text-gray-400 dark:text-gray-500">Edit</span>
                                    <span class="font-medium text-gray-400 dark:text-gray-500">Hapus</span>
                                @else
                                    <a href="{{ route('unsur.edit', $unsur) }}"
                                        class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a>
                                    <form action="{{ route('unsur.destroy', $unsur) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus unsur ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="font-medium text-red-600 hover:underline dark:text-red-500">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 4 : 3 }}" class="py-5 text-center italic text-red-500">Data
                                Unsur Kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-5">
                {{ $unsurs->links() }}
            </div>
        </div>
    </x-card>
@endsection