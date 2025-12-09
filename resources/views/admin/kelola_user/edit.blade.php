{{-- resources/views/admin/kelola_user/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-user-edit mr-3 text-blue-500"></i>
                            Edit User: {{ $user->name }}
                        </h2>
                        <p class="text-gray-600 mt-1 text-sm">
                            Perbarui data pengguna yang sudah ada
                        </p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 w-full">
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

            <!-- Form Card Lebar Maksimal -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8 w-full">
                <!-- Form Header -->
                <div class="px-6 py-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">
                        Form Edit User
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">
                        Perbarui informasi pengguna sesuai kebutuhan. Field dengan tanda <span class="text-red-500">*</span> wajib diisi.
                    </p>
                </div>

                <!-- Form Content Lebar -->
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-6 py-8">
                        <div class="space-y-8">
                            <!-- Grid 3 Kolom Lebar untuk Nama, Email, Role -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Nama Lengkap -->
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           placeholder="Nama lengkap pengguna"
                                           required>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Masukkan nama lengkap user
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                           placeholder="email@contoh.com"
                                           required>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Email yang valid untuk login
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="form-group">
                                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Role <span class="text-red-500">*</span>
                                    </label>
                                    <select id="role_id"
                                            name="role_id"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none"
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="1" {{ old('role_id', $user->role_id) == '1' ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ old('role_id', $user->role_id) == '2' ? 'selected' : '' }}>Petugas</option>
                                        <option value="3" {{ old('role_id', $user->role_id) == '3' ? 'selected' : '' }}>Petugas Yanbung</option>
                                    </select>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Hak akses yang akan diberikan
                                    </div>
                                </div>
                            </div>

                            <!-- Grid 2 Kolom Lebar untuk Password (Optional) -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Password Baru (Optional) -->
                                <div class="form-group">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password"
                                               name="password"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 pr-10"
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        <button type="button" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm text-gray-600">Kekuatan Password:</span>
                                            <span id="passwordStrengthText" class="text-sm font-medium">-</span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div id="passwordStrengthBar" class="h-full bg-gray-400 rounded-full transition-all duration-300"></div>
                                        </div>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Biarkan kosong jika tidak ingin mengubah password
                                    </div>
                                </div>

                                <!-- Konfirmasi Password Baru -->
                                <div class="form-group">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 pr-10"
                                               placeholder="Ulangi password baru">
                                        <button type="button" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                                onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordMatch" class="mt-2 flex items-center text-sm hidden">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>Password cocok</span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        Diisi hanya jika ingin mengubah password
                                    </div>
                                </div>
                            </div>

                            <!-- Status ditempatkan di bawah kolom password -->
                            <div class="form-group mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Status Akun <span class="text-red-500">*</span>
                                </label>
                                <div class="flex space-x-6">
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               id="status_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $user->is_active) == '1' ? 'checked' : '' }}
                                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="status_active" class="ml-3 flex flex-col">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 mb-1">
                                                <i class="fas fa-check-circle mr-2 text-sm"></i>
                                                Aktif
                                            </span>
                                            <span class="text-xs text-gray-500">User dapat login ke sistem</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               id="status_inactive" 
                                               name="is_active" 
                                               value="0" 
                                               {{ old('is_active', $user->is_active) == '0' ? 'checked' : '' }}
                                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="status_inactive" class="ml-3 flex flex-col">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-800 mb-1">
                                                <i class="fas fa-times-circle mr-2 text-sm"></i>
                                                Nonaktif
                                            </span>
                                            <span class="text-xs text-gray-500">User tidak dapat login</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    Pilih status akun untuk user
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Preview Perubahan
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-blue-600 text-xs font-medium">Nama User:</div>
                                        <div id="previewName" class="text-gray-800 text-sm font-medium">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500" id="nameChangeIndicator"></div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-blue-600 text-xs font-medium">Email:</div>
                                        <div id="previewEmail" class="text-gray-800 text-sm">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-500" id="emailChangeIndicator"></div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-blue-600 text-xs font-medium">Role:</div>
                                        <div id="previewRole" class="text-gray-800 text-sm">
                                            @php
                                                $roleTexts = [
                                                    1 => 'Admin',
                                                    2 => 'Petugas',
                                                    3 => 'Petugas Yanbung',
                                                ];
                                                echo $roleTexts[$user->role_id] ?? 'Unknown';
                                            @endphp
                                        </div>
                                        <div class="text-xs text-gray-500" id="roleChangeIndicator"></div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="text-blue-600 text-xs font-medium mr-3">Status:</div>
                                            <div id="previewStatus" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </div>
                                        </div>
                                        <div id="statusChangeIndicator" class="text-xs text-gray-500"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Information Panel -->
                            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                                    Informasi User Saat Ini
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-gray-600 text-xs">ID User:</div>
                                        <div class="font-medium text-gray-800 text-sm">{{ $user->id }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-gray-600 text-xs">Dibuat:</div>
                                        <div class="font-medium text-gray-800 text-sm">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-gray-600 text-xs">Terakhir Update:</div>
                                        <div class="font-medium text-gray-800 text-sm">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-gray-600 text-xs">Terakhir Login:</div>
                                        <div class="font-medium text-gray-800 text-sm">
                                            @if($user->last_login_at)
                                                {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-gray-500">Belum login</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions Lebar -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-xs text-gray-600 flex items-center">
                                <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                <span>Pastikan semua perubahan telah benar</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="px-6 py-2 bg-gray-100 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200 text-sm">
                                    <i class="fas fa-times mr-1"></i>
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 text-sm">
                                    <i class="fas fa-save mr-1"></i>
                                    Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Form Lebar Maksimal */
        .w-full {
            width: 100% !important;
        }
        
        .px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        @media (min-width: 640px) {
            .sm\:px-6 {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            .lg\:px-8 {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        /* Change indicators */
        .change-indicator {
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.25rem;
            display: inline-block;
        }
        
        .change-added {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .change-removed {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .change-modified {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Password strength colors */
        .strength-weak {
            background-color: #ef4444;
        }
        
        .strength-medium {
            background-color: #f59e0b;
        }
        
        .strength-strong {
            background-color: #10b981;
        }
        
        /* Status radio button styling */
        input[type="radio"]:checked + label span:first-of-type {
            box-shadow: 0 0 0 2px #3b82f6;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editUserForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const roleSelect = document.getElementById('role_id');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const statusActive = document.getElementById('status_active');
        const statusInactive = document.getElementById('status_inactive');
        
        // Original values
        const originalName = '{{ $user->name }}';
        const originalEmail = '{{ $user->email }}';
        const originalRoleId = '{{ $user->role_id }}';
        const originalStatus = '{{ $user->is_active }}';
        
        // Role mapping
        const roleMap = {
            '1': 'Admin',
            '2': 'Petugas',
            '3': 'Petugas Yanbung'
        };
        
        // Update preview function
        function updatePreview() {
            const currentName = nameInput.value;
            const currentEmail = emailInput.value;
            const currentRoleId = roleSelect.value;
            const currentRoleText = roleSelect.options[roleSelect.selectedIndex].text;
            const currentStatus = statusActive.checked ? '1' : '0';
            
            // Update name preview
            document.getElementById('previewName').textContent = currentName;
            
            // Update email preview
            document.getElementById('previewEmail').textContent = currentEmail;
            
            // Update role preview
            document.getElementById('previewRole').textContent = currentRoleText !== 'Pilih Role' ? currentRoleText : '-';
            
            // Update status preview
            const statusPreview = document.getElementById('previewStatus');
            if (statusActive.checked) {
                statusPreview.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Aktif';
                statusPreview.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800';
            } else if (statusInactive.checked) {
                statusPreview.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Nonaktif';
                statusPreview.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800';
            }
            
            // Show change indicators
            updateChangeIndicators(currentName, currentEmail, currentRoleId, currentStatus);
        }
        
        // Update change indicators
        function updateChangeIndicators(currentName, currentEmail, currentRoleId, currentStatus) {
            // Name change
            const nameIndicator = document.getElementById('nameChangeIndicator');
            if (currentName !== originalName) {
                nameIndicator.innerHTML = '<span class="change-indicator change-modified">Diubah</span>';
            } else {
                nameIndicator.innerHTML = '';
            }
            
            // Email change
            const emailIndicator = document.getElementById('emailChangeIndicator');
            if (currentEmail !== originalEmail) {
                emailIndicator.innerHTML = '<span class="change-indicator change-modified">Diubah</span>';
            } else {
                emailIndicator.innerHTML = '';
            }
            
            // Role change
            const roleIndicator = document.getElementById('roleChangeIndicator');
            if (currentRoleId !== originalRoleId) {
                const oldRole = roleMap[originalRoleId] || 'Unknown';
                const newRole = roleMap[currentRoleId] || 'Unknown';
                roleIndicator.innerHTML = `<span class="change-indicator change-modified">${oldRole} → ${newRole}</span>`;
            } else {
                roleIndicator.innerHTML = '';
            }
            
            // Status change
            const statusIndicator = document.getElementById('statusChangeIndicator');
            if (currentStatus !== originalStatus) {
                const oldStatus = originalStatus === '1' ? 'Aktif' : 'Nonaktif';
                const newStatus = currentStatus === '1' ? 'Aktif' : 'Nonaktif';
                statusIndicator.innerHTML = `<span class="change-indicator change-modified">${oldStatus} → ${newStatus}</span>`;
            } else {
                statusIndicator.innerHTML = '';
            }
        }
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (password.length === 0) {
                strengthBar.className = 'h-full bg-gray-400 rounded-full transition-all duration-300';
                strengthText.textContent = '-';
                strengthText.className = 'text-sm font-medium text-gray-600';
            } else if (strength < 25) {
                strengthBar.className = 'h-full bg-red-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'Sangat Lemah';
                strengthText.className = 'text-sm font-medium text-red-600';
            } else if (strength < 50) {
                strengthBar.className = 'h-full bg-orange-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-sm font-medium text-orange-600';
            } else if (strength < 75) {
                strengthBar.className = 'h-full bg-yellow-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'Cukup';
                strengthText.className = 'text-sm font-medium text-yellow-600';
            } else {
                strengthBar.className = 'h-full bg-green-500 rounded-full transition-all duration-300';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-sm font-medium text-green-600';
            }
        }
        
        // Password match checker
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const matchIndicator = document.getElementById('passwordMatch');
            
            if (confirmPassword === '') {
                matchIndicator.classList.add('hidden');
                matchIndicator.classList.remove('text-green-600', 'text-red-600');
                return;
            }
            
            if (password === confirmPassword) {
                matchIndicator.classList.remove('hidden');
                matchIndicator.classList.remove('text-red-600');
                matchIndicator.classList.add('text-green-600');
                matchIndicator.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Password cocok';
            } else {
                matchIndicator.classList.remove('hidden');
                matchIndicator.classList.remove('text-green-600');
                matchIndicator.classList.add('text-red-600');
                matchIndicator.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Password tidak cocok';
            }
        }
        
        // Toggle password visibility
        window.togglePassword = function(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        };
        
        // Event listeners for preview updates
        nameInput.addEventListener('input', updatePreview);
        emailInput.addEventListener('input', updatePreview);
        roleSelect.addEventListener('change', updatePreview);
        statusActive.addEventListener('change', updatePreview);
        statusInactive.addEventListener('change', updatePreview);
        
        // Event listeners for password validation
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Check password if provided
            if (password || confirmPassword) {
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Password dan Konfirmasi Password tidak cocok!');
                    passwordInput.focus();
                    return;
                }
                
                if (password.length > 0 && password.length < 8) {
                    e.preventDefault();
                    alert('Password minimal 8 karakter!');
                    passwordInput.focus();
                    return;
                }
            }
            
            // Check if role is selected
            if (roleSelect.value === '') {
                e.preventDefault();
                alert('Silakan pilih role untuk user!');
                roleSelect.focus();
            }
            
            // Check if status is selected
            if (!statusActive.checked && !statusInactive.checked) {
                e.preventDefault();
                alert('Silakan pilih status akun!');
                return;
            }
        });
        
        // Initialize preview
        updatePreview();
        
        // Initialize password strength
        checkPasswordStrength('');
        checkPasswordMatch();
    });
    </script>
</x-app-layout>