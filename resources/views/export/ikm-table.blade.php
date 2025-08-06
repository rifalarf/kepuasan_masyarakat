<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Indeks Kepuasan Masyarakat</title>
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
            line-height: 1.5;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        .table .center {
            text-align: center;
        }

        .table .font-bold {
            font-weight: bold;
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
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d4/Lambang_Kabupaten_Garut.svg" alt="Logo"
                    class="logo">
            </td>
            <td class="header-text">
                <h1>Pemerintah Kabupaten Garut</h1>
                <h1>Dinas Komunikasi dan Informatika</h1>
                <p>Jl. Pramuka No.6, Pakuwon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44117</p>
            </td>
            <td style="width: 15%;"></td>
        </tr>
    </table>

    <div class="title">
        Laporan Hasil Survei Kepuasan Masyarakat<br>
        Satuan Kerja: {{ strtoupper($villageName) }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Pertanyaan</th>
                <th class="center" style="width: 15%;">Jumlah Nilai/Unsur</th>
                <th class="center" style="width: 12%;">NRR/Unsur</th>
                <th class="center" style="width: 12%;">Bobot</th>
                <th class="center" style="width: 15%;">NRR Tertimbang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->question }}</td>
                    <td class="center">{{ number_format($item->totalNilaiPersepsiPerUnit, 2) }}</td>
                    <td class="center">{{ number_format($item->NRRPerUnsur, 2) }}</td>
                    <td class="center">{{ number_format($bobotNilaiTertimbang, 2) }}</td>
                    <td class="center">{{ number_format($item->NRRTertimbangUnsur, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Data tidak ditemukan.</td>
                </tr>
            @endforelse

            {{-- Summary Rows --}}
            @if (!empty($data))
                <tr class="font-bold">
                    <td colspan="4">IKM</td>
                    <td class="center">{{ number_format($IKM, 2) }}</td>
                </tr>
                <tr class="font-bold">
                    <td colspan="4">Konversi IKM</td>
                    <td class="center">{{ number_format($konversiIKM, 2) }}</td>
                </tr>
                <tr class="font-bold">
                    <td colspan="4">Mutu Pelayanan (Hasil Penilaian)</td>
                    <td class="center">{{ nilaPersepsi($konversiIKM)->mutu }}</td>
                </tr>
                <tr class="font-bold">
                    <td colspan="4">Kinerja Unit Pelayanan</td>
                    <td class="center">{{ nilaPersepsi($konversiIKM)->kinerja }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <div>Garut, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Kepala Diskominfo</div>
                <div class="signature-space"></div>
                <div>Margiyanto, S.H</div>
                <div>Nip.</div>
            </td>
        </tr>
    </table>
</body>

</html>
