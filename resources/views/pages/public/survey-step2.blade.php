@extends('layouts.public')
@section('title', 'Langkah 2: Pilih Layanan')
@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto grid min-h-screen max-w-screen-xl items-center px-4 py-8">
            <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Pilih Layanan</h2>
                <p class="mb-8 text-gray-500 dark:text-gray-400">
        Pilih satuan kerja dan unsur pelayanan yang ingin Anda nilai.
    </p>

    {{-- PERBAIKAN: Ubah method menjadi GET dan hapus @csrf --}}
    <form action="{{ route('survey.step3') }}" method="GET" class="space-y-6">
        <div>
            <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pilih Satuan Kerja</label>
            {{-- HAPUS CLASS 'searchable-select' DARI SINI --}}
            <select id="village_id" name="village_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" required>
                <option value="" hidden>- Pilih Satuan Kerja -</option>
                @foreach ($villages as $village)
                    <option value="{{ $village->id }}">{{ $village->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="unsur_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Pilih Unsur Pelayanan</label>
            <select id="unsur_id" name="unsur_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500" disabled required>
                <option value="" hidden>- Pilih Unsur Pelayanan -</option>
            </select>
            <p id="unsur-status" class="mt-2 text-sm text-gray-500"></p>
        </div>

        <div class="mt-8 text-right">
            <x-button.submit text="Mulai Isi Kuesioner" id="submit-button" class="hidden" />
        </div>
    </form>
</div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const villageSelectEl = document.getElementById('village_id');
        const unsurSelectEl = document.getElementById('unsur_id');
        const unsurStatus = document.getElementById('unsur-status');
        const submitButton = document.getElementById('submit-button');

        // Pastikan TomSelect sudah diinisialisasi oleh app.js sebelum kita menggunakannya.
        // Kita beri sedikit jeda untuk memastikan semuanya siap.
        setTimeout(() => {
            // Ambil INSTANCE TomSelect yang sudah ada dari elemen.
            const villageTomSelect = villageSelectEl.tomselect;
            if (!villageTomSelect) {
                console.error("TomSelect untuk Satuan Kerja gagal diinisialisasi.");
                return;
            }

            // Buat instance TomSelect untuk Unsur secara manual.
            const unsurTomSelect = new TomSelect(unsurSelectEl, {
                create: false,
                sortField: { field: "text", direction: "asc" },
            });

            function resetUnsurDropdown() {
                unsurTomSelect.disable();
                unsurTomSelect.clear();
                unsurTomSelect.clearOptions();
                unsurTomSelect.addOption({ value: '', text: '- Pilih Unsur Pelayanan -' });
                unsurTomSelect.setValue('');
                unsurStatus.textContent = '';
                submitButton.classList.add('hidden');
            }

            resetUnsurDropdown();

            // Pasang event listener LANGSUNG ke instance TomSelect, bukan elemen <select>.
            villageTomSelect.on('change', async function(villageId) {
                resetUnsurDropdown();

                if (!villageId) return;

                unsurStatus.textContent = 'Memuat unsur pelayanan...';
                try {
                    const response = await fetch(`/api/unsurs-by-village/${villageId}`);
                    const unsurs = await response.json();

                    if (unsurs.length > 0) {
                        unsurs.forEach(unsur => {
                            unsurTomSelect.addOption({ value: unsur.id, text: unsur.unsur });
                        });
                        unsurTomSelect.enable();
                        unsurStatus.textContent = 'Silakan pilih unsur pelayanan.';
                    } else {
                        unsurStatus.textContent = 'Tidak ada unsur pelayanan untuk satuan kerja ini.';
                    }
                } catch (error) {
                    unsurStatus.textContent = 'Gagal memuat data unsur.';
                    console.error('Error fetching unsurs:', error);
                }
            });

            unsurTomSelect.on('change', function(value) {
                if (value) {
                    submitButton.classList.remove('hidden');
                } else {
                    submitButton.classList.add('hidden');
                }
            });

        }, 100); // Jeda 100ms untuk memastikan app.js selesai.
    });
</script>
@endpush