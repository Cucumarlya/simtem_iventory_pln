{{-- resources/views/admin/kelola_user/partials/user-table.blade.php --}}
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
                    Tidak ada user ditemukan
                </h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Coba kata kunci pencarian yang berbeda.
                </p>
            </div>
        </td>
    </tr>
@endforelse