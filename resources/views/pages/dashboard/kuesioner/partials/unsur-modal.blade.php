<!-- Main Modal -->
<div id="unsur-modal" tabindex="-1" aria-hidden="true"
    class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
    <div class="relative max-h-full w-full max-w-2xl">
        <!-- Modal content -->
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between rounded-t border-b p-4 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Kelola Unsur Pelayanan
                </h3>
                <button type="button"
                    class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="unsur-modal">
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="space-y-6 p-6">
                {{-- Form Tambah Unsur --}}
                <div class="border-b pb-6 dark:border-gray-600">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">Tambah Unsur Baru</h4>
                    <form action="{{ route('unsur.store') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="flex items-start space-x-3">
                            <div class="flex-grow">
                                <label for="unsur-baru" class="sr-only">Nama Unsur</label>
                                <input type="text" name="unsur" id="unsur-baru"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="Tulis nama unsur baru..." required>
                            </div>
                            <x-button.submit text="+ Tambahkan" />
                        </div>
                    </form>
                </div>

                {{-- Daftar Unsur --}}
                <div>
                    <h4 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Daftar Unsur</h4>
                    <div class="max-h-64 overflow-y-auto">
                        <ul class="space-y-2">
                            @forelse ($unsurs as $item)
                                <li class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                    <div>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $item->unsur }}</span>
                                        @if (auth()->user()->role === 'satker' && $item->village_id === null)
                                            <span class="text-red-500 text-xs italic">(dibuat oleh Admin Utama)</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @can('update', $item)
                                            {{-- Tombol Edit --}}
                                            <button type="button" data-modal-target="edit-unsur-modal-{{ $item->id }}"
                                                data-modal-toggle="edit-unsur-modal-{{ $item->id }}"
                                                class="font-medium text-blue-600 hover:underline dark:text-blue-500">
                                                Edit
                                            </button>
                                        @else
                                            <button type="button" onclick="showGlobalUnsurAlert()"
                                                class="font-medium text-blue-600 opacity-50 cursor-not-allowed dark:text-blue-500">
                                                Edit
                                            </button>
                                        @endcan

                                        @can('delete', $item)
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('unsur.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus unsur ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline dark:text-red-500">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                             <button type="button" onclick="showGlobalUnsurAlert()"
                                                class="font-medium text-red-600 opacity-50 cursor-not-allowed dark:text-red-500">
                                                Hapus
                                            </button>
                                        @endcan
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-center italic text-gray-500">
                                    Belum ada unsur yang ditambahkan.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals (Satu untuk setiap unsur) -->
@foreach ($unsurs as $item)
    <div id="edit-unsur-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
        class="fixed left-0 right-0 top-0 z-[60] hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <div class="flex items-start justify-between rounded-t border-b p-4 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Unsur
                    </h3>
                    <button type="button"
                        class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="edit-unsur-modal-{{ $item->id }}">
                        <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('unsur.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="unsur-edit-{{ $item->id }}"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Unsur</label>
                            <input type="text" name="unsur" id="unsur-edit-{{ $item->id }}" value="{{ $item->unsur }}"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <x-button.submit text="Simpan Perubahan" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function showGlobalUnsurAlert() {
        alert('Anda tidak dapat mengedit atau menghapus unsur global.');
    }
</script>