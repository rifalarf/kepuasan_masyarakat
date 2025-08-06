@extends('layouts.public')
@section('title', 'Langkah 2: Pilih Unit Layanan')
@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto grid min-h-screen max-w-screen-xl items-center px-4 py-8">
            <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Pilih Unit Layanan</h2>
                <p class="mb-6 text-gray-500 dark:text-gray-400">Pilih Satuan Kerja yang ingin Anda berikan penilaian.</p>

                <form action="{{ route('survey.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan Kerja / Unit Layanan</label>
                        <select id="village_id" name="village_id" class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                            <option value="" hidden>- Pilih -</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" @if($village->kuesioners_count == 0) disabled @endif>
                                    {{ $village->name }}
                                    @if($village->kuesioners_count == 0)
                                        (Kuesioner tidak tersedia)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="kuesioner-container" class="hidden">
                        <!-- Pertanyaan akan dimuat di sini oleh JavaScript -->
                    </div>

                    <div id="feedback-container" class="hidden mt-6">
                         <label for="feedback" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kritik dan Saran (Opsional)</label>
                         <textarea id="feedback" name="feedback" rows="4" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Tuliskan kritik atau saran Anda untuk perbaikan layanan kami..."></textarea>
                    </div>

                    <div class="mt-8 text-right">
                        <x-button.submit text="Kirim Penilaian" id="submit-survey-button" class="hidden" />
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const villageSelect = document.getElementById('village_id');
        const kuesionerContainer = document.getElementById('kuesioner-container');
        const feedbackContainer = document.getElementById('feedback-container');
        const submitButton = document.getElementById('submit-survey-button');

        villageSelect.addEventListener('change', async function () {
            const villageId = this.value;
            if (!villageId) {
                kuesionerContainer.innerHTML = '';
                kuesionerContainer.classList.add('hidden');
                feedbackContainer.classList.add('hidden');
                submitButton.classList.add('hidden');
                return;
            }

            kuesionerContainer.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400">Memuat pertanyaan...</p>';
            kuesionerContainer.classList.remove('hidden');

            try {
                const response = await fetch(`/api/kuesioner/${villageId}`);
                const kuesioners = await response.json();

                let html = '<h3 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white">Silakan Beri Penilaian</h3>';
                if (kuesioners.length > 0) {
                    kuesioners.forEach((kuesioner, index) => {
                        html += `
                            <div class="mb-6 rounded-md border p-4 dark:border-gray-600">
                                <p class="mb-3 font-medium text-gray-800 dark:text-gray-200">${index + 1}. ${kuesioner.question}</p>
                                <input type="hidden" name="answers[${index}][kuesioner_id]" value="${kuesioner.id}">
                                <div class="flex justify-around items-center">
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" name="answers[${index}][answer]" value="1" class="mb-1" required>
                                        <span class="text-sm text-red-600">Tidak Baik</span>
                                    </label>
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" name="answers[${index}][answer]" value="2" class="mb-1" required>
                                        <span class="text-sm text-orange-500">Kurang Baik</span>
                                    </label>
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" name="answers[${index}][answer]" value="3" class="mb-1" required>
                                        <span class="text-sm text-yellow-500">Baik</span>
                                    </label>
                                    <label class="flex flex-col items-center cursor-pointer">
                                        <input type="radio" name="answers[${index}][answer]" value="4" class="mb-1" required>
                                        <span class="text-sm text-green-500">Sangat Baik</span>
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    kuesionerContainer.innerHTML = html;
                    feedbackContainer.classList.remove('hidden');
                    submitButton.classList.remove('hidden');
                } else {
                    kuesionerContainer.innerHTML = '<p class="text-center text-red-500 dark:text-red-400">Tidak ada kuesioner yang tersedia untuk unit ini.</p>';
                    feedbackContainer.classList.add('hidden');
                    submitButton.classList.add('hidden');
                }
            } catch (error) {
                kuesionerContainer.innerHTML = '<p class="text-center text-red-500 dark:text-red-400">Gagal memuat pertanyaan.</p>';
            }
        });
    });
</script>
@endpush