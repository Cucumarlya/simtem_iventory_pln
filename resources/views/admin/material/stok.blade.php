<x-app-layout>
    <x-slot name="title">SIPMATSAR - Stok Material</x-slot>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/stok-material.css') }}">
    @endpush

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>

    <div class="sipmatsar-stok-material">
        <!-- Tab Navigation -->
        <div class="tab-navigation-wrapper">
            <div class="tab-navigation">
                <button class="tab-button active" data-tab="rekap-stok">
                    <div class="tab-icon">
                        <ion-icon name="bar-chart-outline"></ion-icon>
                    </div>
                    <div class="tab-text">
                        <div class="tab-title">Rekap Stok</div>
                        <div class="tab-subtitle">Data lengkap stok material</div>
                    </div>
                </button>
                
                <button class="tab-button" data-tab="belum-ago">
                    <div class="tab-icon">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                    <div class="tab-text">
                        <div class="tab-title">Belum AGO</div>
                        <div class="tab-subtitle">Monitoring approval</div>
                    </div>
                </button>
                
                <button class="tab-button" data-tab="normalisasi">
                    <div class="tab-icon">
                        <ion-icon name="barcode-outline"></ion-icon>
                    </div>
                    <div class="tab-text">
                        <div class="tab-title">Normalisasi</div>
                        <div class="tab-subtitle">Kode standar material</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="tab-content">
                <!-- TAB 1: REKAP STOK -->
                <div id="rekap-stok" class="tab-pane active">
                    <div class="content-card">
                        <!-- Action Bar -->
                        <div class="action-bar">
                            <div class="action-left">
                                <h3 class="section-title">
                                    <ion-icon name="list-outline"></ion-icon>
                                    Data Rekap Stok Material
                                </h3>
                                <p class="section-subtitle">Management stok material PLN</p>
                            </div>
                            <div class="action-right">
                                <div class="action-group">
                                    <div class="search-box">
                                        <ion-icon name="search-outline" class="search-icon"></ion-icon>
                                        <input type="text" placeholder="Cari material..." class="search-input" id="searchRekap">
                                        <button class="search-clear" id="clearSearchRekap">
                                            <ion-icon name="close-outline"></ion-icon>
                                        </button>
                                    </div>
                                    <div class="export-buttons">
                                        <button class="btn btn-pdf" id="exportPdfRekap">
                                            <ion-icon name="document-outline"></ion-icon>
                                            PDF
                                        </button>
                                        <button class="btn btn-excel" id="exportExcelRekap">
                                            <ion-icon name="download-outline"></ion-icon>
                                            Excel
                                        </button>
                                    </div>
                                    <button class="btn btn-primary" id="tambahStokBtn">
                                        <ion-icon name="add-outline"></ion-icon>
                                        Tambah Stok
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table Container -->
                        <div class="table-wrapper">
                            <div class="table-container">
                                <table class="modern-table" id="rekapTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" rowspan="2">NO</th>
                                            <th rowspan="2">MATERIAL</th>
                                            <th class="text-center" rowspan="2">STOK AWAL</th>
                                            <th class="text-center" rowspan="2">PENERIMAAN</th>
                                            <th class="text-center" colspan="3">PENGELUARAN</th>
                                            <th class="text-center" colspan="2">PLN</th>
                                            <th class="text-center" rowspan="2">STOK AKHIR</th>
                                            <th class="text-center" rowspan="2">MMS</th>
                                            <th class="text-center" rowspan="2">SELISIH</th>
                                            <th class="text-center" rowspan="2">KETERANGAN</th>
                                            <th class="text-center" colspan="4">STOK LEBIH DI LUAR AGO</th>
                                            <th class="text-center" rowspan="2">AKSI</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">YANBUNG</th>
                                            <th class="text-center">P2TL</th>
                                            <th class="text-center">GANGGUAN</th>
                                            <th class="text-center">IN TRANSIT (PENERIMAAN)</th>
                                            <th class="text-center">IN TRANSIT (PENGELUARAN)</th>
                                            <th class="text-center">KOEF SELISIH</th>
                                            <th class="text-center">SISA REAL</th>
                                            <th class="text-center">TANGGAL</th>
                                            <th class="text-center">WAKTU</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stock-table-body">
                                        <tr>
                                            <td colspan="19" class="text-center loading-state">
                                                <div class="loading-content">
                                                    <i class="fas fa-spinner fa-spin loading-icon"></i>
                                                    <span class="loading-text">Memuat data stok material...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Table Footer -->
                        <div class="table-footer">
                            <div class="footer-info">
                                <div class="pagination-info">
                                    Menampilkan <strong id="display-count">0</strong> dari <strong id="total-count">0</strong> data
                                </div>
                            </div>
                            <div class="footer-actions">
                                <div class="last-update">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Terakhir update: <span id="lastUpdateTime">{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: BELUM AGO -->
                <div id="belum-ago" class="tab-pane">
                    <div class="content-card">
                        <div class="action-bar">
                            <div class="action-left">
                                <h3 class="section-title">
                                    <ion-icon name="time-outline"></ion-icon>
                                    Monitoring Belum AGO
                                </h3>
                                <p class="section-subtitle">Data material yang sedang dalam proses approval</p>
                            </div>
                            <div class="action-right">
                                <div class="action-group">
                                    <div class="search-box">
                                        <ion-icon name="search-outline" class="search-icon"></ion-icon>
                                        <input type="text" placeholder="Cari material..." class="search-input" id="searchAgo">
                                    </div>
                                    <div class="export-buttons">
                                        <button class="btn btn-pdf" id="exportPdfAgo">
                                            <ion-icon name="document-outline"></ion-icon>
                                            PDF
                                        </button>
                                        <button class="btn btn-excel" id="exportExcelAgo">
                                            <ion-icon name="download-outline"></ion-icon>
                                            Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <div class="table-container">
                                <table class="modern-table" id="agoTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NO</th>
                                            <th>MATERIAL</th>
                                            <th class="text-center">SEDANG DIPAKAI</th>
                                            <th class="text-center">SELISIH MMS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ago-table-body">
                                        <tr>
                                            <td colspan="4" class="text-center loading-state">
                                                <div class="loading-content">
                                                    <i class="fas fa-spinner fa-spin loading-icon"></i>
                                                    <span class="loading-text">Memuat data approval...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: NORMALISASI -->
                <div id="normalisasi" class="tab-pane">
                    <div class="content-card">
                        <div class="action-bar">
                            <div class="action-left">
                                <h3 class="section-title">
                                    <ion-icon name="barcode-outline"></ion-icon>
                                    Kode Normalisasi Material
                                </h3>
                                <p class="section-subtitle">Management kode standar material PLN</p>
                            </div>
                            <div class="action-right">
                                <div class="action-group">
                                    <div class="search-box">
                                        <ion-icon name="search-outline" class="search-icon"></ion-icon>
                                        <input type="text" placeholder="Cari material atau kode..." class="search-input" id="searchNormalisasi">
                                    </div>
                                    <div class="export-buttons">
                                        <button class="btn btn-pdf" id="exportPdfNormalisasi">
                                            <ion-icon name="document-outline"></ion-icon>
                                            PDF
                                        </button>
                                        <button class="btn btn-excel" id="exportExcelNormalisasi">
                                            <ion-icon name="download-outline"></ion-icon>
                                            Excel
                                        </button>
                                    </div>
                                    <button class="btn btn-primary" id="tambahNormalisasiBtn">
                                        <ion-icon name="add-outline"></ion-icon>
                                        Tambah Kode
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <div class="table-container">
                                <table class="modern-table" id="normalisasiTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NO</th>
                                            <th>MATERIAL</th>
                                            <th class="text-center">KODE NORMALISASI</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="normalisasi-table-body">
                                        <tr>
                                            <td colspan="4" class="text-center loading-state">
                                                <div class="loading-content">
                                                    <i class="fas fa-spinner fa-spin loading-icon"></i>
                                                    <span class="loading-text">Memuat data normalisasi...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmation-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="confirmation-title">Konfirmasi</h3>
                <button class="modal-close" id="close-confirmation-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirmation-content">
                    <div class="confirmation-icon" id="confirmation-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="confirmation-text">
                        <h4 id="confirmation-message">Apakah Anda yakin ingin melanjutkan?</h4>
                        <p id="confirmation-details">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" id="btn-cancel-confirmation">Batal</button>
                <button class="modal-btn modal-btn-confirm" id="btn-confirm-action">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('js/stok-material.js') }}"></script>
    @endpush
</x-app-layout>