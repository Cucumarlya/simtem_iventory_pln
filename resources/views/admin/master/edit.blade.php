{{-- resources/views/admin/master/material/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-edit mr-3 text-blue-500"></i>
                            Edit Material: {{ $material->kode_material }}
                        </h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Perbarui data material yang sudah ada
                        </p>
                    </div>
                    <a href="{{ route('admin.master.material.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-red-800">Terjadi kesalahan!</div>
                            <ul class="mt-2 list-disc list-inside text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
                <!-- Form Header -->
                <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        Form Edit Material
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">
                        Perbarui informasi material sesuai kebutuhan. Field dengan tanda <span class="text-red-500">*</span> wajib diisi.
                    </p>
                </div>

                <!-- Form Content -->
                <form action="{{ route('admin.master.material.update', $material->id) }}" method="POST" id="materialForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-8 py-6">
                        <div class="space-y-6">
                            <!-- Row 1: Kode & Nama Material -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kode Material -->
                                <div class="form-group">
                                    <label for="kode_material" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kode Material <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="kode_material"
                                           name="kode_material"
                                           value="{{ old('kode_material', $material->kode_material) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           placeholder="MAT-001"
                                           required
                                           maxlength="20">
                                </div>

                                <!-- Nama Material -->
                                <div class="form-group">
                                    <label for="nama_material" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Material <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="nama_material"
                                           name="nama_material"
                                           value="{{ old('nama_material', $material->nama_material) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           placeholder="Nama material lengkap"
                                           required>
                                </div>
                            </div>

                            <!-- Row 2: Satuan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Satuan -->
                                <div class="form-group">
                                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Satuan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="satuan"
                                           name="satuan"
                                           value="{{ old('satuan', $material->satuan) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           placeholder="Contoh: PCS, KG, M, ROLL"
                                           required
                                           maxlength="20">
                                    <div class="mt-1 text-xs text-gray-500">
                                        Contoh: PCS (Buah), KG (Kilogram), M (Meter), ROLL (Gulung)
                                    </div>
                                </div>

                                <!-- Spacer untuk alignment -->
                                <div></div>
                            </div>

                            <!-- Row 3: Stok Awal & Stok Minimum -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Stok Awal -->
                                <div class="form-group">
                                    <label for="stok_awal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stok Awal <span class="text-red-500">*</span>
                                        @if($hasTransactions)
                                            <span class="text-xs text-gray-500">(tidak dapat diubah karena sudah ada transaksi)</span>
                                        @endif
                                    </label>
                                    <input type="number" 
                                           id="stok_awal"
                                           name="stok_awal"
                                           value="{{ old('stok_awal', $material->stok_awal) }}"
                                           min="0"
                                           step="1"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 {{ $hasTransactions ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                           {{ $hasTransactions ? 'readonly' : 'required' }}>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Jumlah stok awal material
                                    </div>
                                </div>

                                <!-- Stok Minimum -->
                                <div class="form-group">
                                    <label for="min_stok" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stok Minimum <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           id="min_stok"
                                           name="min_stok"
                                           value="{{ old('min_stok', $material->min_stok) }}"
                                           min="0"
                                           step="1"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           required>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Peringatan akan muncul saat stok mencapai level ini
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Perubahan akan langsung diterapkan ke sistem
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.master.material.index') }}" 
                                   class="px-6 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                    Update Material
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Form input focus styles */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kodeInput = document.getElementById('kode_material');
        const satuanInput = document.getElementById('satuan');
        
        // Auto-format kode material (uppercase)
        kodeInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            value = value.replace(/[^A-Z0-9-]/g, '');
            e.target.value = value;
        });
        
        // Auto-format satuan (uppercase)
        satuanInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            e.target.value = value;
        });
        
        // Form validation for stock
        const form = document.getElementById('materialForm');
        const stokAwalInput = document.getElementById('stok_awal');
        const minStokInput = document.getElementById('min_stok');
        
        form.addEventListener('submit', function(e) {
            const stokAwal = parseInt(stokAwalInput.value) || 0;
            const minStok = parseInt(minStokInput.value) || 0;
            
            if (minStok > stokAwal) {
                e.preventDefault();
                alert('Stok minimum tidak boleh lebih besar dari stok awal. Silakan periksa kembali.');
                minStokInput.focus();
                minStokInput.select();
            }
            
            // Validate satuan is not empty
            const satuan = satuanInput.value.trim();
            if (!satuan) {
                e.preventDefault();
                alert('Satuan wajib diisi.');
                satuanInput.focus();
            }
        });
    });
    </script>
</x-app-layout>