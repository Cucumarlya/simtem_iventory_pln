<x-app-layout>
    <x-slot name="header">
        <div class="header-content">
            <div class="header-title">
                <i class="fas fa-boxes"></i>
                <div class="header-text">
                    <h1>Master Material</h1>
                    <p>Sistem Informasi Pengelolaan Material Terpadu - PLN</p>
                </div>
            </div>
            <!-- HAPUS TOMBOL TAMBAH MATERIAL KARENA SUDAH ADA DI BAWAH -->
        </div>
    </x-slot>

    <div class="master-material-container">
        <div class="container-max">

            <!-- Button Penerimaan dan Pengeluaran -->
            <div class="action-buttons-section">
                <div class="action-buttons-container">
                    <a href="{{ route('transaksi.penerimaan.create') }}" class="btn-action-green">
                        <i class="fas fa-sign-in-alt"></i>
                        Tambah Penerimaan
                    </a>
                    <a href="{{ route('transaksi.pengeluaran.create') }}" class="btn-action-red">
                        <i class="fas fa-sign-out-alt"></i>
                        Tambah Pengeluaran
                    </a>
                </div>
            </div>

            <!-- Filter Section - Horizontal tanpa card -->
            <div class="filter-section-horizontal">
                <div class="search-filters-horizontal">
                    <div class="search-box-horizontal">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Cari transaksi berdasarkan nama, keperluan, ID..." id="search-input">
                    </div>
                    <div class="date-filter-horizontal">
                        <i class="fas fa-calendar date-icon"></i>
                        <input type="date" class="date-input" id="date-filter">
                    </div>
                    <select class="type-filter-horizontal" id="type-filter">
                        <option value="">Semua Jenis</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                        <option value="Penerimaan">Penerimaan</option>
                    </select>
                    
                    <!-- Export Buttons saja, tanpa Tambah Data -->
                    <div class="action-buttons-horizontal">
                        <!-- Export Buttons -->
                        <div class="export-buttons">
                            <button class="btn-export btn-export-pdf" id="btn-export-pdf">
                                <i class="fas fa-file-pdf"></i>
                                <span class="export-text">PDF</span>
                            </button>
                            <button class="btn-export btn-export-excel" id="btn-export-excel">
                                <i class="fas fa-file-excel"></i>
                                <span class="export-text">Excel</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-section">
                <div class="table-container">
                    <table class="modern-table" id="material-table">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th>TANGGAL</th>
                                <th>JENIS</th>
                                <th>NAMA PENGAMBIL/PENERIMA</th>
                                <th>KEPERLUAN</th>
                                <th>ID PELANGGAN</th>
                                <th>MCB 2A</th>
                                <th>MCB 4A</th>
                                <th>MCB 6A</th>
                                <th>MCB 10A</th>
                                <th>MCB 16A</th>
                                <th>MCB 20A</th>
                                <th>MCB 25A</th>
                                <th>MCB 35A</th>
                                <th>SEGEL</th>
                                <th>LPB</th>
                                <th>PASKA</th>
                                <th>SR</th>
                                <th>FOTO SR SEBELUM</th>
                                <th>FOTO SR SESUDAH</th>
                                <th>SWC</th>
                                <th>LINTAP 10-16</th>
                                <th>LINTAP 16-35</th>
                                <th>LINTAP 50-70</th>
                                <th>KONDOM</th>
                                <th>SOLASI</th>
                                <th>FOTO BUKTI PENGAMBILAN</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="material-table-body">
                            @foreach($materials as $material)
                            <tr data-id="{{ $material->id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td><span class="tanggal-text">{{ \Carbon\Carbon::parse($material->tanggal)->format('d/m/Y') }}</span></td>
                                <td><span class="type-text">{{ $material->jenis }}</span></td>
                                <td><span class="nama-text" title="{{ $material->nama }}">{{ $material->nama }}</span></td>
                                <td><span class="keperluan-text" title="{{ ucfirst($material->keperluan) }}">{{ ucfirst($material->keperluan) }}</span></td>
                                <td>{{ $material->id_pelanggan ?? '-' }}</td>
                                <td class="quantity-cell {{ $material->mcb_2a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_2a }}</td>
                                <td class="quantity-cell {{ $material->mcb_4a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_4a }}</td>
                                <td class="quantity-cell {{ $material->mcb_6a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_6a }}</td>
                                <td class="quantity-cell {{ $material->mcb_10a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_10a }}</td>
                                <td class="quantity-cell {{ $material->mcb_16a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_16a }}</td>
                                <td class="quantity-cell {{ $material->mcb_20a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_20a }}</td>
                                <td class="quantity-cell {{ $material->mcb_25a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_25a }}</td>
                                <td class="quantity-cell {{ $material->mcb_35a === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->mcb_35a }}</td>
                                <td class="quantity-cell {{ $material->segel === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->segel }}</td>
                                <td class="quantity-cell {{ $material->lpb === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->lpb }}</td>
                                <td class="quantity-cell {{ $material->paska === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->paska }}</td>
                                <td class="quantity-cell {{ $material->sr === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->sr }}</td>
                                <td class="text-center">
                                    @if($material->foto_sr_sebelum)
                                        <a href="{{ Storage::url($material->foto_sr_sebelum) }}" target="_blank" class="photo-preview" title="Lihat Foto SR Sebelum">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="photo-preview disabled" title="Tidak ada foto">
                                            <i class="fas fa-camera"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($material->foto_sr_sesudah)
                                        <a href="{{ Storage::url($material->foto_sr_sesudah) }}" target="_blank" class="photo-preview" title="Lihat Foto SR Sesudah">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="photo-preview disabled" title="Tidak ada foto">
                                            <i class="fas fa-camera"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="quantity-cell {{ $material->swc === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->swc }}</td>
                                <td class="quantity-cell {{ $material->lintap_10_16 === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->lintap_10_16 }}</td>
                                <td class="quantity-cell {{ $material->lintap_16_35 === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->lintap_16_35 }}</td>
                                <td class="quantity-cell {{ $material->lintap_50_70 === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->lintap_50_70 }}</td>
                                <td class="quantity-cell {{ $material->kondom === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->kondom }}</td>
                                <td class="quantity-cell {{ $material->solasi === 0 ? 'quantity-zero' : 'quantity-positive' }}">{{ $material->solasi }}</td>
                                <td class="text-center">
                                    @if($material->foto_bukti)
                                        <a href="{{ Storage::url($material->foto_bukti) }}" target="_blank" class="photo-preview" title="Lihat Foto Bukti Pengambilan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="photo-preview disabled" title="Tidak ada foto">
                                            <i class="fas fa-camera"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <!-- PERBAIKAN: Tombol Edit mengarah ke halaman edit terpisah -->
                                        <a href="{{ route('material.edit', $material->id) }}" class="btn-action btn-edit" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <button class="btn-action btn-delete" data-id="{{ $material->id }}" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($materials->isEmpty())
                            <tr>
                                <td colspan="28" class="text-center">
                                    <div class="loading-content">
                                        <i class="fas fa-inbox" style="color: var(--pln-gray-400);"></i>
                                        <span class="loading-text">Belum ada data material</span>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- HAPUS MODAL EDIT MATERIAL KARENA SEKARANG HALAMAN TERPISAH -->

    <!-- Photo Modal -->
    <div class="modal-overlay" id="photo-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Preview Foto</h3>
                <button class="modal-close" id="close-photo-modal">&times;</button>
            </div>
            <div class="modal-body">
                <img src="" alt="Preview" class="modal-image" id="modal-image">
            </div>
            <div class="modal-footer">
                <p class="modal-filename" id="modal-filename"></p>
            </div>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-confirm-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button class="modal-close" id="close-delete-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-confirm-content">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="delete-text">
                        <h4>Apakah Anda yakin ingin menghapus data ini?</h4>
                        <p>Data yang sudah dihapus tidak dapat dikembalikan. Pastikan data yang akan dihapus benar.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" id="btn-cancel-delete">Batal</button>
                <button class="modal-btn modal-btn-danger" id="btn-confirm-delete">Ya, Hapus Data</button>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/master-material.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="{{ asset('js/master-material.js') }}"></script>
    @endpush
</x-app-layout>