<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-600">Ringkasan sistem pengelolaan material</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Verifications -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Materials -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-cubes text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Material</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $materialCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Stok Menipis</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $lowStockCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Transactions -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h3>
                <div class="space-y-3">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $transaction->kode_transaksi }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $transaction->jenis === 'penerimaan' ? 'Penerimaan' : 'Pengeluaran' }} • 
                                {{ $transaction->nama_pihak_transaksi }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="badge {{ $transaction->lastVerifikasi && $transaction->lastVerifikasi->status === 'disetujui' ? 'badge-success' : 'badge-warning' }}">
                                {{ $transaction->lastVerifikasi ? ucfirst($transaction->lastVerifikasi->status) : 'Menunggu' }}
                            </span>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $transaction->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Stock Alerts -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Peringatan Stok</h3>
                <div class="space-y-3">
                    @foreach($lowStockMaterials as $material)
                    <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div>
                            <p class="font-medium text-red-900">{{ $material->nama_material }}</p>
                            <p class="text-sm text-red-600">
                                Stok: {{ $material->stok_awal }} • Minimal: {{ $material->min_stok }}
                            </p>
                        </div>
                        <span class="badge badge-danger">Kritis</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dashboard specific scripts can go here
        });
    </script>
    @endpush
</x-app-layout>