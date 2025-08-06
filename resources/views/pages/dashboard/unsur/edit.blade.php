<?php
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Manajemen Unsur' => route('unsur.index'),
        'Edit Unsur' => '#',
    ],
])
@section('title', 'Edit Unsur')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <form action="{{ route('unsur.update', $unsur) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="unsur_id" class="mb-2 block text-sm font-medium">Unsur Pelayanan</label>
                    <div class="flex items-center space-x-2">
                        <select name="unsur_id" id="unsur_id"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="" hidden>-- Pilih Unsur --</option>
                            @foreach ($unsurs as $unsur)
                                <option value="{{ $unsur->id }}"
                                    {{ $kuesioner->unsur_id == $unsur->id ? 'selected' : '' }}>
                                    {{ $unsur->unsur }}</option>
                            @endforeach
                        </select>
                        <button type="button" data-modal-target="unsur-modal" data-modal-toggle="unsur-modal"
                            class="rounded-lg bg-blue-700 p-2.5 text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M17.402 2.612a1.5 1.5 0 012.12.002l.002.002a1.5 1.5 0 010 2.12l-1.17 1.17-3.24-3.24 1.17-1.17.002-.002.002-.002zm-2.12 2.12l-9.54 9.54a1.5 1.5 0 01-.88.44l-3.5 1a.5.5 0 01-.62-.62l1-3.5a1.5 1.5 0 01.44-.88l9.54-9.54 3.24 3.24z" />
                            </svg>
                            <span class="sr-only">Kelola Unsur</span>
                        </button>
                    </div>
                </div>
                {{-- Tampilkan dropdown Satuan Kerja hanya untuk Admin Utama --}}
                @if (auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="satuan_kerja_id"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan Kerja</label>
                        <select name="satuan_kerja_id" id="satuan_kerja_id"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="" hidden>-- Pilih Satuan Kerja --</option>
                            @foreach ($satuanKerja as $sk)
                                <option value="{{ $sk->id }}"
                                    {{ $kuesioner->satuan_kerja_id == $sk->id ? 'selected' : '' }}>
                                    {{ $sk->satuan_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex justify-end">
                    <x-button.submit text="Perbarui" />
                </div>
            </form>
        </div>
    </x-card>

    {{-- Modal Kelola Unsur --}}
    <x-modal id="unsur-modal" title="Kelola Unsur">
        <form action="{{ route('unsur.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="unsur" class="block text-sm font-medium text-gray-900 dark:text-white">Nama Unsur</label>
                <input type="text" name="unsur" id="unsur"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    placeholder="Contoh: Kualitas Pelayanan" required>
                @error('unsur')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <x-button.submit text="Simpan Unsur" />
            </div>
        </form>
    </x-modal>
@endsection