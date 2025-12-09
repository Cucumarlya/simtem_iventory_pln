// Edit Stok Material - Specific JavaScript
class EditStokMaterial {
    constructor() {
        this.originalData = {};
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.initializeCalculations();
        this.initializeDateTime();
        this.initializeFormValidation();
        this.captureOriginalData();
    }

    // Capture original form data for change detection
    captureOriginalData() {
        const form = document.getElementById('formEditStok');
        if (!form) return;

        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            this.originalData[key] = value;
        }
    }

    // Check if form has changes
    hasFormChanges() {
        const form = document.getElementById('formEditStok');
        if (!form) return false;

        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            if (this.originalData[key] !== value) {
                return true;
            }
        }
        return false;
    }

    // Initialize all event listeners
    initializeEventListeners() {
        // Stok input calculations
        const stokInputs = document.querySelectorAll('.stok-input');
        stokInputs.forEach(input => {
            input.addEventListener('input', this.debounce(() => this.calculateStock(), 300));
            input.addEventListener('change', () => this.calculateStock());
        });

        // Material selection
        const namaMaterialSelect = document.getElementById('nama_material');
        if (namaMaterialSelect) {
            namaMaterialSelect.addEventListener('change', (e) => this.handleMaterialChange(e));
        }

        // Form submission
        const form = document.getElementById('formEditStok');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Before unload warning
        window.addEventListener('beforeunload', (e) => this.handleBeforeUnload(e));

        // Real-time validation
        const requiredInputs = form.querySelectorAll('input[required], select[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('blur', (e) => this.validateField(e.target));
            input.addEventListener('input', (e) => this.clearFieldError(e.target));
        });

        // Input change detection
        const allInputs = form.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            input.addEventListener('change', () => this.checkForChanges());
        });
    }

    // Initialize calculations
    initializeCalculations() {
        this.calculateStock();
    }

    // Initialize date time display
    initializeDateTime() {
        this.updateDateTime();
        setInterval(() => this.updateDateTime(), 1000);
    }

    // Initialize form validation
    initializeFormValidation() {
        // Additional validation rules can be added here
    }

    // Calculate stock values
    calculateStock() {
        try {
            const stokAwal = parseFloat(document.getElementById('stok_awal').value) || 0;
            const penerimaan = parseFloat(document.getElementById('penerimaan').value) || 0;
            const pengeluaranYanbung = parseFloat(document.getElementById('pengeluaran_yanbung').value) || 0;
            const pengeluaranP2tl = parseFloat(document.getElementById('pengeluaran_p2tl').value) || 0;
            const pengeluaranGangguan = parseFloat(document.getElementById('pengeluaran_gangguan').value) || 0;
            const inTransitPenerimaan = parseFloat(document.getElementById('in_transit_penerimaan').value) || 0;
            const inTransitPengeluaran = parseFloat(document.getElementById('in_transit_pengeluaran').value) || 0;
            const mms = parseFloat(document.getElementById('mms').value) || 0;
            const koefSelisih = parseFloat(document.getElementById('koef_selisih_lemari_putih').value) || 0;
            
            const totalPenerimaan = penerimaan + inTransitPenerimaan;
            const totalPengeluaran = pengeluaranYanbung + pengeluaranP2tl + pengeluaranGangguan + inTransitPengeluaran;
            
            const stokAkhir = stokAwal + totalPenerimaan - totalPengeluaran;
            const selisih = stokAkhir - mms;
            const sisaReal = stokAkhir - koefSelisih;
            
            this.updateResultDisplay('stok_akhir', stokAkhir);
            this.updateResultDisplay('selisih', selisih);
            this.updateResultDisplay('sisa_real', sisaReal);
            
        } catch (error) {
            console.error('Error in calculateStock:', error);
        }
    }

    // Update result display with styling
    updateResultDisplay(elementId, value) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const roundedValue = Math.round(value * 100) / 100;
        element.value = roundedValue;
        
        // Remove existing classes
        element.classList.remove('negative', 'positive', 'neutral');
        
        // Add appropriate class based on value
        if (value < 0) {
            element.classList.add('negative');
        } else if (value > 0) {
            element.classList.add('positive');
        } else {
            element.classList.add('neutral');
        }
    }

    // Handle material change
    handleMaterialChange(event) {
        this.checkForChanges();
        this.showNotification('Material berhasil diubah', 'info');
    }

    // Handle form submission
    handleFormSubmit(event) {
        event.preventDefault();
        
        if (!this.validateForm()) {
            this.showNotification('Harap perbaiki error pada form!', 'error');
            return;
        }

        const submitBtn = event.target.querySelector('.primary-btn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<ion-icon name="refresh-outline" class="spinning"></ion-icon> Memperbarui...';
        submitBtn.disabled = true;

        // Submit the form
        setTimeout(() => {
            event.target.submit();
        }, 1000);
    }

    // Handle before unload
    handleBeforeUnload(event) {
        if (this.hasFormChanges()) {
            event.preventDefault();
            event.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
            return event.returnValue;
        }
    }

    // Check for changes and update UI
    checkForChanges() {
        const hasChanges = this.hasFormChanges();
        const saveBtn = document.querySelector('.primary-btn');
        
        if (hasChanges) {
            saveBtn.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
            saveBtn.innerHTML = '<ion-icon name="save-outline"></ion-icon> Simpan Perubahan*';
        } else {
            saveBtn.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            saveBtn.innerHTML = '<ion-icon name="save-outline"></ion-icon> Simpan Perubahan';
        }
    }

    // Validate entire form
    validateForm() {
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Field ini wajib diisi');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Additional validation for stock values
        const stokAwal = parseFloat(document.getElementById('stok_awal').value) || 0;
        if (stokAwal < 0) {
            this.showFieldError(document.getElementById('stok_awal'), 'Stok awal tidak boleh negatif');
            isValid = false;
        }

        const mms = parseFloat(document.getElementById('mms').value) || 0;
        if (mms < 0) {
            this.showFieldError(document.getElementById('mms'), 'MMS tidak boleh negatif');
            isValid = false;
        }

        return isValid;
    }

    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        
        if (!value) {
            this.showFieldError(field, 'Field ini wajib diisi');
        } else if (field.type === 'number' && parseFloat(value) < 0) {
            this.showFieldError(field, 'Nilai tidak boleh negatif');
        } else {
            this.clearFieldError(field);
        }
    }

    // Show field error
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    // Clear field error
    clearFieldError(field) {
        field.classList.remove('error');
        
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    // Update date time display
    updateDateTime() {
        const now = new Date();
        const dateTimeString = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) + ' ' + now.toLocaleTimeString('id-ID');
        
        const datetimeElement = document.getElementById('current_datetime');
        if (datetimeElement) {
            datetimeElement.textContent = dateTimeString;
        }
    }

    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.custom-notification').forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `custom-notification ${type}`;
        
        const icons = {
            success: 'checkmark-circle',
            error: 'warning',
            info: 'information-circle'
        };

        notification.innerHTML = `
            <div class="notification-content">
                <ion-icon name="${icons[type]}"></ion-icon>
                <p>${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.editStokMaterialApp = new EditStokMaterial();
});

// Additional utility functions for edit
function confirmCancel() {
    if (window.editStokMaterialApp.hasFormChanges()) {
        return confirm('Anda memiliki perubahan yang belum disimpan. Yakin ingin membatalkan?');
    }
    return true;
}

function resetForm() {
    if (confirm('Yakin ingin mengembalikan ke data semula?')) {
        window.location.reload();
    }
}