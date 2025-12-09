<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-inbox mr-2"></i>Form Penerimaan Material
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Form untuk menambahkan penerimaan material baru
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('petugas.penerimaan.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div id="successNotification" class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
                            </p>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- FORM CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('petugas.penerimaan.store') }}" enctype="multipart/form-data" id="formPenerimaan">
                    @csrf
                    
                    <!-- HEADER FORM -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-file-alt mr-2"></i>Form Penerimaan Material
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Isi form berikut untuk menambahkan penerimaan material</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- FORM DATA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       required />
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="nama_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_penerima" id="nama_penerima" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('nama_penerima') }}"
                                       placeholder="Masukkan nama lengkap penerima"
                                       required />
                                @error('nama_penerima')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- MATERIAL SECTION -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <button type="button" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center" 
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
                                                Nama Material <span class="text-red-500">*</span>
                                            </label>
                                            <select name="material[0][nama]" 
                                                    class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="">Pilih Material</option>
                                                @foreach($materials as $material)
                                                <option value="{{ $material->nama_material }}" 
                                                        data-satuan="{{ $material->satuan }}"
                                                        {{ old('material.0.nama') == $material->nama_material ? 'selected' : '' }}>
                                                    {{ $material->nama_material }}
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
                                                   min="1" value="{{ old('material.0.jumlah', 1) }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                            <input type="text" name="material[0][satuan]" 
                                                   class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                                                   value="{{ old('material.0.satuan') }}"
                                                   readonly>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" 
                                                    class="btn-hapus-material w-full px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    disabled>
                                                <i class="fas fa-trash mr-2"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @error('material')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- FOTO BUKTI -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Foto Bukti Penerimaan <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer relative"
                                 onclick="document.getElementById('foto_bukti').click()">
                                <div class="text-gray-400 mb-3">
                                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                </div>
                                <div class="font-medium text-gray-700 mb-1">Klik untuk upload foto</div>
                                <div class="text-sm text-gray-500">PNG, JPG, JPEG maksimal 2MB</div>
                                <input type="file" name="foto_bukti" id="foto_bukti" 
                                       class="hidden" 
                                       accept="image/*"
                                       required>
                            </div>
                            @error('foto_bukti')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="imagePreview" class="hidden mt-4">
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border">
                                    <div class="flex items-center">
                                        <i class="fas fa-image text-blue-500 text-xl mr-3"></i>
                                        <div>
                                            <p id="fileName" class="font-medium text-gray-700"></p>
                                            <p id="fileSize" class="text-sm text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeImage()" 
                                            class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <img id="previewImage" class="mt-2 max-w-full h-auto rounded-lg shadow" src="" alt="Preview">
                            </div>
                        </div>
                        
                        <!-- ACTION BUTTONS -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('petugas.penerimaan.index') }}" 
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

    @push('styles')
    <style>
        .material-row {
            transition: all 0.2s ease;
        }
        
        .material-row:hover {
            border-color: #3b82f6;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        
        .slide-up {
            animation: slideUp 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom scrollbar for material container */
        #materialContainer {
            max-height: 400px;
            overflow-y: auto;
        }
        
        #materialContainer::-webkit-scrollbar {
            width: 6px;
        }
        
        #materialContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        #materialContainer::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        #materialContainer::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let materialCounter = {{ count(old('material', [])) }};
        let currentImage = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date
            const today = new Date().toISOString().split('T')[0];
            if (!document.getElementById('tanggal').value) {
                document.getElementById('tanggal').value = today;
            }
            
            // Setup material system
            setupMaterialSystem();
            
            // Setup file upload
            setupFileUpload();
            
            // Jika ada data dari old(), tambahkan material rows
            @if(old('material'))
                const oldMaterials = @json(old('material'));
                if (oldMaterials.length > 1) {
                    for (let i = 1; i < oldMaterials.length; i++) {
                        addMaterialRow(oldMaterials[i]);
                    }
                }
            @endif
        });
        
        function setupMaterialSystem() {
            const container = document.getElementById('materialContainer');
            const btnTambah = document.getElementById('btnTambahMaterial');
            
            // Event untuk tambah material
            btnTambah.addEventListener('click', function() {
                addMaterialRow();
            });
            
            // Event delegation untuk select dan hapus
            container.addEventListener('change', function(e) {
                if (e.target.classList.contains('material-select')) {
                    updateSatuan(e.target);
                }
            });
            
            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-hapus-material')) {
                    const btn = e.target.closest('.btn-hapus-material');
                    if (!btn.disabled) {
                        removeMaterialRow(btn);
                    }
                }
            });
            
            // Initialize first row
            const firstSelect = container.querySelector('.material-select');
            if (firstSelect) {
                updateSatuan(firstSelect);
            }
            
            // Update initial delete button state
            updateDeleteButtons();
        }
        
        function addMaterialRow(materialData = null) {
            const container = document.getElementById('materialContainer');
            
            const newRow = document.createElement('div');
            newRow.className = 'material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 fade-in slide-up';
            
            const materialOptions = document.querySelector('.material-select').innerHTML;
            
            newRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Material <span class="text-red-500">*</span>
                        </label>
                        <select name="material[${materialCounter}][nama]" 
                                class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Material</option>
                            ${materialOptions}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="material[${materialCounter}][jumlah]" 
                               class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               min="1" value="${materialData ? materialData.jumlah : '1'}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" name="material[${materialCounter}][satuan]" 
                               class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                               value="${materialData ? materialData.satuan : ''}"
                               readonly>
                    </div>
                    <div class="flex items-end">
                        <button type="button" 
                                class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            
            // Jika ada data, set selected option
            if (materialData && materialData.nama) {
                const select = newRow.querySelector('.material-select');
                select.value = materialData.nama;
                updateSatuan(select);
            }
            
            materialCounter++;
            
            // Update delete buttons
            updateDeleteButtons();
            
            // Scroll to new row
            setTimeout(() => {
                newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }
        
        function updateSatuan(selectElement) {
            const selectedOption = selectElement.selectedOptions[0];
            const satuan = selectedOption ? selectedOption.dataset.satuan : '';
            
            const row = selectElement.closest('.material-row');
            const satuanInput = row.querySelector('.material-satuan');
            if (satuanInput && satuan) {
                satuanInput.value = satuan;
            }
        }
        
        function removeMaterialRow(button) {
            const row = button.closest('.material-row');
            const container = document.getElementById('materialContainer');
            
            if (container.children.length > 1) {
                if (confirm('Apakah Anda yakin ingin menghapus material ini?')) {
                    // Add fade out animation
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-10px)';
                    row.style.transition = 'all 0.3s ease';
                    
                    setTimeout(() => {
                        row.remove();
                        updateMaterialIndexes();
                        updateDeleteButtons();
                    }, 300);
                }
            } else {
                alert('Minimal harus ada satu material');
            }
        }
        
        function updateMaterialIndexes() {
            const container = document.getElementById('materialContainer');
            const rows = container.querySelectorAll('.material-row');
            
            rows.forEach((row, index) => {
                // Update select name
                const select = row.querySelector('.material-select');
                if (select) {
                    select.name = `material[${index}][nama]`;
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
            });
            
            materialCounter = rows.length;
        }
        
        function updateDeleteButtons() {
            const container = document.getElementById('materialContainer');
            const rows = container.querySelectorAll('.material-row');
            const deleteButtons = container.querySelectorAll('.btn-hapus-material');
            
            if (rows.length <= 1) {
                deleteButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700', 'focus:ring-red-500');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300', 'focus:ring-gray-500');
                });
            } else {
                deleteButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300', 'focus:ring-gray-500');
                    btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700', 'focus:ring-red-500');
                });
            }
        }
        
        function setupFileUpload() {
            const fileInput = document.getElementById('foto_bukti');
            const preview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const previewImage = document.getElementById('previewImage');
            
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file harus JPG, JPEG, atau PNG');
                        this.value = '';
                        return;
                    }
                    
                    currentImage = file;
                    
                    // Update file info
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.classList.remove('hidden');
                        preview.classList.add('fade-in');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        function removeImage() {
            const fileInput = document.getElementById('foto_bukti');
            const preview = document.getElementById('imagePreview');
            
            fileInput.value = '';
            currentImage = null;
            
            // Add fade out animation
            preview.style.opacity = '0';
            preview.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                preview.classList.add('hidden');
                preview.style.opacity = '1';
                preview.classList.remove('fade-in');
            }, 300);
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
    @endpush
</x-app-layout>