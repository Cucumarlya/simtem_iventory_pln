<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Detail Verifikasi Transaksi</h1>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $isPenerimaan ? 'Detail penerimaan material' : 'Detail pengeluaran material' }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="history.back()" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
                <span class="text-sm text-gray-500">
                    {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r {{ $isPenerimaan ? 'from-blue-50 to-white' : 'from-green-50 to-white' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 {{ $isPenerimaan ? 'bg-blue-100' : 'bg-green-100' }} rounded-lg mr-4">
                                @if($isPenerimaan)
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $isPenerimaan ? 'DETAIL PENERIMAAN MATERIAL' : 'DETAIL PENGELUARAN MATERIAL' }}
                                </h3>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $transaksi['status'] == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($transaksi['status'] == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaksi['status']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            ID: {{ $transaksi['id'] }}
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Utama dalam satu kotak -->
                <div class="p-6 border-b border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi {{ $isPenerimaan ? 'Penerimaan' : 'Pengeluaran' }}
                    </h4>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                            <!-- Kode Transaksi -->
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-500">Kode Transaksi</div>
                                <div class="text-base font-semibold text-gray-900">{{ $transaksi['kode_transaksi'] }}</div>
                            </div>
                            
                            <!-- Tanggal -->
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $isPenerimaan ? 'Tanggal Penerimaan' : 'Tanggal Pengeluaran' }}
                                </div>
                                <div class="text-base font-semibold text-gray-900">{{ $transaksi['tanggal'] }}</div>
                            </div>
                            
                            <!-- Nama -->
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $isPenerimaan ? 'Nama Penerima' : 'Nama Pengambil' }}
                                </div>
                                <div class="text-base font-semibold text-gray-900">{{ $transaksi['nama'] }}</div>
                                @if($transaksi['jabatan'])
                                <div class="text-sm text-gray-600">{{ $transaksi['jabatan'] }}</div>
                                @endif
                            </div>
                            
                            <!-- Keperluan -->
                            <div class="space-y-2">
                                <div class="text-sm font-medium text-gray-500">Keperluan</div>
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $transaksi['kodeKeperluan'] == 'YANBUNG' ? 'bg-blue-100 text-blue-800' : 
                                           ($transaksi['kodeKeperluan'] == 'P2TL' ? 'bg-green-100 text-green-800' : 
                                           ($transaksi['kodeKeperluan'] == 'GANGGUAN' ? 'bg-purple-100 text-purple-800' : 
                                           ($transaksi['kodeKeperluan'] == 'PLN' ? 'bg-orange-100 text-orange-800' : 
                                           'bg-gray-100 text-gray-800'))) }} w-fit mb-2">
                                        {{ $transaksi['kodeKeperluan'] }}
                                    </span>
                                    @if($transaksi['keperluan'] && $transaksi['keperluan'] != 'Tidak ada')
                                    <div class="text-sm text-gray-700">
                                        {{ $transaksi['keperluan'] }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- ID Pelanggan (jika ada) -->
                            @if(!$isPenerimaan && $transaksi['idPelanggan'])
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-500">ID Pelanggan</div>
                                <div class="text-base font-semibold text-gray-900">{{ $transaksi['idPelanggan'] }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Rincian Material -->
                <div class="p-6 border-b border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Rincian Material
                    </h4>
                    
                    @if(count($transaksi['material']) > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KODE</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA MATERIAL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">JUMLAH</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">SATUAN</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transaksi['material'] as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['kode'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['nama'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">{{ $item['jumlah'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item['satuan'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        Tidak ada data material
                    </div>
                    @endif
                </div>
                
                <!-- Dokumentasi Foto -->
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Dokumentasi
                    </h4>
                    
                    @php
                        // Cari dokumentasi berdasarkan judul untuk urutan yang benar
                        $srSebelum = null;
                        $srSesudah = null;
                        $buktiTransaksi = null;
                        
                        if(isset($transaksi['dokumentasi']) && count($transaksi['dokumentasi']) > 0) {
                            foreach($transaksi['dokumentasi'] as $dokumen) {
                                if(isset($dokumen['judul'])) {
                                    if(str_contains(strtolower($dokumen['judul']), 'sebelum') || str_contains(strtolower($dokumen['judul']), 'sr sebelum')) {
                                        $srSebelum = $dokumen;
                                    } elseif(str_contains(strtolower($dokumen['judul']), 'sesudah') || str_contains(strtolower($dokumen['judul']), 'sr sesudah')) {
                                        $srSesudah = $dokumen;
                                    } elseif(str_contains(strtolower($dokumen['judul']), 'bukti')) {
                                        $buktiTransaksi = $dokumen;
                                    }
                                }
                            }
                            
                            // Jika tidak ada data dengan judul spesifik, gunakan urutan default dari array
                            if(!$srSebelum && isset($transaksi['dokumentasi'][0])) {
                                $srSebelum = $transaksi['dokumentasi'][0];
                            }
                            if(!$srSesudah && isset($transaksi['dokumentasi'][1])) {
                                $srSesudah = $transaksi['dokumentasi'][1];
                            }
                            if(!$buktiTransaksi && isset($transaksi['dokumentasi'][2])) {
                                $buktiTransaksi = $transaksi['dokumentasi'][2];
                            }
                            
                            // Fallback jika masih null, coba cari dari semua data
                            if(!$srSebelum || !$srSesudah || !$buktiTransaksi) {
                                foreach($transaksi['dokumentasi'] as $dokumen) {
                                    if(!$srSebelum) {
                                        $srSebelum = $dokumen;
                                    } elseif(!$srSesudah) {
                                        $srSesudah = $dokumen;
                                    } elseif(!$buktiTransaksi) {
                                        $buktiTransaksi = $dokumen;
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    @if($srSebelum || $srSesudah || $buktiTransaksi)
                        <!-- Foto SR Sebelum -->
                        @if($srSebelum && isset($srSebelum['foto']) && count($srSebelum['foto']) > 0)
                        <div class="mb-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Foto SR Sebelum</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($srSebelum['foto'] as $fotoIndex => $foto)
                                <div class="relative group">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ $foto }}" 
                                             alt="Foto SR Sebelum {{ $fotoIndex + 1 }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                             onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Tidak+Tersedia'">
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <button onclick="showImageModal('{{ $foto }}', 'Foto SR Sebelum {{ $fotoIndex + 1 }}')" 
                                                class="bg-white bg-opacity-90 p-2 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Foto SR Sesudah -->
                        @if($srSesudah && isset($srSesudah['foto']) && count($srSesudah['foto']) > 0)
                        <div class="mb-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Foto SR Sesudah</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($srSesudah['foto'] as $fotoIndex => $foto)
                                <div class="relative group">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ $foto }}" 
                                             alt="Foto SR Sesudah {{ $fotoIndex + 1 }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                             onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Tidak+Tersedia'">
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <button onclick="showImageModal('{{ $foto }}', 'Foto SR Sesudah {{ $fotoIndex + 1 }}')" 
                                                class="bg-white bg-opacity-90 p-2 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Foto Bukti Penerimaan/Pengeluaran -->
                        @if($buktiTransaksi && isset($buktiTransaksi['foto']) && count($buktiTransaksi['foto']) > 0)
                        <div class="mb-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">
                                Foto Bukti {{ $isPenerimaan ? 'Penerimaan' : 'Pengeluaran' }}
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($buktiTransaksi['foto'] as $fotoIndex => $foto)
                                <div class="relative group">
                                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ $foto }}" 
                                             alt="Foto Bukti {{ $isPenerimaan ? 'Penerimaan' : 'Pengeluaran' }} {{ $fotoIndex + 1 }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                             onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Tidak+Tersedia'">
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <button onclick="showImageModal('{{ $foto }}', 'Foto Bukti {{ $isPenerimaan ? 'Penerimaan' : 'Pengeluaran' }} {{ $fotoIndex + 1 }}')" 
                                                class="bg-white bg-opacity-90 p-2 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2">Tidak ada dokumentasi foto</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons (hanya tampil jika status menunggu) -->
            @if($transaksi['status'] == 'menunggu')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.verifikasi.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="showKembalikanModal()" 
                                class="verifikasi-btn-action verifikasi-btn-kembalikan">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            Kembalikan
                        </button>
                        <button onclick="confirmSetujui()" 
                                class="verifikasi-btn-action verifikasi-btn-setujui">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Setujui
                        </button>
                    </div>
                </div>
            </div>
            @else
            <!-- Hanya tombol kembali jika status bukan menunggu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Transaksi sudah {{ $transaksi['status'] == 'disetujui' ? 'disetujui' : 'dikembalikan' }} pada {{ $transaksi['tanggal'] }}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.verifikasi.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Container untuk Aksi -->
    <div id="modalContainer"></div>
    
    <!-- Modal untuk tampil gambar full -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="imageModalTitle" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 flex justify-center">
                <img id="modalImage" src="" alt="" class="max-h-[70vh] max-w-full object-contain">
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verifikasi.css') }}">
        <style>
            .aspect-video {
                aspect-ratio: 16/9;
            }
            
            #imageModal {
                transition: opacity 0.3s ease;
                z-index: 10001;
            }
            
            #imageModal:not(.hidden) {
                display: flex !important;
            }
            
            /* Fix untuk modal overlay */
            .verifikasi-modal-overlay {
                z-index: 10000;
            }
            
            /* Pastikan gambar di modal bisa di-scroll jika terlalu besar */
            #modalImage {
                max-height: 70vh;
                max-width: 100%;
                object-fit: contain;
                cursor: pointer;
            }
            
            /* Tambahkan scroll di dalam modal jika gambar besar */
            #imageModal .bg-white {
                max-height: 90vh;
                overflow-y: auto;
            }
            
            /* Custom scroll untuk modal */
            #imageModal .bg-white::-webkit-scrollbar {
                width: 6px;
            }
            
            #imageModal .bg-white::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }
            
            #imageModal .bg-white::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }
            
            #imageModal .bg-white::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
            
            /* Prevent background scroll when modal is open */
            body.modal-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
            }
            
            /* Styling untuk informasi utama dalam satu kotak */
            .info-container {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                padding: 1.5rem;
            }
            
            .info-item {
                margin-bottom: 1rem;
            }
            
            .info-item:last-child {
                margin-bottom: 0;
            }
            
            .info-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: #6b7280;
                margin-bottom: 0.25rem;
            }
            
            .info-value {
                font-size: 1rem;
                font-weight: 600;
                color: #111827;
            }
            
            .info-subvalue {
                font-size: 0.875rem;
                color: #6b7280;
            }
            
            .keperluan-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                margin-bottom: 0.5rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Fungsi untuk modal tampil gambar - DIPERBAIKI
            function showImageModal(imageSrc, title) {
                // Simpan posisi scroll sebelum modal dibuka
                const scrollY = window.scrollY;
                
                // Set modal content
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModalTitle').textContent = title;
                document.getElementById('imageModal').classList.remove('hidden');
                
                // Prevent body scroll dengan cara yang benar
                document.body.classList.add('modal-open');
                document.body.style.top = `-${scrollY}px`;
                
                console.log('Image modal opened, scrollY saved:', scrollY);
            }
            
            function closeImageModal() {
                // Close modal
                document.getElementById('imageModal').classList.add('hidden');
                
                // Restore body scroll
                const scrollY = parseInt(document.body.style.top || '0') * -1;
                document.body.classList.remove('modal-open');
                document.body.style.top = '';
                
                // Scroll kembali ke posisi semula
                if (scrollY) {
                    window.scrollTo(0, scrollY);
                }
                
                console.log('Image modal closed, scroll restored to:', scrollY);
            }
            
            // Tutup modal gambar dengan ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                    closeImageModal();
                }
            });
            
            // Tutup modal gambar klik di luar
            document.getElementById('imageModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });

            // Fungsi untuk modal setujui - DIPERBAIKI
            function confirmSetujui() {
                const transaksiId = "{{ $transaksi['id'] }}";
                const nama = "{{ $transaksi['nama'] }}";
                const type = "{{ $type }}";
                
                // Simpan posisi scroll sebelum modal dibuka
                const scrollY = window.scrollY;
                
                const modalHTML = `
                    <div class="verifikasi-modal-overlay" id="setujuiModal">
                        <div class="verifikasi-modal-content">
                            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Setujui Transaksi</h3>
                                            <p class="text-sm text-gray-600 mt-1">Setujui transaksi ini?</p>
                                        </div>
                                    </div>
                                    <button onclick="closeActionModal()" class="verifikasi-modal-close p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="mb-6">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-center text-gray-700 mb-2">
                                        Apakah Anda yakin ingin <span class="font-semibold text-green-600">menyetujui</span> transaksi ini?
                                    </p>
                                    <p class="text-center text-sm text-gray-500">
                                        Transaksi akan divalidasi dan diproses untuk update stok.
                                    </p>
                                </div>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <div class="text-sm text-yellow-800">
                                            <strong>Perhatian:</strong> Pastikan semua dokumen lengkap sebelum menyetujui.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button onclick="closeActionModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button onclick="performSetujui('${transaksiId}')" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-medium hover:from-green-600 hover:to-green-700 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Ya, Setujui Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('modalContainer').innerHTML = modalHTML;
                
                // Prevent body scroll dengan cara yang benar
                document.body.classList.add('modal-open');
                document.body.style.top = `-${scrollY}px`;
                
                // Tambahkan event listener untuk close modal if outside
                setTimeout(() => {
                    const modalElement = document.getElementById('setujuiModal');
                    if (modalElement) {
                        modalElement.addEventListener('click', function(e) {
                            if (e.target === this) {
                                closeActionModal();
                            }
                        });
                    }
                }, 100);
            }
            
            // Fungsi untuk modal kembalikan - DIPERBAIKI
            function showKembalikanModal() {
                const transaksiId = "{{ $transaksi['id'] }}";
                const nama = "{{ $transaksi['nama'] }}";
                
                // Simpan posisi scroll sebelum modal dibuka
                const scrollY = window.scrollY;
                
                const modalHTML = `
                    <div class="verifikasi-modal-overlay" id="kembalikanModal">
                        <div class="verifikasi-modal-content">
                            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Kembalikan Transaksi</h3>
                                            <p class="text-sm text-gray-600 mt-1">Kembalikan transaksi untuk direvisi</p>
                                        </div>
                                    </div>
                                    <button onclick="closeActionModal()" class="verifikasi-modal-close p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="mb-6">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                    </div>
                                    <p class="text-center text-gray-700 mb-2">
                                        Anda akan <span class="font-semibold text-red-600">mengembalikan</span> transaksi untuk direvisi.
                                    </p>
                                    <p class="text-center text-sm text-gray-500">
                                        Transaksi akan dikembalikan ke pembuat dengan alasan yang Anda berikan.
                                    </p>
                                </div>
                                
                                <form id="kembalikanForm" onsubmit="handleKembalikanSubmit(event, '${transaksiId}')">
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <span class="text-red-600">*</span> Alasan Pengembalian
                                        </label>
                                        <textarea 
                                            id="alasanPengembalian" 
                                            name="alasan"
                                            rows="4" 
                                            class="verifikasi-form-input" 
                                            placeholder="Berikan alasan yang jelas dan spesifik mengapa transaksi ini dikembalikan..."
                                            required
                                        ></textarea>
                                        <p class="text-xs text-gray-500 mt-2">
                                            Alasan yang jelas akan membantu penerima untuk memperbaiki transaksi dengan tepat.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div class="text-sm text-yellow-800">
                                                <strong>Perhatian:</strong> Transaksi yang dikembalikan akan muncul kembali di daftar penerima untuk diperbaiki.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" onclick="closeActionModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit" class="submit-kembalikan-btn verifikasi-btn-action verifikasi-btn-kembalikan">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            Kembalikan Transaksi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('modalContainer').innerHTML = modalHTML;
                
                // Prevent body scroll dengan cara yang benar
                document.body.classList.add('modal-open');
                document.body.style.top = `-${scrollY}px`;
                
                // Tambahkan event listener untuk close modal if outside
                setTimeout(() => {
                    const modalElement = document.getElementById('kembalikanModal');
                    if (modalElement) {
                        modalElement.addEventListener('click', function(e) {
                            if (e.target === this) {
                                closeActionModal();
                            }
                        });
                    }
                }, 100);
            }
            
            // Fungsi untuk menutup modal aksi - DIPERBAIKI
            function closeActionModal() {
                // Clear modal content
                document.getElementById('modalContainer').innerHTML = '';
                
                // Restore body scroll
                const scrollY = parseInt(document.body.style.top || '0') * -1;
                document.body.classList.remove('modal-open');
                document.body.style.top = '';
                
                // Scroll kembali ke posisi semula
                if (scrollY) {
                    window.scrollTo(0, scrollY);
                }
                
                console.log('Action modal closed, scroll restored to:', scrollY);
            }
            
            // Fungsi untuk handle submit kembalikan
            function handleKembalikanSubmit(e, transaksiId) {
                e.preventDefault();
                const alasan = document.getElementById('alasanPengembalian').value;
                
                if (!alasan || alasan.trim() === '') {
                    alert('Harap isi alasan pengembalian');
                    return false;
                }
                
                performKembalikan(transaksiId, alasan);
                return false;
            }
            
            // Fungsi untuk menyetujui transaksi via API
            async function performSetujui(transaksiId) {
                try {
                    const response = await fetch(`/admin/verifikasi/${transaksiId}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: 'disetujui'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        alert('Transaksi berhasil disetujui!');
                        window.location.href = "{{ route('admin.verifikasi.index') }}";
                    } else {
                        alert(data.error || 'Terjadi kesalahan saat menyetujui transaksi');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat menyetujui transaksi');
                }
            }
            
            // Fungsi untuk mengembalikan transaksi via API
            async function performKembalikan(transaksiId, alasan) {
                try {
                    const response = await fetch(`/admin/verifikasi/${transaksiId}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: 'dikembalikan',
                            alasan_pengembalian: alasan
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        alert('Transaksi berhasil dikembalikan!');
                        window.location.href = "{{ route('admin.verifikasi.index') }}";
                    } else {
                        alert(data.error || 'Terjadi kesalahan saat mengembalikan transaksi');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat mengembalikan transaksi');
                }
            }
            
            // Event listener untuk mencegah scroll di background saat modal terbuka
            document.addEventListener('wheel', function(e) {
                if (document.body.classList.contains('modal-open')) {
                    // Jika sedang di dalam modal gambar, biarkan scroll di modal
                    const imageModal = document.getElementById('imageModal');
                    if (imageModal && !imageModal.classList.contains('hidden')) {
                        // Cek jika scroll terjadi di dalam modal gambar
                        if (e.target.closest('#imageModal .bg-white')) {
                            return; // Biarkan scroll di dalam modal
                        }
                    }
                    
                    // Jika sedang di dalam modal aksi, biarkan scroll di modal
                    const modalContainer = document.getElementById('modalContainer');
                    if (modalContainer && modalContainer.innerHTML !== '') {
                        // Cek jika scroll terjadi di dalam modal aksi
                        if (e.target.closest('.verifikasi-modal-content')) {
                            return; // Biarkan scroll di dalam modal
                        }
                    }
                    
                    // Jika bukan di dalam modal, prevent scroll
                    e.preventDefault();
                    return false;
                }
            }, { passive: false });
            
            // Event listener untuk touchmove (mobile)
            document.addEventListener('touchmove', function(e) {
                if (document.body.classList.contains('modal-open')) {
                    // Jika sedang di dalam modal gambar, biarkan scroll di modal
                    const imageModal = document.getElementById('imageModal');
                    if (imageModal && !imageModal.classList.contains('hidden')) {
                        // Cek jika touch terjadi di dalam modal gambar
                        if (e.target.closest('#imageModal .bg-white')) {
                            return; // Biarkan touch di dalam modal
                        }
                    }
                    
                    // Jika sedang di dalam modal aksi, biarkan scroll di modal
                    const modalContainer = document.getElementById('modalContainer');
                    if (modalContainer && modalContainer.innerHTML !== '') {
                        // Cek jika touch terjadi di dalam modal aksi
                        if (e.target.closest('.verifikasi-modal-content')) {
                            return; // Biarkan touch di dalam modal
                        }
                    }
                    
                    // Jika bukan di dalam modal, prevent scroll
                    e.preventDefault();
                    return false;
                }
            }, { passive: false });
            
            // Initialize ketika DOM siap
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Verifikasi detail page loaded');
            });
        </script>
    @endpush
</x-app-layout>