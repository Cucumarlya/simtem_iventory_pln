<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Penerimaan Material
            </h2>
            <div class="text-sm text-gray-500">
                <i class="fas fa-eye mr-1 text-blue-500"></i>
                Mode Lihat Detail
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
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8 mb-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">Detail Transaksi Material</h1>
            <div class="w-20 h-1 bg-yellow-400 mx-auto mb-4"></div>
            <p class="text-lg opacity-90">Informasi lengkap penerimaan material</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @php
            // Tentukan kelas status
            $statusClass = '';
            $statusIcon = '';
            $statusText = '';
            switch($penerimaan->status) {
                case 'menunggu':
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                    $statusIcon = 'fas fa-clock';
                    $statusText = 'MENUNGGU';
                    break;
                case 'dikembalikan':
                    $statusClass = 'bg-red-100 text-red-800';
                    $statusIcon = 'fas fa-times-circle';
                    $statusText = 'DIKEMBALIKAN';
                    break;
                case 'disetujui':
                    $statusClass = 'bg-green-100 text-green-800';
                    $statusIcon = 'fas fa-check-circle';
                    $statusText = 'DISETUJUI';
                    break;
                default:
                    $statusClass = 'bg-gray-100 text-gray-800';
                    $statusIcon = 'fas fa-info-circle';
                    $statusText = strtoupper($penerimaan->status);
            }
        @endphp

        <!-- Status Badge dan Info -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 inline-flex items-center text-sm font-semibold rounded-full {{ $statusClass }}">
                        <i class="{{ $statusIcon }} mr-2"></i> {{ $statusText }}
                    </span>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-barcode mr-1"></i> {{ $penerimaan->kode_transaksi }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($penerimaan->tanggal)->translatedFormat('d F Y') }}
                    </div>
                </div>
                <a href="{{ route('petugas.penerimaan.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto" aria-label="Tabs">
                    <button id="tab-penerimaan" 
                            class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-receipt mr-2"></i> Informasi Penerimaan
                    </button>
                    <button id="tab-material" 
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-boxes mr-2"></i> Material
                    </button>
                    <button id="tab-dokumen" 
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-file-alt mr-2"></i> Dokumen
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Tab 1: Informasi Penerimaan -->
                <div id="content-penerimaan" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Utama -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                Informasi Utama
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Kode Transaksi</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $penerimaan->kode_transaksi }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tanggal</p>
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($penerimaan->tanggal)->translatedFormat('d F Y') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Nama Penerima</p>
                                    <p class="text-gray-900">{{ $penerimaan->nama_pihak_transaksi }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Keperluan</p>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        {{ $penerimaan->keperluan }}
                                    </span>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Status</p>
                                    <span class="px-3 py-1 inline-flex items-center text-sm font-semibold rounded-full {{ $statusClass }}">
                                        <i class="{{ $statusIcon }} mr-2"></i> {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Tambahan -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                Informasi Tambahan
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Dibuat Oleh</p>
                                    <p class="text-gray-900">{{ $penerimaan->user->name ?? 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Dibuat Pada</p>
                                    <p class="text-gray-900">{{ $penerimaan->created_at->translatedFormat('d F Y H:i') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Terakhir Diperbarui</p>
                                    <p class="text-gray-900">{{ $penerimaan->updated_at->translatedFormat('d F Y H:i') }}</p>
                                </div>
                                
                                @if($penerimaan->verifikator)
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Diverifikasi Oleh</p>
                                    <p class="text-gray-900">{{ $penerimaan->verifikator->name }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tanggal Verifikasi</p>
                                    <p class="text-gray-900">
                                        {{ $penerimaan->tanggal_verifikasi ? \Carbon\Carbon::parse($penerimaan->tanggal_verifikasi)->translatedFormat('d F Y H:i') : 'N/A' }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab 2: Material -->
                <div id="content-material" class="tab-content hidden">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                            Daftar Material
                        </h3>
                        
                        @if($penerimaan->details && $penerimaan->details->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Material</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Material</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($penerimaan->details as $index => $detail)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $detail->material->nama_material ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            {{ $detail->material->kode_material ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            {{ $detail->material->satuan ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $detail->jumlah }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            Total Material:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $penerimaan->details->sum('jumlah') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="fas fa-box-open text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Tidak ada data material</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Tab 3: Dokumen -->
                <div id="content-dokumen" class="tab-content hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Foto Bukti -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-image mr-2 text-blue-500"></i> Foto Bukti
                            </h3>
                            
                            @if($penerimaan->foto_bukti)
                            <div class="image-container bg-white p-4 rounded-lg shadow border">
                                <img src="{{ Storage::url($penerimaan->foto_bukti) }}" 
                                     alt="Foto Bukti Penerimaan" 
                                     class="w-full h-auto rounded-lg max-h-96 object-contain">
                            </div>
                            
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-2"></i> Foto diambil pada saat penyerahan material
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="far fa-clock mr-1"></i> Diupload: {{ $penerimaan->created_at->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                            @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-image text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Foto bukti tidak tersedia</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Informasi Verifikasi -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                                <i class="fas fa-check-circle mr-2 text-blue-500"></i> Informasi Verifikasi
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- Status -->
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">Status Verifikasi</p>
                                    <div class="mt-2">
                                        <span class="px-3 py-1 inline-flex items-center text-sm font-semibold rounded-full {{ $statusClass }}">
                                            <i class="{{ $statusIcon }} mr-2"></i> {{ $statusText }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($penerimaan->status === 'dikembalikan' && $penerimaan->alasan_penolakan)
                                <!-- Alasan Penolakan -->
                                <div class="pt-4 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Alasan Pengembalian</p>
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    {{ $penerimaan->alasan_penolakan }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($penerimaan->status === 'disetujui' && $penerimaan->verifikator)
                                <!-- Info Verifikasi -->
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">Diverifikasi Oleh</p>
                                        <p class="text-gray-900">{{ $penerimaan->verifikator->name ?? 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">Tanggal Verifikasi</p>
                                        <p class="text-gray-900">
                                            {{ $penerimaan->tanggal_verifikasi ? \Carbon\Carbon::parse($penerimaan->tanggal_verifikasi)->translatedFormat('d F Y H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <!-- Tombol Edit/Hapus (hanya muncul jika status = "dikembalikan") -->
            @if($penerimaan->status === 'dikembalikan')
            <div class="flex space-x-3">
                <a href="{{ route('petugas.penerimaan.edit', $penerimaan->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <button type="button" 
                        onclick="showDeleteModal('{{ $penerimaan->id }}', '{{ $penerimaan->kode_transaksi }}')"
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </div>
            @endif
            
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('petugas.penerimaan.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-list mr-2"></i> Lihat Semua
                </a>
                @if($penerimaan->foto_bukti)
                <a href="{{ Storage::url($penerimaan->foto_bukti) }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-download mr-2"></i> Download Foto
                </a>
                @endif
                <a href="{{ route('petugas.penerimaan.print', $penerimaan->id) }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-print mr-2"></i> Cetak Detail
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-message">
                                    Apakah Anda yakin ingin menghapus penerimaan ini?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tab-button {
            padding: 1rem 1.5rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .tab-button:hover {
            color: #3b82f6;
        }
        
        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease-out;
        }
        
        .image-container {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        
        .image-container:hover {
            transform: scale(1.01);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Fungsi untuk mengubah tab
        function switchTab(tabName) {
            // Update tab aktif
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            const activeTab = document.getElementById(`tab-${tabName}`);
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            
            // Sembunyikan semua konten
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Tampilkan konten yang dipilih
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
        }
        
        // Event listener untuk tab
        document.getElementById('tab-penerimaan').addEventListener('click', () => switchTab('penerimaan'));
        document.getElementById('tab-material').addEventListener('click', () => switchTab('material'));
        document.getElementById('tab-dokumen').addEventListener('click', () => switchTab('dokumen'));
        
        // Fungsi untuk menampilkan modal hapus
        function showDeleteModal(id, kode) {
            const modal = document.getElementById('deleteModal');
            const modalMessage = document.getElementById('modal-message');
            const deleteForm = document.getElementById('deleteForm');
            
            modalMessage.textContent = `Apakah Anda yakin ingin menghapus penerimaan ${kode}? Tindakan ini tidak dapat dibatalkan.`;
            deleteForm.action = `/petugas/penerimaan/${id}`;
            
            modal.classList.remove('hidden');
        }
        
        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal ketika klik di luar
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>