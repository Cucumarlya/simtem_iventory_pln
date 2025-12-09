// Verifikasi Transaksi Management JavaScript - Updated untuk halaman detail

class VerifikasiManager {
    constructor() {
        this.initialized = false;
        this.searchInput = null;
        this.filterButton = null;
        this.dateFilter = null;
        this.keperluanFilter = null;
        this.init();
    }

    init() {
        if (this.initialized) return;
        
        this.initializeTabs();
        this.initializeEventListeners();
        this.initializeFilter();
        this.initializeSearch();
        this.initialized = true;
        
        console.log('VerifikasiManager initialized');
    }

    // Tab Management
    initializeTabs() {
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
    }

    // Event Listeners
    initializeEventListeners() {
        this.initializeFilterButton();
    }

    initializeFilterButton() {
        this.filterButton = document.querySelector('.verifikasi-btn-primary');
        if (this.filterButton) {
            this.filterButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.applyFilter();
            });
        }
    }

    // Initialize search functionality
    initializeSearch() {
        this.searchInput = document.querySelector('.verifikasi-form-input[type="text"]');
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.performSearch(e.target.value);
            });
        }
    }

    // Search functionality
    performSearch(searchTerm) {
        const activeTab = document.querySelector('.verifikasi-tab-content.active');
        if (!activeTab) return;

        const rows = activeTab.querySelectorAll('tbody tr');
        const lowerSearchTerm = searchTerm.toLowerCase().trim();
        
        if (lowerSearchTerm === '') {
            rows.forEach(row => row.style.display = '');
            return;
        }

        rows.forEach(row => {
            const kode = row.querySelector('td:nth-child(2) .text-sm.font-semibold')?.textContent.toLowerCase() || '';
            const nama = row.querySelector('td:nth-child(4) .text-sm.font-medium')?.textContent.toLowerCase() || '';
            
            if (kode.includes(lowerSearchTerm) || nama.includes(lowerSearchTerm)) {
                row.style.display = '';
                row.classList.add('bg-yellow-50');
                
                // Remove highlight after 2 seconds
                setTimeout(() => {
                    row.classList.remove('bg-yellow-50');
                }, 2000);
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Filter Management
    initializeFilter() {
        this.dateFilter = document.querySelector('input[type="date"]');
        this.keperluanFilter = document.querySelector('.verifikasi-form-input[type="text"] + select');
        
        if (this.dateFilter) {
            this.dateFilter.value = new Date().toISOString().split('T')[0];
        }
    }

    applyFilter() {
        const button = document.querySelector('.verifikasi-btn-primary');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<span class="verifikasi-loading mr-2"></span>Memfilter...';
        button.disabled = true;

        // Simulate API call
        setTimeout(() => {
            this.showNotification('Filter berhasil diterapkan', 'success');
            button.innerHTML = originalHTML;
            button.disabled = false;
        }, 1000);
    }

    // Modal Management - FUNGSI BARU UNTUK MODAL GAMBAR
    showImageModal(imageSrc, title) {
        // Simpan posisi scroll sebelum modal dibuka
        const scrollY = window.scrollY;
        
        // Cek apakah modal sudah ada, jika belum buat
        let modal = document.getElementById('imageModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 id="imageModalTitle" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="verifikasiManager.closeImageModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 flex justify-center">
                        <img id="modalImage" src="" alt="" class="max-h-[70vh] max-w-full object-contain">
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Tambah event listener untuk klik di luar
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeImageModal();
                }
            });
        }
        
        // Set modal content
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalTitle').textContent = title;
        modal.classList.remove('hidden');
        
        // Prevent body scroll dengan cara yang benar
        document.body.classList.add('modal-open');
        document.body.style.top = `-${scrollY}px`;
        
        console.log('Image modal opened, scrollY saved:', scrollY);
        
        // Tambah event listener untuk ESC
        const escListener = (e) => {
            if (e.key === 'Escape') {
                this.closeImageModal();
                document.removeEventListener('keydown', escListener);
            }
        };
        document.addEventListener('keydown', escListener);
        
        // Simpan listener untuk dihapus nanti
        this.currentEscListener = escListener;
    }
    
    closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (!modal) return;
        
        modal.classList.add('hidden');
        
        // Restore body scroll
        const scrollY = parseInt(document.body.style.top || '0') * -1;
        document.body.classList.remove('modal-open');
        document.body.style.top = '';
        
        // Scroll kembali ke posisi semula
        if (scrollY) {
            window.scrollTo(0, scrollY);
        }
        
        console.log('Image modal closed, scroll restored to:', scrollY);
        
        // Hapus event listener ESC
        if (this.currentEscListener) {
            document.removeEventListener('keydown', this.currentEscListener);
            this.currentEscListener = null;
        }
    }
    
    // Modal Action Management
    showSetujuiModal(transaksiId, nama, type) {
        const scrollY = window.scrollY;
        
        const modalHTML = `
            <div class="verifikasi-modal-overlay" id="setujuiModal">
                <div class="verifikasi-modal-content">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Setujui Transaksi</h3>
                                    <p class="text-sm text-gray-600 mt-1">Setujui transaksi ini?</p>
                                </div>
                            </div>
                            <button onclick="verifikasiManager.closeActionModal()" class="verifikasi-modal-close p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-center text-gray-700 mb-2">
                                Apakah Anda yakin ingin <span class="font-semibold text-green-600">menyetujui</span> transaksi ini?
                            </p>
                            <p class="text-center text-sm text-gray-500">
                                Transaksi akan divalidasi dan diproses untuk update stok.
                            </p>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <strong>Perhatian:</strong> Pastikan semua dokumen lengkap sebelum menyetujui.
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="verifikasiManager.closeActionModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button onclick="verifikasiManager.performSetujui('${transaksiId}')" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-medium hover:from-green-600 hover:to-green-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ya, Setujui Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const modalContainer = document.getElementById('modalContainer') || document.createElement('div');
        if (!modalContainer.id) {
            modalContainer.id = 'modalContainer';
            document.body.appendChild(modalContainer);
        }
        
        modalContainer.innerHTML = modalHTML;
        
        // Prevent body scroll dengan cara yang benar
        document.body.classList.add('modal-open');
        document.body.style.top = `-${scrollY}px`;
        
        // Tambah event listener untuk klik di luar modal
        setTimeout(() => {
            const modalElement = document.getElementById('setujuiModal');
            if (modalElement) {
                modalElement.addEventListener('click', (e) => {
                    if (e.target === modalElement) {
                        this.closeActionModal();
                    }
                });
            }
        }, 100);
    }
    
    showKembalikanModal(transaksiId, nama) {
        const scrollY = window.scrollY;
        
        const modalHTML = `
            <div class="verifikasi-modal-overlay" id="kembalikanModal">
                <div class="verifikasi-modal-content">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Kembalikan Transaksi</h3>
                                    <p class="text-sm text-gray-600 mt-1">Kembalikan transaksi untuk direvisi</p>
                                </div>
                            </div>
                            <button onclick="verifikasiManager.closeActionModal()" class="verifikasi-modal-close p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                            </div>
                            <p class="text-center text-gray-700 mb-2">
                                Anda akan <span class="font-semibold text-red-600">mengembalikan</span> transaksi untuk direvisi.
                            </p>
                            <p class="text-center text-sm text-gray-500">
                                Transaksi akan dikembalikan ke pembuat dengan alasan yang Anda berikan.
                            </p>
                        </div>
                        
                        <form id="kembalikanForm" onsubmit="verifikasiManager.handleKembalikanSubmit(event, '${transaksiId}')">
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="text-red-600">*</span> Alasan Pengembalian
                                </label>
                                <textarea 
                                    id="alasanPengembalian" 
                                    name="alasan"
                                    rows="4" 
                                    class="verifikasi-form-input" 
                                    placeholder="Berikan alasan yang jelas dan spesifik mengapa transaksi ini dikembalikan..."
                                    required
                                ></textarea>
                                <p class="text-xs text-gray-500 mt-2">
                                    Alasan yang jelas akan membantu penerima untuk memperbaiki transaksi dengan tepat.
                                </p>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div class="text-sm text-yellow-800">
                                        <strong>Perhatian:</strong> Transaksi yang dikembalikan akan muncul kembali di daftar penerima untuk diperbaiki.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="verifikasiManager.closeActionModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="submit-kembalikan-btn verifikasi-btn-action verifikasi-btn-kembalikan">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Kembalikan Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
        
        const modalContainer = document.getElementById('modalContainer') || document.createElement('div');
        if (!modalContainer.id) {
            modalContainer.id = 'modalContainer';
            document.body.appendChild(modalContainer);
        }
        
        modalContainer.innerHTML = modalHTML;
        
        // Prevent body scroll dengan cara yang benar
        document.body.classList.add('modal-open');
        document.body.style.top = `-${scrollY}px`;
        
        // Tambah event listener untuk klik di luar modal
        setTimeout(() => {
            const modalElement = document.getElementById('kembalikanModal');
            if (modalElement) {
                modalElement.addEventListener('click', (e) => {
                    if (e.target === modalElement) {
                        this.closeActionModal();
                    }
                });
            }
        }, 100);
    }
    
    closeActionModal() {
        const modalContainer = document.getElementById('modalContainer');
        if (modalContainer) {
            modalContainer.innerHTML = '';
        }
        
        // Restore body scroll
        const scrollY = parseInt(document.body.style.top || '0') * -1;
        document.body.classList.remove('modal-open');
        document.body.style.top = '';
        
        // Scroll kembali ke posisi semula
        if (scrollY) {
            window.scrollTo(0, scrollY);
        }
        
        console.log('Action modal closed, scroll restored to:', scrollY);
    }
    
    handleKembalikanSubmit(e, transaksiId) {
        e.preventDefault();
        const alasan = document.getElementById('alasanPengembalian')?.value;
        
        if (!alasan || alasan.trim() === '') {
            alert('Harap isi alasan pengembalian');
            return false;
        }
        
        this.performKembalikan(transaksiId, alasan);
        return false;
    }
    
    async performSetujui(transaksiId) {
        try {
            const response = await fetch(`/admin/verifikasi/${transaksiId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'disetujui'
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                alert('Transaksi berhasil disetujui!');
                window.location.href = document.querySelector('meta[name="verifikasi-index-url"]')?.getAttribute('content') || '/admin/verifikasi';
            } else {
                alert(data.error || 'Terjadi kesalahan saat menyetujui transaksi');
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menyetujui transaksi');
        }
    }
    
    async performKembalikan(transaksiId, alasan) {
        try {
            const response = await fetch(`/admin/verifikasi/${transaksiId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'dikembalikan',
                    alasan_pengembalian: alasan
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                alert('Transaksi berhasil dikembalikan!');
                window.location.href = document.querySelector('meta[name="verifikasi-index-url"]')?.getAttribute('content') || '/admin/verifikasi';
            } else {
                alert(data.error || 'Terjadi kesalahan saat mengembalikan transaksi');
            }
        } catch (error) {
            alert('Terjadi kesalahan saat mengembalikan transaksi');
        }
    }

    showNotification(message, type = 'info') {
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
    
    // Scroll Prevention Management
    initializeScrollPrevention() {
        // Event listener untuk mencegah scroll di background saat modal terbuka
        document.addEventListener('wheel', (e) => {
            if (document.body.classList.contains('modal-open')) {
                // Jika sedang di dalam modal gambar, biarkan scroll di modal
                const imageModal = document.getElementById('imageModal');
                if (imageModal && !imageModal.classList.contains('hidden')) {
                    // Cek jika scroll terjadi di dalam modal gambar
                    if (e.target.closest('#imageModal .bg-white')) {
                        return; // Biarkan scroll di dalam modal
                    }
                }
                
                // Jika sedang di dalam modal aksi, biarkan scroll di modal
                const modalContainer = document.getElementById('modalContainer');
                if (modalContainer && modalContainer.innerHTML !== '') {
                    // Cek jika scroll terjadi di dalam modal aksi
                    if (e.target.closest('.verifikasi-modal-content')) {
                        return; // Biarkan scroll di dalam modal
                    }
                }
                
                // Jika bukan di dalam modal, prevent scroll
                e.preventDefault();
                return false;
            }
        }, { passive: false });
        
        // Event listener untuk touchmove (mobile)
        document.addEventListener('touchmove', (e) => {
            if (document.body.classList.contains('modal-open')) {
                // Jika sedang di dalam modal gambar, biarkan scroll di modal
                const imageModal = document.getElementById('imageModal');
                if (imageModal && !imageModal.classList.contains('hidden')) {
                    // Cek jika touch terjadi di dalam modal gambar
                    if (e.target.closest('#imageModal .bg-white')) {
                        return; // Biarkan touch di dalam modal
                    }
                }
                
                // Jika sedang di dalam modal aksi, biarkan scroll di modal
                const modalContainer = document.getElementById('modalContainer');
                if (modalContainer && modalContainer.innerHTML !== '') {
                    // Cek jika touch terjadi di dalam modal aksi
                    if (e.target.closest('.verifikasi-modal-content')) {
                        return; // Biarkan touch di dalam modal
                    }
                }
                
                // Jika bukan di dalam modal, prevent scroll
                e.preventDefault();
                return false;
            }
        }, { passive: false });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('[class*="verifikasi"]')) {
        window.verifikasiManager = new VerifikasiManager();
        window.verifikasiManager.initializeScrollPrevention();
        
        // Setup global functions untuk dipanggil dari Blade
        window.showImageModal = function(imageSrc, title) {
            if (window.verifikasiManager) {
                window.verifikasiManager.showImageModal(imageSrc, title);
            }
        };
        
        window.closeImageModal = function() {
            if (window.verifikasiManager) {
                window.verifikasiManager.closeImageModal();
            }
        };
        
        window.confirmSetujui = function() {
            const transaksiId = document.querySelector('[name="transaksi_id"]')?.value || 
                               document.querySelector('[data-transaksi-id]')?.dataset.transaksiId ||
                               '1';
            const nama = document.querySelector('[name="transaksi_nama"]')?.value || 'User';
            const type = document.querySelector('[name="transaksi_type"]')?.value || 'penerimaan';
            
            if (window.verifikasiManager) {
                window.verifikasiManager.showSetujuiModal(transaksiId, nama, type);
            }
        };
        
        window.showKembalikanModal = function() {
            const transaksiId = document.querySelector('[name="transaksi_id"]')?.value || 
                               document.querySelector('[data-transaksi-id]')?.dataset.transaksiId ||
                               '1';
            const nama = document.querySelector('[name="transaksi_nama"]')?.value || 'User';
            
            if (window.verifikasiManager) {
                window.verifikasiManager.showKembalikanModal(transaksiId, nama);
            }
        };
        
        window.closeActionModal = function() {
            if (window.verifikasiManager) {
                window.verifikasiManager.closeActionModal();
            }
        };
        
        window.performSetujui = function(transaksiId) {
            if (window.verifikasiManager) {
                window.verifikasiManager.performSetujui(transaksiId);
            }
        };
        
        window.performKembalikan = function(transaksiId, alasan) {
            if (window.verifikasiManager) {
                window.verifikasiManager.performKembalikan(transaksiId, alasan);
            }
        };
        
        window.handleKembalikanSubmit = function(e, transaksiId) {
            if (window.verifikasiManager) {
                window.verifikasiManager.handleKembalikanSubmit(e, transaksiId);
            }
        };
        
        console.log('VerifikasiManager loaded with modal functions');
    }
});

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

// Global helper function untuk notification
function showNotification(message, type = 'info') {
    if (window.verifikasiManager) {
        window.verifikasiManager.showNotification(message, type);
    } else {
        // Fallback simple notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}