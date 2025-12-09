// resources/js/user-edit.js
class UserEditForm {
    constructor() {
        this.form = document.getElementById('userEditForm');
        this.submitBtn = this.form?.querySelector('button[type="submit"]');
        this.isSubmitting = false;
        this.originalData = {};
        
        this.init();
    }
    
    init() {
        if (!this.form) return;
        
        this.captureOriginalData();
        this.setupRealTimeValidation();
        this.setupFormSubmission();
        this.setupPasswordValidation();
        this.setupPasswordStrength();
        this.setupPasswordRequirements();
        this.setupKeyboardShortcuts();
        this.setupAutoSave();
        this.setupNotifications();
        this.setupChangeDetection();
    }
    
    captureOriginalData() {
        // Capture original form data for change detection
        const formData = new FormData(this.form);
        for (let [key, value] of formData.entries()) {
            this.originalData[key] = value;
        }
        
        // Store original checkbox state
        const isActiveCheckbox = document.getElementById('is_active');
        if (isActiveCheckbox) {
            this.originalData.is_active = isActiveCheckbox.checked;
        }
    }
    
    setupRealTimeValidation() {
        const inputs = this.form.querySelectorAll('.user-edit-input, .user-edit-select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', (e) => {
                this.validateField(e.target);
            });
            
            input.addEventListener('input', (e) => {
                this.clearFieldError(e.target);
                
                if (e.target.name === 'password') {
                    this.updatePasswordStrength(e.target.value);
                    this.updatePasswordRequirements(e.target.value);
                }
                
                if (e.target.name === 'password_confirmation') {
                    this.validatePasswordMatch();
                }
            });
        });
    }
    
    setupFormSubmission() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (this.isSubmitting) return;
            
            if (this.validateForm()) {
                this.showLoadingState();
                this.form.submit();
            } else {
                this.showFirstError();
                this.shakeForm();
            }
        });
    }
    
    setupPasswordValidation() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        
        if (passwordInput && passwordConfirmationInput) {
            passwordConfirmationInput.addEventListener('input', () => {
                this.validatePasswordMatch();
            });
        }
    }
    
    setupPasswordStrength() {
        const passwordInput = document.getElementById('password');
        if (!passwordInput) return;
        
        let strengthContainer = passwordInput.parentNode.querySelector('.password-strength');
        if (!strengthContainer) {
            strengthContainer = document.createElement('div');
            strengthContainer.className = 'password-strength';
            
            const strengthFill = document.createElement('div');
            strengthFill.className = 'password-strength-fill';
            
            strengthContainer.appendChild(strengthFill);
            passwordInput.parentNode.appendChild(strengthContainer);
            
            this.strengthFill = strengthFill;
        } else {
            this.strengthFill = strengthContainer.querySelector('.password-strength-fill');
        }
    }
    
    setupPasswordRequirements() {
        this.requirements = {
            length: { regex: /.{8,}/, element: document.querySelector('[data-requirement="length"]') },
            lowercase: { regex: /[a-z]/, element: document.querySelector('[data-requirement="lowercase"]') },
            uppercase: { regex: /[A-Z]/, element: document.querySelector('[data-requirement="uppercase"]') },
            number: { regex: /\d/, element: document.querySelector('[data-requirement="number"]') },
            special: { regex: /[^a-zA-Z\d]/, element: document.querySelector('[data-requirement="special"]') }
        };
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.form.dispatchEvent(new Event('submit'));
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                const backBtn = document.querySelector('.user-edit-back-btn');
                if (backBtn) {
                    backBtn.click();
                }
            }
        });
    }
    
    setupAutoSave() {
        const inputs = this.form.querySelectorAll('input, select');
        const storageKey = 'userEditFormDraft';
        
        this.loadDraft(storageKey);
        
        const saveDraft = this.debounce(() => {
            this.saveDraft(storageKey);
        }, 1000);
        
        inputs.forEach(input => {
            input.addEventListener('input', saveDraft);
        });
        
        this.form.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });
    }
    
    setupNotifications() {
        // Auto-hide notifications after 5 seconds
        const notifications = document.querySelectorAll('.user-edit-notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.animation = 'user-edit-slideOutRight 0.3s ease-in forwards';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        });
    }
    
    setupChangeDetection() {
        // Add change detection for critical fields
        const roleSelect = document.getElementById('role');
        const isActiveCheckbox = document.getElementById('is_active');
        
        if (roleSelect) {
            roleSelect.addEventListener('change', (e) => {
                if (e.target.value !== this.originalData.role) {
                    this.showRoleChangeWarning();
                }
            });
        }
        
        if (isActiveCheckbox) {
            isActiveCheckbox.addEventListener('change', (e) => {
                if (e.target.checked !== this.originalData.is_active) {
                    this.showStatusChangeWarning(e.target.checked);
                }
            });
        }
    }
    
    showRoleChangeWarning() {
        // Optional: Show role change confirmation
        console.log('Role changed - consider adding confirmation dialog');
    }
    
    showStatusChangeWarning(isActive) {
        // Optional: Show status change confirmation
        console.log(`User status changed to ${isActive ? 'active' : 'inactive'} - consider adding confirmation dialog`);
    }
    
    validateForm() {
        const requiredFields = this.form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        if (!this.validatePasswordMatch()) {
            isValid = false;
        }
        
        return isValid;
    }
    
    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        
        this.clearFieldError(field);
        
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, `${this.getFieldLabel(field)} harus diisi`);
            return false;
        }
        
        if (field.type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'Format email tidak valid');
            return false;
        }
        
        // Password validation only if provided
        if (fieldName === 'password' && value) {
            if (value.length < 8) {
                this.showFieldError(field, 'Password minimal 8 karakter');
                return false;
            }
            
            const strength = this.calculatePasswordStrength(value);
            if (strength.score < 3) {
                this.showFieldError(field, 'Password terlalu lemah. Gunakan kombinasi huruf, angka, dan simbol');
                return false;
            }
        }
        
        return true;
    }
    
    validatePasswordMatch() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        
        if (!passwordInput || !passwordConfirmationInput) return true;
        
        const password = passwordInput.value;
        const passwordConfirmation = passwordConfirmationInput.value;
        
        const matchIndicator = passwordConfirmationInput.parentNode.querySelector('.password-match-indicator .match-text');
        
        // Only validate if password is provided
        if (password) {
            if (passwordConfirmation) {
                if (password === passwordConfirmation) {
                    this.clearFieldError(passwordConfirmationInput);
                    if (matchIndicator) {
                        matchIndicator.textContent = 'Password cocok';
                        matchIndicator.className = 'match-text match-valid';
                    }
                    return true;
                } else {
                    this.showFieldError(passwordConfirmationInput, 'Password dan konfirmasi password tidak sama');
                    if (matchIndicator) {
                        matchIndicator.textContent = 'Password tidak cocok';
                        matchIndicator.className = 'match-text match-invalid';
                    }
                    return false;
                }
            } else {
                // Password provided but no confirmation
                this.showFieldError(passwordConfirmationInput, 'Harap konfirmasi password baru');
                if (matchIndicator) {
                    matchIndicator.textContent = 'Harap konfirmasi password';
                    matchIndicator.className = 'match-text match-invalid';
                }
                return false;
            }
        } else {
            // No password provided, clear any errors
            this.clearFieldError(passwordConfirmationInput);
            if (matchIndicator) {
                matchIndicator.textContent = '';
                matchIndicator.className = 'match-text';
            }
        }
        
        return true;
    }
    
    calculatePasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (password.match(/[a-z]/)) score++;
        if (password.match(/[A-Z]/)) score++;
        if (password.match(/\d/)) score++;
        if (password.match(/[^a-zA-Z\d]/)) score++;
        
        return { score };
    }
    
    updatePasswordStrength(password) {
        if (!this.strengthFill) return;
        
        const { score } = this.calculatePasswordStrength(password);
        
        if (password.length === 0) {
            this.strengthFill.style.width = '0';
            this.strengthFill.className = 'password-strength-fill';
        } else if (score <= 2) {
            this.strengthFill.style.width = '33%';
            this.strengthFill.className = 'password-strength-fill password-strength-weak';
        } else if (score <= 4) {
            this.strengthFill.style.width = '66%';
            this.strengthFill.className = 'password-strength-fill password-strength-medium';
        } else {
            this.strengthFill.style.width = '100%';
            this.strengthFill.className = 'password-strength-fill password-strength-strong';
        }
    }
    
    updatePasswordRequirements(password) {
        if (!this.requirements) return;
        
        Object.keys(this.requirements).forEach(key => {
            const requirement = this.requirements[key];
            if (requirement.element) {
                const isMet = requirement.regex.test(password);
                requirement.element.classList.toggle('requirement-met', isMet);
            }
        });
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('user-edit-input-error');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'user-edit-error';
        errorElement.innerHTML = `<ion-icon name="warning-outline"></ion-icon> ${message}`;
        errorElement.setAttribute('role', 'alert');
        
        field.parentNode.insertBefore(errorElement, field.nextSibling);
        
        if (!this.firstErrorField) {
            this.firstErrorField = field;
        }
    }
    
    clearFieldError(field) {
        field.classList.remove('user-edit-input-error');
        
        const errorElement = field.parentNode.querySelector('.user-edit-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    showFirstError() {
        if (this.firstErrorField) {
            this.firstErrorField.focus();
            this.firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            this.firstErrorField = null;
        }
    }
    
    shakeForm() {
        this.form.style.animation = 'user-edit-shake 0.5s ease-in-out';
        setTimeout(() => {
            this.form.style.animation = '';
        }, 500);
    }
    
    showLoadingState() {
        this.isSubmitting = true;
        
        this.submitBtn.setAttribute('data-original-text', this.submitBtn.innerHTML);
        this.submitBtn.innerHTML = '<span class="user-edit-loading"></span> Memperbarui...';
        this.submitBtn.disabled = true;
        
        this.form.classList.add('user-edit-form-loading');
        
        setTimeout(() => {
            this.hideLoadingState();
        }, 10000);
    }
    
    hideLoadingState() {
        this.isSubmitting = false;
        
        const originalText = this.submitBtn.getAttribute('data-original-text');
        if (originalText) {
            this.submitBtn.innerHTML = originalText;
        }
        
        this.submitBtn.disabled = false;
        this.form.classList.remove('user-edit-form-loading');
    }
    
    saveDraft(storageKey) {
        const formData = new FormData(this.form);
        const draftData = {};
        
        for (let [key, value] of formData.entries()) {
            draftData[key] = value;
        }
        
        localStorage.setItem(storageKey, JSON.stringify(draftData));
    }
    
    loadDraft(storageKey) {
        const draft = localStorage.getItem(storageKey);
        if (!draft) return;
        
        try {
            const draftData = JSON.parse(draft);
            Object.keys(draftData).forEach(key => {
                const field = this.form.querySelector(`[name="${key}"]`);
                if (field && !field.value) {
                    field.value = draftData[key];
                    
                    if (field.hasAttribute('required')) {
                        this.validateField(field);
                    }
                    
                    if (key === 'password') {
                        this.updatePasswordStrength(draftData[key]);
                        this.updatePasswordRequirements(draftData[key]);
                    }
                }
            });
            
            this.showDraftNotification();
        } catch (error) {
            console.warn('Failed to load draft:', error);
        }
    }
    
    showDraftNotification() {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: var(--pln-blue-light);
            border: 1px solid var(--pln-blue);
            color: var(--pln-blue-dark);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            z-index: 999;
            font-size: 0.875rem;
            box-shadow: var(--shadow-md);
            animation: user-edit-slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        `;
        notification.innerHTML = `
            <ion-icon name="document-text-outline"></ion-icon>
            <span>Draft tersimpan telah dimuat</span>
        `;
        notification.setAttribute('role', 'status');
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'user-edit-slideOutRight 0.3s ease-in forwards';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    getFieldLabel(field) {
        const label = field.parentNode.querySelector('.user-edit-label');
        if (label) {
            return label.textContent.replace('*', '').trim();
        }
        return 'Field ini';
    }
    
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new UserEditForm();
});

// Handle browser back button with form data warning
window.addEventListener('beforeunload', function(e) {
    const form = document.getElementById('userEditForm');
    if (form) {
        const formData = new FormData(form);
        let hasChanges = false;
        
        for (let [key, value] of formData.entries()) {
            // Check if value has changed from original
            if (value !== '' && value != form.querySelector(`[name="${key}"]`).defaultValue) {
                hasChanges = true;
                break;
            }
        }
        
        // Also check checkbox
        const isActiveCheckbox = document.getElementById('is_active');
        if (isActiveCheckbox && isActiveCheckbox.checked != isActiveCheckbox.defaultChecked) {
            hasChanges = true;
        }
        
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
            return e.returnValue;
        }
    }
});