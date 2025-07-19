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
            'value' => 'D',
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
    $domiciles = [
        (object) [
            'value' => 'Garut',
            'label' => 'Garut',
        ],
        (object) [
            'value' => 'Luar Garut',
            'label' => 'Luar Garut',
        ],
    ];
@endphp
@extends('layouts.public')
@section('title', 'Kuesioner')
@section('content')
    <section class="bg-white dark:bg-gray-900">

        <div class="mx-auto flex flex-col space-y-5 max-w-screen-lg px-4 py-8">
            @if ($step == 1)
                <x-form.personal-info :genders="$genders" :educations="$educations" :jobs="$jobs" :total-kuesioner="$totalKuesioner"
                    :villages="$villages" :domiciles="$domiciles" />
            @elseif ($step == 2)
                <x-form.kuesioner :previous="$previous" :step="$step" :question="$question" :total-kuesioner="$totalKuesioner" :next="$next"
                    :kuesioner="$kuesioner" :data="$data" />
            @elseif ($step == 3)
                <x-form.confirmation :kuesioner="$kuesioner" :data="$data" :step="$step" />
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const personalInfoForm = document.getElementById('personal-info-form');
            if (!personalInfoForm) return;

            const sendOtpBtn = document.getElementById('send-otp-btn');
            const emailInput = document.getElementById('survey-email');
            const otpContainer = document.getElementById('otp-container');
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpStatus = document.getElementById('otp-status');
            const submitBtn = document.getElementById('submit-personal-info');
            let isOtpValid = false;

            // Fungsi utama untuk mengirim OTP
            sendOtpBtn.addEventListener('click', async function() {
                if (!emailInput.value) {
                    alert('Silakan masukkan alamat email Anda.');
                    return;
                }
                sendOtpBtn.disabled = true;
                sendOtpBtn.textContent = 'Mengirim...';
                try {
                    const response = await fetch('{{ route('otp.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: emailInput.value
                        })
                    });
                    const result = await response.json();
                    alert(result.message);
                    if (response.ok) {
                        // Paksa elemen untuk terlihat, lalu aktifkan input
                        otpContainer.style.display = 'block'; // Mengganti classList.remove('hidden')
                        otpContainer.classList.remove('hidden');
                        otpInputs.forEach(input => input.disabled = false);
                        otpInputs[0].focus();
                    }
                } catch (error) {
                    console.error('Gagal mengirim permintaan OTP:', error);
                    alert('Terjadi kesalahan. Silakan periksa konsol browser.');
                } finally {
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.textContent = 'Kirim OTP';
                }
            });

            // --- Logika untuk input OTP (termasuk paste) ---
            otpInputs.forEach((input, index) => {
                // Event listener untuk input manual
                input.addEventListener('input', () => {
                    if (input.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    if (Array.from(otpInputs).every(i => i.value)) {
                        verifyOtp();
                    }
                });

                // Event listener untuk navigasi backspace
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // Event listener khusus untuk PASTE di input pertama
            otpInputs[0].addEventListener('paste', (event) => {
                event.preventDefault();
                const pasteData = (event.clipboardData || window.clipboardData).getData('text').trim();
                if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                    pasteData.split('').forEach((char, index) => {
                        otpInputs[index].value = char;
                    });
                    otpInputs[5].focus(); // Pindahkan fokus ke input terakhir
                    verifyOtp(); // Langsung verifikasi
                }
            });

            // --- Fungsi verifikasi dan validasi form ---
            async function verifyOtp() {
                // Ubah baris ini untuk memastikan OTP selalu uppercase
                const otp = Array.from(otpInputs).map(i => i.value).join('').toUpperCase();
                if (otp.length !== 6) return;

                otpStatus.innerHTML =
                    '<span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Memverifikasi...</span>';

                try {
                    const response = await fetch('{{ route('otp.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: emailInput.value,
                            otp: otp
                        })
                    });
                    const result = await response.json();
                    if (result.valid) {
                        otpStatus.innerHTML =
                            '<span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">✔ Valid</span>';
                        isOtpValid = true;

                        // KUNCI: Nonaktifkan input OTP setelah berhasil diverifikasi
                        otpInputs.forEach(input => input.disabled = true);

                    } else {
                        otpStatus.innerHTML =
                            '<span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">✖ Tidak Valid</span>';
                        isOtpValid = false;
                    }
                    checkFormValidity();
                } catch (error) {
                    otpStatus.innerHTML =
                        '<span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">Error</span>';
                    isOtpValid = false;
                    checkFormValidity();
                }
            }

            function checkFormValidity() {
                const isFormComplete = Array.from(personalInfoForm.elements).every(el => {
                    if (el.type === 'hidden' || el.type === 'submit' || el.tagName === 'BUTTON' || el.name
                        .startsWith('otp')) return true;
                    return el.value.trim() !== '';
                });
                submitBtn.disabled = !(isFormComplete && isOtpValid);
            }
            personalInfoForm.addEventListener('input', checkFormValidity);
        });
    </script>
@endpush
