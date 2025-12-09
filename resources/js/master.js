// ==================== MASTER MATERIAL MODERN SYSTEM ====================

class ModernMaterialManager {
    constructor() {
        this.initialize();
    }

    initialize() {
        this.initSearch();
        this.initDeleteConfirmations();
        this.initFormValidation();
        this.initAnimations();
        this.initTooltips();
    }

    initSearch() {
        const searchInput = document.getElementById('searchMaterial');
        if (!searchInput) return;

        let searchTimeout;
        
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            
            // Add loading effect
            searchInput.parentElement.classList.add('searching');
            
            searchTimeout = setTimeout(() => {
                this.performSearch(e.target.value);
                searchInput.parentElement.classList.remove('searching');
            }, 300);
        });
    }

    async performSearch(query) {
        try {
            const response = await fetch(`/master/materials/search?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.text();
                const tableBody = document.querySelector('#materialTable tbody');
                if (tableBody) {
                    tableBody.innerHTML = data;
                    this.initDeleteConfirmations();
                    this.initTooltips();
                }
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showToast('Terjadi kesalahan saat mencari', 'error');
        }
    }

    initDeleteConfirmations() {
        document.querySelectorAll('.delete-material-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleDeleteClick(button);
            });
        });
    }

    handleDeleteClick(button) {
        const materialName = button.dataset.materialName || 'material ini';
        const materialCode = button.dataset.materialCode || '';
        const deleteUrl = button.dataset.deleteUrl;

        Swal.fire({
            title: 'Hapus Material?',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-600">Anda akan menghapus material berikut:</p>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                        <div class="font-bold text-blue-700 text-lg">${materialCode}</div>
                        <div class="text-gray-700 mt-1">${materialName}</div>
                    </div>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Tindakan ini tidak dapat dibatalkan.</span>
                        </div>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-6 py-3 rounded-xl font-semibold',
                cancelButton: 'px-6 py-3 rounded-xl font-semibold'
            },
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return this.submitDeleteForm(deleteUrl);
            }
        });
    }

    submitDeleteForm(deleteUrl) {
        return new Promise((resolve, reject) => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.style.display = 'none';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            
            form.appendChild(methodInput);
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            
            // Show loading
            Swal.showLoading();
            
            setTimeout(() => {
                form.submit();
                resolve();
            }, 1000);
        });
    }

    initFormValidation() {
        const forms = document.querySelectorAll('.material-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            // Real-time validation
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
                
                input.addEventListener('input', () => {
                    if (input.classList.contains('input-error-modern')) {
                        this.clearFieldError(input);
                    }
                });
            });
        });

        // Auto-format kode material
        const kodeInput = document.getElementById('kode_material');
        if (kodeInput) {
            kodeInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
            });
        }

        // Stok validation
        const stokAwalInput = document.getElementById('stok_awal');
        const minStokInput = document.getElementById('min_stok');
        
        if (stokAwalInput && minStokInput) {
            const validateStok = () => {
                const stokAwal = parseInt(stokAwalInput.value) || 0;
                const minStok = parseInt(minStokInput.value) || 0;
                
                if (minStok > stokAwal) {
                    this.showFieldError(minStokInput, 'Stok minimum tidak boleh lebih besar dari stok awal');
                } else {
                    this.clearFieldError(minStokInput);
                }
            };

            stokAwalInput.addEventListener('input', validateStok);
            minStokInput.addEventListener('input', validateStok);
        }
    }

    validateForm(form) {
        let isValid = true;
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Field ini wajib diisi');
                isValid = false;
            }
        });

        const numberFields = form.querySelectorAll('input[type="number"]');
        numberFields.forEach(field => {
            const value = parseFloat(field.value);
            const min = parseFloat(field.min);
            
            if (!isNaN(min) && value < min) {
                this.showFieldError(field, `Nilai minimal adalah ${min}`);
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'Field ini wajib diisi');
            return false;
        }
        
        if (field.type === 'number') {
            const numValue = parseFloat(value);
            const min = parseFloat(field.min);
            
            if (!isNaN(min) && numValue < min) {
                this.showFieldError(field, `Nilai minimal adalah ${min}`);
                return false;
            }
        }
        
        this.clearFieldError(field);
        return true;
    }

    showFieldError(field, message) {
        field.classList.add('input-error-modern');
        
        let errorElement = field.parentElement.querySelector('.error-message-modern');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message-modern';
            errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            field.parentElement.appendChild(errorElement);
        } else {
            errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        }
    }

    clearFieldError(field) {
        field.classList.remove('input-error-modern');
        
        const errorElement = field.parentElement.querySelector('.error-message-modern');
        if (errorElement) {
            errorElement.remove();
        }
    }

    initAnimations() {
        // Animate table rows on load
        const tableRows = document.querySelectorAll('#materialTable tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    initTooltips() {
        const tooltips = document.querySelectorAll('[data-tooltip]');
        tooltips.forEach(element => {
            element.addEventListener('mouseenter', () => {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg';
                tooltip.textContent = element.dataset.tooltip;
                tooltip.style.top = `${element.offsetTop - element.offsetHeight - 10}px`;
                tooltip.style.left = `${element.offsetLeft + element.offsetWidth / 2}px`;
                tooltip.style.transform = 'translateX(-50%)';
                
                document.body.appendChild(tooltip);
                element._tooltip = tooltip;
            });
            
            element.addEventListener('mouseleave', () => {
                if (element._tooltip) {
                    element._tooltip.remove();
                    delete element._tooltip;
                }
            });
        });
    }

    showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast-modern');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `toast-modern fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ease-out ${
            type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' :
            type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' :
            'bg-gradient-to-r from-blue-500 to-indigo-600'
        }`;
        toast.style.transform = 'translateX(100%)';
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${icon} text-white text-xl mr-3"></i>
                <div>
                    <div class="font-semibold text-white">${type === 'success' ? 'Sukses' : type === 'error' ? 'Error' : 'Info'}</div>
                    <div class="text-white text-sm opacity-90">${message}</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    // Export functionality
    exportToCSV() {
        const table = document.getElementById('materialTable');
        if (!table) return;
        
        const rows = table.querySelectorAll('tr');
        const csv = [];
        
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('th, td');
            
            cells.forEach(cell => {
                if (cell.classList.contains('action-cell')) return;
                
                let text = cell.textContent.replace(/\s+/g, ' ').trim();
                if (text.includes(',') || text.includes('"') || text.includes("'")) {
                    text = '"' + text.replace(/"/g, '""') + '"';
                }
                rowData.push(text);
            });
            
            if (rowData.length > 0) {
                csv.push(rowData.join(','));
            }
        });
        
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        link.href = URL.createObjectURL(blob);
        link.download = `material_${new Date().toISOString().slice(0, 10)}.csv`;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        this.showToast('Data berhasil di-export ke CSV', 'success');
    }

    // Print functionality
    printTable() {
        const printContent = document.getElementById('materialTable').cloneNode(true);
        
        // Remove action buttons
        const actionCells = printContent.querySelectorAll('.action-cell');
        actionCells.forEach(cell => cell.innerHTML = '');
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Daftar Material</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
                        body { 
                            font-family: 'Inter', sans-serif; 
                            margin: 40px; 
                            color: #111827;
                            background: white;
                        }
                        .print-header { 
                            text-align: center; 
                            margin-bottom: 40px;
                            padding-bottom: 20px;
                            border-bottom: 2px solid #e5e7eb;
                        }
                        .print-header h1 { 
                            margin: 0 0 10px 0; 
                            color: #111827;
                            font-size: 28px;
                            font-weight: 700;
                        }
                        .print-info {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 30px;
                            font-size: 14px;
                            color: #6b7280;
                        }
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin: 20px 0;
                            font-size: 14px;
                        }
                        th { 
                            background: #f9fafb; 
                            text-align: left; 
                            padding: 12px 16px; 
                            border: 1px solid #e5e7eb;
                            font-weight: 600;
                            color: #374151;
                            text-transform: uppercase;
                            font-size: 12px;
                            letter-spacing: 0.05em;
                        }
                        td { 
                            padding: 12px 16px; 
                            border: 1px solid #e5e7eb;
                            vertical-align: middle;
                        }
                        .kode-material {
                            background: #f0f9ff;
                            color: #0369a1;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-family: monospace;
                            font-weight: 600;
                        }
                        .status-badge {
                            padding: 4px 12px;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 600;
                            display: inline-block;
                        }
                        .status-aman { background: #d1fae5; color: #065f46; }
                        .status-peringatan { background: #fef3c7; color: #92400e; }
                        .status-bahaya { background: #fee2e2; color: #991b1b; }
                        .print-footer {
                            margin-top: 40px;
                            padding-top: 20px;
                            border-top: 1px solid #e5e7eb;
                            font-size: 12px;
                            color: #6b7280;
                            text-align: center;
                        }
                        @media print {
                            body { margin: 20px; }
                            @page { margin: 2cm; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>DAFTAR MATERIAL</h1>
                        <div>Sistem Manajemen Material</div>
                    </div>
                    <div class="print-info">
                        <div>Tanggal Cetak: ${new Date().toLocaleString('id-ID')}</div>
                        <div>Total Data: ${printContent.rows.length - 1}</div>
                    </div>
                    ${printContent.outerHTML}
                    <div class="print-footer">
                        Halaman 1 dari 1 • Dicetak oleh Sistem Manajemen Material
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        
        // Wait for content to load
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.materialManager = new ModernMaterialManager();
    
    // Initialize tooltips for action buttons
    const initActionTooltips = () => {
        const viewButtons = document.querySelectorAll('.action-btn-view');
        const editButtons = document.querySelectorAll('.action-btn-edit');
        const deleteButtons = document.querySelectorAll('.action-btn-delete');
        
        viewButtons.forEach(btn => {
            btn.setAttribute('data-tooltip', 'Detail Material');
            btn.classList.add('tooltip');
        });
        
        editButtons.forEach(btn => {
            btn.setAttribute('data-tooltip', 'Edit Material');
            btn.classList.add('tooltip');
        });
        
        deleteButtons.forEach(btn => {
            btn.setAttribute('data-tooltip', 'Hapus Material');
            btn.classList.add('tooltip');
        });
    };
    
    initActionTooltips();
});

// Global functions for buttons
window.exportToCSV = function() {
    if (window.materialManager) {
        window.materialManager.exportToCSV();
    }
};

window.printTable = function() {
    if (window.materialManager) {
        window.materialManager.printTable();
    }
};