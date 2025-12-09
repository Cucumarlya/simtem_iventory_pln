<!-- resources/views/admin/transaksi/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Transaksi Material
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Edit data transaksi material
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.transaksi.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- FORM CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.transaksi.update', $transaksi->id) }}" enctype="multipart/form-data" id="formTransaksi">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="jenis" value="{{ $transaksi->jenis }}">
                    
                    <!-- HEADER FORM -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            @if($transaksi->jenis == 'penerimaan')
                                Edit Penerimaan Material
                            @else
                                Edit Pengeluaran Material
                            @endif
                        </h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-barcode mr-1"></i>{{ $transaksi->kode_transaksi }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <!-- FORM DATA -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal {{ $transaksi->jenis == 'penerimaan' ? 'Penerimaan' : 'Pengeluaran' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}"
                                       required />
                                @error('tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_pihak_transaksi" class="block text-sm font-medium text-gray-700 mb-1">
                                    @if($transaksi->jenis == 'penerimaan')
                                    Nama Penerima <span class="text-red-500">*</span>
                                    @else
                                    Nama Pengambil <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="text" name="nama_pihak_transaksi" id="nama_pihak_transaksi" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('nama_pihak_transaksi', $transaksi->nama_pihak_transaksi) }}"
                                       placeholder="{{ $transaksi->jenis == 'penerimaan' ? 'Masukkan nama penerima/supplier' : 'Masukkan nama pengambil' }}"
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
                                    <option value="YANBUNG" {{ old('keperluan', $transaksi->keperluan) == 'YANBUNG' ? 'selected' : '' }}>YANBUNG</option>
                                    <option value="P2TL" {{ old('keperluan', $transaksi->keperluan) == 'P2TL' ? 'selected' : '' }}>P2TL</option>
                                    <option value="GANGGUAN" {{ old('keperluan', $transaksi->keperluan) == 'GANGGUAN' ? 'selected' : '' }}>GANGGUAN</option>
                                    <option value="PLN" {{ old('keperluan', $transaksi->keperluan) == 'PLN' ? 'selected' : '' }}>PLN</option>
                                </select>
                                @error('keperluan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if($transaksi->jenis == 'pengeluaran')
                            <div>
                                <label for="nomor_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">
                                    ID Pelanggan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_pelanggan" id="nomor_pelanggan" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       value="{{ old('nomor_pelanggan', $transaksi->nomor_pelanggan) }}"
                                       placeholder="Masukkan ID pelanggan" 
                                       required />
                                @error('nomor_pelanggan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        </div>
                        
                        <!-- MATERIAL -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-medium text-gray-700">Rincian Material</h4>
                                <button type="button" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center transition-colors" 
                                        id="btnTambahMaterial">
                                    <i class="fas fa-plus mr-2"></i>Tambah Material
                                </button>
                            </div>
                            
                            <div id="materialContainer">
                                @php
                                    $oldMaterials = old('material');
                                @endphp
                                
                                @if($oldMaterials)
                                    @foreach($oldMaterials as $index => $item)
                                    <div class="material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Material <span class="text-red-500">*</span>
                                                </label>
                                                <select name="material[{{ $index }}][id]" 
                                                        class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="">Pilih Material</option>
                                                    @foreach($materials as $material)
                                                    <option value="{{ $material->id }}" 
                                                            data-satuan="{{ $material->satuan }}"
                                                            data-kode="{{ $material->kode_material }}"
                                                            {{ $item['id'] == $material->id ? 'selected' : '' }}>
                                                        {{ $material->kode_material }} - {{ $material->nama_material }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Jumlah <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" name="material[{{ $index }}][jumlah]" 
                                                       class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                       min="1" value="{{ $item['jumlah'] ?? 1 }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                                <input type="text" name="material[{{ $index }}][satuan]" 
                                                       class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                                                       value="{{ $materials->firstWhere('id', $item['id'])->satuan ?? '' }}"
                                                       readonly>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    @foreach($transaksi->details as $index => $detail)
                                    <div class="material-row bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Material <span class="text-red-500">*</span>
                                                </label>
                                                <select name="material[{{ $index }}][id]" 
                                                        class="material-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                    <option value="">Pilih Material</option>
                                                    @foreach($materials as $material)
                                                    <option value="{{ $material->id }}" 
                                                            data-satuan="{{ $material->satuan }}"
                                                            data-kode="{{ $material->kode_material }}"
                                                            {{ $detail->material_id == $material->id ? 'selected' : '' }}>
                                                        {{ $material->kode_material }} - {{ $material->nama_material }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    Jumlah <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" name="material[{{ $index }}][jumlah]" 
                                                       class="material-jumlah w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                       min="1" value="{{ $detail->jumlah }}" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                                <input type="text" name="material[{{ $index }}][satuan]" 
                                                       class="material-satuan w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm" 
                                                       value="{{ $detail->material->satuan ?? '' }}"
                                                       readonly>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <!-- DOKUMENTASI -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-4">Dokumentasi</h4>
                            <div class="space-y-6">
                                @if($transaksi->jenis == 'pengeluaran')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Foto SR Sebelum
                                            <span class="text-gray-500 text-xs">(Opsional)</span>
                                            @if($transaksi->foto_sr_sebelum)
                                            <span class="text-xs text-green-600 ml-1">(Foto saat ini sudah ada)</span>
                                            @endif
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors cursor-pointer relative">
                                            <div class="text-gray-400 mb-2">
                                                <i class="fas fa-camera text-xl"></i>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-1">Upload Foto Baru</div>
                                            <div class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah</div>
                                            <input type="file" name="foto_sr_sebelum" id="foto_sr_sebelum" 
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                   accept="image/*" onchange="previewImage(this, 'preview-sr-sebelum')">
                                        </div>
                                        @error('foto_sr_sebelum')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <div id="preview-sr-sebelum" class="mt-2 hidden">
                                            <p class="text-xs text-gray-500">Preview foto baru:</p>
                                            <img id="preview-sr-sebelum-img" class="w-32 h-32 object-cover rounded border mt-1">
                                        </div>
                                        @if($transaksi->foto_sr_sebelum)
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500">Foto saat ini:</p>
                                            <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_sr_sebelum) }}')"
                                                    class="mt-1">
                                                <img src="{{ Storage::url($transaksi->foto_sr_sebelum) }}" 
                                                     alt="Foto SR Sebelum" 
                                                     class="w-32 h-32 object-cover rounded border hover:opacity-90 transition-opacity cursor-pointer">
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Foto SR Sesudah
                                            <span class="text-gray-500 text-xs">(Opsional)</span>
                                            @if($transaksi->foto_sr_sesudah)
                                            <span class="text-xs text-green-600 ml-1">(Foto saat ini sudah ada)</span>
                                            @endif
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors cursor-pointer relative">
                                            <div class="text-gray-400 mb-2">
                                                <i class="fas fa-camera text-xl"></i>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-1">Upload Foto Baru</div>
                                            <div class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah</div>
                                            <input type="file" name="foto_sr_sesudah" id="foto_sr_sesudah" 
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                   accept="image/*" onchange="previewImage(this, 'preview-sr-sesudah')">
                                        </div>
                                        @error('foto_sr_sesudah')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <div id="preview-sr-sesudah" class="mt-2 hidden">
                                            <p class="text-xs text-gray-500">Preview foto baru:</p>
                                            <img id="preview-sr-sesudah-img" class="w-32 h-32 object-cover rounded border mt-1">
                                        </div>
                                        @if($transaksi->foto_sr_sesudah)
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500">Foto saat ini:</p>
                                            <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_sr_sesudah) }}')"
                                                    class="mt-1">
                                                <img src="{{ Storage::url($transaksi->foto_sr_sesudah) }}" 
                                                     alt="Foto SR Sesudah" 
                                                     class="w-32 h-32 object-cover rounded border hover:opacity-90 transition-opacity cursor-pointer">
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Foto Bukti {{ $transaksi->jenis == 'penerimaan' ? 'Penerimaan' : 'Pengeluaran' }}
                                        <span class="text-gray-500 text-xs">(Opsional)</span>
                                        @if($transaksi->foto_bukti)
                                        <span class="text-xs text-green-600 ml-1">(Foto saat ini sudah ada)</span>
                                        @endif
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors cursor-pointer relative">
                                        <div class="text-gray-400 mb-2">
                                            <i class="fas fa-file-image text-xl"></i>
                                        </div>
                                        <div class="text-sm text-gray-600 mb-1">Upload Foto Baru</div>
                                        <div class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah</div>
                                        <input type="file" name="foto_bukti" id="foto_bukti" 
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                               accept="image/*" onchange="previewImage(this, 'preview-bukti')">
                                    </div>
                                    @error('foto_bukti')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <div id="preview-bukti" class="mt-2 hidden">
                                        <p class="text-xs text-gray-500">Preview foto baru:</p>
                                        <img id="preview-bukti-img" class="w-32 h-32 object-cover rounded border mt-1">
                                    </div>
                                    @if($transaksi->foto_bukti)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">Foto saat ini:</p>
                                        <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_bukti) }}')"
                                                class="mt-1">
                                            <img src="{{ Storage::url($transaksi->foto_bukti) }}" 
                                                 alt="Foto Bukti" 
                                                 class="w-32 h-32 object-cover rounded border hover:opacity-90 transition-opacity cursor-pointer">
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- ACTION BUTTONS -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.transaksi.index') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Image Viewer -->
    <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"></div>
            
            <div class="inline-block align-middle max-w-5xl w-full">
                <div class="relative">
                    <button type="button" 
                            onclick="closeImageModal()"
                            class="absolute -top-10 right-0 bg-white rounded-full p-2 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-gray-600 text-xl"></i>
                    </button>
                    <img id="modalImage" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeEditMaterialSystem();
    });

    function initializeEditMaterialSystem() {
        let materialCounter = document.getElementById('materialContainer').querySelectorAll('.material-row').length;
        const container = document.getElementById('materialContainer');
        
        // Update satuan saat material dipilih
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('material-select')) {
                const select = e.target;
                const selectedOption = select.selectedOptions[0];
                const satuan = selectedOption ? selectedOption.dataset.satuan : '';
                
                const row = select.closest('.material-row');
                const satuanInput = row.querySelector('input[name*="satuan"]');
                if (satuanInput && satuan) {
                    satuanInput.value = satuan;
                }
            }
        });
        
        // Tombol tambah material
        document.getElementById('btnTambahMaterial').addEventListener('click', function() {
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
                            ${document.querySelector('.material-select').innerHTML.replace(/selected/g, '')}
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
                        <button type="button" 
                                class="btn-hapus-material w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            materialCounter++;
            
            // Update status tombol hapus
            updateDeleteButtons();
        });
        
        // Tombol hapus material (event delegation)
        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus-material')) {
                const btn = e.target.closest('.btn-hapus-material');
                const row = btn.closest('.material-row');
                
                if (row && container.children.length > 1) {
                    if (confirm('Apakah Anda yakin ingin menghapus material ini?')) {
                        row.remove();
                        updateMaterialIndexes();
                        updateDeleteButtons();
                    }
                } else {
                    alert('Minimal harus ada satu material');
                }
            }
        });
        
        // Update index material
        function updateMaterialIndexes() {
            const rows = container.querySelectorAll('.material-row');
            materialCounter = 0;
            
            rows.forEach((row, index) => {
                // Update select name
                const select = row.querySelector('.material-select');
                if (select) select.name = `material[${index}][id]`;
                
                // Update jumlah input name
                const jumlahInput = row.querySelector('input[name*="jumlah"]');
                if (jumlahInput) jumlahInput.name = `material[${index}][jumlah]`;
                
                // Update satuan input name
                const satuanInput = row.querySelector('input[name*="satuan"]');
                if (satuanInput) satuanInput.name = `material[${index}][satuan]`;
                
                materialCounter++;
            });
        }
        
        // Update status tombol hapus
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
        
        // Validasi form sebelum submit
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
                e.preventDefault();
                alert('Perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
            }
        });
        
        // Inisialisasi awal
        updateDeleteButtons();
    }
    
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const previewDiv = document.getElementById(previewId);
            const previewImg = document.getElementById(previewId + '-img');
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function showImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });
    
    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
    </script>
    @endpush
    
    @push('styles')
    <style>
    #imageModal {
        z-index: 9999;
    }
    
    #modalImage {
        max-height: 85vh;
        object-fit: contain;
    }
    
    .material-select option {
        padding: 8px;
    }
    </style>
    @endpush
</x-app-layout>