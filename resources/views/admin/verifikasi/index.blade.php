<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Verifikasi Transaksi</h1>
                <p class="text-sm text-gray-600 mt-1">Verifikasi transaksi masuk dan keluar material</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button id="tabPenerimaan" class="verifikasi-tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                            Penerimaan
                        </button>
                        <button id="tabPengeluaran" class="verifikasi-tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Pengeluaran
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Filter Section with Search -->
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari transaksi...</label>
                        <input type="text" 
                               placeholder="Masukkan nama penerima" 
                               class="verifikasi-form-input search-input"
                               id="searchInput">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" class="verifikasi-form-input" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                        <select class="verifikasi-form-input">
                            <option>Semua Keperluan</option>
                            <option>YANBUNG</option>
                            <option>P2TL</option>
                            <option>GANGGUAN</option>
                            <option>PLN</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Penerimaan Table -->
            <div id="penerimaanContent" class="verifikasi-tab-content active">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Verifikasi Transaksi Penerimaan</h3>
                                <p class="text-sm text-gray-600">Transaksi masuk material yang perlu diverifikasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">NO</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA PENERIMA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KEPERLUAN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Baris 1 -->
                                <tr class="hover:bg-blue-50/30 transition-colors duration-150 group search-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">15/01/2024</div>
                                        <div class="text-xs text-gray-500">10:30 WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Ahmad Santoso</div>
                                            <div class="text-xs text-gray-500">Petugas</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                YANBUNG
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.verifikasi.detail.page', ['type' => 'penerimaan', 'id' => 1]) }}" 
                                           class="verifikasi-btn-action verifikasi-btn-detail group-hover:scale-105 transition-transform">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                <!-- Baris 2 -->
                                <tr class="hover:bg-blue-50/30 transition-colors duration-150 group search-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">16/01/2024</div>
                                        <div class="text-xs text-gray-500">14:15 WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Budi Pratama</div>
                                            <div class="text-xs text-gray-500">Petugas YANBUNG</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                P2TL
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.verifikasi.detail.page', ['type' => 'penerimaan', 'id' => 2]) }}" 
                                           class="verifikasi-btn-action verifikasi-btn-detail group-hover:scale-105 transition-transform">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">5</span> dari <span class="font-semibold">15</span> data penerimaan
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Terakhir update: {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Table -->
            <div id="pengeluaranContent" class="verifikasi-tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Verifikasi Transaksi Pengeluaran</h3>
                                <p class="text-sm text-gray-600">Transaksi keluar material yang perlu diverifikasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">NO</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA PENERIMA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KEPERLUAN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Baris 1 -->
                                <tr class="hover:bg-green-50/30 transition-colors duration-150 group search-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">18/01/2024</div>
                                        <div class="text-xs text-gray-500">09:45 WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Siti Rahmawati</div>
                                            <div class="text-xs text-gray-500">Petugas</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                GANGGUAN
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.verifikasi.detail.page', ['type' => 'pengeluaran', 'id' => 1]) }}" 
                                           class="verifikasi-btn-action verifikasi-btn-detail group-hover:scale-105 transition-transform">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                <!-- Baris 2 -->
                                <tr class="hover:bg-green-50/30 transition-colors duration-150 group search-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">25/01/2024</div>
                                        <div class="text-xs text-gray-500">13:20 WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Budi Hartono</div>
                                            <div class="text-xs text-gray-500">Petugas YANBUNG</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                PLN
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.verifikasi.detail.page', ['type' => 'pengeluaran', 'id' => 2]) }}" 
                                           class="verifikasi-btn-action verifikasi-btn-detail group-hover:scale-105 transition-transform">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">5</span> dari <span class="font-semibold">8</span> data pengeluaran
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Terakhir update: {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Container (hidden by default) -->
    <div id="modalContainer"></div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verifikasi.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/verifikasi.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Update event listeners untuk tombol tab
                const tabPenerimaan = document.getElementById('tabPenerimaan');
                const tabPengeluaran = document.getElementById('tabPengeluaran');
                const penerimaanContent = document.getElementById('penerimaanContent');
                const pengeluaranContent = document.getElementById('pengeluaranContent');
                
                if (tabPenerimaan && tabPengeluaran && penerimaanContent && pengeluaranContent) {
                    tabPenerimaan.addEventListener('click', (e) => {
                        e.preventDefault();
                        
                        tabPenerimaan.classList.add('active', 'border-blue-500', 'text-blue-600');
                        tabPenerimaan.classList.remove('border-transparent', 'text-gray-500');
                        
                        tabPengeluaran.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        tabPengeluaran.classList.add('border-transparent', 'text-gray-500');
                        
                        penerimaanContent.classList.remove('hidden');
                        penerimaanContent.classList.add('active');
                        
                        pengeluaranContent.classList.add('hidden');
                        pengeluaranContent.classList.remove('active');
                    });
                    
                    tabPengeluaran.addEventListener('click', (e) => {
                        e.preventDefault();
                        
                        tabPengeluaran.classList.add('active', 'border-blue-500', 'text-blue-600');
                        tabPengeluaran.classList.remove('border-transparent', 'text-gray-500');
                        
                        tabPenerimaan.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        tabPenerimaan.classList.add('border-transparent', 'text-gray-500');
                        
                        pengeluaranContent.classList.remove('hidden');
                        pengeluaranContent.classList.add('active');
                        
                        penerimaanContent.classList.add('hidden');
                        penerimaanContent.classList.remove('active');
                    });
                }
                
                // Update event listener untuk search
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const searchTerm = e.target.value.toLowerCase().trim();
                        const activeTab = document.querySelector('.verifikasi-tab-content.active');
                        
                        if (!activeTab) return;

                        const rows = activeTab.querySelectorAll('tbody tr');
                        
                        if (searchTerm === '') {
                            rows.forEach(row => row.style.display = '');
                            return;
                        }

                        rows.forEach(row => {
                            const nama = row.querySelector('td:nth-child(3) .text-sm.font-medium')?.textContent.toLowerCase() || '';
                            
                            if (nama.includes(searchTerm)) {
                                row.style.display = '';
                                row.classList.add('bg-yellow-50');
                                
                                setTimeout(() => {
                                    row.classList.remove('bg-yellow-50');
                                }, 2000);
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                }
            });
            
            // Fungsi untuk notification
            function showNotification(message, type = 'info') {
                const existingNotifications = document.querySelectorAll('.verifikasi-notification');
                existingNotifications.forEach(notification => notification.remove());
                
                const colors = {
                    success: 'bg-gradient-to-r from-green-500 to-green-600',
                    error: 'bg-gradient-to-r from-red-500 to-red-600',
                    info: 'bg-gradient-to-r from-blue-500 to-blue-600',
                    warning: 'bg-gradient-to-r from-yellow-500 to-yellow-600'
                };
                
                const icons = {
                    success: '✅',
                    error: '❌',
                    info: 'ℹ️',
                    warning: '⚠️'
                };
                
                const notification = document.createElement('div');
                notification.className = `verifikasi-notification fixed top-4 right-4 ${colors[type]} text-white p-4 rounded-lg shadow-xl z-50 transform translate-x-full transition-transform duration-300`;
                notification.style.maxWidth = '400px';
                
                notification.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0 text-lg">
                            ${icons[type]}
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="ml-4 flex-shrink-0 text-white hover:text-gray-200 transition-colors text-lg">
                            ×
                        </button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => notification.remove(), 300);
                    }
                }, 5000);
            }
            
            // Update last update time every minute
            setInterval(() => {
                const updateElements = document.querySelectorAll('.text-gray-500');
                const now = new Date();
                const formattedTime = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                updateElements.forEach(element => {
                    if (element.textContent.includes('Terakhir update:')) {
                        element.textContent = `Terakhir update: ${formattedTime}`;
                    }
                });
            }, 60000);
        </script>
    @endpush
</x-app-layout>