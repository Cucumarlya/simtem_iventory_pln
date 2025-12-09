// JavaScript untuk Petugas Penerimaan Material

// Data dummy untuk simulasi
const dummyData = [
    {
        id: 1,
        kode_transaksi: "TRX-2023-001",
        tanggal: "2023-10-15",
        nama_penerima: "Budi Santoso",
        keperluan: "YANBUNG",
        status: "menunggu"
    },
    {
        id: 2,
        kode_transaksi: "TRX-2023-002",
        tanggal: "2023-10-16",
        nama_penerima: "Sri Aisyah",
        keperluan: "P2TL",
        status: "disetujui"
    },
    {
        id: 3,
        kode_transaksi: "TRX-2023-003",
        tanggal: "2023-10-17",
        nama_penerima: "Ahmad Fauzi",
        keperluan: "GANGGUAN",
        status: "dikembalikan"
    },
    {
        id: 4,
        kode_transaksi: "TRX-2023-004",
        tanggal: "2023-10-18",
        nama_penerima: "Dewi Anggraini",
        keperluan: "PLN",
        status: "menunggu"
    },
    {
        id: 5,
        kode_transaksi: "TRX-2023-005",
        tanggal: "2023-10-19",
        nama_penerima: "Rizki Pratama",
        keperluan: "YANBUNG",
        status: "disetujui"
    },
    {
        id: 6,
        kode_transaksi: "TRX-2023-006",
        tanggal: "2023-10-20",
        nama_penerima: "Linda Wijaya",
        keperluan: "P2TL",
        status: "menunggu"
    },
    {
        id: 7,
        kode_transaksi: "TRX-2023-007",
        tanggal: "2023-10-21",
        nama_penerima: "Hendra Kurniawan",
        keperluan: "GANGGUAN",
        status: "dikembalikan"
    }
];

// Variables
let currentPage = 1;
const itemsPerPage = 5;
let filteredData = [...dummyData];
let itemToDelete = null;

/**
 * Fungsi untuk menghitung statistik
 */
function updateStats(data) {
    const countMenunggu = data.filter(item => item.status === 'menunggu').length;
    const countDisetujui = data.filter(item => item.status === 'disetujui').length;
    const countDikembalikan = data.filter(item => item.status === 'dikembalikan').length;
    const total = data.length;
    
    // Update UI
    document.getElementById('total-data').textContent = total;
    document.getElementById('total-data-pagination').textContent = total;
    
    // Update stat cards
    document.querySelectorAll('.bg-yellow-500 + div p')[1].textContent = countMenunggu;
    document.querySelectorAll('.bg-green-500 + div p')[1].textContent = countDisetujui;
    document.querySelectorAll('.bg-red-500 + div p')[1].textContent = countDikembalikan;
    
    // Update pagination info
    const start = ((currentPage - 1) * itemsPerPage) + 1;
    const end = Math.min(currentPage * itemsPerPage, total);
    document.getElementById('data-start').textContent = start;
    document.getElementById('data-end').textContent = end;
}

/**
 * Fungsi untuk merender tabel
 */
function renderTable(data) {
    const tableBody = document.getElementById('table-body');
    const emptyState = document.getElementById('empty-state');
    const pagination = document.querySelector('.pagination-controls');
    
    if (!tableBody) return;
    
    // Pagination
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = data.slice(startIndex, endIndex);
    
    tableBody.innerHTML = '';
    
    if (pageData.length === 0) {
        tableBody.classList.add('hidden');
        if (emptyState) emptyState.classList.remove('hidden');
        if (pagination) pagination.classList.add('hidden');
        
        updateStats([]);
        return;
    }
    
    tableBody.classList.remove('hidden');
    if (emptyState) emptyState.classList.add('hidden');
    if (pagination) pagination.classList.remove('hidden');
    
    pageData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-150';
        
        // Format tanggal
        const dateObj = new Date(item.tanggal);
        const formattedDate = dateObj.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
        
        // Status badge
        let statusClass = '';
        let statusText = '';
        
        switch(item.status) {
            case 'menunggu':
                statusClass = 'badge-waiting';
                statusText = 'MENUNGGU';
                break;
            case 'dikembalikan':
                statusClass = 'badge-returned';
                statusText = 'DIKEMBALIKAN';
                break;
            case 'disetujui':
                statusClass = 'badge-approved';
                statusText = 'DISETUJUI';
                break;
        }
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                ${startIndex + index + 1}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-700">
                ${item.kode_transaksi}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                ${formattedDate}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                ${item.nama_penerima}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="keperluan-badge">
                    ${item.keperluan}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="badge ${statusClass}">
                    ${statusText}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="action-buttons">
                    <a href="/petugas/penerimaan/${item.id}" 
                       class="action-btn btn-detail">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>
                    ${item.status === 'dikembalikan' ? `
                        <a href="/petugas/penerimaan/${item.id}/edit" 
                           class="action-btn btn-edit">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <button type="button" 
                                class="action-btn btn-delete delete-btn"
                                data-id="${item.id}"
                                data-kode="${item.kode_transaksi}">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    ` : ''}
                </div>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // Update stats and pagination
    updateStats(data);
    updatePaginationButtons(data.length);
    
    // Add event listeners for delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            itemToDelete = {
                id: this.getAttribute('data-id'),
                kode: this.getAttribute('data-kode')
            };
            showDeleteModal(itemToDelete.kode);
        });
    });
}

/**
 * Fungsi untuk filter data
 */
function filterData() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const keperluan = document.getElementById('filterKeperluan').value;
    const dariTanggal = document.getElementById('filterDariTanggal').value;
    const sampaiTanggal = document.getElementById('filterSampaiTanggal').value;
    
    filteredData = dummyData.filter(item => {
        // Search filter
        const matchesSearch = searchTerm === '' || 
            item.nama_penerima.toLowerCase().includes(searchTerm) || 
            item.kode_transaksi.toLowerCase().includes(searchTerm) ||
            item.keperluan.toLowerCase().includes(searchTerm);
        
        // Status filter
        const matchesStatus = status === '' || item.status === status;
        
        // Keperluan filter
        const matchesKeperluan = keperluan === '' || item.keperluan === keperluan;
        
        // Date filter
        let matchesDate = true;
        if (dariTanggal) {
            const itemDate = new Date(item.tanggal);
            const filterDate = new Date(dariTanggal);
            matchesDate = matchesDate && itemDate >= filterDate;
        }
        if (sampaiTanggal) {
            const itemDate = new Date(item.tanggal);
            const filterDate = new Date(sampaiTanggal);
            matchesDate = matchesDate && itemDate <= filterDate;
        }
        
        return matchesSearch && matchesStatus && matchesKeperluan && matchesDate;
    });
    
    currentPage = 1;
    renderTable(filteredData);
}

/**
 * Fungsi untuk update pagination buttons
 */
function updatePaginationButtons(totalItems) {
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    
    if (btnPrev) {
        btnPrev.disabled = currentPage === 1;
    }
    
    if (btnNext) {
        btnNext.disabled = currentPage * itemsPerPage >= totalItems;
    }
}

/**
 * Fungsi untuk menampilkan modal hapus
 */
function showDeleteModal(kodeTransaksi) {
    const modal = document.getElementById('deleteModal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    
    modalTitle.textContent = 'Konfirmasi Hapus';
    modalMessage.textContent = `Apakah Anda yakin ingin menghapus penerimaan ${kodeTransaksi}? Tindakan ini tidak dapat dibatalkan.`;
    
    modal.classList.remove('hidden');
    
    // Setup confirm button
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.onclick = function() {
        if (itemToDelete) {
            deleteItem(itemToDelete.id, itemToDelete.kode);
        }
        modal.classList.add('hidden');
    };
}

/**
 * Fungsi untuk menghapus item
 */
function deleteItem(id, kode) {
    // Simulasi penghapusan dari data dummy
    const index = dummyData.findIndex(item => item.id == id);
    if (index !== -1) {
        dummyData.splice(index, 1);
        showNotification(`Penerimaan ${kode} berhasil dihapus`, 'success');
        filterData(); // Re-filter data setelah penghapusan
    }
}

/**
 * Fungsi untuk menampilkan notifikasi
 */
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                 type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icon} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

/**
 * Main initialization function
 */
function initialize() {
    // Setup search input
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', filterData);
    }
    
    // Setup filter button
    const filterBtn = document.getElementById('btnTerapkanFilter');
    if (filterBtn) {
        filterBtn.addEventListener('click', filterData);
    }
    
    // Setup pagination buttons
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    
    if (btnPrev) {
        btnPrev.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderTable(filteredData);
            }
        });
    }
    
    if (btnNext) {
        btnNext.addEventListener('click', function() {
            if (currentPage * itemsPerPage < filteredData.length) {
                currentPage++;
                renderTable(filteredData);
            }
        });
    }
    
    // Render initial data
    if (document.getElementById('table-body')) {
        renderTable(dummyData);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initialize);