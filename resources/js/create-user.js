// resources/js/user-create.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userCreateForm');
    
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select');
    
    // Reset semua input ke state normal saat halaman dimuat
    inputs.forEach(input => {
        input.classList.remove('user-create-input-error');
        
        // Hapus atribut required untuk mencegah validasi HTML5 otomatis
        input.removeAttribute('required');
        
        // Tambah event listener untuk validasi
        input.addEventListener('blur', validateField);
    });
    
    // Validasi form saat submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        
        // Validasi semua field yang required
        inputs.forEach(input => {
            if (input.getAttribute('data-required') === 'true') {
                if (!validateField({ target: input })) {
                    isValid = false;
                }
            }
        });
        
        if (isValid) {
            // Tampilkan loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="user-create-loading"></div> Menyimpan...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
        }
    });
    
    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        const isRequired = field.getAttribute('data-required') === 'true';
        
        // Reset state
        field.classList.remove('user-create-input-error');
        
        // Hapus pesan error sebelumnya
        const existingError = field.parentNode.querySelector('.user-create-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Tampilkan hint kembali
        const existingHint = field.parentNode.querySelector('.user-create-hint');
        if (existingHint) {
            existingHint.style.display = 'block';
        }
        
        let isValid = true;
        let errorMessage = '';
        
        // Validasi required
        if (isRequired && !value) {
            isValid = false;
            errorMessage = getFieldName(field) + ' harus diisi';
        }
        
        // Validasi email
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
        }
        
        // Validasi password
        if (field.id === 'password' && value) {
            if (value.length < 8) {
                isValid = false;
                errorMessage = 'Password minimal 8 karakter';
            }
        }
        
        // Tampilkan error jika ada
        if (!isValid) {
            field.classList.add('user-create-input-error');
            showError(field, errorMessage);
        }
        
        return isValid;
    }
    
    function showError(field, message) {
        // Sembunyikan hint
        const existingHint = field.parentNode.querySelector('.user-create-hint');
        if (existingHint) {
            existingHint.style.display = 'none';
        }
        
        // Buat element error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'user-create-error';
        errorDiv.innerHTML = `
            <ion-icon name="warning-outline"></ion-icon>
            ${message}
        `;
        
        field.parentNode.appendChild(errorDiv);
    }
    
    function getFieldName(field) {
        const label = field.parentNode.querySelector('.user-create-label');
        if (label) {
            return label.textContent.replace('*', '').trim();
        }
        return 'Field ini';
    }
});