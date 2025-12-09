// Penerimaan Module JavaScript - Complete Version

class PenerimaanModule {
    constructor() {
        this.materialCounter = 0;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeDatePicker();
        this.addInitialMaterialRow();
    }

    setupEventListeners() {
        // Delete confirmation
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-delete-item]')) {
                this.confirmDelete(e);
            }
        });

        // Form validation
        const form = document.getElementById('pmPenerimaanForm');
        if (form) {
            form.addEventListener('submit', (e) => this.validateForm(e));
        }

        // Real-time validation
        this.setupRealTimeValidation();

        // File upload preview
        this.setupFileUploads();

        // Material management
        const addMaterialBtn = document.querySelector('[data-add-material]');
        if (addMaterialBtn) {
            addMaterialBtn.addEventListener('click', () => this.addMaterialRow());
        }
    }

    confirmDelete(e) {
        e.preventDefault();
        const button = e.target.closest('[data-delete-item]');
        const id = button.dataset.id;
        const kode = button.dataset.kode;
        const url = button.dataset.url;

        if (confirm(`Apakah Anda yakin ingin menghapus penerimaan ${kode}?`)) {
            this.deleteItem(url);
        }
    }

    async deleteItem(url) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.showToast(data.message || 'Gagal menghapus data', 'error');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showToast('Terjadi kesalahan saat menghapus data', 'error');
        }
    }

    addMaterialRow() {
        const template = document.getElementById('pmMaterialRowTemplate');
        if (!template) return;

        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.pm-material-row');

        // Hide "no material" row
        const noMaterialRow = document.getElementById('pmNoMaterialRow');
        if (noMaterialRow) {
            noMaterialRow.style.display = 'none';
        }

        // Update material index
        this.materialCounter++;
        const indexElement = row.querySelector('.pm-material-index');
        if (indexElement) {
            indexElement.textContent = this.materialCounter;
        }

        // Update input names
        row.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name.includes('[]')) {
                input.setAttribute('name', name.replace('[]', `[${this.materialCounter - 1}]`));
            }
        });

        // Add event listeners to new row
        const select = row.querySelector('select');
        const input = row.querySelector('input[type="number"]');
        const deleteBtn = row.querySelector('[data-remove-material]');

        if (select) {
            select.addEventListener('change', (e) => this.validateMaterialSelect(e.target));
        }

        if (input) {
            input.addEventListener('input', (e) => this.validateMaterialQuantity(e.target));
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => this.removeMaterialRow(row));
        }

        // Add to table
        const materialBody = document.getElementById('pmMaterialBody');
        if (materialBody) {
            materialBody.appendChild(row);
            row.classList.add('pm-slide-in');
        }

        // Update validation
        this.updateMaterialValidation();
    }

    removeMaterialRow(row) {
        row.style.animation = 'pm-fadeIn 0.2s ease-out reverse';

        setTimeout(() => {
            row.remove();
            this.materialCounter--;
            this.updateMaterialIndices();
            this.updateMaterialValidation();
        }, 200);
    }

    updateMaterialIndices() {
        const rows = document.querySelectorAll('.pm-material-row');
        rows.forEach((row, index) => {
            const indexElement = row.querySelector('.pm-material-index');
            if (indexElement) {
                indexElement.textContent = index + 1;
            }

            // Update input names
            row.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            });
        });

        this.materialCounter = rows.length;

        // Show empty state if no rows
        if (this.materialCounter === 0) {
            const noMaterialRow = document.getElementById('pmNoMaterialRow');
            if (noMaterialRow) {
                noMaterialRow.style.display = '';
            }
        }
    }

    updateMaterialValidation() {
        const rows = document.querySelectorAll('.pm-material-row');
        const materialError = document.getElementById('pmMaterialError');

        if (rows.length === 0 && materialError) {
            materialError.classList.remove('hidden');
            return false;
        }

        if (materialError) {
            materialError.classList.add('hidden');
        }

        // Validate each row
        let isValid = true;
        rows.forEach(row => {
            const select = row.querySelector('select');
            const input = row.querySelector('input[type="number"]');

            if (!this.validateMaterialSelect(select) || !this.validateMaterialQuantity(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateMaterialSelect(select) {
        if (!select.value) {
            select.classList.add('border-red-500');
            const feedback = select.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'Material harus dipilih';
                feedback.classList.remove('hidden');
            }
            return false;
        }

        select.classList.remove('border-red-500');
        const feedback = select.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.classList.add('hidden');
        }
        return true;
    }

    validateMaterialQuantity(input) {
        const value = parseInt(input.value);
        const min = parseInt(input.getAttribute('min')) || 1;

        if (isNaN(value) || value < min) {
            input.classList.add('border-red-500');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = `Jumlah minimal ${min}`;
                feedback.classList.remove('hidden');
            }
            return false;
        }

        input.classList.remove('border-red-500');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.classList.add('hidden');
        }
        return true;
    }

    setupRealTimeValidation() {
        const inputs = document.querySelectorAll('.pm-form-input, .pm-form-select, .pm-form-textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => {
                input.classList.remove('border-red-500');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.classList.add('hidden');
                }
            });
        });
    }

    validateField(field) {
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('border-red-500');
            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.classList.remove('hidden');
            }
            return false;
        }

        // Additional validation based on field type
        switch (field.type) {
            case 'email':
                if (!this.isValidEmail(field.value)) {
                    field.classList.add('border-red-500');
                    const feedback = field.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = 'Format email tidak valid';
                        feedback.classList.remove('hidden');
                    }
                    return false;
                }
                break;

            case 'number':
                const min = parseFloat(field.getAttribute('min'));
                const max = parseFloat(field.getAttribute('max'));
                const value = parseFloat(field.value);

                if (!isNaN(min) && value < min) {
                    field.classList.add('border-red-500');
                    const feedback = field.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = `Nilai minimal ${min}`;
                        feedback.classList.remove('hidden');
                    }
                    return false;
                }

                if (!isNaN(max) && value > max) {
                    field.classList.add('border-red-500');
                    const feedback = field.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = `Nilai maksimal ${max}`;
                        feedback.classList.remove('hidden');
                    }
                    return false;
                }
                break;

            case 'date':
                const maxDate = field.getAttribute('max');
                if (maxDate && field.value > maxDate) {
                    field.classList.add('border-red-500');
                    const feedback = field.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = 'Tanggal tidak boleh melebihi hari ini';
                        feedback.classList.remove('hidden');
                    }
                    return false;
                }
                break;
        }

        field.classList.remove('border-red-500');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.classList.add('hidden');
        }
        return true;
    }

    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    setupFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => this.handleFileSelect(e));
        });

        // Drag and drop
        const dropZones = document.querySelectorAll('.pm-file-upload-zone');
        dropZones.forEach(zone => {
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                zone.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = zone.querySelector('input[type="file"]');
                    if (input) {
                        input.files = files;
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                }
            });
        });
    }

    handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file
        if (!this.validateFile(file)) {
            e.target.value = '';
            return;
        }

        // Show preview
        this.showImagePreview(file, e.target);
    }

    validateFile(file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!validTypes.includes(file.type)) {
            this.showToast('Hanya file JPG, PNG, GIF yang diperbolehkan', 'error');
            return false;
        }

        if (file.size > maxSize) {
            this.showToast('Ukuran file maksimal 5MB', 'error');
            return false;
        }

        return true;
    }

    showImagePreview(file, input) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const zone = input.closest('.pm-file-upload-zone');
            if (!zone) return;

            // Hide upload content
            const uploadContent = zone.querySelector('.pm-upload-content');
            if (uploadContent) {
                uploadContent.classList.add('hidden');
            }

            // Create or update preview
            let preview = zone.querySelector('.pm-image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.className = 'pm-image-preview mt-3';
                zone.appendChild(preview);
            }

            preview.innerHTML = `
                <div class="relative">
                    <img src="${e.target.result}" alt="Preview" 
                         class="w-full h-48 object-cover rounded-lg shadow-sm">
                    <button type="button" onclick="pmRemoveImagePreview(this)" 
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 
                                   flex items-center justify-center text-xs hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            // Store reference to input for removal
            preview.dataset.inputId = input.id;
        };

        reader.readAsDataURL(file);
    }

    removeImagePreview(button) {
        const preview = button.closest('.pm-image-preview');
        if (!preview) return;

        const inputId = preview.dataset.inputId;
        const input = document.getElementById(inputId);
        if (input) {
            input.value = '';
        }

        const zone = preview.closest('.pm-file-upload-zone');
        if (zone) {
            const uploadContent = zone.querySelector('.pm-upload-content');
            if (uploadContent) {
                uploadContent.classList.remove('hidden');
            }
        }

        preview.remove();
    }

    async validateForm(e) {
        e.preventDefault();

        let isValid = true;

        // Validate all fields
        const fields = document.querySelectorAll('.pm-form-input, .pm-form-select, .pm-form-textarea');
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Validate materials
        if (!this.updateMaterialValidation()) {
            isValid = false;
        }

        // Validate file uploads
        const fileInputs = document.querySelectorAll('input[type="file"][required]');
        fileInputs.forEach(input => {
            if (!input.files || input.files.length === 0) {
                isValid = false;
                input.classList.add('border-red-500');
                const error = document.getElementById(`${input.id}Error`);
                if (error) {
                    error.classList.remove('hidden');
                }
            }
        });

        if (!isValid) {
            // Scroll to first error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        // Show loading
        this.showLoading();

        // Submit form
        const form = e.target;
        form.submit();
    }

    initializeDatePicker() {
        const dateInput = document.getElementById('pmTanggal');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
        }
    }

    addInitialMaterialRow() {
        setTimeout(() => {
            const materialRows = document.querySelectorAll('.pm-material-row');
            if (materialRows.length === 0) {
                this.addMaterialRow();
            }
        }, 100);
    }

    showLoading() {
        const overlay = document.getElementById('pmLoadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }

        const submitBtn = document.getElementById('pmSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        }
    }

    hideLoading() {
        const overlay = document.getElementById('pmLoadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }

        const submitBtn = document.getElementById('pmSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Penerimaan';
        }
    }

    showToast(message, type = 'info') {
        // Create toast container if not exists
        let container = document.getElementById('pmToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'pmToastContainer';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }

        // Create toast
        const toast = document.createElement('div');
        toast.className = `pm-toast pm-toast-${type} px-4 py-3 rounded-lg shadow-lg 
                           transform transition-transform duration-300 translate-x-full`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="${icons[type] || icons.info}"></i>
                <span class="font-medium">${message}</span>
            </div>
        `;

        container.appendChild(toast);

        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Export function for button click
    exportToExcel(event, url) {
        event.preventDefault();
        this.showLoading();
        window.location.href = url;
        setTimeout(() => this.hideLoading(), 2000);
    }

    // Delete modal functions
    confirmDeleteModal(id, kode) {
        const modal = document.getElementById('pmDeleteModal');
        const itemCode = document.getElementById('pmDeleteItemCode');
        const deleteForm = document.getElementById('pmDeleteForm');

        if (itemCode) {
            itemCode.textContent = `Kode: ${kode}`;
        }

        if (deleteForm) {
            deleteForm.action = `/petugas/penerimaan/${id}`;
        }

        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.opacity = '1';
                modal.querySelector('.pm-modal-content').classList.add('pm-modal-show');
            }, 10);
        }
    }

    closeDeleteModal() {
        const modal = document.getElementById('pmDeleteModal');
        if (modal) {
            modal.style.opacity = '0';
            modal.querySelector('.pm-modal-content').classList.remove('pm-modal-show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }
}

// Initialize module when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.pmModule = new PenerimaanModule();
});

// Global helper functions
window.pmAddMaterialRow = function() {
    if (window.pmModule) {
        window.pmModule.addMaterialRow();
    }
};

window.pmRemoveMaterialRow = function(button) {
    if (window.pmModule) {
        window.pmModule.removeMaterialRow(button.closest('.pm-material-row'));
    }
};

window.pmExportToExcel = function(event, url) {
    if (window.pmModule) {
        window.pmModule.exportToExcel(event, url);
    }
};

window.pmConfirmDeleteModal = function(id, kode) {
    if (window.pmModule) {
        window.pmModule.confirmDeleteModal(id, kode);
    }
};

window.pmCloseDeleteModal = function() {
    if (window.pmModule) {
        window.pmModule.closeDeleteModal();
    }
};

window.pmRemoveImagePreview = function(button) {
    if (window.pmModule) {
        window.pmModule.removeImagePreview(button);
    }
};