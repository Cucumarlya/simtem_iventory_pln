// State aplikasi
// HAPUS variabel modal
let isEditing = false; // HAPUS
let currentEditId = null; // HAPUS

// DOM Elements
const userTableBody = document.getElementById('userTableBody');
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
// HAPUS variabel modal
const addUserBtn = document.getElementById('addUserBtn'); // HAPUS karena sudah jadi link
const userModal = document.getElementById('userModal'); // HAPUS
const modalClose = document.getElementById('modalClose'); // HAPUS
const cancelBtn = document.getElementById('cancelBtn'); // HAPUS
const saveBtn = document.getElementById('saveBtn'); // HAPUS
const userForm = document.getElementById('userForm'); // HAPUS
const modalTitle = document.getElementById('modalTitle'); // HAPUS

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    attachRowEventListeners();
});

// Setup Event Listeners
function setupEventListeners() {
    // Filter dan pencarian
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
    
    if (roleFilter) {
        roleFilter.addEventListener('change', handleFilter);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', handleFilter);
    }
    
    // HAPUS semua event listener modal
}

// Attach event listeners to table rows
function attachRowEventListeners() {
    // HAPUS event listener untuk edit button karena sudah jadi link
    document.querySelectorAll('.user-management-delete-btn').forEach(btn => {
        btn.addEventListener('click', handleDeleteUser);
    });
}

// Handle pencarian dengan debounce
function handleSearch() {
    applyFilters();
}

// Handle filter
function handleFilter() {
    applyFilters();
}

// Terapkan semua filter
function applyFilters() {
    const searchTerm = searchInput.value;
    const roleValue = roleFilter.value;
    const statusValue = statusFilter.value;
    
    const params = new URLSearchParams({
        search: searchTerm,
        role: roleValue,
        status: statusValue,
        ajax: 'true'
    });
    
    // Tampilkan loading state
    userTableBody.innerHTML = `
        <tr>
            <td colspan="6" class="user-management-empty-state">
                <ion-icon name="refresh-outline" class="user-management-loading"></ion-icon>
                <p>Memuat data...</p>
            </td>
        </tr>
    `;
    
    fetch(`/users?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateTable(data);
        } else {
            throw new Error(data.message || 'Failed to load data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        userTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="user-management-empty-state">
                    <ion-icon name="warning-outline"></ion-icon>
                    <p>Terjadi kesalahan saat memuat data</p>
                </td>
            </tr>
        `;
        showAlert('Error', 'Terjadi kesalahan saat memuat data', 'error');
    });
}

// Update tabel dengan data baru
function updateTable(data) {
    if (data.users && data.users.data && data.users.data.length > 0) {
        let tableHTML = '';
        
        data.users.data.forEach(user => {
            tableHTML += `
                <tr>
                    <td>
                        <div class="user-management-info">
                            <strong>${escapeHtml(user.name)}</strong>
                        </div>
                    </td>
                    <td>${escapeHtml(user.email)}</td>
                    <td>
                        <span class="user-management-role-text">
                            ${user.role_name || 'User'}
                        </span>
                    </td>
                    <td>
                        <span class="user-management-status-badge user-management-status-${user.is_active ? 'active' : 'inactive'}">
                            ${user.is_active ? 'AKTIF' : 'NONAKTIF'}
                        </span>
                    </td>
                    <td>${formatDate(user.created_at)}</td>
                    <td>
                        <div class="user-management-action-buttons">
                            <!-- PERUBAHAN: Edit button menjadi link -->
                            <a href="/users/${user.id}/edit" class="user-management-action-btn user-management-edit-btn" title="Edit">
                                <ion-icon name="create-outline"></ion-icon>
                                Edit
                            </a>
                            <button class="user-management-action-btn user-management-delete-btn" data-id="${user.id}" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        userTableBody.innerHTML = tableHTML;
        
        attachRowEventListeners();
    } else {
        userTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="user-management-empty-state">
                    <ion-icon name="people-outline"></ion-icon>
                    <p>Tidak ada data user yang ditemukan</p>
                </td>
            </tr>
        `;
    }
}

// HAPUS fungsi handleEditUser karena sudah tidak perlu
// HAPUS fungsi openAddModal
// HAPUS fungsi openEditModal  
// HAPUS fungsi closeModal
// HAPUS fungsi handleSaveUser
// HAPUS fungsi validateForm
// HAPUS fungsi showFieldError
// HAPUS fungsi clearErrors
// HAPUS fungsi displayErrors

// Handle delete user (TETAP PERTAHANKAN)
function handleDeleteUser(e) {
    const userId = parseInt(e.currentTarget.dataset.id);
    const userName = e.currentTarget.closest('tr').querySelector('strong').textContent;
    
    showConfirm(
        'Hapus User?',
        `Anda yakin ingin menghapus <strong>${userName}</strong>?`,
        'warning',
        'Ya, Hapus!',
        'Batal'
    ).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading di tombol hapus
            const originalHTML = e.currentTarget.innerHTML;
            e.currentTarget.innerHTML = '<ion-icon name="refresh-outline" class="user-management-loading"></ion-icon> Menghapus...';
            e.currentTarget.disabled = true;
            
            // Kirim request hapus ke server
            fetch(`/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Terhapus!', data.message, 'success');
                    applyFilters(); // Refresh tabel
                } else {
                    showAlert('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error!', 'Terjadi kesalahan saat menghapus user', 'error');
            })
            .finally(() => {
                // Reset button state
                e.currentTarget.innerHTML = '<ion-icon name="trash-outline"></ion-icon> Hapus';
                e.currentTarget.disabled = false;
            });
        }
    });
}

// Validasi email (TETAP PERTAHANKAN untuk validasi lain jika perlu)
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Helper functions (TETAP PERTAHANKAN)
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return unsafe;
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

// SweetAlert helpers (TETAP PERTAHANKAN)
function showAlert(title, text, icon, timer = null) {
    const config = {
        title: title,
        text: text,
        icon: icon,
        confirmButtonColor: '#2563eb'
    };
    
    if (timer) {
        config.timer = timer;
        config.showConfirmButton = false;
    }
    
    return Swal.fire(config);
}

function showConfirm(title, html, icon, confirmText, cancelText) {
    return Swal.fire({
        title: title,
        html: html,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText
    });
}

// Keyboard shortcuts (MODIFIKASI)
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K untuk fokus pencarian
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        if (searchInput) searchInput.focus();
    }
    
    // HAPUS shortcut Escape untuk modal
});