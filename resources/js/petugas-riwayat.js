// Riwayat Verifikasi Transaksi - Main Application
class RiwayatVerifikasi {
    constructor() {
        this.currentData = [];
        this.filteredData = [];
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.loadTransactionData();
        this.initializeModal();
        this.initializeRejectionReasonModal();
    }

    // Initialize all event listeners
    initializeEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('riwayat-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }

        // Date filter
        const dateFilter = document.getElementById('riwayat-date-filter');
        if (dateFilter) {
            dateFilter.addEventListener('change', (e) => {
                this.handleDateFilter(e.target.value);
            });
        }

        // Type filter
        const typeFilter = document.getElementById('riwayat-type-filter');
        if (typeFilter) {
            typeFilter.addEventListener('change', (e) => {
                this.handleTypeFilter(e.target.value);
            });
        }

        // Status filter
        const statusFilter = document.getElementById('riwayat-status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.handleStatusFilter(e.target.value);
            });
        }

        // Photo modal close
        const closePhotoModal = document.getElementById('riwayat-close-photo-modal');
        if (closePhotoModal) {
            closePhotoModal.addEventListener('click', () => {
                this.hidePhotoModal();
            });
        }
    }

    // Initialize rejection reason modal
    initializeRejectionReasonModal() {
        const modal = document.getElementById('riwayat-rejection-reason-modal');
        const closeModal = document.getElementById('riwayat-close-rejection-reason-modal');

        // Close modal events - HANYA tombol X yang bisa menutup modal
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                this.hideRejectionReasonModal();
            });
        }

        // Mencegah penutupan modal dengan klik di luar
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            modal.addEventListener('mousedown', (e) => {
                if (e.target === modal) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }
    }

    // Initialize modal functionality
    initializeModal() {
        const photoModal = document.getElementById('riwayat-photo-modal');
        const rejectionReasonModal = document.getElementById('riwayat-rejection-reason-modal');

        // Photo Modal - TIDAK BISA DITUTUP DENGAN KLIK DI LUAR
        if (photoModal) {
            photoModal.addEventListener('click', (e) => {
                if (e.target === photoModal) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            photoModal.addEventListener('mousedown', (e) => {
                if (e.target === photoModal) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }

        // Rejection Reason Modal - TIDAK BISA DITUTUP DENGAN KLIK DI LUAR
        if (rejectionReasonModal) {
            rejectionReasonModal.addEventListener('click', (e) => {
                if (e.target === rejectionReasonModal) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            rejectionReasonModal.addEventListener('mousedown', (e) => {
                if (e.target === rejectionReasonModal) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }

        // Close modal with Escape key (kecuali photo modal dan rejection reason modal)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const photoModal = document.getElementById('riwayat-photo-modal');
                if (photoModal && photoModal.style.display === 'flex') {
                    return;
                }
                
                const rejectionReasonModal = document.getElementById('riwayat-rejection-reason-modal');
                if (rejectionReasonModal && rejectionReasonModal.style.display === 'flex') {
                    return;
                }
            }
        });
    }

    // Show modal with photo - TIDAK BISA DITUTUP DENGAN KLIK DI LUAR
    showPhotoModal(imageSrc, filename = '') {
        const modal = document.getElementById('riwayat-photo-modal');
        const modalImage = document.getElementById('riwayat-modal-image');
        const modalFilename = document.getElementById('riwayat-modal-filename');

        if (modal && modalImage) {
            modalImage.src = imageSrc;
            modalImage.alt = filename || 'Preview Foto';
            modalFilename.textContent = filename || 'Preview Foto';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                const closeBtn = document.getElementById('riwayat-close-photo-modal');
                if (closeBtn) closeBtn.focus();
            }, 100);
        }
    }

    // Hide photo modal - HANYA DIPANGGIL OLEH TOMBOL X
    hidePhotoModal() {
        const modal = document.getElementById('riwayat-photo-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Show rejection reason modal - TIDAK BISA DITUTUP DENGAN KLIK DI LUAR
    showRejectionReasonModal(reason, itemInfo = null) {
        const modal = document.getElementById('riwayat-rejection-reason-modal');
        const reasonDetail = document.getElementById('riwayat-rejection-reason-detail');
        const rejectionMeta = document.getElementById('riwayat-rejection-meta');

        if (modal && reasonDetail) {
            reasonDetail.textContent = reason || 'Tidak ada alasan revisi yang tersedia.';
            
            if (itemInfo) {
                rejectionMeta.innerHTML = `
                    <div><strong>Transaksi:</strong> <span>${itemInfo.nama} - ${itemInfo.keperluan}</span></div>
                    <div><strong>Tanggal:</strong> <span>${this.formatDate(itemInfo.tanggal)}</span></div>
                    ${itemInfo.rejectedBy ? `<div><strong>Direvisi oleh:</strong> <span>${itemInfo.rejectedBy}</span></div>` : ''}
                    ${itemInfo.rejectedAt ? `<div><strong>Waktu permintaan revisi:</strong> <span>${this.formatDateTime(itemInfo.rejectedAt)}</span></div>` : ''}
                `;
            } else {
                rejectionMeta.innerHTML = '';
            }
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                const closeBtn = document.getElementById('riwayat-close-rejection-reason-modal');
                if (closeBtn) closeBtn.focus();
            }, 100);
        }
    }

    // Hide rejection reason modal - HANYA DIPANGGIL OLEH TOMBOL X
    hideRejectionReasonModal() {
        const modal = document.getElementById('riwayat-rejection-reason-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Load transaction data (simulated)
    async loadTransactionData() {
        try {
            await new Promise(resolve => setTimeout(resolve, 1500));

            this.currentData = this.generateMockData();
            this.filteredData = [...this.currentData];
            
            this.renderTable();
            this.updateTotalData();
        } catch (error) {
            console.error('Error loading transaction data:', error);
            this.showNotification('Gagal memuat data riwayat verifikasi', 'error');
        }
    }

    // Generate mock data for demonstration - HANYA DATA YANG SUDAH DISETUJUI ATAU PERLU REVISI
    generateMockData() {
        const types = ['Pengeluaran', 'Penerimaan'];
        const statuses = ['approved', 'needs_revision'];
        const names = ['Budi Santoso', 'Sari Dewi', 'Ahmad Fauzi', 'Maya Sari', 'Rizki Pratama'];
        const keperluan = ['Pemasangan Baru', 'Perbaikan', 'Penggantian', 'Maintenance', 'Proyek Khusus'];
        const revisionReasons = [
            null,
            null,
            null,
            'Foto bukti pengambilan tidak jelas, mohon upload ulang',
            'Data transaksi tidak lengkap, silakan lengkapi informasi yang diperlukan',
            null,
            'Keperluan tidak sesuai dengan standar operasional, mohon penjelasan lebih detail',
            null,
            null,
            'Stok material tidak mencukupi, silakan konfirmasi ulang jumlah yang diminta'
        ];

        return Array.from({ length: 12 }, (_, index) => {
            const type = types[Math.floor(Math.random() * types.length)];
            const status = statuses[Math.floor(Math.random() * statuses.length)];
            const revisionReason = status === 'needs_revision' ? revisionReasons[index] || 'Perlu revisi data' : null;
            
            return {
                id: index + 1,
                tanggal: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                jenis: type,
                nama: names[Math.floor(Math.random() * names.length)],
                keperluan: keperluan[Math.floor(Math.random() * keperluan.length)],
                idPelanggan: `PLN${String(index + 1).padStart(6, '0')}`,
                mcb2A: Math.floor(Math.random() * 5),
                mcb4A: Math.floor(Math.random() * 10),
                mcb6A: Math.floor(Math.random() * 8),
                mcb10A: Math.floor(Math.random() * 6),
                mcb16A: Math.floor(Math.random() * 4),
                mcb20A: Math.floor(Math.random() * 3),
                mcb25A: Math.floor(Math.random() * 2),
                mcb35A: Math.floor(Math.random() * 2),
                segel: Math.floor(Math.random() * 10),
                lpb: Math.floor(Math.random() * 5),
                paska: Math.floor(Math.random() * 8),
                sr: Math.floor(Math.random() * 15),
                fotoSrSebelum: Math.random() > 0.3 ? `foto_sr_sebelum_${index + 1}.jpg` : null,
                fotoSrSesudah: Math.random() > 0.4 ? `foto_sr_sesudah_${index + 1}.jpg` : null,
                swc: Math.floor(Math.random() * 20),
                lintap10_16: Math.floor(Math.random() * 5),
                lintap16_35: Math.floor(Math.random() * 3),
                lintap50_70: Math.floor(Math.random() * 2),
                kondom: Math.floor(Math.random() * 25),
                solasi: Math.floor(Math.random() * 30),
                fotoBukti: Math.random() > 0.2 ? `bukti_pengambilan_${index + 1}.jpg` : null,
                status: status,
                rejectionReason: revisionReason,
                rejectedBy: status === 'needs_revision' ? 'Admin' : null,
                rejectedAt: status === 'needs_revision' ? new Date(Date.now() - Math.random() * 7 * 24 * 60 * 60 * 1000).toISOString() : null
            };
        });
    }

    // Render table with data
    renderTable() {
        const tbody = document.getElementById('riwayat-transaction-table-body');
        if (!tbody) return;

        if (this.filteredData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="30" class="riwayat-text-center">
                        <div class="riwayat-loading-content">
                            <i class="fas fa-search"></i>
                            <span class="riwayat-loading-text">Tidak ada data riwayat yang ditemukan</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.filteredData.map(item => this.createTableRow(item)).join('');
        
        this.attachRowEventListeners();
        this.ensureButtonTextNotCut();
    }

    // Create table row HTML
    createTableRow(item) {
        const statusClass = item.status === 'needs_revision' ? 'riwayat-status-revision' : `riwayat-status-${item.status}`;
        const statusText = this.getStatusText(item.status);
        
        return `
            <tr data-id="${item.id}" class="${item.status === 'needs_revision' ? 'riwayat-table-row-revision' : ''}">
                <td class="riwayat-text-center">${item.id}</td>
                <td>${this.formatDate(item.tanggal)}</td>
                <td><span class="riwayat-type-text">${item.jenis}</span></td>
                <td>${item.nama}</td>
                <td><span class="riwayat-keperluan-text" title="${item.keperluan}">${item.keperluan}</span></td>
                <td>${item.idPelanggan}</td>
                <td class="riwayat-quantity-cell ${item.mcb2A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb2A}</td>
                <td class="riwayat-quantity-cell ${item.mcb4A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb4A}</td>
                <td class="riwayat-quantity-cell ${item.mcb6A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb6A}</td>
                <td class="riwayat-quantity-cell ${item.mcb10A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb10A}</td>
                <td class="riwayat-quantity-cell ${item.mcb16A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb16A}</td>
                <td class="riwayat-quantity-cell ${item.mcb20A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb20A}</td>
                <td class="riwayat-quantity-cell ${item.mcb25A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb25A}</td>
                <td class="riwayat-quantity-cell ${item.mcb35A === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.mcb35A}</td>
                <td class="riwayat-quantity-cell ${item.segel === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.segel}</td>
                <td class="riwayat-quantity-cell ${item.lpb === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.lpb}</td>
                <td class="riwayat-quantity-cell ${item.paska === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.paska}</td>
                <td class="riwayat-quantity-cell ${item.sr === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.sr}</td>
                <td class="riwayat-text-center">
                    ${this.createPhotoPreview(item.fotoSrSebelum, 'Foto SR Sebelum')}
                </td>
                <td class="riwayat-text-center">
                    ${this.createPhotoPreview(item.fotoSrSesudah, 'Foto SR Sesudah')}
                </td>
                <td class="riwayat-quantity-cell ${item.swc === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.swc}</td>
                <td class="riwayat-quantity-cell ${item.lintap10_16 === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.lintap10_16}</td>
                <td class="riwayat-quantity-cell ${item.lintap16_35 === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.lintap16_35}</td>
                <td class="riwayat-quantity-cell ${item.lintap50_70 === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.lintap50_70}</td>
                <td class="riwayat-quantity-cell ${item.kondom === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.kondom}</td>
                <td class="riwayat-quantity-cell ${item.solasi === 0 ? 'riwayat-quantity-zero' : 'riwayat-quantity-positive'}">${item.solasi}</td>
                <td class="riwayat-text-center">
                    ${this.createPhotoPreview(item.fotoBukti, 'Foto Bukti Pengambilan')}
                </td>
                <td class="riwayat-text-center">
                    <span class="riwayat-status-badge ${statusClass}">${statusText}</span>
                </td>
                <td class="riwayat-rejection-reason-cell">
                    ${this.createRejectionReason(item)}
                </td>
                <td class="riwayat-text-center">
                    <div class="riwayat-action-buttons">
                        <button class="riwayat-btn-action riwayat-btn-detail" data-id="${item.id}" data-name="${item.nama}">
                            <i class="fas fa-eye"></i>
                            <span class="riwayat-btn-text riwayat-text-no-wrap">Lihat Detail</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Create rejection reason display dengan fungsi klik
    createRejectionReason(item) {
        if (!item.rejectionReason) {
            return '<span class="riwayat-rejection-reason-empty">-</span>';
        }
        
        const rejectionTypeText = item.jenis === 'Pengeluaran' ? 'PENGELUARAN DIREVISI' : 'PENERIMAAN DIREVISI';
        
        return `
            <div class="riwayat-rejection-reason-clickable" 
                 data-reason="${item.rejectionReason}"
                 data-item-id="${item.id}">
                <div class="riwayat-rejection-header">
                    <i class="fas fa-edit riwayat-rejection-warning-icon"></i>
                    <span class="riwayat-rejection-type-text">${rejectionTypeText}</span>
                </div>
            </div>
        `;
    }

    // Create photo preview element dengan icon mata
    createPhotoPreview(photoName, title) {
        if (!photoName) {
            return `<span class="riwayat-photo-preview disabled" title="Tidak ada foto">
                <i class="fas fa-camera"></i>
            </span>`;
        }

        const photoUrl = `https://via.placeholder.com/600x400/0054a6/ffffff?text=${encodeURIComponent(photoName)}`;
        
        return `<span class="riwayat-photo-preview" data-src="${photoUrl}" data-filename="${photoName}" title="Klik untuk melihat ${title}">
            <i class="fas fa-eye"></i>
        </span>`;
    }

    // Get status text in Indonesian
    getStatusText(status) {
        const statusMap = {
            'approved': 'Disetujui',
            'needs_revision': 'PERLU REVISI'
        };
        return statusMap[status] || status;
    }

    // Format date to Indonesian format
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Format date and time for display
    formatDateTime(dateTimeString) {
        if (!dateTimeString) return '-';
        
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Update total data count
    updateTotalData() {
        const totalDataElement = document.getElementById('riwayatTotalData');
        if (totalDataElement) {
            totalDataElement.textContent = this.filteredData.length;
        }
    }

    // Attach event listeners to table rows
    attachRowEventListeners() {
        const photoPreviews = document.querySelectorAll('.riwayat-photo-preview:not(.disabled)');
        photoPreviews.forEach(preview => {
            preview.addEventListener('click', (e) => {
                const src = e.currentTarget.getAttribute('data-src');
                const filename = e.currentTarget.getAttribute('data-filename');
                this.showPhotoModal(src, filename);
            });
        });

        const rejectionReasons = document.querySelectorAll('.riwayat-rejection-reason-clickable');
        rejectionReasons.forEach(element => {
            element.addEventListener('click', (e) => {
                const reason = e.currentTarget.getAttribute('data-reason');
                const itemId = e.currentTarget.getAttribute('data-item-id');
                const item = this.currentData.find(item => item.id == itemId);
                
                this.showRejectionReasonModal(reason, item);
            });
        });

        const detailButtons = document.querySelectorAll('.riwayat-btn-detail');
        detailButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                const name = e.currentTarget.getAttribute('data-name');
                this.showDetailModal(id, name);
            });
        });
    }

    // Handle search
    handleSearch(searchTerm) {
        if (!searchTerm) {
            this.filteredData = [...this.currentData];
        } else {
            const term = searchTerm.toLowerCase();
            this.filteredData = this.currentData.filter(item => 
                item.nama.toLowerCase().includes(term) ||
                item.keperluan.toLowerCase().includes(term) ||
                item.idPelanggan.toLowerCase().includes(term) ||
                item.jenis.toLowerCase().includes(term) ||
                (item.rejectionReason && item.rejectionReason.toLowerCase().includes(term))
            );
        }
        this.renderTable();
        this.updateTotalData();
    }

    // Handle date filter
    handleDateFilter(date) {
        if (!date) {
            this.filteredData = [...this.currentData];
        } else {
            this.filteredData = this.currentData.filter(item => item.tanggal === date);
        }
        this.renderTable();
        this.updateTotalData();
    }

    // Handle type filter
    handleTypeFilter(type) {
        if (!type) {
            this.filteredData = [...this.currentData];
        } else {
            this.filteredData = this.currentData.filter(item => item.jenis === type);
        }
        this.renderTable();
        this.updateTotalData();
    }

    // Handle status filter
    handleStatusFilter(status) {
        if (!status) {
            this.filteredData = [...this.currentData];
        } else {
            this.filteredData = this.currentData.filter(item => item.status === status);
        }
        this.renderTable();
        this.updateTotalData();
    }

    // Show detail modal (placeholder)
    showDetailModal(id, name) {
        this.showNotification(`Menampilkan detail untuk transaksi ${name} (ID: ${id})`, 'info');
    }

    // Show notification
    showNotification(message, type = 'info') {
        document.querySelectorAll('.riwayat-notification').forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `riwayat-notification ${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            info: 'fa-info-circle'
        };

        notification.innerHTML = `
            <div class="riwayat-notification-content">
                <i class="fas ${icons[type]} riwayat-notification-icon"></i>
                <div class="riwayat-notification-text">
                    <p>${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => notification.classList.add('show'), 100);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Fungsi tambahan untuk memastikan teks tidak terpotong di button
    ensureButtonTextNotCut() {
        const buttons = document.querySelectorAll('.riwayat-btn-action');
        buttons.forEach(button => {
            const textSpan = button.querySelector('.riwayat-btn-text');
            if (textSpan) {
                textSpan.classList.add('riwayat-btn-text-force-visible');
            }
        });
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new RiwayatVerifikasi();
});