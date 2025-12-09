<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Penerimaan Material</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Custom CSS -->
    <style>
        /* Custom Styles for Dashboard */
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .chart-container {
            height: 320px;
            position: relative;
        }
        
        .filter-btn {
            transition: all 0.2s ease-in-out;
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .notification-item {
            transition: all 0.2s ease-in-out;
        }
        
        .notification-item:hover {
            background-color: #f9fafb;
            transform: translateX(4px);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .loading {
            position: relative;
            overflow: hidden;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Gradient Background for Stats Cards */
        .card-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-menunggu {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }
        
        .card-dikembalikan {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .card-disetujui {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .bg-white {
                background-color: #1f2937;
            }
            
            .text-gray-900 {
                color: #f9fafb;
            }
            
            .text-gray-700 {
                color: #d1d5db;
            }
            
            .text-gray-600 {
                color: #9ca3af;
            }
            
            .bg-gray-50 {
                background-color: #111827;
            }
            
            .border-gray-200 {
                border-color: #374151;
            }
            
            .divide-gray-200 > * + * {
                border-color: #374151;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <x-app-layout>
        <!-- Main Content -->
        <div class="min-h-screen">
            <div class="py-8 fade-in">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Header Section -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Dashboard Petugas</h1>
                                <p class="text-gray-600 mt-2">Ringkasan penerimaan material {{ now()->format('F Y') }}</p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                                <div class="bg-white rounded-lg shadow-sm px-4 py-2 border border-gray-200">
                                    <p class="text-sm text-gray-600">{{ now()->format('l, d F Y') }}</p>
                                </div>
                                <button onclick="refreshDashboard()" 
                                        class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid - 4 Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Card 1: Total Penerimaan -->
                        <div class="stats-card rounded-2xl p-6 border border-gray-200 card-total">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <i data-lucide="dollar-sign" class="w-7 h-7 text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/90">Total Penerimaan</p>
                                    <p class="text-2xl font-bold text-white mt-1">
                                        Rp {{ number_format($stats['totalPenerimaan'] ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-white/80 mr-1"></i>
                                        <span class="text-xs text-white/80">Bulan {{ now()->format('F') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Menunggu Verifikasi -->
                        <div class="stats-card rounded-2xl p-6 border border-gray-200 card-menunggu">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <i data-lucide="clock" class="w-7 h-7 text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/90">Menunggu Verifikasi</p>
                                    <p class="text-2xl font-bold text-white mt-1">
                                        {{ number_format($stats['menungguVerifikasi'] ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <i data-lucide="alert-circle" class="w-4 h-4 text-white/80 mr-1"></i>
                                        <span class="text-xs text-white/80">Perlu tindakan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Dikembalikan -->
                        <div class="stats-card rounded-2xl p-6 border border-gray-200 card-dikembalikan">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <i data-lucide="x-circle" class="w-7 h-7 text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/90">Dikembalikan</p>
                                    <p class="text-2xl font-bold text-white mt-1">
                                        {{ number_format($stats['dikembalikan'] ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <i data-lucide="refresh-cw" class="w-4 h-4 text-white/80 mr-1"></i>
                                        <span class="text-xs text-white/80">Perlu revisi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Disetujui -->
                        <div class="stats-card rounded-2xl p-6 border border-gray-200 card-disetujui">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-7 h-7 text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/90">Disetujui</p>
                                    <p class="text-2xl font-bold text-white mt-1">
                                        {{ number_format($stats['disetujui'] ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <i data-lucide="trending-up" class="w-4 h-4 text-white/80 mr-1"></i>
                                        <span class="text-xs text-white/80">Berhasil diverifikasi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="mb-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-2 sm:mb-0">Grafik Penerimaan Material</h2>
                                <div class="flex space-x-2 bg-gray-100 p-1 rounded-lg">
                                    <button id="filterBulanan" class="filter-btn px-4 py-2 rounded-md text-sm font-medium active" 
                                            onclick="changeChartFilter('bulanan')">
                                        Bulan Ini
                                    </button>
                                    <button id="filterTahunan" class="filter-btn px-4 py-2 rounded-md text-sm font-medium" 
                                            onclick="changeChartFilter('tahunan')">
                                        Tahun Ini
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="penerimaanChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="mt-8">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Penerimaan Terbaru</h2>
                                        <p class="text-gray-600 text-sm mt-1">5 transaksi penerimaan terbaru</p>
                                    </div>
                                    @php
                                        // Helper untuk cek route
                                        function route_exists($name) {
                                            try {
                                                route($name);
                                                return true;
                                            } catch (\Exception $e) {
                                                return false;
                                            }
                                        }
                                    @endphp
                                    
                                    @if(route_exists('petugas.penerimaan.index'))
                                    <a href="{{ route('petugas.penerimaan.index') }}" 
                                       class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                                        <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                                        Lihat Semua Penerimaan
                                    </a>
                                    @else
                                    <a href="{{ url('/petugas/penerimaan') }}" 
                                       class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                                        <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                                        Lihat Semua Penerimaan
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kode Transaksi
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Penerima
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Keperluan
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah Material
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($transaksiTerbaru as $transaksi)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                                    {{ $transaksi->formatted_date ?? $transaksi->tanggal ?? now()->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaksi->kode_transaksi ?? 'TRX-' . ($transaksi->id ?? '001') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaksi->penerima ?? $transaksi->nama_pihak_transaksi ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                                {{ $transaksi->keperluan ?? 'Tidak ada keterangan' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaksi->jumlah_material ?? 0 }} item
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                        'disetujui' => 'bg-green-100 text-green-800',
                                                        'dikembalikan' => 'bg-red-100 text-red-800',
                                                        'diproses' => 'bg-blue-100 text-blue-800',
                                                        'selesai' => 'bg-purple-100 text-purple-800',
                                                        'batal' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $statusClass = $statusColors[$transaksi->status ?? 'menunggu'] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ ucfirst($transaksi->status ?? 'menunggu') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i data-lucide="package-x" class="w-12 h-12 text-gray-400 mb-3"></i>
                                                    <p class="text-gray-600">Belum ada data penerimaan</p>
                                                    <p class="text-sm text-gray-500 mt-1">Silakan buat penerimaan pertama Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Initialize Lucide Icons
            lucide.createIcons();
            
            // Chart Variables
            let penerimaanChart = null;
            let currentChartFilter = 'bulanan';
            
            // Chart Data from PHP
            const chartData = @json($chartData ?? [
                'bulanan' => [],
                'tahunan' => []
            ]);
            
            // Initialize Chart
            function initChart(filter = 'bulanan') {
                const ctx = document.getElementById('penerimaanChart');
                if (!ctx) return;
                
                if (penerimaanChart) {
                    penerimaanChart.destroy();
                }
                
                let labels, data, label, colors;
                
                if (filter === 'tahunan') {
                    // Data tahunan
                    labels = chartData.tahunan.map(item => item.bulan);
                    data = chartData.tahunan.map(item => item.total || item.jumlah || 0);
                    label = 'Penerimaan Tahunan (Rp)';
                    colors = {
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgb(59, 130, 246)',
                        pointColor: 'rgb(59, 130, 246)'
                    };
                } else {
                    // Data bulanan (default)
                    labels = chartData.bulanan.map(item => item.hari || item.tanggal);
                    data = chartData.bulanan.map(item => item.total || item.jumlah || 0);
                    label = 'Penerimaan Bulan Ini (Rp)';
                    colors = {
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgb(16, 185, 129)',
                        pointColor: 'rgb(16, 185, 129)'
                    };
                }
                
                // Jika data kosong, buat data dummy
                if (data.length === 0) {
                    if (filter === 'tahunan') {
                        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        data = [15000000, 18000000, 22000000, 25000000, 28000000, 30000000, 
                                32000000, 35000000, 38000000, 40000000, 42000000, 45000000];
                    } else {
                        // Data bulanan (30 hari)
                        const today = new Date();
                        labels = [];
                        data = [];
                        for (let i = 29; i >= 0; i--) {
                            const date = new Date();
                            date.setDate(today.getDate() - i);
                            labels.push(date.getDate());
                            data.push(Math.floor(Math.random() * 5000000) + 1000000);
                        }
                    }
                }
                
                penerimaanChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: colors.backgroundColor,
                            borderColor: colors.borderColor,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.pointColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return `Rp ${context.raw.toLocaleString('id-ID')}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(1) + 'K';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Change Chart Filter
            function changeChartFilter(filter) {
                currentChartFilter = filter;
                
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.getElementById(`filter${filter.charAt(0).toUpperCase() + filter.slice(1)}`).classList.add('active');
                
                // Update chart
                initChart(filter);
            }
            
            // Refresh Dashboard
            function refreshDashboard() {
                location.reload();
            }
            
            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                initChart('bulanan');
                
                // Add loading animation to stats cards on page load
                const statsCards = document.querySelectorAll('.stats-card');
                statsCards.forEach(card => {
                    card.classList.add('loading');
                    setTimeout(() => {
                        card.classList.remove('loading');
                    }, 1000);
                });
            });
            
            // Handle window resize for chart responsiveness
            window.addEventListener('resize', function() {
                if (penerimaanChart) {
                    penerimaanChart.resize();
                }
            });
        </script>
    </x-app-layout>
</body>
</html>