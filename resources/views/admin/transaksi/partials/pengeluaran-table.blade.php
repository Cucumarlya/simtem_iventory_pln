{{-- resources/views/admin/transaksi/partials/pengeluaran-table.blade.php --}}

<!-- FILTER PENGELUARAN -->
<div class="bg-gray-50 p-4 rounded-lg mb-6">
    <form method="GET" action="{{ route('admin.transaksi.index') }}" class="space-y-4">
        <input type="hidden" name="tab" value="pengeluaran">
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search -->
            <div class="md:col-span-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                           placeholder="Cari nama, material, atau kode...">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="md:col-span-4">
                <div class="flex gap-2">
                    <select name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.transaksi.index', ['tab' => 'pengeluaran']) }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- TABLE PENGELUARAN -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Transaksi</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pengambil</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pengeluaran as $index => $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($pengeluaran->currentPage() - 1) * $pengeluaran->perPage() + $loop->iteration }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ $item->kode_transaksi }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->tanggal->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_pihak_transaksi }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @php
                        $keperluanColors = [
                            'YANBUNG' => 'bg-blue-100 text-blue-800',
                            'P2TL' => 'bg-green-100 text-green-800',
                            'GANGGUAN' => 'bg-red-100 text-red-800',
                            'PLN' => 'bg-yellow-100 text-yellow-800'
                        ];
                        $colorClass = $keperluanColors[$item->keperluan] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                        {{ $item->keperluan }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($item->status == 'disetujui')
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Disetujui
                        </span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Dikembalikan
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.transaksi.show', $item->id) }}" 
                           class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center text-xs">
                            <i class="fas fa-eye mr-1.5"></i>Detail
                        </a>
                        @if($item->status == 'menunggu')
                        <a href="{{ route('admin.transaksi.edit', $item->id) }}" 
                           class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors flex items-center text-xs">
                            <i class="fas fa-edit mr-1.5"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('admin.transaksi.destroy', $item->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center text-xs"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                <i class="fas fa-trash mr-1.5"></i>Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data pengeluaran
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($pengeluaran->count())
    <div class="mt-4">
        {{ $pengeluaran->appends(['tab' => 'pengeluaran', 'search' => request('search'), 'status' => request('status')])->links() }}
    </div>
    @endif
</div>

<!-- TOMBOL TAMBAH PENGELUARAN -->
<div class="mt-6 flex justify-end">
    <a href="{{ route('admin.transaksi.create', ['jenis' => 'pengeluaran']) }}" 
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran
    </a>
</div>