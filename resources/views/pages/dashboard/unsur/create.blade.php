<?php
@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Manajemen Unsur' => route('unsur.index'),
        'Tambah Unsur' => '#',
    ],
])
@section('title', 'Tambah Unsur')
@section('content')
    <x-card>
        <div class="p-5">
            <form action="{{ route('unsur.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="unsur" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Unsur
                    </label>
                    <input type="text" name="unsur" id="unsur"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        placeholder="Contoh: Kualitas Pelayanan" value="{{ old('unsur') }}" required>
                    @error('unsur')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if (auth()->user()->role === 'admin')
                    <div class="mb-4">
                        <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Kepemilikan (Satuan Kerja)
                        </label>
                        <select name="village_id" id="village_id"
                            class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">-- Unsur Global (Untuk Semua Satker) --</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                    {{ $village->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Kosongkan jika unsur ini bersifat global dan dapat digunakan oleh semua satuan kerja.
                        </p>
                    </div>
                @endif

                <div class="flex justify-end">
                    <x-button.submit text="Simpan" />
                </div>
            </form>
        </div>
    </x-card>
@endsection