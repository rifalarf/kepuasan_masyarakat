@extends('layouts.public')
@section('title', 'Index Kepuasan Masyarakat')
@section('content')
	
		<div class="mx-auto mt-10 grid h-full max-w-screen-lg rounded-xl border-2 bg-gray-200 px-4 py-8 shadow lg:h-auto lg:grid-cols-12 lg:gap-8 lg:py-16 xl:gap-0">
			<div class="mr-auto md:pl-10 place-self-center text-center lg:col-span-7 lg:place-self-start lg:text-start">
				<h1 class="mb-4 max-w-2xl text-4xl font-extrabold leading-none tracking-tight dark:text-white md:text-5xl xl:text-6xl">
					Survey Kepuasan Masyarakat</h1>
				<p class="mb-6 max-w-2xl font-light text-gray-500 dark:text-gray-400 md:text-lg lg:mb-8 lg:text-xl">
					Selamat Datang di Survey Kepuasan Masyarakat</p>
				<a href="{{ route('kuesioner') }}" class="mr-3 inline-flex items-center justify-center rounded-lg bg-primary-700 px-5 py-3 text-center text-base font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
					Klik Untuk Memulai Survey
				</a>
			</div>
			<div class="hidden lg:col-span-5 lg:mt-0 lg:flex">
				<div class="rounded-xl bg-white dark:bg-gray-800">
					<x-chart.donut :answers="$answers" />
				</div>
			</div>
		</div>
	</section>
	<section class="mb-10 min-h-screen grid place-content-center px-5">
		<div class="mx-auto max-w-screen-lg">
			<div class="p-8">
				<h1 class="mt-6 text-center text-4xl font-medium text-gray-700"> Indek Kepuasan Masyarakat </h1>
				<p class="mt-6 text-center text-lg font-light text-gray-500">Terimakasih atas kepercayaan dan dukungan yang Anda berikan kepada kami. Kami
					sangat bersemangat untuk terus memberikan layanan terbaik bagi Anda</p>
			</div>
			<div class="grid grid-cols-2 gap-5 md:grid-cols-4">
				<div class="rounded-xl border p-8 shadow">
					<div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 shadow-2xl">
						<img src="{{ asset('assets/1.svg') }}" alt="Tidak Memuaskan" class="p-3">
					</div>
					<h2 class="mb-3 mt-6 font-medium uppercase text-red-500">Tidak Memuaskan</h2>
					<p class="text-5xl">{{ $answers->details[0]['total'] }}</p>
				</div>
				<div class="rounded-xl border p-8 shadow">
					<div class="flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-orange-500 shadow-2xl">
						<img src="{{ asset('assets/2.svg') }}" alt="Kurang Memuaskan" class="p-3">
					</div>
					<h2 class="mb-3 mt-6 font-medium uppercase text-orange-500">Kurang Memuaskan</h2>
					<p class="text-5xl">{{ $answers->details[1]['total'] }}</p>
				</div>
				<div class="rounded-xl border p-8 shadow">
					<div class="flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 text-yellow-500 shadow-2xl">
						<img src="{{ asset('assets/3.svg') }}" alt="Memuaskan" class="p-3">
					</div>
					<h2 class="mb-3 mt-6 font-medium uppercase text-yellow-500">Memuaskan</h2>
					<p class="text-5xl">{{ $answers->details[2]['total'] }}</p>
				</div>
				<div class="rounded-xl border p-8 shadow">
					<div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-500 shadow-2xl">
						<img src="{{ asset('assets/4.svg') }}" alt="Sangat Memuaskan" class="p-3">
					</div>
					<h2 class="mb-3 mt-6 font-medium uppercase text-green-500">Sangat Memuaskan</h2>
					<p class="text-5xl">{{ $answers->details[3]['total'] }}</p>
				</div>
			</div>
		</div>
	</section>
	<footer class="border-t-2">
		<div class="mx-auto max-w-screen-lg px-4 py-8 sm:px-6 lg:px-8">
			<div class="sm:flex sm:items-center sm:justify-between">
				<a href="{{ route('index') }}" class="flex items-center">
          <img src="{{ asset('assets/logo.jpeg') }}" class="mr-3 h-8" alt="Logo" />
					<span class="self-center whitespace-nowrap text-2xl font-medium dark:text-white"></span>
        </a>
				<p class="mt-4 text-center text-sm text-gray-500 lg:mt-0 lg:text-right">
					Copyright &copy; 2024.diskominfo
				</p>
			</div>
		</div>
	</footer>
@endsection
