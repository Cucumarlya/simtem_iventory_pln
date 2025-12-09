@forelse($materials as $material)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
            {{ $loop->iteration + (($materials->currentPage() - 1) * $materials->perPage()) }}
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
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    Tidak ada hasil pencarian
                </h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Coba gunakan kata kunci lain.
                </p>
            </div>
        </td>
    </tr>
@endforelse