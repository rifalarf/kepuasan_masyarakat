@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Manajemen Admin Satker' => '#',
    ],
])
@section('title', 'Manajemen Admin Satker')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <div class="flex flex-col items-center justify-between space-y-4 pb-4 md:flex-row md:space-y-0">
                <div>
                    <a href="{{ route('admin-satker.create') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="-ml-1 mr-2 h-3.5 w-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Admin Satker
                    </a>
                </div>
                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('admin-satker.index') }}" class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Cari nama atau email...">
                    </div>
                    <button type="submit"
                        class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm text-white hover:bg-blue-800">Cari
                    </button>
                </form>
            </div>
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Satuan Kerja</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admin_satkers as $user)
                        <tr
                            class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->village->name ?? 'N/A' }}</td>
                            <td class="flex items-center space-x-3 px-6 py-4">
                                <a href="{{ route('admin-satker.edit', $user) }}"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a>
                                <form action="{{ route('admin-satker.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-600 hover:underline dark:text-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center italic text-red-500">Data Admin Satker Kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-5">
                {{ $admin_satkers->appends(request()->query())->links() }}
            </div>
        </div>
    </x-card>
@endsection
