@props(['title', 'value'])

<div class="block rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-700 dark:bg-gray-800">
    <h5 class="mb-2 text-sm font-normal tracking-tight text-gray-500 dark:text-gray-400">{{ $title }}</h5>
    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
</div>
