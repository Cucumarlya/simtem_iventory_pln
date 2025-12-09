// Stok Material - Main Application
class StokMaterial {
    constructor() {
        this.currentData = [];
        this.filteredData = [];
        this.agoData = [];
        this.normalisasiData = [];
        this.pendingAction = null;
        this.pendingItemId = null;
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.loadStockData();
        this.initializeModal();
        this.initializeTabNavigation();
    }

    // Initialize all event listeners
    initializeEventListeners() {
        // Search functionality for Rekap Stok
        const searchRekap = document.getElementById('searchRekap');
        if (searchRekap) {
            searchRekap.addEventListener('input', (e) => {
                this.handleSearch(e.target.value, 'rekap');
            });
        }

        // Search functionality for Belum AGO
        const searchAgo = document.getElementById('searchAgo');
        if (searchAgo) {
            searchAgo.addEventListener('input', (e) => {
                this.handleSearch(e.target.value, 'ago');
            });
        }

        // Search functionality for Normalisasi
        const searchNormalisasi = document.getElementById('searchNormalisasi');
        if (searchNormalisasi) {
            searchNormalisasi.addEventListener('input', (e) => {
                this.handleSearch(e.target.value, 'normalisasi');
            });
        }

        // Clear search buttons
        const clearSearchRekap = document.getElementById('clearSearchRekap');
        if (clearSearchRekap) {
            clearSearchRekap.addEventListener('click', () => {
                this.clearSearch('rekap');
            });
        }

        // Confirmation modal events
        const closeConfirmationModal = document.getElementById('close-confirmation-modal');
        const cancelConfirmation = document.getElementById('btn-cancel-confirmation');
        const confirmAction = document.getElementById('btn-confirm-action');

        if (closeConfirmationModal) {
            closeConfirmationModal.addEventListener('click', () => {
                this.hideConfirmationModal();
            });
        }

        if (cancelConfirmation) {
            cancelConfirmation.addEventListener('click', () => {
                this.hideConfirmationModal();
            });
        }

        if (confirmAction) {
            confirmAction.addEventListener('click', () => {
                this.executePendingAction();
            });
        }

        // Tambah Stok Button - FIXED: removed conflicting event listener
        const tambahStokBtn = document.getElementById('tambahStokBtn');
        if (tambahStokBtn) {
            tambahStokBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.tambahStok();
            });
        }

        // Tambah Normalisasi Button - FIXED: removed conflicting event listener
        const tambahNormalisasiBtn = document.getElementById('tambahNormalisasiBtn');
        if (tambahNormalisasiBtn) {
            tambahNormalisasiBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.tambahNormalisasi();
            });
        }

        // Export buttons
        const exportPdfRekap = document.getElementById('exportPdfRekap');
        if (exportPdfRekap) {
            exportPdfRekap.addEventListener('click', () => {
                this.exportToPDF('rekap');
            });
        }

        const exportExcelRekap = document.getElementById('exportExcelRekap');
        if (exportExcelRekap) {
            exportExcelRekap.addEventListener('click', () => {
                this.exportToExcel('rekap');
            });
        }

        const exportPdfAgo = document.getElementById('exportPdfAgo');
        if (exportPdfAgo) {
            exportPdfAgo.addEventListener('click', () => {
                this.exportToPDF('ago');
            });
        }

        const exportExcelAgo = document.getElementById('exportExcelAgo');
        if (exportExcelAgo) {
            exportExcelAgo.addEventListener('click', () => {
                this.exportToExcel('ago');
            });
        }

        const exportPdfNormalisasi = document.getElementById('exportPdfNormalisasi');
        if (exportPdfNormalisasi) {
            exportPdfNormalisasi.addEventListener('click', () => {
                this.exportToPDF('normalisasi');
            });
        }

        const exportExcelNormalisasi = document.getElementById('exportExcelNormalisasi');
        if (exportExcelNormalisasi) {
            exportExcelNormalisasi.addEventListener('click', () => {
                this.exportToExcel('normalisasi');
            });
        }
    }

    // Initialize tab navigation
    initializeTabNavigation() {
        const tabButtons = document.querySelectorAll('.tab-button');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                this.switchTab(targetTab);
            });
        });
    }

    // Switch between tabs
    switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`.tab-button[data-tab="${tabName}"]`).classList.add('active');

        // Update tab content
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        document.getElementById(tabName).classList.add('active');

        // Load data for the selected tab if needed
        if (tabName === 'belum-ago' && this.agoData.length === 0) {
            this.loadAgoData();
        } else if (tabName === 'normalisasi' && this.normalisasiData.length === 0) {
            this.loadNormalisasiData();
        }
    }

    // Initialize modal functionality
    initializeModal() {
        const confirmationModal = document.getElementById('confirmation-modal');

        // Close modal when clicking outside
        if (confirmationModal) {
            confirmationModal.addEventListener('click', (e) => {
                if (e.target === confirmationModal) {
                    this.hideConfirmationModal();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideConfirmationModal();
            }
        });
    }

    // Show confirmation modal
    showConfirmationModal(type, itemId, itemName) {
        // Hanya tampilkan modal untuk delete, untuk edit langsung proses
        if (type !== 'delete') {
            this.processEdit(itemId);
            return;
        }

        const modal = document.getElementById('confirmation-modal');
        const title = document.getElementById('confirmation-title');
        const message = document.getElementById('confirmation-message');
        const details = document.getElementById('confirmation-details');
        const icon = document.getElementById('confirmation-icon');
        const confirmBtn = document.getElementById('btn-confirm-action');

        if (modal) {
            title.textContent = 'Hapus Material';
            message.textContent = `Hapus material ${itemName}?`;
            details.textContent = 'Material yang sudah dihapus tidak dapat dikembalikan. Pastikan material ini tidak digunakan dalam transaksi aktif.';
            icon.innerHTML = '<i class="fas fa-trash"></i>';
            confirmBtn.textContent = 'Ya, Hapus';
            confirmBtn.className = 'modal-btn modal-btn-danger';

            this.pendingAction = type;
            this.pendingItemId = itemId;
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    // Hide confirmation modal
    hideConfirmationModal() {
        const modal = document.getElementById('confirmation-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            this.pendingAction = null;
            this.pendingItemId = null;
        }
    }

    // Execute pending action
    executePendingAction() {
        if (this.pendingAction && this.pendingItemId) {
            if (this.pendingAction === 'edit') {
                this.processEdit(this.pendingItemId);
            } else {
                this.processDelete(this.pendingItemId);
            }
            this.hideConfirmationModal();
        }
    }

    // Load stock data
    async loadStockData() {
        try {
            // Show loading state
            const tbody = document.getElementById('stock-table-body');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="19" class="text-center loading-state">
                            <div class="loading-content">
                                <i class="fas fa-spinner fa-spin loading-icon"></i>
                                <span class="loading-text">Memuat data stok material...</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            // Generate mock data
            this.currentData = this.generateMockData();
            this.filteredData = [...this.currentData];
            
            this.renderTable();
            this.updateTableFooter();
        } catch (error) {
            console.error('Error loading stock data:', error);
            this.showNotification('Gagal memuat data stok material', 'error');
        }
    }

    // Load AGO data
    async loadAgoData() {
        try {
            // Show loading state
            const tbody = document.getElementById('ago-table-body');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center loading-state">
                            <div class="loading-content">
                                <i class="fas fa-spinner fa-spin loading-icon"></i>
                                <span class="loading-text">Memuat data approval...</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 800));

            // Generate mock data dengan data yang sesuai gambar
            this.agoData = this.generateAgoMockData();
            
            this.renderAgoTable();
        } catch (error) {
            console.error('Error loading AGO data:', error);
            this.showNotification('Gagal memuat data approval', 'error');
        }
    }

    // Load Normalisasi data
    async loadNormalisasiData() {
        try {
            // Show loading state
            const tbody = document.getElementById('normalisasi-table-body');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center loading-state">
                            <div class="loading-content">
                                <i class="fas fa-spinner fa-spin loading-icon"></i>
                                <span class="loading-text">Memuat data normalisasi...</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 800));

            // Generate mock data
            this.normalisasiData = this.generateNormalisasiMockData();
            
            this.renderNormalisasiTable();
        } catch (error) {
            console.error('Error loading normalisasi data:', error);
            this.showNotification('Gagal memuat data normalisasi', 'error');
        }
    }

    // Generate mock data for Rekap Stok
    generateMockData() {
        const materialNames = [
            'Kabel NYY 4x25mm', 'MCB 35A Schneider', 'MCB 4A Schneider', 
            'MCB 20A Schneider', 'Kabel NYY 4x16mm', 'MCB 10A Schneider',
            'MCB 16A Schneider', 'Segel Plastik Putih', 'Stop Kontak 16A',
            'Lampu LED 18W', 'Trafo 50kVA', 'Panel LVMDP'
        ];

        return materialNames.map((nama, index) => {
            const stokAwal = Math.floor(Math.random() * 100) + 20;
            const penerimaan = Math.floor(Math.random() * 50);
            const yanbung = Math.floor(Math.random() * 20);
            const p2tl = Math.floor(Math.random() * 15);
            const gangguan = Math.floor(Math.random() * 10);
            const stokAkhir = stokAwal + penerimaan - yanbung - p2tl - gangguan;
            const mms = Math.floor(Math.random() * 150) + 50;
            const selisih = stokAkhir - mms;
            
            return {
                id: index + 1,
                namaMaterial: nama,
                stokAwal: stokAwal,
                penerimaan: penerimaan,
                yanbung: yanbung,
                p2tl: p2tl,
                gangguan: gangguan,
                inTransitPenerimaan: Math.floor(Math.random() * 5),
                inTransitPengeluaran: Math.floor(Math.random() * 3),
                stokAkhir: stokAkhir,
                mms: mms,
                selisih: selisih,
                keterangan: this.getKeterangan(selisih),
                koefSelisih: (selisih / mms).toFixed(2),
                sisaReal: (stokAkhir * 0.8).toFixed(1),
                tanggal: new Date().toLocaleDateString('id-ID'),
                waktu: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
            };
        });
    }

    // Generate mock data for Belum AGO - SESUAI GAMBAR
    generateAgoMockData() {
        return [
            {
                id: 1,
                namaMaterial: 'MCB 10A Schneider',
                sedangDipakai: 56,
                selisihMMS: 4
            },
            {
                id: 2,
                namaMaterial: 'MCB 16A Schneider',
                sedangDipakai: 41,
                selisihMMS: 1
            },
            {
                id: 3,
                namaMaterial: 'Kabel NYY 4x16mm',
                sedangDipakai: 25,
                selisihMMS: 9
            },
            {
                id: 4,
                namaMaterial: 'Segel Plastik Putih',
                sedangDipakai: 42,
                selisihMMS: 6
            },
            {
                id: 5,
                namaMaterial: 'Stop Kontak 16A',
                sedangDipakai: 43,
                selisihMMS: -1
            },
            {
                id: 6,
                namaMaterial: 'Lampu LED 18W',
                sedangDipakai: 13,
                selisihMMS: -8
            }
        ];
    }

    // Generate mock data for Normalisasi
    generateNormalisasiMockData() {
        const materialNames = [
            'MCB 2A Schneider', 'MCB 4A Schneider', 'MCB 6A Schneider', 
            'MCB 10A Schneider', 'MCB 16A Schneider', 'MCB 20A Schneider',
            'Segel Plastik Putih', 'Kabel NYY 4x16mm', 'Stop Kontak 16A'
        ];

        return materialNames.map((nama, index) => {
            return {
                id: index + 1,
                namaMaterial: nama,
                kodeNormalisasi: `NAT${String(1000 + index).padStart(6, '0')}`
            };
        });
    }

    // Get keterangan based on selisih
    getKeterangan(selisih) {
        if (selisih > 20) return 'Stok berlebih';
        if (selisih > 0) return 'Stok normal';
        if (selisih > -20) return 'Stok menipis';
        return 'Stok kritis';
    }

    // Render table with data for Rekap Stok
    renderTable() {
        const tbody = document.getElementById('stock-table-body');
        if (!tbody) return;

        if (this.filteredData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="19" class="text-center">
                        <div class="loading-content">
                            <i class="fas fa-search" style="color: var(--pln-gray-400);"></i>
                            <span class="loading-text">Tidak ada data yang ditemukan</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.filteredData.map(item => this.createTableRow(item)).join('');
        
        // Add event listeners to action buttons
        this.attachRowEventListeners();
        this.updateTableFooter();
    }

    // Render table for Belum AGO
    renderAgoTable() {
        const tbody = document.getElementById('ago-table-body');
        if (!tbody) return;

        if (this.agoData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">
                        <div class="loading-content">
                            <i class="fas fa-search" style="color: var(--pln-gray-400);"></i>
                            <span class="loading-text">Tidak ada data yang ditemukan</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.agoData.map(item => this.createAgoTableRow(item)).join('');
    }

    // Render table for Normalisasi
    renderNormalisasiTable() {
        const tbody = document.getElementById('normalisasi-table-body');
        if (!tbody) return;

        if (this.normalisasiData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">
                        <div class="loading-content">
                            <i class="fas fa-search" style="color: var(--pln-gray-400);"></i>
                            <span class="loading-text">Tidak ada data yang ditemukan</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.normalisasiData.map(item => this.createNormalisasiTableRow(item)).join('');
        
        // Add event listeners to action buttons
        this.attachNormalisasiEventListeners();
    }

    // Create table row HTML untuk Rekap Stok
    createTableRow(item) {
        const stokAkhirClass = item.stokAkhir > 0 ? 'positive' : 'negative';
        const selisihClass = item.selisih >= 0 ? 'positive' : 'negative';
        const koefClass = parseFloat(item.koefSelisih) >= 0 ? 'positive' : 'negative';

        return `
            <tr data-id="${item.id}">
                <td class="text-center">${item.id}</td>
                <td class="material-cell">
                    <div class="material-info">
                        <div class="material-details">
                            <div class="material-name">${item.namaMaterial}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <div class="stock-value">${item.stokAwal}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value positive">${item.penerimaan}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value negative">${item.yanbung}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value negative">${item.p2tl}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value negative">${item.gangguan}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value info">${item.inTransitPenerimaan}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value info">${item.inTransitPengeluaran}</div>
                </td>
                <td class="text-center">
                    <div class="stock-badge ${stokAkhirClass}">${item.stokAkhir}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value">${item.mms}</div>
                </td>
                <td class="text-center">
                    <div class="difference-badge ${selisihClass}">${item.selisih}</div>
                </td>
                <td class="text-center">
                    <div class="keterangan-text">${item.keterangan}</div>
                </td>
                <td class="text-center">
                    <div class="koef-value ${koefClass}">${item.koefSelisih}</div>
                </td>
                <td class="text-center">
                    <div class="stock-value ${parseFloat(item.sisaReal) >= 0 ? 'positive' : 'negative'}">${item.sisaReal}</div>
                </td>
                <td class="text-center">
                    <div class="date-value">${item.tanggal}</div>
                </td>
                <td class="text-center">
                    <div class="time-value">${item.waktu}</div>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" data-id="${item.id}" data-name="${item.namaMaterial}">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </button>
                        <button class="btn-action btn-delete" data-id="${item.id}" data-name="${item.namaMaterial}">
                            <i class="fas fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Create table row HTML untuk Belum AGO
    createAgoTableRow(item) {
        return `
            <tr data-id="${item.id}">
                <td class="text-center">${item.id}</td>
                <td class="material-cell">
                    <div class="material-info">
                        <div class="material-details">
                            <div class="material-name">${item.namaMaterial}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <div class="usage-value">${item.sedangDipakai}</div>
                </td>
                <td class="text-center">
                    <div class="difference-badge ${item.selisihMMS >= 0 ? 'positive' : 'negative'}">${item.selisihMMS}</div>
                </td>
            </tr>
        `;
    }

    // Create table row HTML untuk Normalisasi
    createNormalisasiTableRow(item) {
        return `
            <tr data-id="${item.id}">
                <td class="text-center">${item.id}</td>
                <td class="material-cell">
                    <div class="material-info">
                        <div class="material-details">
                            <div class="material-name">${item.namaMaterial}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <div class="normalization-code">
                        <span class="code-value">${item.kodeNormalisasi}</span>
                        <button class="btn-copy" data-code="${item.kodeNormalisasi}" title="Salin Kode">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" data-id="${item.id}" data-name="${item.namaMaterial}">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </button>
                        <button class="btn-action btn-delete" data-id="${item.id}" data-name="${item.namaMaterial}">
                            <i class="fas fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Attach event listeners to table rows
    attachRowEventListeners() {
        // Action buttons
        const editButtons = document.querySelectorAll('.btn-edit');
        const deleteButtons = document.querySelectorAll('.btn-delete');

        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                // Langsung proses edit tanpa modal konfirmasi
                this.processEdit(id);
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                const name = e.currentTarget.getAttribute('data-name');
                // Untuk delete tetap pakai modal konfirmasi
                this.showConfirmationModal('delete', id, name);
            });
        });
    }

    // Attach event listeners for normalisasi table
    attachNormalisasiEventListeners() {
        // Action buttons for normalisasi table
        const editButtons = document.querySelectorAll('#normalisasi-table-body .btn-edit');
        const deleteButtons = document.querySelectorAll('#normalisasi-table-body .btn-delete');
        const copyButtons = document.querySelectorAll('#normalisasi-table-body .btn-copy');

        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                // Langsung proses edit tanpa modal konfirmasi
                this.processEdit(id);
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                const name = e.currentTarget.getAttribute('data-name');
                // Untuk delete tetap pakai modal konfirmasi
                this.showConfirmationModal('delete', id, name);
            });
        });

        copyButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const code = e.currentTarget.getAttribute('data-code');
                this.copyToClipboard(code);
            });
        });
    }

    // Handle search
    handleSearch(searchTerm, tableType) {
        const term = searchTerm.toLowerCase();
        
        if (tableType === 'rekap') {
            this.filteredData = this.currentData.filter(item => 
                item.namaMaterial.toLowerCase().includes(term) ||
                item.keterangan.toLowerCase().includes(term)
            );
            this.renderTable();
        } else if (tableType === 'ago') {
            const filteredAgoData = this.agoData.filter(item => 
                item.namaMaterial.toLowerCase().includes(term)
            );
            
            const tbody = document.getElementById('ago-table-body');
            if (tbody) {
                if (filteredAgoData.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="loading-content">
                                    <i class="fas fa-search" style="color: var(--pln-gray-400);"></i>
                                    <span class="loading-text">Tidak ada data yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = filteredAgoData.map(item => this.createAgoTableRow(item)).join('');
                }
            }
        } else if (tableType === 'normalisasi') {
            const filteredNormalisasiData = this.normalisasiData.filter(item => 
                item.namaMaterial.toLowerCase().includes(term) ||
                item.kodeNormalisasi.toLowerCase().includes(term)
            );
            
            const tbody = document.getElementById('normalisasi-table-body');
            if (tbody) {
                if (filteredNormalisasiData.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="loading-content">
                                    <i class="fas fa-search" style="color: var(--pln-gray-400);"></i>
                                    <span class="loading-text">Tidak ada data yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = filteredNormalisasiData.map(item => this.createNormalisasiTableRow(item)).join('');
                    this.attachNormalisasiEventListeners();
                }
            }
        }
    }

    // Clear search
    clearSearch(tableType) {
        const searchInput = document.getElementById(`search${tableType.charAt(0).toUpperCase() + tableType.slice(1)}`);
        if (searchInput) {
            searchInput.value = '';
            if (tableType === 'rekap') {
                this.filteredData = [...this.currentData];
                this.renderTable();
            } else if (tableType === 'ago') {
                this.renderAgoTable();
            } else if (tableType === 'normalisasi') {
                this.renderNormalisasiTable();
            }
        }
    }

    // Update table footer information
    updateTableFooter() {
        const displayCount = document.getElementById('display-count');
        const totalCount = document.getElementById('total-count');
        
        if (displayCount && totalCount) {
            displayCount.textContent = this.filteredData.length;
            totalCount.textContent = this.currentData.length;
        }

        // Update last update time
        const lastUpdateTime = document.getElementById('lastUpdateTime');
        if (lastUpdateTime) {
            const now = new Date();
            lastUpdateTime.textContent = now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }

    // Process edit action
    async processEdit(id) {
        // Determine which tab is active
        const activeTab = document.querySelector('.tab-pane.active').id;
        
        if (activeTab === 'rekap-stok') {
            // Navigasi ke halaman edit stok material di tab yang sama
            window.location.href = `/stok-material/${id}/edit`;
        } else if (activeTab === 'normalisasi') {
            // Navigasi ke halaman edit normalisasi di tab yang sama
            window.location.href = `/normalisasi/${id}/edit`;
        }
    }

    // Process delete action
    async processDelete(id) {
        try {
            // Remove item from data
            this.currentData = this.currentData.filter(item => item.id != id);
            this.filteredData = this.filteredData.filter(item => item.id != id);
            this.normalisasiData = this.normalisasiData.filter(item => item.id != id);
            
            this.renderTable();
            this.renderNormalisasiTable();
            this.showNotification('Material berhasil dihapus', 'success');
            
        } catch (error) {
            console.error('Error deleting material:', error);
            this.showNotification('Gagal menghapus material', 'error');
        }
    }

    // Tambah Stok - FIXED: langsung navigasi tanpa alert
    tambahStok() {
        // Navigasi ke halaman tambah stok material di tab yang sama
        window.location.href = '/stok-material/tambah';
    }

    // Tambah Normalisasi - FIXED: langsung navigasi tanpa alert
    tambahNormalisasi() {
        // Navigasi ke halaman tambah normalisasi di tab yang sama
        window.location.href = '/normalisasi/tambah';
    }

    // Copy to clipboard
    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showNotification('Kode berhasil disalin', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            this.showNotification('Gagal menyalin kode', 'error');
        });
    }

    // Export to PDF
    exportToPDF(type) {
        this.showNotification(`Export ${type} ke PDF akan segera tersedia`, 'info');
    }

    // Export to Excel
    exportToExcel(type) {
        this.showNotification(`Export ${type} ke Excel akan segera tersedia`, 'info');
    }

    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            info: 'fa-info-circle'
        };

        const titles = {
            success: 'Berhasil!',
            error: 'Error!',
            info: 'Info'
        };

        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${icons[type]} notification-icon"></i>
                <div class="notification-text">
                    <h4>${titles[type]}</h4>
                    <p>${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);

        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.stokMaterialApp = new StokMaterial();
});