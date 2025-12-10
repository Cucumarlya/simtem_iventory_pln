<!-- resources/views/admin/transaksi/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi Material') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- HEADER -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daftar Transaksi Material</h3>
                            <p class="text-sm text-gray-600">Histori transaksi material yang telah disetujui dan
                                dikembalikan</p>
                        </div>

                        <!-- Action Buttons Group -->
                        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                            <!-- Export & Print Buttons -->
                            <div class="flex gap-2 mb-3 md:mb-0 md:mr-4">
                                <button
                                    class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center text-sm font-medium"
                                    title="Export to Excel"
                                    onclick="alert('Fitur export akan aktif saat data real tersedia')">
                                    <i class="fas fa-file-excel mr-2"></i> Excel
                                </button>

                                <button
                                    class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center text-sm font-medium"
                                    title="Export to PDF"
                                    onclick="alert('Fitur export akan aktif saat data real tersedia')">
                                    <i class="fas fa-file-pdf mr-2"></i> PDF
                                </button>

                                <button onclick="printTransaksi()"
                                    class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center text-sm font-medium"
                                    title="Print">
                                    <i class="fas fa-print mr-2"></i> Print
                                </button>
                            </div>

                            <!-- Button Tambah -->
                            <a href="{{ route('admin.transaksi.create', ['jenis' => 'penerimaan']) }}"
                                id="btnTambahTransaksi"
                                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center text-sm font-medium justify-center">
                                <i class="fas fa-plus mr-2"></i> Tambah Penerimaan
                            </a>
                        </div>
                    </div>

                    <!-- TABS -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <a href="{{ route('admin.transaksi.index', ['tab' => 'penerimaan']) }}"
                                class="tab-link py-3 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200"
                                data-tab="penerimaan">
                                Penerimaan
                            </a>
                            <a href="{{ route('admin.transaksi.index', ['tab' => 'pengeluaran']) }}"
                                class="tab-link py-3 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200"
                                data-tab="pengeluaran">
                                Pengeluaran
                            </a>
                        </nav>
                    </div>

                    <!-- TAB CONTENT -->
                    <div id="tabContent">
                        <!-- TAB PENERIMAAN -->
                        <div id="tab-penerimaan" class="tab-pane">
                            <div class="overflow-x-auto rounded-lg border border-gray-200">

                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Penerima</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Keperluan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($transaksis as $index => $transaksi)
                                            @if ($transaksi->jenis != 'penerimaan')
                                                @continue
                                            @endif
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $transaksi['nama_pihak_transaksi'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $transaksi->keperluan }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        $statusText = [
                                                            'disetujui' => 'Disetujui',
                                                            'dikembalikan' => 'Dikembalikan',
                                                        ];
                                                        $text =
                                                            $statusText[$transaksi['status']] ??
                                                            ucfirst($transaksi->status);
                                                    @endphp
                                                    {{ $text }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex flex-wrap gap-2">
                                                        <!-- TOMBOL DETAIL (BIRU) -->
                                                        <a href="{{ route('admin.transaksi.show', $transaksi->id) }}"
                                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm font-medium"
                                                            title="Lihat Detail Transaksi">
                                                            Detail
                                                        </a>

                                                        <!-- LOGIKA BUTTON EDIT DAN HAPUS -->
                                                        @php
                                                            $tampilkanEditHapus = false;

                                                            if (
                                                                $transaksi->status == 'disetujui' &&
                                                                $transaksi->input_oleh == 'admin'
                                                            ) {
                                                                $tampilkanEditHapus = true;
                                                            }
                                                        @endphp

                                                        @if ($tampilkanEditHapus)
                                                            <!-- TOMBOL EDIT (KUNING) -->
                                                            <a href="{{ route('admin.transaksi.edit', $transaksi->id) }}"
                                                                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors text-sm font-medium"
                                                                title="Edit Transaksi">
                                                                Edit
                                                            </a>

                                                            <!-- TOMBOL HAPUS (MERAH) -->
                                                            <form method="POST"
                                                                action="{{ route('admin.transaksi.destroy', $transaksi->id) }}"
                                                                class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-sm font-medium delete-btn"
                                                                    onclick="confirmDelete({{ $transaksi->id }}, this, '{{ $transaksi->nama_pihak_transaksi }}')"
                                                                    title="Hapus Transaksi">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="text-center py-12">
                                                        <div class="text-gray-400 mb-4">
                                                            <i class="fas fa-outbox fa-4x"></i>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                                            Belum ada data penerimaan
                                                        </h3>
                                                        <p class="text-gray-500 mb-6">
                                                            Mulai dengan menambahkan data penerimaan material baru
                                                        </p>
                                                        <a href="{{ route('admin.transaksi.create', ['jenis' => 'penerimaan']) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <i class="fas fa-plus mr-2"></i>Tambah Penerimaan Pertama
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <!-- TAB PENGELUARAN -->
                        <div id="tab-pengeluaran" class="tab-pane hidden">
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Pengambil</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Keperluan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID Pelanggan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($transaksis as $index => $transaksi)
                                            @if ($transaksi->jenis != 'penerimaan')
                                                @continue
                                            @endif
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $transaksi['nama_pihak_transaksi'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $transaksi->keperluan }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php
                                                        $statusText = [
                                                            'disetujui' => 'Disetujui',
                                                            'dikembalikan' => 'Dikembalikan',
                                                        ];
                                                        $text =
                                                            $statusText[$transaksi['status']] ??
                                                            ucfirst($transaksi->status);
                                                    @endphp
                                                    {{ $text }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex flex-wrap gap-2">
                                                        <!-- TOMBOL DETAIL (BIRU) -->
                                                        <a href="{{ route('admin.transaksi.show', $transaksi->id) }}"
                                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors text-sm font-medium"
                                                            title="Lihat Detail Transaksi">
                                                            Detail
                                                        </a>

                                                        <!-- LOGIKA BUTTON EDIT DAN HAPUS -->
                                                        @php
                                                            $tampilkanEditHapus = false;

                                                            if (
                                                                $transaksi->status == 'disetujui' &&
                                                                $transaksi->input_oleh == 'admin'
                                                            ) {
                                                                $tampilkanEditHapus = true;
                                                            }
                                                        @endphp

                                                        @if ($tampilkanEditHapus)
                                                            <!-- TOMBOL EDIT (KUNING) -->
                                                            <a href="{{ route('admin.transaksi.edit', $transaksi->id) }}"
                                                                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors text-sm font-medium"
                                                                title="Edit Transaksi">
                                                                Edit
                                                            </a>

                                                            <!-- TOMBOL HAPUS (MERAH) -->
                                                            <form method="POST"
                                                                action="{{ route('admin.transaksi.destroy', $transaksi->id) }}"
                                                                class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-sm font-medium delete-btn"
                                                                    onclick="confirmDelete({{ $transaksi->id }}, this, '{{ $transaksi->nama_pihak_transaksi }}')"
                                                                    title="Hapus Transaksi">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <div class="text-center py-12">
                                                        <div class="text-gray-400 mb-4">
                                                            <i class="fas fa-outbox fa-4x"></i>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                                            Belum ada data pengeluaran
                                                        </h3>
                                                        <p class="text-gray-500 mb-6">
                                                            Mulai dengan menambahkan data pengeluaran material baru
                                                        </p>
                                                        <a href="{{ route('admin.transaksi.create', ['jenis' => 'pengeluaran']) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran Pertama
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Fungsi konfirmasi delete
            function confirmDelete(id, button, namaTransaksi) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus transaksi <strong>${namaTransaksi}</strong>?<br>
                  <span class="text-sm text-red-600">Data yang dihapus tidak dapat dikembalikan!</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        const originalHTML = button.innerHTML;
                        button.innerHTML = 'Menghapus...';
                        button.disabled = true;

                        // Submit form
                        const form = button.closest('form');
                        form.submit();
                    }
                });
            }

            // Fungsi untuk print halaman transaksi
            function printTransaksi() {
                const tab = getActiveTab();
                Swal.fire({
                    title: 'Print Transaksi',
                    text: `Mencetak data ${tab === 'pengeluaran' ? 'Pengeluaran' : 'Penerimaan'}...`,
                    icon: 'info',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            }

            // Fungsi untuk mendapatkan tab aktif
            function getActiveTab() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('tab') || 'penerimaan';
            }

            // Fungsi untuk update tab secara dinamis
            function updateActiveTab(tab) {
                // Update URL parameter
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                window.history.pushState({}, '', url);

                // Update tombol "Tambah"
                const btnTambah = document.getElementById('btnTambahTransaksi');
                if (tab === 'pengeluaran') {
                    btnTambah.href = "{{ route('admin.transaksi.create', ['jenis' => 'pengeluaran']) }}";
                    btnTambah.innerHTML = '<i class="fas fa-plus mr-2"></i> Tambah Pengeluaran';
                    btnTambah.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'focus:ring-blue-500');
                    btnTambah.classList.add('bg-green-600', 'hover:bg-green-700', 'focus:ring-green-500');
                } else {
                    btnTambah.href = "{{ route('admin.transaksi.create', ['jenis' => 'penerimaan']) }}";
                    btnTambah.innerHTML = '<i class="fas fa-plus mr-2"></i> Tambah Penerimaan';
                    btnTambah.classList.remove('bg-green-600', 'hover:bg-green-700', 'focus:ring-green-500');
                    btnTambah.classList.add('bg-blue-600', 'hover:bg-blue-700', 'focus:ring-blue-500');
                }

                // Update tab style
                document.querySelectorAll('.tab-link').forEach(link => {
                    link.classList.remove('border-blue-500', 'text-blue-600', 'border-green-500', 'text-green-600');
                    link.classList.add('border-transparent', 'text-gray-500');
                });

                const activeTabLink = document.querySelector(`.tab-link[data-tab="${tab}"]`);
                if (activeTabLink) {
                    activeTabLink.classList.remove('border-transparent', 'text-gray-500');
                    if (tab === 'pengeluaran') {
                        activeTabLink.classList.add('border-green-500', 'text-green-600');
                    } else {
                        activeTabLink.classList.add('border-blue-500', 'text-blue-600');
                    }
                }

                // Update tab content visibility
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.add('hidden');
                });

                const activePane = document.getElementById(`tab-${tab}`);
                if (activePane) {
                    activePane.classList.remove('hidden');
                }
            }

            // Auto aktifkan tab berdasarkan URL parameter saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                const initialTab = getActiveTab();
                updateActiveTab(initialTab);

                // Setup event untuk tab switching
                document.querySelectorAll('.tab-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const tab = this.getAttribute('data-tab');
                        updateActiveTab(tab);
                    });
                });

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    const tab = getActiveTab();
                    updateActiveTab(tab);
                });
            });
        </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            .tab-pane {
                animation: fadeIn 0.3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .delete-btn {
                transition: all 0.2s ease;
            }

            .delete-btn:hover {
                transform: translateY(-1px);
            }

            /* Custom scrollbar for table */
            .overflow-x-auto::-webkit-scrollbar {
                height: 8px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            /* Style untuk button aksi */
            .action-btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
                min-width: 80px;
                text-align: center;
            }

            .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush
</x-app-layout>
