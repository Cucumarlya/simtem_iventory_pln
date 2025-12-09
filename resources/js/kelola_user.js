// User Management System
class UserManagement {
    constructor() {
        this.initializeElements();
        this.bindEvents();
        this.initializeDeleteButtons();
    }

    initializeElements() {
        // Search and filter elements
        this.searchInput = document.getElementById('searchInput');
        this.roleFilter = document.getElementById('roleFilter');
        this.statusFilter = document.getElementById('statusFilter');
        this.userTableContainer = document.getElementById('userTableContainer');
        
        // State
        this.searchTimeout = null;
        this.isSearching = false;
    }

    bindEvents() {
        // Search with debounce
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => this.debouncedSearch());
        }

        // Filter change events
        if (this.roleFilter) {
            this.roleFilter.addEventListener('change', () => this.performSearch());
        }

        if (this.statusFilter) {
            this.statusFilter.addEventListener('change', () => this.performSearch());
        }
    }

    debouncedSearch() {
        clearTimeout(this.searchTimeout);
        this.searchInput.parentElement.classList.add('searching');
        this.searchTimeout = setTimeout(() => {
            this.performSearch();
            this.searchInput.parentElement.classList.remove('searching');
        }, 500);
    }

    performSearch() {
        if (this.isSearching) return;
        
        this.isSearching = true;
        const search = this.searchInput.value;
        const role = this.roleFilter.value;
        const status = this.statusFilter.value;
        
        this.showLoading();
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        if (status) params.append('status', status);
        params.append('ajax', '1');
        
        fetch(`/admin/kelola-user/search?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            this.userTableContainer.innerHTML = html;
            this.initializeDeleteButtons();
            this.isSearching = false;
            
            // Scroll to table smoothly
            this.userTableContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            this.showError('Terjadi kesalahan saat memuat data');
            this.isSearching = false;
        });
    }

    showLoading() {
        this.userTableContainer.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="user-loading">
                        <div class="user-loading-spinner"></div>
                        <p class="text-gray-600 mt-2">Memuat data...</p>
                    </div>
                </td>
            </tr>
        `;
    }

    showError(message) {
        this.userTableContainer.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">
                            ${message}
                        </h3>
                        <button onclick="userManager.performSearch()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150 mt-2">
                            <i class="fas fa-redo mr-2"></i>
                            Coba Lagi
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    initializeDeleteButtons() {
        document.querySelectorAll('.user-action-delete-full').forEach(button => {
            // Remove existing event listeners to prevent duplicates
            button.removeEventListener('click', this.handleDeleteClick);
            button.addEventListener('click', (e) => this.handleDeleteClick(e, button));
        });
    }

    handleDeleteClick(e, button) {
        e.preventDefault();
        e.stopPropagation();
        
        if (button.disabled) {
            return;
        }
        
        const userId = button.getAttribute('data-id');
        const userName = button.getAttribute('data-user-name') || 'user ini';
        const userEmail = button.getAttribute('data-user-email') || '';
        const deleteUrl = button.getAttribute('data-delete-url');
        
        this.confirmDelete(userId, userName, userEmail, deleteUrl, button);
    }

    confirmDelete(userId, userName, userEmail, deleteUrl, button) {
        Swal.fire({
            title: 'Hapus User?',
            html: `
                <div class="text-left">
                    <p class="mb-3">Apakah Anda yakin ingin menghapus user ini?</p>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-user text-gray-500 mr-2 w-5"></i>
                            <span class="font-semibold">${userName}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-500 mr-2 w-5"></i>
                            <span>${userEmail}</span>
                        </div>
                    </div>
                    <p class="text-sm text-red-600 mt-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        User yang dihapus tidak dapat dikembalikan.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: 'rgba(0,0,0,0.4)',
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteUser(userId, userName, deleteUrl, button);
            }
        });
    }

    deleteUser(userId, userName, deleteUrl, button) {
        const originalText = button.innerHTML;
        const originalClass = button.className;
        
        // Show loading state on button
        button.innerHTML = '<span class="user-loading-spinner" style="width: 1rem; height: 1rem; display: inline-block;"></span> Memproses...';
        button.disabled = true;
        button.style.opacity = '0.7';
        
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                } else {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                // Reload the user list after a short delay
                setTimeout(() => {
                    this.performSearch();
                }, 500);
            } else {
                throw new Error(data.message || 'Gagal menghapus user');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            
            Swal.fire({
                title: 'Error!',
                html: `
                    <div class="text-left">
                        <p class="mb-2">Gagal menghapus user:</p>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <p class="text-red-700">${error.message || 'Terjadi kesalahan saat menghapus user.'}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">
                            Pastikan user ini tidak memiliki transaksi aktif.
                        </p>
                    </div>
                `,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                width: '500px'
            });
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
            button.style.opacity = '1';
        });
    }
}

// Form Validation Functions
class UserFormValidation {
    static initPasswordStrength() {
        const passwordInput = document.getElementById('password');
        if (!passwordInput) return;

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            
            if (!strengthBar || !strengthText) return;
            
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 25) {
                strengthBar.style.background = '#ef4444';
                strengthText.textContent = 'Password sangat lemah';
                strengthText.className = 'text-red-600 text-sm mt-1';
            } else if (strength < 50) {
                strengthBar.style.background = '#f97316';
                strengthText.textContent = 'Password lemah';
                strengthText.className = 'text-orange-600 text-sm mt-1';
            } else if (strength < 75) {
                strengthBar.style.background = '#eab308';
                strengthText.textContent = 'Password cukup';
                strengthText.className = 'text-yellow-600 text-sm mt-1';
            } else {
                strengthBar.style.background = '#10b981';
                strengthText.textContent = 'Password kuat';
                strengthText.className = 'text-green-600 text-sm mt-1';
            }
        });
    }

    static initPasswordMatch() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        if (!passwordInput || !confirmInput) return;

        const checkMatch = () => {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            const matchIndicator = document.getElementById('passwordMatch');
            
            if (!matchIndicator) return;
            
            if (confirmPassword === '') {
                matchIndicator.style.display = 'none';
            } else if (password === confirmPassword) {
                matchIndicator.style.display = 'block';
                matchIndicator.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Password cocok';
                matchIndicator.className = 'text-green-600 text-sm mt-1';
            } else {
                matchIndicator.style.display = 'block';
                matchIndicator.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Password tidak cocok';
                matchIndicator.className = 'text-red-600 text-sm mt-1';
            }
        };

        passwordInput.addEventListener('input', checkMatch);
        confirmInput.addEventListener('input', checkMatch);
    }

    static initFormValidation() {
        const userForm = document.getElementById('userForm');
        const editUserForm = document.getElementById('editUserForm');
        
        const validateForm = (form, e) => {
            const password = form.querySelector('#password')?.value;
            const confirmPassword = form.querySelector('#password_confirmation')?.value;
            
            if (password && confirmPassword && password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Password dan konfirmasi password tidak cocok',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
                return false;
            }
            return true;
        };
        
        if (userForm) {
            userForm.addEventListener('submit', (e) => validateForm(userForm, e));
        }
        
        if (editUserForm) {
            editUserForm.addEventListener('submit', (e) => validateForm(editUserForm, e));
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on user management page
    const userManagementContainer = document.querySelector('.user-management-container');
    if (userManagementContainer) {
        // Initialize User Management System
        window.userManager = new UserManagement();
    }
    
    // Initialize Form Validation (for create/edit forms)
    UserFormValidation.initPasswordStrength();
    UserFormValidation.initPasswordMatch();
    UserFormValidation.initFormValidation();
    
    // Add SweetAlert custom styles
    const style = document.createElement('style');
    style.textContent = `
        .swal2-popup {
            border-radius: 0.75rem !important;
            padding: 2rem !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        }
        
        .swal2-title {
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            color: #1f2937 !important;
            margin-bottom: 1rem !important;
        }
        
        .swal2-html-container {
            font-size: 0.95rem !important;
            color: #6b7280 !important;
            line-height: 1.6 !important;
            text-align: left !important;
            margin: 1rem 0 !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .swal2-confirm {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3) !important;
            border: none !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 8px -1px rgba(239, 68, 68, 0.4) !important;
        }
        
        .swal2-cancel {
            background: white !important;
            color: #6b7280 !important;
            border: 2px solid #e5e7eb !important;
        }
        
        .swal2-cancel:hover {
            background: #f9fafb !important;
            border-color: #6b7280 !important;
            color: #4b5563 !important;
            transform: translateY(-2px) !important;
        }
        
        .swal2-icon {
            border-width: 3px !important;
            margin: 0 auto 1rem !important;
        }
        
        .swal2-success [class^=swal2-success-line] {
            background-color: #10b981 !important;
        }
        
        .swal2-success .swal2-success-ring {
            border-color: rgba(16, 185, 129, 0.3) !important;
        }
        
        .swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
        
        .swal2-error {
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }
    `;
    document.head.appendChild(style);
});

// Export for global access
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { UserManagement, UserFormValidation };
}

// Global helper function
function confirmDeleteUser(button) {
    if (window.userManager) {
        window.userManager.handleDeleteClick(new Event('click'), button);
    }
}