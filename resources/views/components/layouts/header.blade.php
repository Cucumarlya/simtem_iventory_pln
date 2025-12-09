<header class="header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            @hasSection('breadcrumb')
                @yield('breadcrumb')
            @else
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">{{ $breadcrumb ?? 'Dashboard' }}</span>
                </nav>
            @endif
        </div>
        
        <div class="header-right">
            <div class="header-actions">
                <!-- Notifications -->
                <div class="notification-dropdown">
                    <button class="notification-btn" id="notificationBtn">
                        <ion-icon name="notifications-outline"></ion-icon>
                        <span class="notification-badge" id="notificationCount">0</span>
                    </button>
                    <div class="notification-dropdown-content">
                        <div class="notification-header">
                            <h3>Notifikasi</h3>
                            <button class="text-sm text-primary" onclick="markAllNotificationsAsRead()">Tandai semua terbaca</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">Selamat datang di SIPMATSAR</p>
                                    <span class="notification-time">Baru saja</span>
                                </div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="#" class="text-sm text-primary">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="user-dropdown">
                    <button class="user-btn" id="userBtn">
                        <div class="user-avatar">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}">
                            @else
                                <ion-icon name="person-circle-outline"></ion-icon>
                            @endif
                        </div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <ion-icon name="chevron-down-outline"></ion-icon>
                    </button>
                    <div class="user-dropdown-content">
                        <div class="user-info">
                            <div class="user-avatar large">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <ion-icon name="person-circle-outline"></ion-icon>
                                @endif
                            </div>
                            <div class="user-details">
                                <strong>{{ auth()->user()->name }}</strong>
                                <span class="capitalize">
                                    @php
                                        $roleName = auth()->user()->role->name ?? 'user';
                                        $roleDisplayNames = [
                                            'admin' => 'Administrator',
                                            'petugas' => 'Petugas',
                                            'petugas_yanbung' => 'Petugas Yanbung'
                                        ];
                                    @endphp
                                    {{ $roleDisplayNames[$roleName] ?? ucfirst($roleName) }}
                                </span>
                            </div>
                        </div>
                        <div class="user-dropdown-menu">
                            <a href="{{ route('profile.show') }}" class="user-menu-item">
                                <ion-icon name="person-outline"></ion-icon>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('settings') }}" class="user-menu-item">
                                <ion-icon name="settings-outline"></ion-icon>
                                <span>Pengaturan</span>
                            </a>
                            <hr>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="user-menu-item logout-btn w-full text-left">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>