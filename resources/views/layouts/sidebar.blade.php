<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="logo-container">
                <img src="{{ asset('build/assets/images/Logo_PLN.png') }}" alt="Logo PLN" class="sidebar-logo">
                <button class="sidebar-hamburger" id="sidebarHamburger">
                    <ion-icon name="menu-outline"></ion-icon>
                </button>
            </div>
            <span class="sidebar-brand-name">SIPMATSAR</span>
        </div>
        <button class="sidebar-close-mobile" id="sidebarCloseMobile">
            <ion-icon name="close-outline"></ion-icon>
        </button>
    </div>

    <nav class="menu">
        @php
            $userRole = auth()->user()->role->name ?? null;
        @endphp

        <!-- Dashboard -->
        @if($userRole === 'admin')
            <a href="{{ route('dashboard.admin') }}" class="menu-item {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                <ion-icon name="home-outline"></ion-icon>
                <span>Dashboard</span>
            </a>
        @elseif($userRole === 'petugas')
            <a href="{{ route('dashboard.petugas') }}" class="menu-item {{ request()->routeIs('dashboard.petugas') ? 'active' : '' }}">
                <ion-icon name="home-outline"></ion-icon>
                <span>Dashboard</span>
            </a>
        @elseif($userRole === 'petugas_yanbung')
            <a href="{{ route('dashboard.petugas_yanbung') }}" class="menu-item {{ request()->routeIs('dashboard.petugas_yanbung') ? 'active' : '' }}">
                <ion-icon name="home-outline"></ion-icon>
                <span>Dashboard</span>
            </a>
        @endif

        <!-- Menu Admin -->
        @if($userRole === 'admin')
            <a href="{{ route('verifikasi.index') }}" class="menu-item {{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
                <ion-icon name="checkmark-done-outline"></ion-icon>
                <span>Verifikasi Transaksi</span>
            </a>

            <a href="{{ route('transaksi.index') }}" class="menu-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                <ion-icon name="swap-horizontal-outline"></ion-icon>
                <span>Transaksi Material</span>
            </a>

            <a href="{{ route('material.index') }}" class="menu-item {{ request()->routeIs('material.*') ? 'active' : '' }}">
                <ion-icon name="cube-outline"></ion-icon>
                <span>Master Material</span>
            </a>

            <a href="{{ route('stok-material.index') }}" class="menu-item {{ request()->routeIs('stok-material.*') ? 'active' : '' }}">
                <ion-icon name="bar-chart-outline"></ion-icon>
                <span>Stok Material</span>
            </a>

            <a href="{{ route('users.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <ion-icon name="people-outline"></ion-icon>
                <span>Kelola Pengguna</span>
            </a>
        @endif

        <!-- Menu Petugas -->
        @if($userRole === 'petugas')
            <a href="{{ route('transaksi.penerimaan.create') }}" class="menu-item {{ request()->routeIs('transaksi.penerimaan.*') ? 'active' : '' }}">
                <ion-icon name="arrow-down-outline"></ion-icon>
                <span>Buat Penerimaan</span>
            </a>

            <a href="{{ route('transaksi.pengeluaran.create') }}" class="menu-item {{ request()->routeIs('transaksi.pengeluaran.*') ? 'active' : '' }}">
                <ion-icon name="arrow-up-outline"></ion-icon>
                <span>Buat Pengeluaran</span>
            </a>

            <a href="{{ route('transaksi.index') }}" class="menu-item {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                <ion-icon name="list-outline"></ion-icon>
                <span>Transaksi Saya</span>
            </a>
        @endif

        <!-- Menu Petugas Yanbung -->
        @if($userRole === 'petugas_yanbung')
            <a href="{{ route('transaksi.pengeluaran.create') }}" class="menu-item {{ request()->routeIs('transaksi.pengeluaran.*') ? 'active' : '' }}">
                <ion-icon name="arrow-up-outline"></ion-icon>
                <span>Buat Pengeluaran</span>
            </a>

            <a href="{{ route('transaksi.index') }}" class="menu-item {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                <ion-icon name="list-outline"></ion-icon>
                <span>Transaksi Saya</span>
            </a>
        @endif
    </nav>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<button class="hamburger-menu" id="mobileHamburger">
    <ion-icon name="menu-outline"></ion-icon>
</button>