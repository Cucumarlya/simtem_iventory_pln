<!-- resources/views/admin/transaksi/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if($transaksi->jenis == 'penerimaan')
                    Detail Penerimaan Material
                @else
                    Detail Pengeluaran Material
                @endif
            </h2>
            <a href="{{ route('admin.transaksi.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Detail Transaksi Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            @if($transaksi->jenis == 'penerimaan')
                                Detail Penerimaan Material
                            @else
                                Detail Pengeluaran Material
                            @endif
                        </h3>
                    </div>
                    
                    <!-- Informasi Umum -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">
                                @if($transaksi->jenis == 'penerimaan')
                                    Informasi Penerimaan
                                @else
                                    Informasi Pengeluaran
                                @endif
                            </h4>
                            <div class="space-y-3">
                                @if($transaksi->jenis == 'penerimaan')
                                    <div>
                                        <span class="text-sm text-gray-500">Kode Transaksi:</span>
                                        <p class="font-medium">{{ $transaksi->kode_transaksi }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Tanggal Penerimaan:</span>
                                        <p class="font-medium">{{ $transaksi->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Nama Penerima:</span>
                                        <p class="font-medium">{{ $transaksi->nama_pihak_transaksi }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Keperluan:</span>
                                        <p class="font-medium">{{ $transaksi->keperluan }}</p>
                                    </div>
                                @else
                                    <div>
                                        <span class="text-sm text-gray-500">Kode Transaksi:</span>
                                        <p class="font-medium">{{ $transaksi->kode_transaksi }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Tanggal Pengeluaran:</span>
                                        <p class="font-medium">{{ $transaksi->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Nama Pengambil:</span>
                                        <p class="font-medium">{{ $transaksi->nama_pihak_transaksi }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Keperluan:</span>
                                        <p class="font-medium">{{ $transaksi->keperluan }}</p>
                                    </div>
                                    @if($transaksi->nomor_pelanggan)
                                    <div>
                                        <span class="text-sm text-gray-500">ID Pelanggan:</span>
                                        <p class="font-medium font-mono">{{ $transaksi->nomor_pelanggan }}</p>
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-3">Informasi Verifikasi</h4>
                            <div class="space-y-3">
                                @if($transaksi->status != 'menunggu')
                                <div>
                                    <span class="text-sm text-gray-500">Tanggal Verifikasi:</span>
                                    <p class="font-medium">{{ $transaksi->tanggal_verifikasi->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Diverifikasi Oleh:</span>
                                    <p class="font-medium">{{ $transaksi->verifikator->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <p class="font-medium">
                                        @if($transaksi->status == 'disetujui')
                                            <span class="text-green-600">Disetujui</span>
                                        @elseif($transaksi->status == 'dikembalikan')
                                            <span class="text-red-600">Dikembalikan</span>
                                        @endif
                                    </p>
                                </div>
                                @if($transaksi->status == 'dikembalikan')
                                <div>
                                    <span class="text-sm text-gray-500">Alasan Pengembalian:</span>
                                    <p class="font-medium text-red-600">{{ $transaksi->alasan_penolakan }}</p>
                                </div>
                                @endif
                                @else
                                <div>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <p class="font-medium text-yellow-600">Menunggu Verifikasi</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Dibuat Oleh:</span>
                                    <p class="font-medium">{{ $transaksi->user->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Tanggal Dibuat:</span>
                                    <p class="font-medium">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rincian Material -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-700 mb-4">Rincian Material</h4>
                        @if($transaksi->details && $transaksi->details->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Material</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Material</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transaksi->details as $index => $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                            {{ $detail->material->kode_material ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $detail->material->nama_material ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->jumlah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->material->satuan ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-4">Tidak ada data material</p>
                        @endif
                    </div>
                    
                    <!-- Dokumentasi -->
                    <div>
                        <h4 class="font-medium text-gray-700 mb-4">Dokumentasi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($transaksi->jenis == 'pengeluaran' && $transaksi->foto_sr_sebelum)
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">Foto SR Sebelum</p>
                                <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_sr_sebelum) }}')"
                                        class="w-full">
                                    <img src="{{ Storage::url($transaksi->foto_sr_sebelum) }}" 
                                         alt="Foto SR Sebelum" 
                                         class="w-full h-48 object-cover rounded-lg border hover:opacity-90 transition-opacity cursor-pointer">
                                </button>
                            </div>
                            @endif
                            
                            @if($transaksi->jenis == 'pengeluaran' && $transaksi->foto_sr_sesudah)
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">Foto SR Sesudah</p>
                                <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_sr_sesudah) }}')"
                                        class="w-full">
                                    <img src="{{ Storage::url($transaksi->foto_sr_sesudah) }}" 
                                         alt="Foto SR Sesudah" 
                                         class="w-full h-48 object-cover rounded-lg border hover:opacity-90 transition-opacity cursor-pointer">
                                </button>
                            </div>
                            @endif
                            
                            @if($transaksi->foto_bukti)
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">Foto Bukti {{ $transaksi->jenis == 'penerimaan' ? 'Penerimaan' : 'Pengeluaran' }}</p>
                                <button type="button" onclick="showImageModal('{{ Storage::url($transaksi->foto_bukti) }}')"
                                        class="w-full">
                                    <img src="{{ Storage::url($transaksi->foto_bukti) }}" 
                                         alt="Foto Bukti" 
                                         class="w-full h-48 object-cover rounded-lg border hover:opacity-90 transition-opacity cursor-pointer">
                                </button>
                            </div>
                            @endif
                        </div>
                        
                        @if(!$transaksi->foto_sr_sebelum && !$transaksi->foto_sr_sesudah && !$transaksi->foto_bukti)
                        <p class="text-gray-500 text-center py-4">Tidak ada dokumentasi</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Tombol Navigasi -->
            <div class="flex justify-between">
                <a href="{{ route('admin.transaksi.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
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
    </style>
    @endpush
</x-app-layout>