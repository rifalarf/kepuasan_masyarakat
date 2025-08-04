@props(['name', 'id', 'label', 'placeholder' => '••••••••', 'required' => false, 'value' => ''])

<div>
    <label for="{{ $id }}"
        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    <div class="relative">
        <input type="password" name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
            {{ $required ? 'required' : '' }}>
        <button type="button" data-toggle-password="{{ $id }}"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            {{-- Ikon mata terbuka --}}
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" data-eye-open>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                </path>
            </svg>
            {{-- Ikon mata tertutup --}}
            <svg class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" data-eye-closed>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .95-3.112 3.54-5.61 6.69-6.584M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 2l20 20"></path>
            </svg>
        </button>
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
    @enderror
</div>
