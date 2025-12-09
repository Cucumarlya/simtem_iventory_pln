// Create Stok Material - Specific JavaScript
class CreateStokMaterial {
    constructor() {
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.initializeCalculations();
        this.initializeDateTime();
        this.initializeFormValidation();
    }

    // Initialize all event listeners
    initializeEventListeners() {
        // Stok input calculations
        const stokInputs = document.querySelectorAll('.stok-input');
        stokInputs.forEach(input => {
            input.addEventListener('input', this.debounce(() => this.calculateStock(), 300));
            input.addEventListener('change', () => this.calculateStock());
        });

        // Material selection auto-fill
        const namaMaterialSelect = document.getElementById('nama_material');
        if (namaMaterialSelect) {
            namaMaterialSelect.addEventListener('change', (e) => this.handleMaterialChange(e));
        }

        // Form submission
        const form = document.getElementById('formTambahStok');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Real-time validation
        const requiredInputs = form.querySelectorAll('input[required], select[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('blur', (e) => this.validateField(e.target));
            input.addEventListener('input', (e) => this.clearFieldError(e.target));
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

    // Handle material change for auto-fill
    handleMaterialChange(event) {
        const materialData = {
            'MCB 2A': { stokAwal: 132, penerimaan: 39, pengeluaranYanbung: 272, mms: 0 },
            'MCB 4A': { stokAwal: 781, penerimaan: 3704, pengeluaranYanbung: 4355, mms: 79 },
            'MCB 6A': { stokAwal: 627, penerimaan: 422, pengeluaranYanbung: 967, mms: 1 },
            'MCB 10A': { stokAwal: 153, penerimaan: 455, pengeluaranYanbung: 571, mms: 33 },
            'MCB 18A': { stokAwal: 119, penerimaan: 110, pengeluaranYanbung: 185, mms: 39 },
            'MCB 20A': { stokAwal: 49, penerimaan: 62, pengeluaranYanbung: 43, mms: 67 },
            'MCB 25A': { stokAwal: 43, penerimaan: 242, pengeluaranYanbung: 238, mms: 44 },
            'MCB 35A': { stokAwal: 100, penerimaan: 5, pengeluaranYanbung: 87, mms: 18 }
        };
        
        const selectedMaterial = event.target.value;
        if (materialData[selectedMaterial]) {
            const data = materialData[selectedMaterial];
            
            document.getElementById('stok_awal').value = data.stokAwal;
            document.getElementById('penerimaan').value = data.penerimaan;
            document.getElementById('pengeluaran_yanbung').value = data.pengeluaranYanbung;
            document.getElementById('mms').value = data.mms;
            
            this.calculateStock();
            this.showNotification(`Data ${selectedMaterial} berhasil dimuat`, 'success');
        }
    }

    // Handle form submission
    handleFormSubmit(event) {
        event.preventDefault();
        
        if (!this.validateForm()) {
            this.showNotification('Harap isi semua field yang wajib diisi!', 'error');
            return;
        }

        const submitBtn = event.target.querySelector('.primary-btn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<ion-icon name="refresh-outline" class="spinning"></ion-icon> Menyimpan...';
        submitBtn.disabled = true;

        // Simulate API call or proceed with form submission
        setTimeout(() => {
            // For demo purposes, we'll submit the form after a delay
            event.target.submit();
        }, 1500);
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

        return isValid;
    }

    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        
        if (!value) {
            this.showFieldError(field, 'Field ini wajib diisi');
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
    window.createStokMaterialApp = new CreateStokMaterial();
});

// Additional utility functions
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function validateNumberInput(input) {
    // Remove any non-numeric characters except decimal point
    input.value = input.value.replace(/[^\d.]/g, '');
    
    // Ensure only one decimal point
    const parts = input.value.split('.');
    if (parts.length > 2) {
        input.value = parts[0] + '.' + parts.slice(1).join('');
    }
}