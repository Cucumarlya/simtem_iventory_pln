<!-- File: resources/views/layouts/includes/sidebar.blade.php -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header dengan Logo dan Nama -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="logo-container">
                <img src="{{ asset('build/assets/images/Logo_PLN.png') }}" alt="Logo PLN" class="sidebar-logo">
            </div>
            <span class="sidebar-brand-name">SINVOSAR</span>
        </div>
        <button class="sidebar-close-mobile" id="sidebarCloseMobile">
            <ion-icon name="close-outline"></ion-icon>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="menu">
        <!-- Dashboard -->
        @php
            $userRole = auth()->user()->role;
        @endphp
        
        @if($userRole === 'admin' || (is_object($userRole) && $userRole->name === 'admin'))
        <a href="{{ route('admin.dashboard') }}" 
           class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           data-title="Dashboard">
            <ion-icon name="home-outline"></ion-icon>
            <span>Dashboard</span>
        </a>
        
        <!-- Verifikasi Transaksi Material -->
        <a href="{{ route('admin.verifikasi.index') }}" 
           class="menu-item {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}"
           data-title="Verifikasi Transaksi">
            <ion-icon name="checkmark-done-outline"></ion-icon>
            <span>Verifikasi Transaksi</span>
        </a>

        <!-- Transaksi Material -->
        <a href="{{ route('admin.transaksi.index') }}" 
            class="menu-item {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}"
            data-title="Transaksi Material">
            <ion-icon name="swap-horizontal-outline"></ion-icon>
            <span>Transaksi Material</span>
        </a>

        <!-- MASTER MATERIAL -->
        <a href="{{ route('admin.master.material.index') }}" 
           class="menu-item {{ request()->routeIs('admin.master.material.*') ? 'active' : '' }}"
           data-title="Master Material">
            <ion-icon name="cube-outline"></ion-icon>
            <span>Master Material</span>
        </a>

        <!-- Stok Material -->
        <a href="{{ route('admin.stok-material.index') }}" 
           class="menu-item {{ request()->routeIs('admin.stok-material.*') ? 'active' : '' }}"
           data-title="Stok Material">
            <ion-icon name="bar-chart-outline"></ion-icon>
            <span>Stok Material</span>
        </a>

        <!-- Kelola Data User -->
        <a href="{{ route('admin.users.index') }}" 
           class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           data-title="Kelola Data User">
            <ion-icon name="people-circle-outline"></ion-icon>
            <span>Kelola Data User</span>
        </a>
        
        @elseif($userRole === 'petugas' || (is_object($userRole) && $userRole->name === 'petugas'))
        <a href="{{ route('petugas.dashboard') }}" 
           class="menu-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}"
           data-title="Dashboard">
            <ion-icon name="home-outline"></ion-icon>
            <span>Dashboard</span>
        </a>
        
        <!-- Daftar Penerimaan -->
        <a href="{{ route('petugas.penerimaan.index') }}" 
           class="menu-item {{ request()->routeIs('petugas.penerimaan.*') ? 'active' : '' }}"
           data-title="Daftar Penerimaan">
            <ion-icon name="list-outline"></ion-icon>
            <span>Daftar Penerimaan</span>
        </a>
        
        @elseif($userRole === 'petugas_yanbung' || (is_object($userRole) && $userRole->name === 'petugas_yanbung'))
        <a href="{{ route('dashboard.petugas_yanbung') }}" 
           class="menu-item {{ request()->routeIs('dashboard.petugas_yanbung') ? 'active' : '' }}"
           data-title="Dashboard">
            <ion-icon name="home-outline"></ion-icon>
            <span>Dashboard</span>
        </a>
        @endif
    </nav>
</aside>