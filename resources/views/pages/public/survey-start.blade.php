@extends('layouts.public')

@section('title', 'Mulai Survei - Data Diri')

@section('content')
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto grid min-h-screen max-w-screen-xl items-center px-4 py-8">
            {{-- Komponen form personal-info akan ditampilkan di sini --}}
            @include('components.form.personal-info')
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainForm = document.getElementById('personal-info-form');
        if (!mainForm) return;

        const emailInput = mainForm.querySelector('#email');
        const sendOtpBtn = mainForm.querySelector('#send-otp-btn');
        const otpContainer = mainForm.querySelector('#otp-container');
        const otpInputs = mainForm.querySelectorAll('.otp-input');
        const otpStatus = mainForm.querySelector('#otp-status');
        const submitButton = mainForm.querySelector('#submit-personal-info');

        if (!emailInput || !sendOtpBtn || !otpContainer || !submitButton) {
            console.error('Elemen OTP tidak ditemukan!');
            return;
        }

        // Nonaktifkan tombol submit pada awalnya
        submitButton.disabled = true;
        let isOtpValid = false;

        // Fungsi untuk mengirim OTP
        sendOtpBtn.addEventListener('click', async () => {
            if (!emailInput.value) {
                alert('Silakan masukkan alamat email Anda.');
                return;
            }
            sendOtpBtn.disabled = true;
            sendOtpBtn.textContent = 'Mengirim...';

            try {
                const response = await fetch("{{ route('otp.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });
                const result = await response.json();
                alert(result.message); // Tampilkan pesan dari server (mis: "OTP telah dikirim")
                if (response.ok) {
                    otpContainer.classList.remove('hidden');
                    otpInputs.forEach(input => input.disabled = false);
                    otpInputs[0].focus();
                }
            } catch (error) {
                alert('Terjadi kesalahan. Periksa koneksi Anda dan coba lagi.');
                console.error('Error sending OTP:', error);
            } finally {
                sendOtpBtn.disabled = false;
                sendOtpBtn.textContent = 'Kirim OTP';
            }
        });

        // Fungsi verifikasi OTP
        async function verifyOtp() {
            const otp = Array.from(otpInputs).map(i => i.value).join('').toUpperCase();
            if (otp.length !== 6) return;

            otpStatus.innerHTML = '<span class="text-xs text-blue-500">Memverifikasi...</span>';
            try {
                const response = await fetch("{{ route('otp.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: emailInput.value, otp: otp })
                });
                const result = await response.json();
                if (result.valid) {
                    otpStatus.innerHTML = '<span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">✔ Valid</span>';
                    isOtpValid = true;
                    submitButton.disabled = false; // Aktifkan tombol submit
                    otpInputs.forEach(input => {
                        input.readOnly = true; // <-- UBAH BARIS INI
                        input.classList.add('border-green-500');
                    });
                } else {
                    otpStatus.innerHTML = '<span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">✖ Kode Salah</span>';
                    isOtpValid = false;
                    submitButton.disabled = true;
                    otpInputs.forEach(input => input.classList.add('border-red-500'));
                }
            } catch (error) {
                otpStatus.innerHTML = '<span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Error verifikasi.</span>';
                isOtpValid = false;
            }
        }

        // Event listener untuk input OTP
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                // Hapus status error saat mulai mengetik lagi
                input.classList.remove('border-red-500');
                otpStatus.innerHTML = '';

                if (input.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                // Jika semua 6 digit terisi, verifikasi
                if (Array.from(otpInputs).every(i => i.value)) {
                    verifyOtp();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // TAMBAHKAN BLOK INI: Logika untuk paste OTP
        otpInputs[0].addEventListener('paste', (event) => {
            event.preventDefault();
            const pasteData = (event.clipboardData || window.clipboardData).getData('text').trim();
            // Pastikan data yang di-paste adalah 6 karakter alphanumeric
            if (/^[a-zA-Z0-9]{6}$/.test(pasteData)) { // <-- UBAH REGEX DI SINI
                pasteData.split('').forEach((char, index) => {
                    otpInputs[index].value = char;
                });
                // Fokus ke input terakhir dan jalankan verifikasi
                otpInputs[5].focus();
                verifyOtp();
            }
        });

        // Event listener untuk submit form utama
        mainForm.addEventListener('submit', (event) => {
            if (!isOtpValid) {
                event.preventDefault();
                alert('Verifikasi email dengan OTP yang valid diperlukan sebelum melanjutkan.');
            }
        });
    });
</script>
@endpush