<x-app-layout>
    <x-slot name="title">SIPMATSAR - Verifikasi Transaksi Material</x-slot>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/transaksi-material.css') }}">
    @endpush

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="detail-modal" style="display: none;">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h3 class="modal-title">Detail Transaksi</h3>
                <button class="modal-close" id="close-detail-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modal-content">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" id="btn-close-detail">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Pengembalian -->
    <div class="modal-overlay" id="pengembalian-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Alasan Pengembalian</h3>
                <button class="modal-close" id="close-pengembalian-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="pengembalian-form">
                    @csrf
                    <input type="hidden" id="pengembalian-transaksi-id">
                    <div class="form-group">
                        <label class="form-label">Alasan Pengembalian *</label>
                        <textarea class="form-control" id="alasan-pengembalian" name="alasan_pengembalian" 
                                  rows="4" placeholder="Masukkan alasan pengembalian..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" id="btn-cancel-pengembalian">Batal</button>
                <button class="modal-btn modal-btn-danger" id="btn-submit-pengembalian">Submit</button>
            </div>
        </div>
    </div>

    <div class="sipmatsar-transaksi-material">
        <!-- Tab Navigation -->
        <div class="tab-navigation-wrapper">
            <div class="tab-navigation">
                <button class="tab-button active" data-tab="penerimaan">
                    <div class="tab-icon">
                        <ion-icon name="arrow-down-outline"></ion-icon>
                    </div>
                    <div class="tab-text">
                        <div class="tab-title">Penerimaan</div>
                        <div class="tab-subtitle">Verifikasi transaksi masuk material</div>
                    </div>
                </button>
                
                <button class="tab-button" data-tab="pengeluaran">
                    <div class="tab-icon">
                        <ion-icon name="arrow-up-outline"></ion-icon>
                    </div>
                    <div class="tab-text">
                        <div class="tab-title">Pengeluaran</div>
                        <div class="tab-subtitle">Verifikasi transaksi keluar material</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="tab-content">
                <!-- TAB 1: PENERIMAAN -->
                <div id="penerimaan" class="tab-pane active">
                    <div class="content-card">
                        <!-- Action Bar -->
                        <div class="action-bar">
                            <div class="action-left">
                                <h3 class="section-title">
                                    <ion-icon name="list-outline"></ion-icon>
                                    Daftar Verifikasi Penerimaan
                                </h3>
                                <p class="section-subtitle">Verifikasi transaksi material masuk yang menunggu persetujuan</p>
                            </div>
                            <div class="action-right">
                                <div class="action-group">
                                    <div class="search-box">
                                        <ion-icon name="search-outline" class="search-icon"></ion-icon>
                                        <input type="text" placeholder="Cari transaksi..." class="search-input" id="searchPenerimaan">
                                        <button class="search-clear" id="clearSearchPenerimaan">
                                            <ion-icon name="close-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table Container -->
                        <div class="table-wrapper">
                            <div class="table-container">
                                <table class="modern-table" id="penerimaanTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NO</th>
                                            <th>TANGGAL</th>
                                            <th>NAMA PENERIMA</th>
                                            <th>KEPERLUAN</th>
                                            <th>DIBUAT OLEH</th>
                                            <th class="text-center">TOTAL ITEM</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="penerimaan-table-body">
                                        <!-- Data akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Table Footer -->
                        <div class="table-footer">
                            <div class="footer-info">
                                <div class="pagination-info">
                                    Menampilkan <strong id="penerimaan-count">0</strong> data
                                </div>
                            </div>
                            <div class="footer-actions">
                                <div class="last-update">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Terakhir update: <span id="last-update">{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PENGELUARAN -->
                <div id="pengeluaran" class="tab-pane">
                    <div class="content-card">
                        <!-- Action Bar -->
                        <div class="action-bar">
                            <div class="action-left">
                                <h3 class="section-title">
                                    <ion-icon name="list-outline"></ion-icon>
                                    Daftar Verifikasi Pengeluaran
                                </h3>
                                <p class="section-subtitle">Verifikasi transaksi material keluar yang menunggu persetujuan</p>
                            </div>
                            <div class="action-right">
                                <div class="action-group">
                                    <div class="search-box">
                                        <ion-icon name="search-outline" class="search-icon"></ion-icon>
                                        <input type="text" placeholder="Cari transaksi..." class="search-input" id="searchPengeluaran">
                                        <button class="search-clear" id="clearSearchPengeluaran">
                                            <ion-icon name="close-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table Container -->
                        <div class="table-wrapper">
                            <div class="table-container">
                                <table class="modern-table" id="pengeluaranTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NO</th>
                                            <th>TANGGAL</th>
                                            <th>NAMA PENGAMBIL</th>
                                            <th>KEPERLUAN</th>
                                            <th>ID PELANGGAN</th>
                                            <th>DIBUAT OLEH</th>
                                            <th class="text-center">TOTAL ITEM</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pengeluaran-table-body">
                                        <!-- Data akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Table Footer -->
                        <div class="table-footer">
                            <div class="footer-info">
                                <div class="pagination-info">
                                    Menampilkan <strong id="pengeluaran-count">0</strong> data
                                </div>
                            </div>
                            <div class="footer-actions">
                                <div class="last-update">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Terakhir update: <span id="last-update-pengeluaran">{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('js/verifikasi-transaksi.js') }}"></script>
    @endpush
</x-app-layout>