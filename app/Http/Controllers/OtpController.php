<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $otp = strtoupper(Str::random(6));

        Cache::put('otp_' . $email, $otp, now()->addMinutes(5));

        // --- PERBAIKAN: Gunakan logo PNG dan sesuaikan nama pengirim ---
        $senderName = 'DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN GARUT';
        // Menggunakan format PNG untuk kompatibilitas email yang lebih baik
        $logoUrl = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Lambang_Kabupaten_Garut.svg/200px-Lambang_Kabupaten_Garut.svg.png';
        $htmlContent = "
            <!DOCTYPE html>
            <html lang=\"id\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Kode Verifikasi Anda</title>
            </head>
            <body style=\"font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #3d4852; background-color: #f7fafc; margin: 0; padding: 20px;\">
                <div style=\"max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e8e5ef; border-radius: 5px; overflow: hidden;\">
                    <div style=\"padding: 20px; text-align: center; background-color: #f8fafc; border-bottom: 1px solid #e8e5ef;\">
                        <img src=\"{$logoUrl}\" alt=\"Logo Kabupaten Garut\" style=\"max-height: 80px; margin-bottom: 10px;\">
                        <h1 style=\"margin: 0; font-size: 20px; color: #2d3748;\">DINAS KOMUNIKASI DAN INFORMATIKA<br>KABUPATEN GARUT</h1>
                    </div>
                    <div style=\"padding: 30px;\">
                        <p>Halo,</p>
                        <p>Gunakan kode di bawah ini untuk memverifikasi alamat email Anda.</p>
                        <div style=\"background-color: #edf2f7; border-radius: 3px; padding: 20px; text-align: center; margin: 20px 0;\">
                            <div style=\"font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2d3748;\">{$otp}</div>
                        </div>
                        <p>Kode ini akan kedaluwarsa dalam 5 menit. Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email ini.</p>
                        <p>
                            Terima kasih,<br>
                            Tim {$senderName}
                        </p>
                    </div>
                    <div style=\"font-size: 12px; color: #718096; text-align: center; padding: 20px; border-top: 1px solid #e8e5ef;\">
                        &copy; " . date('Y') . " {$senderName}. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
        ";

        try {
            // Mengirim email HTML secara langsung, tanpa Mailable
            Mail::html($htmlContent, function ($message) use ($email, $senderName) {
                $message->to($email)
                        ->subject("Kode Verifikasi Anda dari {$senderName}");
            });
            
            return response()->json(['message' => 'OTP telah dikirim ke email Anda.']);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP (Metode Langsung): ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengirim OTP. Silakan periksa file log untuk detail error.'
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|min:6|max:6',
        ]);

        $email = $request->email;
        $otp = $request->otp;
        $cachedOtp = Cache::get('otp_' . $email);

        if ($cachedOtp && $cachedOtp === $otp) {
            // Cache::forget('otp_' . $email); // <-- HAPUS ATAU KOMENTARI BARIS INI
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }
}
