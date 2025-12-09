<!-- File: resources/views/layouts/includes/topbar.blade.php -->
<header class="topbar">
  <div class="topbar-container">
    
    <!-- Hamburger Menu untuk Mobile -->
    <button class="mobile-hamburger" id="mobileHamburger">
      <ion-icon name="menu-outline"></ion-icon>
    </button>
    
    <!-- Left Section - ICON DAN TULISAN DITENGAH VERTIKAL -->
    <div class="topbar-left-section">
      <div class="topbar-left-content">
        <!-- Tombol Hamburger -->
        <button class="desktop-sidebar-toggle" id="desktopSidebarToggle" title="Toggle Sidebar">
          <ion-icon name="menu-outline"></ion-icon>
        </button>
        
        <!-- Page Title dengan inline style untuk menurunkan -->
        <div class="page-title">
          <span class="page-title-text" id="pageTitle" style="position: relative; top: 5px; padding-top: 6px;">
            @auth
              @php
                $userRole = null;
                if (isset(auth()->user()->role) && is_object(auth()->user()->role)) {
                    $userRole = auth()->user()->role->name ?? auth()->user()->role->role_name ?? null;
                } elseif (isset(auth()->user()->role_name)) {
                    $userRole = auth()->user()->role_name;
                } elseif (isset(auth()->user()->role)) {
                    $userRole = auth()->user()->role;
                }
              @endphp

              @if($userRole === 'admin')
                Dashboard Admin
              @elseif($userRole === 'petugas')
                Dashboard Petugas
              @elseif($userRole === 'petugas_yanbung')
                Dashboard Petugas Yanbung
              @else
                Dashboard
              @endif
            @endauth
          </span>
        </div>
      </div>
    </div>
    
    <!-- Right Section -->
    <div class="topbar-right-section">
      <!-- User Profile -->
      <div class="user-profile-item">
        <div class="user-avatar">
          <img src="{{ Auth::user()->profile_photo_url ?? asset('build/assets/images/Logo_PLN.png') }}" 
               alt="User Avatar" class="avatar-image"
               onerror="this.src='{{ asset('build/assets/images/Logo_PLN.png') }}'">
        </div>
        <div class="user-details">
          <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
          <span class="user-role">
            @auth
              @php
                $userRole = null;
                if (isset(auth()->user()->role) && is_object(auth()->user()->role)) {
                    $userRole = auth()->user()->role->name ?? auth()->user()->role->role_name ?? null;
                } elseif (isset(auth()->user()->role_name)) {
                    $userRole = auth()->user()->role_name;
                } elseif (isset(auth()->user()->role)) {
                    $userRole = auth()->user()->role;
                }
              @endphp

              @if($userRole === 'admin')
                Administrator
              @elseif($userRole === 'petugas')
                Petugas
              @elseif($userRole === 'petugas_yanbung')
                Petugas Yanbung
              @else
                User
              @endif
            @endauth
          </span>
        </div>
      </div>

      <!-- Logout Button -->
      <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn" title="Logout">
          <ion-icon name="log-out-outline"></ion-icon>
          <span class="logout-text">Logout</span>
        </button>
      </form>
    </div>
  </div>
</header>

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>