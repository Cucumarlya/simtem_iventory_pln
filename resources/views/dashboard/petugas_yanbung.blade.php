<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-sm text-gray-600 mt-1">Sistem Manajemen Inventory</p>
            </div>
            <div class="user-profile flex items-center gap-3 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <img src="https://i.pravatar.cc/40?img=5" alt="Admin" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium text-gray-700">Administrator</span>
                <ion-icon name="chevron-down-outline" class="text-gray-400 text-lg"></ion-icon>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat Cards - COLORFUL DESIGN -->
            <div class="cards-grid mb-8">
                <!-- Barang Masuk -->
                <article class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
                    <div class="card-content">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-white/20 text-white">
                                <ion-icon name="cube-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="stat-meta">
                            <p class="label text-blue-100">Barang Masuk</p>
                            <h3 class="value text-white">1,248</h3>
                        </div>
                    </div>
                    <div class="card-pattern"></div>
                </article>

                <!-- Barang Keluar -->
                <article class="stat-card bg-gradient-to-br from-green-500 to-green-600">
                    <div class="card-content">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-white/20 text-white">
                                <ion-icon name="arrow-forward-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="stat-meta">
                            <p class="label text-green-100">Barang Keluar</p>
                            <h3 class="value text-white">892</h3>
                        </div>
                    </div>
                    <div class="card-pattern"></div>
                </article>

                <!-- Pesan Masuk -->
                <article class="stat-card bg-gradient-to-br from-orange-500 to-orange-600">
                    <div class="card-content">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-white/20 text-white">
                                <ion-icon name="mail-unread-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="stat-meta">
                            <p class="label text-orange-100">Pesan Masuk</p>
                            <h3 class="value text-white">47</h3>
                        </div>
                    </div>
                    <div class="card-pattern"></div>
                </article>

                <!-- Stok Barang -->
                <article class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
                    <div class="card-content">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-white/20 text-white">
                                <ion-icon name="layers-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="stat-meta">
                            <p class="label text-purple-100">Stok Barang</p>
                            <h3 class="value text-white">3,567</h3>
                        </div>
                    </div>
                    <div class="card-pattern"></div>
                </article>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Bar Chart -->
                <div class="chart-panel">
                    <div class="panel-header">
                        <h3>Perbandingan Barang Masuk & Keluar</h3>
                        <div class="legend">
                            <span class="legend-item">
                                <span class="dot incoming"></span>
                                Barang Masuk
                            </span>
                            <span class="legend-item">
                                <span class="dot outgoing"></span>
                                Barang Keluar
                            </span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="chart-panel">
                    <div class="panel-header">
                        <h3>Distribusi Stok Barang</h3>
                    </div>
                    <div class="chart-container pie-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="dot electronics"></span>
                            Elektronik (35%)
                        </div>
                        <div class="legend-item">
                            <span class="dot furniture"></span>
                            Furniture (25%)
                        </div>
                        <div class="legend-item">
                            <span class="dot office"></span>
                            Perlengkapan Kantor (20%)
                        </div>
                        <div class="legend-item">
                            <span class="dot other"></span>
                            Lainnya (20%)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table & Activity Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Data Table -->
                <div class="content-panel">
                    <div class="panel-header">
                        <h3>Data Stok Barang</h3>
                        <button class="btn-primary">
                            <ion-icon name="add-outline"></ion-icon>
                            Tambah Barang
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="code">BRG-001</td>
                                    <td class="product">
                                        <div class="product-info">
                                            <div class="product-icon electronics">
                                                <ion-icon name="laptop-outline"></ion-icon>
                                            </div>
                                            <span>Laptop Dell XPS 13</span>
                                        </div>
                                    </td>
                                    <td>Elektronik</td>
                                    <td>45</td>
                                    <td>
                                        <span class="status-badge in-stock">Tersedia</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action edit">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </button>
                                            <button class="btn-action delete">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="code">BRG-002</td>
                                    <td class="product">
                                        <div class="product-info">
                                            <div class="product-icon furniture">
                                                <ion-icon name="business-outline"></ion-icon>
                                            </div>
                                            <span>Kursi Kantor Ergonomis</span>
                                        </div>
                                    </td>
                                    <td>Furniture</td>
                                    <td>23</td>
                                    <td>
                                        <span class="status-badge in-stock">Tersedia</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action edit">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </button>
                                            <button class="btn-action delete">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="code">BRG-003</td>
                                    <td class="product">
                                        <div class="product-info">
                                            <div class="product-icon electronics">
                                                <ion-icon name="print-outline"></ion-icon>
                                            </div>
                                            <span>Printer HP LaserJet</span>
                                        </div>
                                    </td>
                                    <td>Elektronik</td>
                                    <td>12</td>
                                    <td>
                                        <span class="status-badge low-stock">Stok Menipis</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action edit">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </button>
                                            <button class="btn-action delete">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="code">BRG-004</td>
                                    <td class="product">
                                        <div class="product-info">
                                            <div class="product-icon furniture">
                                                <ion-icon name="desktop-outline"></ion-icon>
                                            </div>
                                            <span>Meja Kerja Minimalis</span>
                                        </div>
                                    </td>
                                    <td>Furniture</td>
                                    <td>0</td>
                                    <td>
                                        <span class="status-badge out-of-stock">Habis</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action edit">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </button>
                                            <button class="btn-action delete">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Activity Feed -->
                <div class="content-panel">
                    <div class="panel-header">
                        <h3>Pesan Masuk & Aktivitas</h3>
                    </div>
                    <div class="activity-feed">
                        <div class="activity-item">
                            <div class="activity-icon message">
                                <ion-icon name="mail-unread-outline"></ion-icon>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Pesan Baru dari Supplier</div>
                                <div class="activity-desc">PT. Elektronik Maju mengirim konfirmasi pengiriman</div>
                            </div>
                            <div class="activity-time">10m lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon inventory">
                                <ion-icon name="cube-outline"></ion-icon>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Barang Masuk</div>
                                <div class="activity-desc">25 unit Laptop Dell telah tiba di gudang</div>
                            </div>
                            <div class="activity-time">1 jam lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon outgoing">
                                <ion-icon name="arrow-forward-outline"></ion-icon>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Barang Keluar</div>
                                <div class="activity-desc">Pengiriman ke Cabang Surabaya telah diproses</div>
                            </div>
                            <div class="activity-time">2 jam lalu</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon alert">
                                <ion-icon name="warning-outline"></ion-icon>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Stok Menipis</div>
                                <div class="activity-desc">Printer HP LaserJet tersisa 12 unit</div>
                            </div>
                            <div class="activity-time">5 jam lalu</div>
                        </div>
                    </div>
                    <button class="btn-secondary">
                        Lihat Semua Aktivitas
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

    @push('scripts')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @endpush
</x-app-layout>