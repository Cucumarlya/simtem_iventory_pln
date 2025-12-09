{{-- resources/views/admin/kelola_user/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-users mr-3 text-blue-500"></i>
                            Kelola User
                        </h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Kelola data pengguna dan hak akses
                        </p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-user-plus mr-2"></i>
                        Tambah User
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
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

            @if(session('error'))
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
                            Daftar User
                        </h3>
                        <p class="text-gray-600 mt-1 text-sm">
                            Total {{ $users->total() }} user terdaftar dalam sistem
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Search Box -->
                        <div class="search-container relative">
                            <input type="text" 
                                   id="searchUser" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 search-input"
                                   placeholder="Cari nama atau email...">
                        </div>
                        
                        <!-- Action Buttons Container -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-user-plus mr-2"></i>
                                Tambah User
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table id="userTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="userTableContainer" class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-600">
                                        {{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $user->name }}
                                        @if(auth()->id() === $user->id)
                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                1 => 'bg-blue-100 text-blue-800', // admin
                                                2 => 'bg-purple-100 text-purple-800', // petugas
                                                3 => 'bg-orange-100 text-orange-800', // petugas_yanbung
                                            ];
                                            $roleTexts = [
                                                1 => 'Admin',
                                                2 => 'Petugas',
                                                3 => 'Petugas Yanbung',
                                            ];
                                            $roleClass = $roleColors[$user->role_id] ?? 'bg-gray-100 text-gray-800';
                                            $roleText = $roleTexts[$user->role_id] ?? 'Unknown';
                                        @endphp
                                        <span class="px-3 py-1 {{ $roleClass }} rounded-lg text-sm font-medium">
                                            {{ $roleText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-yellow-500 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150"
                                               title="Edit User">
                                                Edit
                                            </a>
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 delete-user-btn"
                                                    data-user-name="{{ $user->name }}"
                                                    data-user-email="{{ $user->email }}"
                                                    data-delete-url="{{ route('admin.users.destroy', $user->id) }}"
                                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}
                                                    title="{{ auth()->id() === $user->id ? 'Tidak dapat menghapus akun sendiri' : 'Hapus User' }}">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center py-12">
                                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                                                Belum ada data user
                                            </h3>
                                            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                                                Mulai dengan menambahkan user pertama Anda.
                                            </p>
                                            <a href="{{ route('admin.users.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                <i class="fas fa-user-plus mr-2"></i>
                                                Tambah User Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan 
                                <span class="font-semibold">{{ $users->firstItem() }}</span> 
                                sampai 
                                <span class="font-semibold">{{ $users->lastItem() }}</span> 
                                dari 
                                <span class="font-semibold">{{ $users->total() }}</span> 
                                data
                            </div>
                            <div class="flex items-center space-x-1">
                                {{ $users->links('vendor.pagination.tailwind') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchUser');
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
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (!button.disabled) {
                    handleDeleteClick(button);
                }
            });
        });
        
        async function performSearch(query) {
            try {
                const response = await fetch(`{{ route('admin.users.search') }}?search=${encodeURIComponent(query)}&ajax=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.text();
                    const tableBody = document.querySelector('#userTable tbody');
                    if (tableBody) {
                        tableBody.innerHTML = data;
                        // Re-initialize delete buttons
                        document.querySelectorAll('.delete-user-btn').forEach(button => {
                            button.addEventListener('click', (e) => {
                                e.preventDefault();
                                if (!button.disabled) {
                                    handleDeleteClick(button);
                                }
                            });
                        });
                    }
                }
            } catch (error) {
                console.error('Search error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mencari',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        function handleDeleteClick(button) {
            const userName = button.dataset.userName || 'user ini';
            const userEmail = button.dataset.userEmail || '';
            const deleteUrl = button.dataset.deleteUrl;

            Swal.fire({
                title: 'Hapus User?',
                html: `
                    <div class="text-left">
                        <p class="mb-3">Apakah Anda yakin ingin menghapus user ini?</p>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user text-gray-500 mr-2 w-5"></i>
                                <span class="font-semibold">${userName}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-500 mr-2 w-5"></i>
                                <span>${userEmail}</span>
                            </div>
                        </div>
                        <p class="text-sm text-red-600 mt-3">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            User yang dihapus tidak dapat dikembalikan.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: 'rgba(0,0,0,0.4)',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteUser(button, deleteUrl);
                }
            });
        }

        function deleteUser(button, deleteUrl) {
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
    });
    </script>
    @endpush

    @push('styles')
    <style>
        .search-container.searching::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            border: 2px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: translateY(-50%) rotate(360deg); }
        }
        
        .search-input {
            padding-right: 2.5rem !important;
        }
    </style>
    @endpush
</x-app-layout>