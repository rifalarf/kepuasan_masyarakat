<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Analisis Kritik dan Saran</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .header-table td {
            padding: 5px;
            vertical-align: middle;
        }

        .logo {
            width: 60px;
        }

        .header-text {
            text-align: center;
        }

        .header-text h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 4px 0;
            font-size: 11px;
        }

        .title {
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .table .no {
            width: 5%;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-table td {
            text-align: center;
        }

        .signature-space {
            height: 50px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Lambang_Kabupaten_Garut.svg" alt="Logo" class="logo">
            </td>
            <td class="header-text">
                <h1>Pemerintah Kabupaten Garut</h1>
                <h1>Dinas Komunikasi dan Informatika</h1>
                <p>Jl. Pramuka No.6, Pakuwon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44117</p>
            </td>
            <td style="width: 15%;"></td>
        </tr>
    </table>

    <div class="title">Analisis Kritik dan Saran Masyarakat</div>

    <div class="section-title">Ringkasan Analisis</div>
    <p>Berikut adalah kata kunci yang paling sering muncul dalam kritik dan saran dari total <strong>{{ count($data) }}</strong> masukan yang diterima.</p>
    
    {{-- Mengganti bar chart dengan tabel sederhana --}}
    <table class="table">
        <thead>
            <tr>
                <th class="no">No.</th>
                <th>Kata Kunci</th>
                <th style="width: 20%; text-align: center;">Frekuensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topKeywords as $keyword => $count)
                <tr>
                    <td class="no">{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($keyword) }}</td>
                    <td style="text-align: center;">{{ $count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada kata kunci yang dapat dianalisis.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Rincian Kritik dan Saran</div>
    <table class="table">
        <thead>
            <tr>
                <th class="no">No.</th>
                <th>Satuan Kerja</th>
                <th>Unsur Pelayanan</th>
                <th>Kritik dan Saran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="no">{{ $loop->iteration }}</td>
                    <td>{{ $item->responden->village->name ?? 'N/A' }}</td>
                    <td>{{ $item->responden->answers->first()?->kuesioner?->unsur?->unsur ?? 'N/A' }}</td>
                    <td>{{ $item->feedback }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center;">Data tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <div>Garut, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Kepala Diskominfo</div>
                <div class="signature-space"></div>
                <div><strong>Margiyanto, S.H</strong></div>
                <div>Nip.</div>
            </td>
        </tr>
    </table>
</body>
</html>
