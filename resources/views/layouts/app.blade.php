<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'Figtree', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Vite CSS - HANYA YANG DIPERLUKAN -->
    @vite([
        'resources/css/app.css',
        'resources/css/style.css',
        'resources/css/dashboard-admin.css',
        'resources/css/master.css',
        'resources/css/stok.css',
        'resources/css/create-stok.css',
        'resources/css/manage-users.css',
        'resources/css/create-user.css',
        'resources/css/edit-user.css',
        'resources/css/master-material.css',
        'resources/css/transaksi-system.css',
        'resources/css/verifikasi.css',
        'resources/css/dashboard-petugas.css',
        'resources/css/kelola_user.css',
        'resources/css/master-material-form.css',
        'resources/css/penerimaan-module.css',
        'resources/css/petugas-penerimaan.css',
        'resources/js/app.js',
        'resources/js/main.js',
        'resources/js/dashboard-admin.js',
        'resources/js/master.js',
        'resources/js/stok.js',
        'resources/js/create-stok.js',
        'resources/js/manage-users.js',
        'resources/js/create-user.js',
        'resources/js/edit-user.js',
        'resources/js/master-material.js',
        'resources/js/transaksi-system.js',
        'resources/js/verifikasi.js',
        'resources/js/dashboard-petugas.js',
        'resources/js/kelola_user.js',
        'resources/js/master-material-form.js',
        'resources/js/penerimaan-module.js',
        'resources/css/petugas-penerimaan.js',
    ])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="app-wrapper">
        {{-- Sidebar --}}
        @include('layouts.includes.sidebar')

        <div class="app-main">
            {{-- Topbar --}}
            @include('layouts.includes.topbar')

            {{-- Main Content --}}
            <main class="app-content">
                <div class="container-fluid px-4 sm:px-6 lg:px-8 py-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>