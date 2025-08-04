@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Manajemen Admin Satker' => route('admin-satker.index'),
        'Edit Admin Satker' => '#',
    ],
])
@section('title', 'Edit Admin Satker')
@section('content')
    <x-card>
        <div class="relative overflow-x-auto p-5 sm:rounded-lg">
            <form action="{{ route('admin-satker.update', $admin_satker) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" name="name" id="name"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm"
                        value="{{ old('name', $admin_satker->name) }}" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" id="email"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm"
                        value="{{ old('email', $admin_satker->email) }}" required>
                </div>
                <div class="mb-4">
                    <label for="village_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan
                        Kerja</label>
                    <select name="village_id" id="village_id"
                        class="searchable-select block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        required>
                        <option value="" hidden>-- Pilih Satuan Kerja --</option>
                        @foreach ($satkerTypes as $type)
                            <optgroup label="{{ $type->name }}">
                                @foreach ($type->villages as $village)
                                    <option value="{{ $village->id }}"
                                        {{ old('village_id', $admin_satker->village_id) == $village->id ? 'selected' : '' }}>
                                        {{ $village->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('village_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">Kosongkan password jika tidak ingin mengubahnya.
                </p>
                <div class="mb-4">
                    <x-form.password-input name="password" id="password" label="Password Baru" />
                </div>

                <div class="mb-4">
                    <x-form.password-input name="password_confirmation" id="password_confirmation"
                        label="Konfirmasi Password Baru" />
                </div>

                <div class="flex justify-end">
                    <x-button.submit text="Perbarui" />
                </div>
            </form>
        </div>
    </x-card>
@endsection
