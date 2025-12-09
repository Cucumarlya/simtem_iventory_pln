<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-gray-600 text-xs sm:text-sm mt-1">Sistem Informasi Pengelolaan Material Sarana</p>
            </div>
            <div class="text-gray-500 text-xs sm:text-sm">
                <i data-feather="calendar" class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <!-- Statistics Cards - DIBESARKAN SEDIKIT -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Total Material -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-3.5 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] sm:text-xs font-bold uppercase opacity-90 mb-1.5 sm:mb-2.5">TOTAL MATERIAL</p>
                            <h3 class="text-xl sm:text-3xl font-bold">127</h3>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg bg-blue-400/20 flex items-center justify-center ml-2">
                            <i data-feather="package" class="w-4.5 h-4.5 sm:w-6 sm:h-6"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl p-3.5 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] sm:text-xs font-bold uppercase opacity-90 mb-1.5 sm:mb-2.5">TRANSAKSI BULAN INI</p>
                            <h3 class="text-xl sm:text-3xl font-bold">84</h3>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg bg-emerald-400/20 flex items-center justify-center ml-2">
                            <i data-feather="shopping-cart" class="w-4.5 h-4.5 sm:w-6 sm:h-6"></i>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Verifikasi -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-3.5 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 relative">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] sm:text-xs font-bold uppercase opacity-90 mb-1.5 sm:mb-2.5">MENUNGGU VERIFIKASI</p>
                            <h3 class="text-xl sm:text-3xl font-bold">8</h3>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg bg-amber-400/20 flex items-center justify-center ml-2">
                            <i data-feather="clock" class="w-4.5 h-4.5 sm:w-6 sm:h-6"></i>
                        </div>
                    </div>
                    <div class="absolute top-2.5 right-2.5 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">
                        8
                    </div>
                </div>

                <!-- Stok Rendah -->
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-xl p-3.5 sm:p-5 shadow-sm hover:shadow-md transition-shadow duration-200 relative">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] sm:text-xs font-bold uppercase opacity-90 mb-1.5 sm:mb-2.5">STOK RENDAH</p>
                            <h3 class="text-xl sm:text-3xl font-bold">6</h3>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg bg-rose-400/20 flex items-center justify-center ml-2">
                            <i data-feather="alert-triangle" class="w-4.5 h-4.5 sm:w-6 sm:h-6"></i>
                        </div>
                    </div>
                    <div class="absolute top-2.5 right-2.5 bg-red-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">
                        6
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
                <!-- Chart Section - GRAFIK DIPANJANGKAN SAMA DENGAN AKSI CEPAT -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-200 h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 sm:mb-4 gap-2">
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Statistik Transaksi</h3>
                                <p class="text-gray-500 text-xs sm:text-sm">Januari 2024</p>
                            </div>
                            <div class="flex space-x-1 bg-gray-100 rounded-lg p-1">
                                <button id="btnBulanIni" class="px-3 py-1 text-xs bg-white text-blue-600 rounded shadow-sm font-medium">
                                    Bulan Ini
                                </button>
                                <button id="btnTahunIni" class="px-3 py-1 text-xs text-gray-600 rounded font-medium hover:bg-gray-50">
                                    Tahun Ini
                                </button>
                            </div>
                        </div>
                        <!-- GRAFIK DIPANJANGKAN -->
                        <div class="h-56 sm:h-64 md:h-72">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions - TINGGI SAMA DENGAN GRAFIK -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-200 h-full">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Aksi Cepat</h3>
                        
                        <div class="space-y-2.5 h-[calc(100%-3.5rem)] overflow-y-auto pr-1">
                            <!-- Verifikasi Transaksi -->
                            <a href="{{ route('admin.verifikasi.index') }}" class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors duration-200 mb-1.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-feather="check-circle" class="w-4.5 h-4.5 text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">Verifikasi Transaksi</p>
                                        <p class="text-gray-600 text-xs truncate">8 transaksi menunggu</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="bg-blue-500 text-white text-xs font-bold px-2.5 py-1 rounded">8</span>
                                    <i data-feather="chevron-right" class="w-4 h-4 text-blue-400"></i>
                                </div>
                            </a>

                            <!-- Cek Stok Rendah -->
                            <a href="{{ route('stok-material.index') }}" class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors duration-200 mb-1.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-feather="alert-triangle" class="w-4.5 h-4.5 text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">Cek Stok Rendah</p>
                                        <p class="text-gray-600 text-xs truncate">6 material perlu perhatian</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded">6</span>
                                    <i data-feather="chevron-right" class="w-4 h-4 text-red-400"></i>
                                </div>
                            </a>

                            <!-- Kelola Material -->
                            <a href="{{ route('material.index') }}" class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-200 mb-1.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-feather="package" class="w-4.5 h-4.5 text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">Kelola Material</p>
                                        <p class="text-gray-600 text-xs truncate">Kelola 127 material</p>
                                    </div>
                                </div>
                                <div class="flex items-center flex-shrink-0">
                                    <i data-feather="chevron-right" class="w-4 h-4 text-green-400"></i>
                                </div>
                            </a>

                            <!-- Kelola Pengguna -->
                            <a href="{{ route('users.index') }}" class="flex items-center justify-between p-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-feather="users" class="w-4.5 h-4.5 text-white"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">Kelola Pengguna</p>
                                        <p class="text-gray-600 text-xs truncate">Kelola akses pengguna</p>
                                    </div>
                                </div>
                                <div class="flex items-center flex-shrink-0">
                                    <i data-feather="chevron-right" class="w-4 h-4 text-purple-400"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="lg:col-span-3 mt-4 sm:mt-5">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 border-b border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                                <a href="{{ route('admin.verifikasi.index') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium flex items-center">
                                    Lihat semua
                                    <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 sm:px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th class="px-4 sm:px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 sm:px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-4 sm:px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 sm:px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @php
                                        $recentActivities = [
                                            ['code' => 'TRX/001', 'date' => '2024-01-15', 'type' => 'Pengeluaran', 'status' => 'Menunggu', 'officer' => 'Ahmad R'],
                                            ['code' => 'TRX/002', 'date' => '2024-01-15', 'type' => 'Penerimaan', 'status' => 'Disetujui', 'officer' => 'Siti M'],
                                            ['code' => 'TRX/003', 'date' => '2024-01-14', 'type' => 'Pengeluaran', 'status' => 'Dikembalikan', 'officer' => 'Budi K'],
                                            ['code' => 'TRX/004', 'date' => '2024-01-14', 'type' => 'Penerimaan', 'status' => 'Disetujui', 'officer' => 'Dewi S'],
                                            ['code' => 'TRX/005', 'date' => '2024-01-13', 'type' => 'Pengeluaran', 'status' => 'Menunggu', 'officer' => 'Rudi H'],
                                        ];
                                    @endphp
                                    
                                    @foreach($recentActivities as $activity)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                            <span class="font-mono font-semibold text-blue-600 text-xs sm:text-sm">{{ $activity['code'] }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($activity['date'])->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $activity['type'] === 'Penerimaan' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $activity['type'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                            @php
                                                $statusClass = match($activity['status']) {
                                                    'Disetujui' => 'bg-green-100 text-green-800',
                                                    'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                                    'Dikembalikan' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $activity['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-600">
                                            {{ $activity['officer'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Initialize Chart - GRAFIK PANJANG
            const ctx = document.getElementById('transactionChart');
            
            if (ctx) {
                // Data untuk Bulan Ini (Januari)
                const bulanIniData = {
                    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                    penerimaan: [45, 52, 48, 60],
                    pengeluaran: [30, 40, 35, 45]
                };

                // Data untuk Tahun Ini (Jan-Des)
                const tahunIniData = {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    penerimaan: [85, 78, 92, 88, 95, 87, 82, 90, 85, 88, 92, 95],
                    pengeluaran: [45, 52, 48, 60, 55, 58, 50, 62, 57, 60, 65, 68]
                };

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: bulanIniData.labels,
                        datasets: [
                            {
                                label: 'Penerimaan',
                                data: bulanIniData.penerimaan,
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgba(34, 197, 94, 1)',
                                borderWidth: 1,
                                borderRadius: 5,
                                barPercentage: 0.7,
                                categoryPercentage: 0.8,
                            },
                            {
                                label: 'Pengeluaran',
                                data: bulanIniData.pengeluaran,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1,
                                borderRadius: 5,
                                barPercentage: 0.7,
                                categoryPercentage: 0.8,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#374151',
                                    font: {
                                        size: window.innerWidth < 640 ? 11 : 12,
                                        weight: '600'
                                    },
                                    padding: 12,
                                    boxWidth: 10,
                                    boxHeight: 10
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                titleColor: '#1f2937',
                                bodyColor: '#1f2937',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                cornerRadius: 6,
                                displayColors: true,
                                padding: 10,
                                boxPadding: 6,
                                usePointStyle: true,
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.parsed.y} transaksi`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: window.innerWidth < 640 ? 10 : 11,
                                        weight: '500'
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: '#f3f4f6',
                                    drawBorder: false,
                                    lineWidth: 1
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: window.innerWidth < 640 ? 10 : 11,
                                        weight: '500'
                                    },
                                    maxTicksLimit: 7,
                                    padding: 8
                                },
                                beginAtZero: true,
                                suggestedMax: 70
                            }
                        }
                    }
                });

                // Chart period switcher
                const btnBulanIni = document.getElementById('btnBulanIni');
                const btnTahunIni = document.getElementById('btnTahunIni');
                
                if (btnBulanIni && btnTahunIni) {
                    // Set initial active state
                    btnBulanIni.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                    
                    btnBulanIni.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        chart.data.labels = bulanIniData.labels;
                        chart.data.datasets[0].data = bulanIniData.penerimaan;
                        chart.data.datasets[1].data = bulanIniData.pengeluaran;
                        chart.options.scales.y.suggestedMax = 70;
                        chart.update();
                        
                        btnBulanIni.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                        btnBulanIni.classList.remove('text-gray-600', 'hover:bg-gray-50');
                        btnTahunIni.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                        btnTahunIni.classList.add('text-gray-600', 'hover:bg-gray-50');
                    });
                    
                    btnTahunIni.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        chart.data.labels = tahunIniData.labels;
                        chart.data.datasets[0].data = tahunIniData.penerimaan;
                        chart.data.datasets[1].data = tahunIniData.pengeluaran;
                        chart.options.scales.y.suggestedMax = 100;
                        chart.update();
                        
                        btnTahunIni.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                        btnTahunIni.classList.remove('text-gray-600', 'hover:bg-gray-50');
                        btnBulanIni.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                        btnBulanIni.classList.add('text-gray-600', 'hover:bg-gray-50');
                    });
                }
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Fix untuk alignment aksi cepat */
        .quick-actions .flex {
            align-items: center;
        }
        
        /* Pastikan elemen sejajar */
        .min-w-0 {
            min-width: 0;
        }
        
        /* Truncate untuk text yang panjang */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Custom scrollbar untuk aksi cepat */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Fix untuk card height di mobile */
        @media (max-width: 640px) {
            .h-56 {
                height: 14rem !important;
            }
            
            .h-64 {
                height: 16rem !important;
            }
            
            .h-72 {
                height: 18rem !important;
            }
            
            /* Padding lebih kecil di mobile */
            .p-3\.5 {
                padding: 0.875rem !important;
            }
            
            /* Text lebih besar di card */
            .text-xl {
                font-size: 1.25rem !important;
            }
            
            .w-9 {
                width: 2.25rem !important;
            }
            
            .h-9 {
                height: 2.25rem !important;
            }
        }
        
        /* Desktop */
        @media (min-width: 1024px) {
            .h-72 {
                height: 19rem !important;
            }
            
            /* Pastikan tinggi grafik dan aksi cepat sama */
            .lg\:col-span-2 .bg-white,
            .lg\:col-span-1 .bg-white {
                height: 100%;
            }
        }
        
        /* Fix untuk grafik chart */
        #transactionChart {
            width: 100% !important;
        }
        
        /* Card lebih besar */
        .text-3xl {
            font-size: 1.875rem !important;
        }
        
        @media (min-width: 640px) {
            .text-3xl {
                font-size: 2.25rem !important;
            }
        }
    </style>
    @endpush
</x-app-layout>