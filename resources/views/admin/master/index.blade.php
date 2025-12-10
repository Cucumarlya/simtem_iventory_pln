<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-boxes mr-3 text-blue-500"></i>
                            Master Material
                        </h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Kelola data material dan stok dengan mudah
                        </p>
                    </div>
                    <a href="{{ route('admin.master.material.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Material
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-green-800">Sukses!</div>
                            <div class="text-green-700">{{ session('success') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-red-800">Error!</div>
                            <div class="text-red-700">{{ session('error') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Daftar Material
                        </h3>
                        <p class="text-gray-600 mt-1 text-sm">
                            Total {{ $materials->total() }} material terdaftar dalam sistem
                        </p>
                    </div>

                    <!-- SEARCH AND BUTTON CONTAINER -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Search Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchMaterial"
                                class="pl-10 pr-4 py-2.5 w-full sm:w-80 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                placeholder="Cari kode atau nama material...">
                        </div>

                        <!-- Action Buttons Container -->
                        <div class="flex gap-2">
                            <!-- TAMBAH MATERIAL BUTTON -->
                            <a href="{{ route('admin.master.material.create') }}"
                                class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Material
                            </a>

                            <!-- Export Button -->
                            <button onclick="exportToCSV()"
                                class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                                <i class="fas fa-file-export mr-2"></i>
                                Export
                            </button>

                            <!-- Print Button -->
                            <button onclick="printTable()"
                                class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                                <i class="fas fa-print mr-2"></i>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table id="materialTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Material
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Material
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                    Stok Awal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                    Stok Minimum
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($materials as $material)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                        {{ $loop->iteration + ($materials->currentPage() - 1) * $materials->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                        {{ $material->kode_material }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $material->nama_material }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $material->satuan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right font-bold">
                                        {{ number_format($material->stok_awal) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right font-bold">
                                        {{ number_format($material->min_stok) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex justify-center space-x-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.master.material.edit', $material->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200"
                                                title="Edit Material">
                                                Edit
                                            </a>

                                            <!-- Hapus Button -->
                                            <button type="button"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 delete-material-btn"
                                                data-material-name="{{ $material->nama_material }}"
                                                data-material-code="{{ $material->kode_material }}"
                                                data-delete-url="{{ route('admin.master.material.destroy', $material->id) }}"
                                                title="Hapus Material">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12">
                                        <div class="text-center">
                                            <div
                                                class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                                                Belum ada data material
                                            </h3>
                                            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                                                Mulai dengan menambahkan material pertama Anda.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($materials->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-center">
                            {{ $materials->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .flex-col.sm\:flex-row {
                flex-direction: column;
                align-items: stretch;
            }

            .relative {
                width: 100%;
                margin-bottom: 0.75rem;
            }

            .relative input {
                width: 100%;
            }

            .flex.gap-2 {
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .text-sm {
                font-size: 0.875rem;
            }

            /* Make buttons stack on mobile */
            .space-x-2>*+* {
                margin-left: 0.5rem;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            a[href]:after {
                content: none !important;
            }

            button {
                display: none !important;
            }

            .bg-gray-50 {
                background-color: #f9fafb !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchMaterial');
        if (searchInput) {
            let searchTimeout;

            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);

                searchInput.parentElement.classList.add('searching');

                searchTimeout = setTimeout(() => {
                    performSearch(e.target.value);
                    searchInput.parentElement.classList.remove('searching');
                }, 300);
            });
        }

        // Delete confirmations
        document.querySelectorAll('.delete-material-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                handleDeleteClick(button);
            });
        });

        async function performSearch(query) {
            try {
                const response = await fetch(
                    `/admin/master/materials/search?search=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                if (response.ok) {
                    const data = await response.text();
                    const tableBody = document.querySelector('#materialTable tbody');
                    if (tableBody) {
                        tableBody.innerHTML = data;
                        // Re-initialize delete buttons
                        document.querySelectorAll('.delete-material-btn').forEach(button => {
                            button.addEventListener('click', (e) => {
                                e.preventDefault();
                                handleDeleteClick(button);
                            });
                        });
                    }
                }
            } catch (error) {
                console.error('Search error:', error);
                showToast('Terjadi kesalahan saat mencari', 'error');
            }
        }

        function handleDeleteClick(button) {
            const materialName = button.dataset.materialName || 'material ini';
            const materialCode = button.dataset.materialCode || '';
            const deleteUrl = button.dataset.deleteUrl;

            if (confirm(
                    `Apakah Anda yakin ingin menghapus material:\n${materialCode} - ${materialName}\n\nTindakan ini tidak dapat dibatalkan.`
                    )) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                form.appendChild(methodInput);
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
            toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-white text-xl mr-3"></i>
                <div class="text-white">${message}</div>
            </div>
        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Export functionality
        window.exportToCSV = function() {
            const table = document.getElementById('materialTable');
            if (!table) return;

            const rows = table.querySelectorAll('tr');
            const csv = [];

            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('th, td');

                cells.forEach(cell => {
                    let text = cell.textContent.replace(/\s+/g, ' ').trim();
                    if (text.includes(',') || text.includes('"') || text.includes("'")) {
                        text = '"' + text.replace(/"/g, '""') + '"';
                    }
                    rowData.push(text);
                });

                if (rowData.length > 0) {
                    csv.push(rowData.join(','));
                }
            });

            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');

            link.href = URL.createObjectURL(blob);
            link.download = `material_${new Date().toISOString().slice(0, 10)}.csv`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showToast('Data berhasil di-export ke CSV', 'success');
        };

        // Print functionality
        window.printTable = function() {
            // Hide action buttons during print
            const actionButtons = document.querySelectorAll('button, .no-print');
            actionButtons.forEach(btn => btn.classList.add('no-print'));

            window.print();

            // Restore action buttons after print
            actionButtons.forEach(btn => btn.classList.remove('no-print'));
        };
    });
</script>
