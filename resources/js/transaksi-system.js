// public/js/transaksi-system.js - Sistem untuk mengelola form transaksi material

// Variabel global
let materialCounter = 1;
let isAddingMaterial = false;

/**
 * Inisialisasi sistem material untuk form transaksi
 * @param {string} jenis - 'penerimaan' atau 'pengeluaran'
 */
function initializeMaterialSystem(jenis) {
    console.log(`Initializing material system for: ${jenis}`);
    
    const container = document.getElementById('materialContainer');
    if (!container) {
        console.error('Material container not found!');
        return;
    }
    
    // Set counter berdasarkan jumlah material yang ada
    materialCounter = container.querySelectorAll('.material-row').length || 1;
    
    // Event delegation untuk semua event
    setupEventDelegation(container, jenis);
    
    // Setup tombol tambah material
    setupAddMaterialButton(jenis);
    
    // Inisialisasi material yang sudah ada
    initializeExistingMaterials();
    
    // Setup validasi form submit
    setupFormValidation(jenis);
    
    console.log(`Material system initialized. Initial counter: ${materialCounter}`);
}

/**
 * Setup event delegation untuk container material
 */
function setupEventDelegation(container, jenis) {
    // Event untuk select material
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            handleMaterialSelectChange(e.target);
        }
    });
    
    // Event untuk tombol hapus
    container.addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-material')) {
            const btn = e.target.closest('.btn-hapus-material');
            if (!btn.disabled) {
                handleDeleteMaterial(btn, container);
            }
        }
    });
}

/**
 * Setup tombol tambah material
 */
function setupAddMaterialButton(jenis) {
    const btnTambah = document.getElementById('btnTambahMaterial');
    if (!btnTambah) return;
    
    // Hapus event listener lama jika ada
    const newBtn = btnTambah.cloneNode(true);
    btnTambah.parentNode.replaceChild(newBtn, btnTambah);
    
    // Tambah event listener baru
    document.getElementById('btnTambahMaterial').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Cegah multiple execution
        if (isAddingMaterial) {
            console.log('Masih proses tambah material sebelumnya...');
            return;
        }
        
        isAddingMaterial = true;
        
        setTimeout(() => {
            isAddingMaterial = false;
        }, 300);
        
        addMaterialRow(jenis);
    });
}

/**
 * Inisialisasi material yang sudah ada
 */
function initializeExistingMaterials() {
    document.querySelectorAll('.material-select').forEach(select => {
        // Set initial satuan
        if (select.value) {
            const selectedOption = select.selectedOptions[0];
            const satuan = selectedOption ? selectedOption.dataset.satuan : '';
            const row = select.closest('.material-row') || select.closest('.bg-gray-50');
            const satuanInput = row.querySelector('input[name*="satuan"]');
            if (satuanInput && satuan) {
                satuanInput.value = satuan;
            }
        }
    });
    
    // Update status tombol hapus
    updateDeleteButtons();
}

/**
 * Setup validasi form submit
 */
function setupFormValidation(jenis) {
    const form = document.getElementById('formTransaksi');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        if (!validateForm(jenis)) {
            e.preventDefault();
        }
    });
}

/**
 * Validasi form sebelum submit
 */
function validateForm(jenis) {
    let isValid = true;
    const errorMessages = [];
    
    // Validasi minimal satu material
    const materialRows = document.querySelectorAll('.material-row, .bg-gray-50.border');
    if (materialRows.length === 0) {
        errorMessages.push('Minimal satu material harus ditambahkan');
        isValid = false;
    }
    
    // Validasi setiap material
    materialRows.forEach((row, index) => {
        const select = row.querySelector('.material-select');
        const jumlahInput = row.querySelector('input[name*="jumlah"]');
        
        if (!select || !select.value) {
            errorMessages.push(`Material ke-${index + 1}: Harus memilih material`);
            isValid = false;
        }
        
        if (!jumlahInput || !jumlahInput.value || parseInt(jumlahInput.value) < 1) {
            errorMessages.push(`Material ke-${index + 1}: Jumlah harus minimal 1`);
            isValid = false;
        }
    });
    
    // Tampilkan error jika ada
    if (!isValid) {
        alert('Perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
    }
    
    return isValid;
}

/**
 * Tambah baris material baru
 */
function addMaterialRow(jenis) {
    const container = document.getElementById('materialContainer');
    if (!container) return;
    
    const focusClass = jenis === 'penerimaan' ? 
        'focus:ring-blue-500 focus:border-blue-500' : 
        'focus:ring-green-500 focus:border-green-500';
    
    const bgColorClass = jenis === 'penerimaan' ? 'bg-gray-50' : 'bg-gray-50';
    
    const newRow = document.createElement('div');
    newRow.className = `material-row ${bgColorClass} border border-gray-200 rounded-lg p-4 mb-4`;
    
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Material <span class="text-red-500">*</span>
                </label>
                <select name="material[${materialCounter}][id]" 
                        class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 ${focusClass}" required>
                    <option value="">Pilih Material</option>
                    ${getMaterialOptions()}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jumlah <span class="text-red-500">*</span>
                </label>
                <input type="number" name="material[${materialCounter}][jumlah]" 
                       class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 ${focusClass}" 
                       min="1" value="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="material[${materialCounter}][satuan]" 
                       class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                       readonly>
            </div>
            <div class="flex items-end">
                <button type="button" class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    materialCounter++;
    
    // Scroll ke baris baru
    newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Update status tombol hapus
    updateDeleteButtons();
    
    console.log(`Material row added. Total rows: ${materialCounter}`);
}

/**
 * Dapatkan options material dari HTML yang sudah ada
 */
function getMaterialOptions() {
    const firstSelect = document.querySelector('.material-select');
    if (!firstSelect) return '';
    
    // Clone options dari select pertama
    const options = Array.from(firstSelect.options)
        .filter(option => option.value !== '')
        .map(option => {
            const satuan = option.dataset.satuan || '';
            return `<option value="${option.value}" data-satuan="${satuan}">${option.text}</option>`;
        })
        .join('');
    
    return options;
}

/**
 * Handle perubahan select material
 */
function handleMaterialSelectChange(select) {
    const selectedOption = select.selectedOptions[0];
    const satuan = selectedOption ? selectedOption.dataset.satuan : '';
    
    const row = select.closest('.material-row') || select.closest('.bg-gray-50');
    const satuanInput = row.querySelector('input[name*="satuan"]');
    if (satuanInput && satuan) {
        satuanInput.value = satuan;
    }
}

/**
 * Handle penghapusan material
 */
function handleDeleteMaterial(btn, container) {
    const row = btn.closest('.material-row') || btn.closest('.bg-gray-50.border');
    if (!row) return;
    
    if (container.children.length > 1) {
        if (confirm('Apakah Anda yakin ingin menghapus material ini?')) {
            row.remove();
            updateMaterialIndexes();
            updateDeleteButtons();
        }
    } else {
        alert('Minimal harus ada satu material');
    }
}

/**
 * Update index material setelah penghapusan
 */
function updateMaterialIndexes() {
    const container = document.getElementById('materialContainer');
    if (!container) return;
    
    const rows = container.querySelectorAll('.material-row, .bg-gray-50.border');
    materialCounter = 0;
    
    rows.forEach((row, index) => {
        // Update select name
        const select = row.querySelector('.material-select');
        if (select) {
            select.name = `material[${index}][id]`;
        }
        
        // Update jumlah input name
        const jumlahInput = row.querySelector('.material-jumlah') || row.querySelector('input[name*="jumlah"]');
        if (jumlahInput) {
            jumlahInput.name = `material[${index}][jumlah]`;
        }
        
        // Update satuan input name
        const satuanInput = row.querySelector('.material-satuan') || row.querySelector('input[name*="satuan"]');
        if (satuanInput) {
            satuanInput.name = `material[${index}][satuan]`;
        }
        
        materialCounter++;
    });
    
    console.log(`Material indexes updated. Total rows: ${materialCounter}`);
}

/**
 * Update status tombol hapus
 */
function updateDeleteButtons() {
    const container = document.getElementById('materialContainer');
    if (!container) return;
    
    const rows = container.querySelectorAll('.material-row, .bg-gray-50.border');
    const deleteButtons = container.querySelectorAll('.btn-hapus-material');
    
    if (rows.length <= 1) {
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.classList.remove('hover:bg-red-700');
        });
    } else {
        deleteButtons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.classList.add('hover:bg-red-700');
        });
    }
}

// Export fungsi untuk penggunaan global
window.initializeMaterialSystem = initializeMaterialSystem;
window.addMaterialRow = addMaterialRow;
window.updateMaterialIndexes = updateMaterialIndexes;
window.updateDeleteButtons = updateDeleteButtons;

// Auto-initialize jika container ada
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('materialContainer');
    if (container) {
        // Coba deteksi jenis dari URL atau class
        const jenis = document.body.classList.contains('penerimaan-form') ? 'penerimaan' : 
                     document.body.classList.contains('pengeluaran-form') ? 'pengeluaran' : 'penerimaan';
        
        initializeMaterialSystem(jenis);
    }
});