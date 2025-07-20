@php
    $allMenus = [
        (object) [
            'name' => 'Dasbor',
            'link' => route('dasbor'),
            'icon' => '',
            'roles' => ['admin', 'satker'],
        ],
        (object) [
            'name' => 'Kuesioner',
            'link' => route('kuesioner.index'),
            'icon' => '',
            'roles' => ['admin', 'satker'],
        ],
        (object) [
            'name' => 'Responden',
            'link' => route('responden.index'),
            'icon' => '',
            'roles' => ['admin', 'satker'],
        ],
        (object) [
            'name' => 'Indeks Survey',
            'link' => route('ikm.index'),
            'icon' => '',
            'roles' => ['admin', 'satker'],
        ],
        (object) [
            'name' => 'Kritik & Saran',
            'link' => route('feedback.index'),
            'icon' => '',
            'roles' => ['admin', 'satker'],
        ],
        (object) [
            'name' => 'Satuan Kerja',
            'link' => route('village.index'),
            'icon' => '',
            'roles' => ['admin'], // <-- Hanya untuk 'admin'
        ],
        (object) [
            'name' => 'Admin Satker',
            'link' => route('admin-satker.index'),
            'icon' => '',
            'roles' => ['admin'], // <-- Hanya untuk 'admin'
        ],
    ];

    $userRole = auth()->user()->role;
    $menus = array_filter($allMenus, function ($menu) use ($userRole) {
        return in_array($userRole, $menu->roles);
    });
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <x-navbar.dashboard :app-name="config('app.name')" />
    <x-sidebar :menus="$menus" />
    <main>
        <div class="p-4 sm:ml-64">
            <div class="mt-14 p-4">
                <x-card>
                    <div class="flex items-center justify-between px-5 py-4">
                        <h2 class="text-xl font-bold text-gray-700">@yield('title')</h2>
                        <x-breadcrumb :$breadcrumbs />
                    </div>
                </x-card>
                @yield('content')
            </div>
        </div>
    </main>
    <x-script.toast />
</body>

</html>
