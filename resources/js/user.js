// Users Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete actions
    const deleteForms = document.querySelectorAll('form[action*="delete"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                e.preventDefault();
            }
        });
    });

    // Password validation
    const passwordForm = document.querySelector('form[method="post"]');
    if (passwordForm) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        function validatePassword() {
            if (password.value && confirmPassword.value) {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Password tidak cocok');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        }
        
        if (password && confirmPassword) {
            password.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validatePassword);
        }
    }

    // Role badge colors
    function updateRoleBadges() {
        const roleCells = document.querySelectorAll('td:nth-child(4)');
        
        roleCells.forEach(cell => {
            const role = cell.textContent.trim().toLowerCase();
            const badge = cell.querySelector('.role-badge') || document.createElement('span');
            
            if (!badge.classList.contains('role-badge')) {
                badge.className = 'role-badge';
                badge.textContent = cell.textContent;
                cell.textContent = '';
                cell.appendChild(badge);
            }
            
            // Remove existing role classes
            badge.classList.remove('role-admin', 'role-petugas', 'role-teknisi');
            
            // Add appropriate class
            if (role === 'admin') {
                badge.classList.add('role-admin');
            } else if (role === 'petugas') {
                badge.classList.add('role-petugas');
            } else if (role === 'teknisi') {
                badge.classList.add('role-teknisi');
            }
        });
    }

    // Initialize role badges
    updateRoleBadges();

    // Search functionality
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Toast notifications
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'warning'}-outline"></ion-icon>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Check for flash messages
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-error');
    
    if (successMessage) {
        showToast(successMessage.textContent, 'success');
    }
    
    if (errorMessage) {
        showToast(errorMessage.textContent, 'error');
    }
});

// Export functionality
function exportUsers(format = 'csv') {
    // Implement export functionality here
    console.log(`Exporting users in ${format} format`);
    // You can implement AJAX call to export endpoint
}

// Bulk actions
function handleBulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('input[name="selected_users[]"]:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        alert('Pilih setidaknya satu user untuk melakukan aksi ini.');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedUsers.length} user?`)) {
            return;
        }
    }
    
    // Implement bulk action here
    console.log(`Performing ${action} on users:`, selectedUsers);
}