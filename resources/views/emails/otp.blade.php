<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Anda</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            line-height: 1.6;
            color: #3d4852;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            padding: 20px;
            text-align: center;
            background-color: #f8fafc;
            border-bottom: 1px solid #e8e5ef;
        }

        .content {
            padding: 30px;
        }

        .panel {
            background-color: #edf2f7;
            border-radius: 3px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #2d3748;
        }

        .footer {
            font-size: 12px;
            color: #718096;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Kode Verifikasi Anda</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Gunakan kode di bawah ini untuk memverifikasi alamat email Anda.</p>
            <div class="panel">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            <p>Kode ini akan kedaluwarsa dalam 5 menit. Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email
                ini.</p>
            <p>
                Terima kasih,<br>
                {{ config('app.name') }}
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
