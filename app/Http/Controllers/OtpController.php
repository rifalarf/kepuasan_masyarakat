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

        // --- PERUBAHAN UTAMA DI SINI ---
        // Kita akan membuat HTML email secara langsung di sini
        $appName = config('app.name');
        $htmlContent = "
            <!DOCTYPE html>
            <html>
            <head><title>Kode Verifikasi Anda</title></head>
            <body style=\"font-family: sans-serif; color: #333;\">
                <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd;\">
                    <h1 style=\"text-align: center;\">Kode Verifikasi Anda</h1>
                    <p>Gunakan kode di bawah ini untuk memverifikasi alamat email Anda.</p>
                    <div style=\"background-color: #f7f7f7; padding: 20px; text-align: center; margin: 20px 0;\">
                        <p style=\"font-size: 32px; font-weight: bold; letter-spacing: 8px; margin: 0;\">{$otp}</p>
                    </div>
                    <p>Kode ini akan kedaluwarsa dalam 5 menit.</p>
                    <p>Terima kasih,<br/>{$appName}</p>
                </div>
            </body>
            </html>
        ";

        try {
            // Mengirim email HTML secara langsung, tanpa Mailable
            Mail::html($htmlContent, function ($message) use ($email) {
                $message->to($email)
                        ->subject('Kode Verifikasi Anda');
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
            Cache::forget('otp_' . $email);
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }
}
