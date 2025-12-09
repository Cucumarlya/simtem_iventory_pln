// Pengguna Management JavaScript - Enhanced Features

class PenggunaManager {
    constructor() {
        this.init();
    }

    init() {
        this.initSearchFilter();
        this.initPasswordToggle();
        this.initRoleSelection();
        this.initDeleteConfirmations();
        this.initPasswordVisibility();
        this.initSmoothAnimations();
        this.initRealTimeValidation();
    }

    // Search and Filter Functionality
    initSearchFilter() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const userRows = document.querySelectorAll('.user-row');

        const filterUsers = () => {
            const searchTerm = searchInput.value.toLowerCase();
            const roleValue = roleFilter.value;

            userRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2) .text-sm.font-medium')?.textContent.toLowerCase() || '';
                const email = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                const role = row.getAttribute('data-role');
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = !roleValue || role === roleValue;
                
                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease-out';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(filterUsers, 300));
        }
        
        if (roleFilter) {
            roleFilter.addEventListener('change', filterUsers);
        }
    }

    // Password Toggle in Forms
    initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.relative').querySelector('input');
                const icon = this.querySelector('ion-icon');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('name', 'eye-off');
                    this.classList.add('text-blue-600');
                } else {
                    input.type = 'password';
                    icon.setAttribute('name', 'eye');
                    this.classList.remove('text-blue-600');
                }
            });
        });
    }

    // Enhanced Role Selection
    initRoleSelection() {
        const roleOptions = document.querySelectorAll('.role-option');
        
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected state from all options
                roleOptions.forEach(opt => {
                    opt.querySelector('.role-card').classList.remove('ring-2', 'ring-blue-500');
                });
                
                // Add selected state to current option
                this.querySelector('.role-card').classList.add('ring-2', 'ring-blue-500');
                
                // Update the radio button
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });
    }

    // Delete Confirmation
    initDeleteConfirmations() {
        const deleteForms = document.querySelectorAll('form[action*="delete"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const userName = this.closest('tr').querySelector('td:nth-child(2) .text-sm.font-medium').textContent;
                
                if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                    this.submit();
                }
            });
        });
    }

    // Password Visibility Toggle in Table
    initPasswordVisibility() {
        const showPasswordBtns = document.querySelectorAll('.show-password-btn');
        
        showPasswordBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const displaySpan = this.parentElement.querySelector('.password-display');
                const icon = this.querySelector('ion-icon');
                
                if (displaySpan.textContent === '••••••••') {
                    // Show encrypted placeholder
                    displaySpan.textContent = 'encrypted';
                    displaySpan.style.color = '#10b981';
                    displaySpan.style.fontWeight = '600';
                    icon.setAttribute('name', 'eye-off');
                    this.classList.add('text-green-600');
                    
                    // Hide after 3 seconds
                    setTimeout(() => {
                        displaySpan.textContent = '••••••••';
                        displaySpan.style.color = '#6b7280';
                        displaySpan.style.fontWeight = 'normal';
                        icon.setAttribute('name', 'eye');
                        this.classList.remove('text-green-600');
                    }, 3000);
                } else {
                    displaySpan.textContent = '••••••••';
                    displaySpan.style.color = '#6b7280';
                    displaySpan.style.fontWeight = 'normal';
                    icon.setAttribute('name', 'eye');
                    this.classList.remove('text-green-600');
                }
            });
        });
    }

    // Smooth Animations and Interactions
    initSmoothAnimations() {
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Add loading states to form buttons
        const submitButtons = document.querySelectorAll('form button[type="submit"]');
        
        submitButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                if (form.checkValidity()) {
                    const originalText = this.innerHTML;
                    this.innerHTML = `
                        <div class="flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                            Memproses...
                        </div>
                    `;
                    this.disabled = true;
                    
                    // Revert after 5 seconds (in case of error)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 5000);
                }
            });
        });
    }

    // Real-time validation
    initRealTimeValidation() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        if (passwordInput && confirmInput) {
            const validatePassword = () => {
                if (passwordInput.value && confirmInput.value) {
                    if (passwordInput.value !== confirmInput.value) {
                        confirmInput.style.borderColor = '#ef4444';
                        confirmInput.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                    } else {
                        confirmInput.style.borderColor = '#10b981';
                        confirmInput.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
                    }
                } else {
                    confirmInput.style.borderColor = '#e5e7eb';
                    confirmInput.style.boxShadow = 'none';
                }
            };
            
            passwordInput.addEventListener('input', validatePassword);
            confirmInput.addEventListener('input', validatePassword);
        }
    }

    // Utility function for debouncing
    debounce(func, wait) {
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
}

// Export functionality
function exportUsers(format = 'csv') {
    const exportBtn = document.querySelector('.export-btn');
    if (exportBtn) {
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div> Mengekspor...';
        exportBtn.disabled = true;
    }
    
    // Simulate export process
    setTimeout(() => {
        alert(`Data pengguna berhasil diekspor dalam format ${format.toUpperCase()}`);
        if (exportBtn) {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }
    }, 2000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new PenggunaManager();
});