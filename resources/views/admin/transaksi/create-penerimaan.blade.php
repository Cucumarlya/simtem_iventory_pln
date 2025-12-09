<!-- resources/views/admin/transaksi/create-penerimaan.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Form Penerimaan Material
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Form untuk menambahkan penerimaan material baru
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.transaksi.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- FORM CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.transaksi.store') }}" enctype="multipart/form-data" id="formTransaksi">
                    @csrf
                    <input type="hidden" name="jenis" value="penerimaan">
                    
                    <!-- HEADER FORM -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            Form Penerimaan Material
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Isi form berikut untuk menambahkan penerimaan material</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- FORM DATA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Penerimaan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       required />
                                @error('tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_pihak_transaksi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pihak_transaksi" id="nama_pihak_transaksi" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('nama_pihak_transaksi') }}"
                                       placeholder="Masukkan nama penerima/supplier"
                                       required />
                                @error('nama_pihak_transaksi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keperluan <span class="text-red-500">*</span>
                                </label>
                                <select name="keperluan" id="keperluan" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="">Pilih Keperluan</option>
                                    <option value="YANBUNG" {{ old('keperluan') == 'YANBUNG' ? 'selected' : '' }}>YANBUNG</option>
                                    <option value="P2TL" {{ old('keperluan') == 'P2TL' ? 'selected' : '' }}>P2TL</option>
                                    <option value="GANGGUAN" {{ old('keperluan') == 'GANGGUAN' ? 'selected' : '' }}>GANGGUAN</option>
                                    <option value="PLN" {{ old('keperluan') == 'PLN' ? 'selected' : '' }}>PLN</option>
                                </select>
                                @error('keperluan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- MATERIAL -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-medium text-gray-700">Material yang Diterima</h4>
                                <button type="button" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center transition-colors" 
                                        id="btnTambahMaterial">
                                    <i class="fas fa-plus mr-2"></i>Tambah Material
                                </button>
                            </div>
                            
                            <div id="materialContainer">
                                <!-- Baris material pertama -->
                                <div class="material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Material <span class="text-red-500">*</span>
                                            </label>
                                            <select name="material[0][id]" 
                                                    class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="">Pilih Material</option>
                                                @foreach($materials as $material)
                                                <option value="{{ $material->id }}" 
                                                        data-satuan="{{ $material->satuan }}"
                                                        data-kode="{{ $material->kode_material }}">
                                                    {{ $material->kode_material }} - {{ $material->nama_material }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Jumlah <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="material[0][jumlah]" 
                                                   class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   min="1" value="1" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                            <input type="text" name="material[0][satuan]" 
                                                   class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" readonly>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                                    disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- FOTO BUKTI -->
                        <div class="mb-6">
                            <label for="foto_bukti" class="block text-sm font-medium text-gray-700 mb-1">
                                Dokumentasi Bukti Penerimaan <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer relative">
                                <div class="text-gray-400 mb-3">
                                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                </div>
                                <div class="font-medium text-gray-700 mb-1">Klik untuk upload foto</div>
                                <div class="text-sm text-gray-500">PNG, JPG, JPEG maksimal 5MB</div>
                                <input type="file" name="foto_bukti" id="foto_bukti" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                       accept="image/*"
                                       required>
                            </div>
                            @error('foto_bukti')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- ACTION BUTTONS -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.transaksi.index') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Penerimaan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let materialCounter = 1;
        const container = document.getElementById('materialContainer');
        
        // Initialize first row
        initializeMaterialRow(0);
        
        // Setup event untuk material select
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('material-select')) {
                handleMaterialSelectChange(e.target);
            }
        });
        
        // Setup event untuk tombol hapus
        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus-material')) {
                const btn = e.target.closest('.btn-hapus-material');
                if (!btn.disabled) {
                    handleDeleteMaterial(btn);
                }
            }
        });
        
        // Tombol tambah material
        document.getElementById('btnTambahMaterial').addEventListener('click', function() {
            addMaterialRow();
        });
        
        // Function untuk inisialisasi baris material
        function initializeMaterialRow(index) {
            const row = container.querySelectorAll('.material-row')[index];
            if (row) {
                const select = row.querySelector('.material-select');
                if (select) {
                    select.addEventListener('change', function() {
                        handleMaterialSelectChange(this);
                    });
                }
            }
        }
        
        // Function untuk handle perubahan select material
        function handleMaterialSelectChange(select) {
            const selectedOption = select.selectedOptions[0];
            const satuan = selectedOption ? selectedOption.dataset.satuan : '';
            
            const row = select.closest('.material-row');
            const satuanInput = row.querySelector('.material-satuan');
            if (satuanInput && satuan) {
                satuanInput.value = satuan;
            }
        }
        
        // Function untuk tambah baris material
        function addMaterialRow() {
            const newRow = document.createElement('div');
            newRow.className = 'material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4';
            
            newRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Material <span class="text-red-500">*</span>
                        </label>
                        <select name="material[${materialCounter}][id]" 
                                class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Material</option>
                            ${getMaterialOptions()}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="material[${materialCounter}][jumlah]" 
                               class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
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
            
            // Initialize new row
            initializeMaterialRow(materialCounter - 1);
            
            // Update status tombol hapus
            updateDeleteButtons();
            
            // Scroll ke baris baru
            newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Function untuk dapatkan options material
        function getMaterialOptions() {
            const firstSelect = document.querySelector('.material-select');
            if (!firstSelect) return '';
            
            // Clone options dari select pertama
            const options = Array.from(firstSelect.options)
                .filter(option => option.value !== '')
                .map(option => {
                    const satuan = option.dataset.satuan || '';
                    const kode = option.dataset.kode || '';
                    return `<option value="${option.value}" data-satuan="${satuan}" data-kode="${kode}">${option.text}</option>`;
                })
                .join('');
            
            return options;
        }
        
        // Function untuk handle penghapusan material
        function handleDeleteMaterial(btn) {
            const row = btn.closest('.material-row');
            if (!row) return;
            
            const rows = container.querySelectorAll('.material-row');
            if (rows.length > 1) {
                if (confirm('Apakah Anda yakin ingin menghapus material ini?')) {
                    row.remove();
                    updateMaterialIndexes();
                    updateDeleteButtons();
                }
            } else {
                alert('Minimal harus ada satu material');
            }
        }
        
        // Function untuk update index material
        function updateMaterialIndexes() {
            const rows = container.querySelectorAll('.material-row');
            materialCounter = 0;
            
            rows.forEach((row, index) => {
                // Update select name
                const select = row.querySelector('.material-select');
                if (select) {
                    select.name = `material[${index}][id]`;
                }
                
                // Update jumlah input name
                const jumlahInput = row.querySelector('.material-jumlah');
                if (jumlahInput) {
                    jumlahInput.name = `material[${index}][jumlah]`;
                }
                
                // Update satuan input name
                const satuanInput = row.querySelector('.material-satuan');
                if (satuanInput) {
                    satuanInput.name = `material[${index}][satuan]`;
                }
                
                materialCounter++;
            });
        }
        
        // Function untuk update status tombol hapus
        function updateDeleteButtons() {
            const rows = container.querySelectorAll('.material-row');
            const deleteButtons = container.querySelectorAll('.btn-hapus-material');
            
            deleteButtons.forEach(btn => {
                if (rows.length <= 1) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.classList.remove('hover:bg-red-700');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.classList.add('hover:bg-red-700');
                }
            });
        }
        
        // Form validation
        document.getElementById('formTransaksi').addEventListener('submit', function(e) {
            let isValid = true;
            const errorMessages = [];
            
            // Validasi material
            const materialRows = container.querySelectorAll('.material-row');
            if (materialRows.length === 0) {
                errorMessages.push('Minimal satu material harus ditambahkan');
                isValid = false;
            }
            
            materialRows.forEach((row, index) => {
                const select = row.querySelector('.material-select');
                const jumlahInput = row.querySelector('.material-jumlah');
                
                if (!select || !select.value) {
                    errorMessages.push(`Material ke-${index + 1}: Harus memilih material`);
                    isValid = false;
                }
                
                if (!jumlahInput || !jumlahInput.value || parseInt(jumlahInput.value) < 1) {
                    errorMessages.push(`Material ke-${index + 1}: Jumlah harus minimal 1`);
                    isValid = false;
                }
            });
            
            // Validasi file upload
            const fotoInput = document.getElementById('foto_bukti');
            if (!fotoInput.files.length) {
                errorMessages.push('Dokumentasi bukti penerimaan wajib diupload');
                isValid = false;
            }
            
            // Tampilkan error jika ada
            if (!isValid) {
                e.preventDefault();
                alert('Perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
            }
        });
        
        // Inisialisasi awal
        updateDeleteButtons();
    });
    </script>
    @endpush
</x-app-layout>