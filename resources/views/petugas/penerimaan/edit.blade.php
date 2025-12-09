<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Penerimaan Material
            </h2>
            <div class="text-sm text-gray-500">
                <i class="fas fa-edit mr-1 text-blue-500"></i>
                Mode Edit
            </div>
        </div>
    </x-slot>

    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 text-white py-8 mb-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">Edit Penerimaan Material</h1>
            <div class="w-20 h-1 bg-white mx-auto mb-4 opacity-50"></div>
            <p class="text-lg opacity-90">Perbaiki data penerimaan material yang dikembalikan</p>
        </div>
    </div>

    <!-- Status Info -->
    <div class="max-w-4xl mx-auto px-4 mt-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800">Status: DIKEMBALIKAN</h3>
                    <div class="mt-2 text-yellow-700">
                        <p>Penerimaan ini dikembalikan untuk perbaikan. Harap perbaiki data sesuai dengan catatan verifikasi:</p>
                        @if($penerimaan->alasan_penolakan)
                        <ul class="list-disc pl-5 mt-2">
                            <li>{{ $penerimaan->alasan_penolakan }}</li>
                        </ul>
                        @else
                        <p class="mt-2">Tidak ada catatan spesifik dari verifikator.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-edit text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Edit Penerimaan Material</h2>
                            <p class="text-gray-600 text-sm">{{ $penerimaan->kode_transaksi }} - {{ $penerimaan->nama_pihak_transaksi }}</p>
                        </div>
                    </div>
                    <div class="text-sm bg-white px-3 py-1 rounded-full border">
                        <span class="text-red-600 font-medium flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> DIKEMBALIKAN
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Form Content -->
            <form id="editPenerimaanForm" class="p-6" action="{{ route('petugas.penerimaan.update', $penerimaan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Informasi Utama -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Transaksi -->
                        <div>
                            <label for="kode_transaksi" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-barcode mr-2 text-blue-500"></i> Kode Transaksi
                            </label>
                            <input 
                                type="text" 
                                id="kode_transaksi" 
                                name="kode_transaksi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-gray-50"
                                value="{{ $penerimaan->kode_transaksi }}" 
                                readonly
                            >
                            <p class="text-xs text-gray-500 mt-1">Kode transaksi tidak dapat diubah</p>
                        </div>
                        
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Tanggal Penerimaan
                            </label>
                            <input 
                                type="date" 
                                id="tanggal" 
                                name="tanggal"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                value="{{ $penerimaan->tanggal }}"
                                required
                            >
                        </div>
                        
                        <!-- Nama Penerima -->
                        <div>
                            <label for="nama_pihak_transaksi" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i> Nama Penerima
                            </label>
                            <input 
                                type="text" 
                                id="nama_pihak_transaksi" 
                                name="nama_pihak_transaksi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                value="{{ $penerimaan->nama_pihak_transaksi }}" 
                                required
                            >
                        </div>
                        
                        <!-- Keperluan -->
                        <div>
                            <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-list-alt mr-2 text-blue-500"></i> Keperluan
                            </label>
                            <select id="keperluan" 
                                    name="keperluan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required>
                                <option value="YANBUNG" {{ $penerimaan->keperluan == 'YANBUNG' ? 'selected' : '' }}>YANBUNG</option>
                                <option value="P2TL" {{ $penerimaan->keperluan == 'P2TL' ? 'selected' : '' }}>P2TL</option>
                                <option value="GANGGUAN" {{ $penerimaan->keperluan == 'GANGGUAN' ? 'selected' : '' }}>GANGGUAN</option>
                                <option value="PLN" {{ $penerimaan->keperluan == 'PLN' ? 'selected' : '' }}>PLN</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Material Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Material</h3>
                    
                    <div id="materialContainer">
                        @if(old('materials'))
                            @foreach(old('materials') as $index => $material)
                            <div class="material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Material <span class="text-red-500">*</span>
                                        </label>
                                        <select name="materials[{{ $index }}][material_id]" 
                                                class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Pilih Material</option>
                                            @foreach($materials as $materialItem)
                                            <option value="{{ $materialItem->id }}" 
                                                {{ $material['material_id'] == $materialItem->id ? 'selected' : '' }}
                                                data-satuan="{{ $materialItem->satuan }}">
                                                {{ $materialItem->nama_material }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Jumlah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="materials[{{ $index }}][jumlah]" 
                                               class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               min="1" value="{{ $material['jumlah'] }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                        <input type="text" 
                                               class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                                               readonly>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" 
                                                class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            @foreach($penerimaan->details as $index => $detail)
                            <div class="material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Material <span class="text-red-500">*</span>
                                        </label>
                                        <select name="materials[{{ $index }}][material_id]" 
                                                class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Pilih Material</option>
                                            @foreach($materials as $materialItem)
                                            <option value="{{ $materialItem->id }}" 
                                                {{ $detail->material_id == $materialItem->id ? 'selected' : '' }}
                                                data-satuan="{{ $materialItem->satuan }}">
                                                {{ $materialItem->nama_material }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Jumlah <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="materials[{{ $index }}][jumlah]" 
                                               class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               min="1" value="{{ $detail->jumlah }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                        <input type="text" 
                                               value="{{ $detail->material->satuan ?? '' }}"
                                               class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                                               readonly>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" 
                                                class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Tombol Tambah Material -->
                    <div class="mt-4">
                        <button type="button" 
                                id="btnTambahMaterial"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Material
                        </button>
                    </div>
                </div>
                
                <!-- Foto Bukti -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Bukti Penerimaan
                    </label>
                    
                    <!-- Foto Lama -->
                    @if($penerimaan->foto_bukti)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                        <div class="flex items-center space-x-4">
                            <img src="{{ Storage::url($penerimaan->foto_bukti) }}" 
                                 alt="Foto Bukti" 
                                 class="w-32 h-32 object-cover rounded-lg border">
                            <div>
                                <p class="text-sm text-gray-700">Foto sudah diupload sebelumnya</p>
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Upload Baru -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer relative"
                         onclick="document.getElementById('foto_bukti').click()">
                        <div class="text-gray-400 mb-3">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <div class="font-medium text-gray-700 mb-1">Klik untuk upload foto baru</div>
                        <div class="text-sm text-gray-500">PNG, JPG, JPEG maksimal 5MB</div>
                        <input type="file" name="foto_bukti" id="foto_bukti" 
                               class="hidden" 
                               accept="image/*">
                    </div>
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
                
                <!-- Catatan Perbaikan -->
                <div class="mb-8">
                    <label for="catatan_perbaikan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Perbaikan (Opsional)
                    </label>
                    <textarea id="catatan_perbaikan" 
                              name="catatan_perbaikan"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Tambahkan catatan perbaikan yang dilakukan...">{{ old('catatan_perbaikan') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Catatan ini akan membantu verifikator dalam memeriksa perbaikan yang dilakukan</p>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row justify-between space-y-4 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('petugas.penerimaan.show', $penerimaan->id) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        
                        <button type="button" 
                                onclick="confirmCancel()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-colors duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
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
    </style>
    @endpush

    @push('scripts')
    <script>
        let materialCounter = {{ old('materials') ? count(old('materials')) : $penerimaan->details->count() }};
        let currentImage = null;
        
        // Fungsi konfirmasi batal
        function confirmCancel() {
            if (confirm('Perubahan yang belum disimpan akan hilang. Lanjutkan?')) {
                window.location.href = "{{ route('petugas.penerimaan.show', $penerimaan->id) }}";
            }
        }
        
        // Setup material system
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
                    removeMaterialRow(btn);
                }
            });
            
            // Initialize existing rows
            const selects = container.querySelectorAll('.material-select');
            selects.forEach(select => {
                updateSatuan(select);
            });
            
            updateDeleteButtons();
        }
        
        function addMaterialRow() {
            const container = document.getElementById('materialContainer');
            
            const newRow = document.createElement('div');
            newRow.className = 'material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 fade-in slide-up';
            newRow.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Material <span class="text-red-500">*</span>
                        </label>
                        <select name="materials[${materialCounter}][material_id]" 
                                class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Material</option>
                            @foreach($materials as $material)
                            <option value="{{ $material->id }}" data-satuan="{{ $material->satuan }}">{{ $material->nama_material }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="materials[${materialCounter}][jumlah]" 
                               class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               min="1" value="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" 
                               class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
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
            materialCounter++;
            
            updateDeleteButtons();
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
                    row.remove();
                    updateMaterialIndexes();
                    updateDeleteButtons();
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
                    select.name = `materials[${index}][material_id]`;
                }
                
                // Update jumlah input name
                const jumlahInput = row.querySelector('.material-jumlah');
                if (jumlahInput) {
                    jumlahInput.name = `materials[${index}][jumlah]`;
                }
            });
            
            materialCounter = rows.length;
        }
        
        function updateDeleteButtons() {
            const container = document.getElementById('materialContainer');
            const rows = container.querySelectorAll('.material-row');
            
            if (rows.length <= 1) {
                const deleteButtons = container.querySelectorAll('.btn-hapus-material');
                deleteButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700', 'focus:ring-red-500');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300', 'focus:ring-gray-500');
                });
            }
        }
        
        // Setup file upload
        function setupFileUpload() {
            const fileInput = document.getElementById('foto_bukti');
            const preview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const previewImage = document.getElementById('previewImage');
            
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file maksimal 5MB');
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
            preview.classList.add('hidden');
            preview.classList.remove('fade-in');
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Submit form validation
        document.getElementById('editPenerimaanForm').addEventListener('submit', function(e) {
            // Validasi minimal satu material
            const materialRows = document.querySelectorAll('.material-row');
            if (materialRows.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada satu material');
                return;
            }
            
            // Validasi setiap material
            let hasError = false;
            materialRows.forEach((row, index) => {
                const select = row.querySelector('.material-select');
                const jumlahInput = row.querySelector('.material-jumlah');
                
                if (!select.value) {
                    hasError = true;
                    select.classList.add('border-red-500');
                }
                
                if (!jumlahInput.value || parseInt(jumlahInput.value) < 1) {
                    hasError = true;
                    jumlahInput.classList.add('border-red-500');
                }
            });
            
            if (hasError) {
                e.preventDefault();
                alert('Harap periksa kembali data material yang diisi');
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupMaterialSystem();
            setupFileUpload();
        });
    </script>
    @endpush
</x-app-layout>