// Master Material Form Management Script
class MasterMaterialFormManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeForm();
        console.log('Master Material Form Manager initialized');
    }

    bindEvents() {
        const form = document.getElementById('masterMaterialForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                } else {
                    this.showLoading();
                }
            });
        }

        // Real-time validation on input change
        const inputs = form?.querySelectorAll('input[required]');
        if (inputs) {
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
                
                input.addEventListener('input', () => {
                    this.clearFieldError(input);
                });
            });
        }

        // Auto-format kode material
        const kodeInput = document.getElementById('kode_material');
        if (kodeInput) {
            kodeInput.addEventListener('input', (e) => {
                this.formatKodeMaterial(e.target);
            });
        }

        // Number input validation
        const numberInputs = form?.querySelectorAll('input[type="number"]');
        if (numberInputs) {
            numberInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.validateNumberInput(input);
                });
            });
        }
    }

    initializeForm() {
        // Set focus to first input
        const firstInput = document.querySelector('#masterMaterialForm input');
        if (firstInput) {
            firstInput.focus();
        }
        
        // Add animation to form
        const formCard = document.querySelector('.master-material-form-card');
        if (formCard) {
            formCard.style.opacity = '0';
            formCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                formCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                formCard.style.opacity = '1';
                formCard.style.transform = 'translateY(0)';
            }, 100);
        }
    }

    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        // Additional validation for number inputs
        const stokAwal = form.querySelector('#stok_awal');
        const minStok = form.querySelector('#min_stok');
        
        if (stokAwal && minStok) {
            if (parseInt(stokAwal.value) < 0) {
                this.showFieldError(stokAwal, 'Stok awal tidak boleh negatif');
                isValid = false;
            }
            
            if (parseInt(minStok.value) < 0) {
                this.showFieldError(minStok, 'Stok minimum tidak boleh negatif');
                isValid = false;
            }
        }

        return isValid;
    }

    validateField(input) {
        let isValid = true;
        let errorMessage = '';
        
        // Check if empty
        if (!input.value.trim()) {
            errorMessage = 'Field ini wajib diisi';
            isValid = false;
        }
        // Check kode material format
        else if (input.id === 'kode_material') {
            if (!/^[A-Za-z0-9\-]+$/.test(input.value)) {
                errorMessage = 'Kode hanya boleh berisi huruf, angka, dan tanda hubung';
                isValid = false;
            }
        }
        // Check number inputs
        else if (input.type === 'number') {
            const value = parseInt(input.value);
            if (isNaN(value) || value < 0) {
                errorMessage = 'Nilai harus angka positif';
                isValid = false;
            }
        }
        
        if (!isValid) {
            this.showFieldError(input, errorMessage);
        } else {
            this.clearFieldError(input);
            this.showFieldSuccess(input);
        }
        
        return isValid;
    }

    validateNumberInput(input) {
        const value = parseInt(input.value);
        if (isNaN(value) || value < 0) {
            input.value = '0';
            this.showFieldError(input, 'Nilai harus angka positif');
            return false;
        }
        this.clearFieldError(input);
        return true;
    }

    formatKodeMaterial(input) {
        let value = input.value.toUpperCase();
        value = value.replace(/[^A-Z0-9\-]/g, '');
        input.value = value;
    }

    showFieldError(input, message) {
        const wrapper = input.closest('.master-material-form-group');
        let errorElement = wrapper.querySelector('.master-material-error-message');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'master-material-error-message';
            wrapper.appendChild(errorElement);
        }
        
        errorElement.innerHTML = `<ion-icon name="warning-outline"></ion-icon> ${message}`;
        input.classList.add('input-error');
        
        // Scroll to error if needed
        if (!this.isElementInViewport(errorElement)) {
            errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    showFieldSuccess(input) {
        input.classList.remove('input-error');
        const wrapper = input.closest('.master-material-form-group');
        const errorElement = wrapper.querySelector('.master-material-error-message');
        
        if (errorElement) {
            errorElement.remove();
        }
    }

    clearFieldError(input) {
        input.classList.remove('input-error');
        const wrapper = input.closest('.master-material-form-group');
        const errorElement = wrapper.querySelector('.master-material-error-message');
        
        if (errorElement) {
            errorElement.remove();
        }
    }

    isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }

    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    showSuccessMessage(message) {
        // Remove existing success messages
        const existingMessages = document.querySelectorAll('.master-material-success-message');
        existingMessages.forEach(msg => msg.remove());
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'master-material-success-message';
        messageDiv.innerHTML = `
            <ion-icon name="checkmark-circle-outline"></ion-icon>
            <div>
                <h4>Berhasil!</h4>
                <p>${message}</p>
            </div>
        `;
        
        const formContainer = document.querySelector('.master-material-form-container');
        if (formContainer) {
            const backNavigation = formContainer.querySelector('.master-material-back-navigation');
            if (backNavigation) {
                formContainer.insertBefore(messageDiv, backNavigation.nextSibling);
            }
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const formWrapper = document.querySelector('.master-material-form-wrapper');
    if (formWrapper) {
        window.masterMaterialFormManager = new MasterMaterialFormManager();
    }
});