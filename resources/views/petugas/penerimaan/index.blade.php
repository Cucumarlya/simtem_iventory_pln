<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-inbox mr-2"></i>Daftar Penerimaan Material
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Kelola semua transaksi penerimaan material Anda
                </p>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar-alt mr-1"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            @if(session('success'))
                <div id="successNotification" class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- HEADER CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Baris 1: Judul -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Daftar Penerimaan Material</h1>
                        <p class="text-gray-600 mt-1">Kelola semua transaksi penerimaan material yang Anda buat</p>
                    </div>

                    <!-- Baris 2: Search, Status Filter, dan Tambah Button -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <!-- Bagian Kiri: Search dan Status Filter -->
                        <div class="flex flex-col sm:flex-row gap-3 flex-grow">
                            <!-- Search Bar -->
                            <form method="GET" action="{{ route('petugas.penerimaan.index') }}" class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="search"
                                    id="search" 
                                    placeholder="Cari kode transaksi, nama penerima..." 
                                    class="pl-10 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 text-sm"
                                    value="{{ request('search') }}"
                                    aria-label="Cari penerimaan"
                                >
                                @if(request('search'))
                                    <a href="{{ route('petugas.penerimaan.index') }}" 
                                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                       title="Hapus pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                            
                            <!-- Status Filter -->
                            <div class="relative">
                                <form method="GET" action="{{ route('petugas.penerimaan.index') }}" id="statusForm">
                                    <select 
                                        name="status"
                                        id="status-filter"
                                        class="block w-full sm:w-48 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5 text-sm appearance-none bg-white"
                                        aria-label="Filter status"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Bagian Kanan: Tombol TAMBAH PENERIMAAN -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('petugas.penerimaan.create') }}" 
                               class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-md hover:shadow-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-plus mr-2"></i> TAMBAH PENERIMAAN
                            </a>
                        </div>
                    </div>

                    <!-- Baris 3: Info Total dan Reset Filter -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <p class="text-sm text-gray-600">
                                Total <span id="total-data" class="font-bold text-gray-800">{{ $penerimaan->total() }}</span> penerimaan material
                                @if(request('search') || request('status'))
                                    <span class="text-gray-500">(Hasil filter)</span>
                                @endif
                            </p>
                            
                            @if(request('search') || request('status'))
                                <a href="{{ route('petugas.penerimaan.index') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm transition duration-200">
                                    <i class="fas fa-redo mr-1.5"></i> Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    @if($penerimaan->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NO
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            KODE TRANSAKSI
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            TANGGAL
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NAMA PENERIMA
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            KEPERLUAN
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            STATUS
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            AKSI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($penerimaan as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium text-center">
                                                {{ ($penerimaan->currentPage() - 1) * $penerimaan->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-barcode text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div class="text-sm font-medium text-blue-700">
                                                        {{ $item->kode_transaksi }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->nama_pihak_transaksi }}
                                                </div>
                                                @if($item->user)
                                                    <div class="text-xs text-gray-500">
                                                        oleh {{ $item->user->name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $keperluanColors = [
                                                        'YANBUNG' => 'bg-purple-100 text-purple-800',
                                                        'P2TL' => 'bg-indigo-100 text-indigo-800',
                                                        'GANGGUAN' => 'bg-amber-100 text-amber-800',
                                                        'PLN' => 'bg-blue-100 text-blue-800'
                                                    ];
                                                    $keperluanColor = $keperluanColors[$item->keperluan] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $keperluanColor }}">
                                                    {{ $item->keperluan }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = '';
                                                    $statusIcon = '';
                                                    switch($item->status) {
                                                        case 'menunggu':
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            $statusIcon = 'fas fa-clock';
                                                            break;
                                                        case 'dikembalikan':
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusIcon = 'fas fa-times-circle';
                                                            break;
                                                        case 'disetujui':
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusIcon = 'fas fa-check-circle';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gray-100 text-gray-800';
                                                            $statusIcon = 'fas fa-info-circle';
                                                    }
                                                @endphp
                                                <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    <i class="{{ $statusIcon }} mr-1.5"></i>
                                                    @switch($item->status)
                                                        @case('menunggu')
                                                            MENUNGGU
                                                            @break
                                                        @case('dikembalikan')
                                                            DIKEMBALIKAN
                                                            @break
                                                        @case('disetujui')
                                                            DISETUJUI
                                                            @break
                                                        @default
                                                            {{ strtoupper($item->status) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Tombol Detail -->
                                                    <a href="{{ route('petugas.penerimaan.show', $item->id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                                       title="Lihat detail">
                                                        <i class="fas fa-eye mr-1.5"></i> Detail
                                                    </a>
                                                    
                                                    <!-- Tombol Edit (hanya untuk status dikembalikan) -->
                                                    @if($item->status === 'dikembalikan')
                                                        <a href="{{ route('petugas.penerimaan.edit', $item->id) }}" 
                                                           class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1"
                                                           title="Edit penerimaan">
                                                            <i class="fas fa-edit mr-1.5"></i> Edit
                                                        </a>
                                                        
                                                        <!-- Tombol Hapus (hanya untuk status dikembalikan) -->
                                                        <button type="button" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 delete-btn"
                                                                data-id="{{ $item->id }}"
                                                                data-kode="{{ $item->kode_transaksi }}"
                                                                title="Hapus penerimaan">
                                                            <i class="fas fa-trash mr-1.5"></i> Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($penerimaan->hasPages())
                            <div class="mt-6">
                                {{ $penerimaan->withQueryString()->links() }}
                            </div>
                        @endif
                        
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-inbox text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
                            <p class="text-gray-500 max-w-md mx-auto mb-6">
                                @if(request()->has('search') || request()->has('status'))
                                    Tidak ada penerimaan yang sesuai dengan pencarian atau filter yang Anda terapkan.
                                @else
                                    Belum ada data penerimaan material. Mulai dengan membuat penerimaan baru.
                                @endif
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                @if(request()->has('search') || request()->has('status'))
                                    <a href="{{ route('petugas.penerimaan.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-redo mr-2"></i> Reset Filter
                                    </a>
                                @endif
                                <a href="{{ route('petugas.penerimaan.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-plus mr-2"></i> Buat Penerimaan Baru
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-message">
                                    Apakah Anda yakin ingin menghapus penerimaan ini?
                                </p>
                                <p class="text-xs text-gray-400 mt-1" id="modal-code">
                                    <!-- Kode transaksi akan diisi oleh JavaScript -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                    <button type="button" 
                            onclick="closeModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Custom styles for better UX */
        .material-row:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Smooth transitions */
        .btn-action {
            transition: all 0.2s ease;
        }
        
        /* Better focus styles */
        input:focus, select:focus, button:focus {
            outline: none;
            ring-width: 2px;
        }
        
        /* Responsive table */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                min-width: 640px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Animation for modal */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        
        .slide-up {
            animation: slideUp 0.3s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Fungsi untuk menampilkan modal hapus
        function showDeleteModal(id, kode) {
            const modal = document.getElementById('deleteModal');
            const modalMessage = document.getElementById('modal-message');
            const modalCode = document.getElementById('modal-code');
            const deleteForm = document.getElementById('deleteForm');
            
            modalMessage.textContent = `Apakah Anda yakin ingin menghapus penerimaan ini?`;
            modalCode.textContent = `Kode: ${kode}`;
            deleteForm.action = `/petugas/penerimaan/${id}`;
            
            modal.classList.remove('hidden');
            modal.classList.add('fade-in');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
        
        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('fade-in');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
        
        // Setup halaman saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Setup tombol hapus
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const id = this.getAttribute('data-id');
                    const kode = this.getAttribute('data-kode');
                    showDeleteModal(id, kode);
                });
            });
            
            // Close modal ketika klik di luar
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
            
            // Close modal dengan ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
            
            // Live search dengan debounce
            const searchInput = document.getElementById('search');
            if (searchInput) {
                const searchForm = searchInput.closest('form');
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }
            
            // Auto submit status filter
            const statusFilter = document.getElementById('status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    this.form.submit();
                });
            }
            
            // Auto-hide success notification setelah 5 detik
            const successNotification = document.getElementById('successNotification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.style.opacity = '0';
                    successNotification.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (successNotification.parentNode) {
                            successNotification.parentNode.removeChild(successNotification);
                        }
                    }, 500);
                }, 5000);
            }
            
            // Tambahkan animasi untuk baris tabel
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            // Export functionality jika diperlukan
            const exportButtons = document.querySelectorAll('[data-export]');
            exportButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const format = this.getAttribute('data-export');
                    exportData(format);
                });
            });
            
            // Print functionality
            const printButtons = document.querySelectorAll('[data-print]');
            printButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-print');
                    printData(id);
                });
            });
        });
        
        // Fungsi untuk export data (bisa dikembangkan)
        function exportData(format) {
            // Implementasi export data
            alert(`Export data dalam format ${format} akan segera diimplementasikan.`);
        }
        
        // Fungsi untuk print data
        function printData(id) {
            window.open(`/petugas/penerimaan/${id}/print`, '_blank');
        }
        
        // Fungsi untuk filter cepat berdasarkan status
        function filterByStatus(status) {
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        }
        
        // Fungsi untuk reset semua filter
        function resetFilters() {
            window.location.href = "{{ route('petugas.penerimaan.index') }}";
        }
        
        // Export fungsi ke window object untuk akses global
        window.showDeleteModal = showDeleteModal;
        window.closeModal = closeModal;
        window.filterByStatus = filterByStatus;
        window.resetFilters = resetFilters;
        window.exportData = exportData;
        window.printData = printData;
    </script>
    @endpush
</x-app-layout>