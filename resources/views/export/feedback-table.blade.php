<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Indeks Kepuasan Masyarakat</title>
		<style>
			.chart-container {
				text-align: center;
			}

			.chart-img {
				max-width: 50%;
				margin: 0 auto;
				display: block;
			}

			table {
				font-size: .8rem;
			}

			table td,
			table th {
				text-align: left;
			}

			.title {
				text-align: center;
				margin: 20px 0;
			}

			.text-md {
				font-size: 1.1rem;
			}

			.table {
				width: 100%;
				border-collapse: collapse;
			}

			.table tr>th,
			.table tr>td {
				border: 1px solid black;
				padding: 5px;
				text-align: center;
			}
		</style>
	</head>

	<body>
		<div>
			<table style="border-bottom: 1px solid black; width: 100%; text-align: center;">
				<tr>
					<td style="vertical-align: middle; width: 15%;">
						<img src="{{ public_path('assets/logo.png') }}" alt="Logo">
					</td>
					<td style="vertical-align: middle; line-height: 1.5;">
						<div style="text-align: center;">
							<h4 style="font-size: 1.2rem; margin: 0;">DINAS KOMUNIKASI DAN INFORMATIKA</h4>
							<div style="margin: 5px 0;">KANTOR DISKOMINFO GARUT</div>
							<div>Jl. Pramuka No.6, Pakuwon, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44117</div>
						</div>
					</td>
					<td style="width: 15%;"></td>
				</tr>
			</table>
			<div class="title">
				<span class="text-md">LAPORAN KRITIK DAN SARAN MASYARAKAT <br>
					KANTOR DISKOMINFO <br>
					KABUPATEN GARUT</span>
				</span>
			</div>

			<table class="table" style="margin-top: 25px;">
				<thead class="bg-blue-100 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
					<tr>
						<th scope="col" class="px-6 py-3">
							No.
						</th>
						<th scope="col" class="px-6 py-3">
							Kritik dan Saran
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $item)
						<tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
							<td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
								{{ $loop->iteration }}
							</td>
							<td scope="row" class="px-6 py-4 text-gray-900 dark:text-white">
								{{ $item->feedback }}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<table style="margin-top: 50px; width: 100%;">
				<tr>
					<td></td>
					<td style="text-align: center; width: 40%;">
						<div>Garut, {{ now()->format('d/m/Y') }}</div>
						<div style="margin-bottom: 50px;">Kepala Diskominfo</div>
						<div>Margiyanto, S.H</div>
						<div>Nip.</div>
					</td>
				</tr>
			</table>
	</body>

</html>
