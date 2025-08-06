// export function setupOtp() {
//     const mainForm = document.getElementById('personal-info-form');
//     if (!mainForm) return; // Hanya jalankan jika form ada di halaman

//     const emailInput = mainForm.querySelector('#email');
//     const sendOtpBtn = mainForm.querySelector('#send-otp-btn');
//     const otpContainer = mainForm.querySelector('#otp-container');
//     const otpInputs = mainForm.querySelectorAll('.otp-input');
//     const otpStatus = mainForm.querySelector('#otp-status');
//     const submitButton = mainForm.querySelector('#submit-personal-info');

//     if (!emailInput || !sendOtpBtn || !otpContainer) return;

//     // Sembunyikan kontainer OTP dan nonaktifkan tombol submit pada awalnya
//     otpContainer.classList.add('hidden');
//     if (submitButton) submitButton.disabled = true;
//     let isOtpValid = false;

//     // Fungsi untuk mengirim OTP
//     sendOtpBtn.addEventListener('click', async () => {
//         if (!emailInput.value) {
//             alert('Silakan masukkan alamat email Anda.');
//             return;
//         }
//         sendOtpBtn.disabled = true;
//         sendOtpBtn.textContent = 'Mengirim...';

//         try {
//             const response = await fetch('/otp/send', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 },
//                 body: JSON.stringify({ email: emailInput.value })
//             });
//             const result = await response.json();
//             alert(result.message);
//             if (response.ok) {
//                 otpContainer.classList.remove('hidden');
//                 otpInputs.forEach(input => input.disabled = false);
//                 otpInputs[0].focus();
//             }
//         } catch (error) {
//             alert('Terjadi kesalahan saat mengirim OTP.');
//         } finally {
//             sendOtpBtn.disabled = false;
//             sendOtpBtn.textContent = 'Kirim OTP';
//         }
//     });

//     // Fungsi verifikasi OTP
//     async function verifyOtp() {
//         const otp = Array.from(otpInputs).map(i => i.value).join('').toUpperCase();
//         if (otp.length !== 6) return;

//         otpStatus.innerHTML = '<span class="text-xs text-blue-500">Memverifikasi...</span>';
//         try {
//             const response = await fetch('/otp/verify', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 },
//                 body: JSON.stringify({ email: emailInput.value, otp: otp })
//             });
//             const result = await response.json();
//             if (result.valid) {
//                 otpStatus.innerHTML = '<span class="text-xs text-green-500">✔ Valid</span>';
//                 isOtpValid = true;
//                 if (submitButton) submitButton.disabled = false;
//                 otpInputs.forEach(input => input.disabled = true);
//             } else {
//                 otpStatus.innerHTML = '<span class="text-xs text-red-500">✖ Kode Salah</span>';
//                 isOtpValid = false;
//             }
//         } catch (error) {
//             otpStatus.innerHTML = '<span class="text-xs text-red-500">Error verifikasi.</span>';
//             isOtpValid = false;
//         }
//     }

//     // Event listener untuk input OTP
//     otpInputs.forEach((input, index) => {
//         input.addEventListener('input', () => {
//             if (input.value && index < otpInputs.length - 1) {
//                 otpInputs[index + 1].focus();
//             }
//             if (Array.from(otpInputs).every(i => i.value)) {
//                 verifyOtp();
//             }
//         });
//         input.addEventListener('keydown', (e) => {
//             if (e.key === 'Backspace' && !input.value && index > 0) {
//                 otpInputs[index - 1].focus();
//             }
//         });
//     });

//     // Event listener untuk submit form
//     mainForm.addEventListener('submit', (event) => {
//         if (!isOtpValid) {
//             event.preventDefault();
//             alert('Verifikasi email dengan OTP diperlukan sebelum melanjutkan.');
//         }
//     });
// }