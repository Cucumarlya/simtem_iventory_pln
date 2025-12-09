// ========== SCRIPT UNTUK HALAMAN LOGIN ==========
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // ===== Toggle Password Visibility =====
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const eyeIcon = this.querySelector('i');
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }

    // ===== Form Submission Handler =====
    if (loginForm && loginButton) {
        loginForm.addEventListener('submit', function(e) {
            // JANGAN preventDefault() - biarkan form submit normal
            // Hanya tambahkan loading state
            
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            // Validasi sederhana (hanya untuk UX, tidak menghentikan submit)
            if (!email.value || !password.value) {
                showMessage('Harap isi semua field', 'error');
                return; // Biarkan form tetap submit untuk validasi server-side
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showMessage('Format email tidak valid', 'error');
                return; // Biarkan form tetap submit
            }
            
            // Set loading state
            setLoadingState(true);
        });
    }

    // ===== Reset loading state jika halaman dimuat ulang =====
    window.addEventListener('pageshow', function(e) {
        if (e.persisted || (window.performance && window.performance.navigation.type === 2)) {
            setLoadingState(false);
        }
    });

    // ===== Input Focus Effects =====
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 0 0 2px rgba(33, 150, 243, 0.3)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // ===== Enter Key to Submit =====
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const focusedElement = document.activeElement;
            if (focusedElement && (focusedElement.id === 'email' || focusedElement.id === 'password')) {
                loginForm.requestSubmit();
            }
        }
    });

    // ===== Utility Functions =====
    function setLoadingState(isLoading) {
        if (isLoading) {
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            loginButton.disabled = true;
            loginForm.classList.add('loading');
        } else {
            loginButton.innerHTML = 'Login';
            loginButton.disabled = false;
            loginForm.classList.remove('loading');
        }
    }

    function showMessage(message, type = 'info') {
        // Hapus pesan sebelumnya
        const existingMessage = document.querySelector('.alert');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Buat elemen pesan baru
        const messageDiv = document.createElement('div');
        messageDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'}`;
        messageDiv.textContent = message;
        messageDiv.style.marginTop = '15px';
        
        // Sisipkan sebelum tombol login
        loginButton.parentNode.insertBefore(messageDiv, loginButton);
        
        // Hapus otomatis setelah 5 detik
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }

    // Debug info
    console.log('Login page initialized successfully');
    
    // Auto-remove loading state setelah 10 detik (fallback)
    setTimeout(() => {
        setLoadingState(false);
    }, 10000);
});