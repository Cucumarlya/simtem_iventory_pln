<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Inventory Material SAR | PLN</title>
    <meta name="description" content="Platform terintegrasi untuk pengelolaan inventori material SAR PLN secara efektif, terstruktur, dan transparan">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/landing-page.css'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/pln-favicon.png') }}">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-header">
            <div class="hero-header-container">
                <div class="hero-brand">
                    <img src="{{ asset('build/assets/images/Logo_PLN.png') }}" alt="PLN Logo" class="brand-logo">
                    <div class="brand-text">
                        <span class="brand-subtitle">SINVOSAR</span>
                        <span class="brand-description">Sistem Informasi Inventori Material SAR</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-background">
            <div class="hero-bg-shape shape-1"></div>
            <div class="hero-bg-shape shape-2"></div>
            <div class="hero-bg-shape shape-3"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="title-white">Sistem Informasi</span><br>
                        <span class="title-yellow">Inventori Material SAR</span>
                    </h1>
                    <p class="hero-subtitle">
                        Platform digital terintegrasi untuk mengelola inventori material SAR PLN secara real-time, akurat, dan transparan guna mendukung operasional yang optimal.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-medium">
                            <i class="fas fa-sign-in-alt"></i>
                            Login ke Sistem
                        </a>
                        <a href="#tentang-sistem" class="btn btn-secondary btn-medium">
                            <i class="fas fa-info-circle"></i>
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <div class="dashboard-preview">
                        <div class="preview-header">
                            <div class="preview-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="preview-content">
                            <img src="{{ asset('build/assets/images/gambarpln.png') }}" alt="Sistem Pengelolaan Inventori Material" class="system-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Sistem -->
    <section id="tentang-sistem" class="section tentang-sistem">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tentang Sistem</h2>
                <div class="section-divider"></div>
                <p class="section-subtitle">Platform digital untuk transformasi pengelolaan inventori material SAR</p>
            </div>
            
            <div class="sistem-content">
                <div class="sistem-features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Inventori</h3>
                        <p class="feature-description">
                            Sistem terpusat untuk mengelola stok, pemasukan, dan pengeluaran material SAR secara real-time dan akurat
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="feature-title">Verifikasi Otomatis</h3>
                        <p class="feature-description">
                            Proses verifikasi yang sistematis dan otomatis untuk memastikan keakuratan data inventori material
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Analitik Real-time</h3>
                        <p class="feature-description">
                            Monitoring dan analisis data inventori secara real-time untuk pengambilan keputusan yang tepat
                        </p>
                    </div>
                </div>
                
                <div class="sistem-description">
                    <p>
                        <strong>SINVOSAR (Sistem Informasi Inventori Material SAR)</strong> merupakan platform digital yang dirancang khusus untuk mengoptimalkan pengelolaan inventori material SAR PLN. Sistem ini memungkinkan pencatatan yang komprehensif terhadap seluruh aspek inventori, mulai dari penerimaan, penyimpanan, hingga distribusi material.
                    </p>
                    <p>
                        Dengan sistem yang terintegrasi, SINVOSAR membantu mengurangi kesalahan manusia, meminimalkan kehilangan material, dan meningkatkan akurasi data. Sistem ini mendukung pengambilan keputusan yang lebih baik melalui laporan real-time dan analitik yang mendalam, sehingga operasional SAR PLN dapat berjalan lebih efisien dan efektif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tujuan Sistem -->
    <section id="tujuan" class="section tujuan-sistem">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tujuan Sistem</h2>
                <div class="section-divider"></div>
                <p class="section-subtitle">Mewujudkan pengelolaan inventori material yang optimal dan berkelanjutan</p>
            </div>
            
            <div class="tujuan-grid">
                <div class="tujuan-card">
                    <div class="tujuan-number">01</div>
                    <h3 class="tujuan-title">Optimasi Inventori</h3>
                    <p class="tujuan-description">
                        Mengoptimalkan tingkat persediaan material untuk menghindari kekurangan atau kelebihan stok
                    </p>
                </div>
                
                <div class="tujuan-card">
                    <div class="tujuan-number">02</div>
                    <h3 class="tujuan-title">Digitalisasi Lengkap</h3>
                    <p class="tujuan-description">
                        Mentransformasi seluruh proses inventori dari manual ke sistem digital terintegrasi
                    </p>
                </div>
                
                <div class="tujuan-card">
                    <div class="tujuan-number">03</div>
                    <h3 class="tujuan-title">Data Real-time</h3>
                    <p class="tujuan-description">
                        Menyediakan informasi inventori yang akurat dan real-time untuk semua stakeholder terkait
                    </p>
                </div>
                
                <div class="tujuan-card">
                    <div class="tujuan-number">04</div>
                    <h3 class="tujuan-title">Akuntabilitas Total</h3>
                    <p class="tujuan-description">
                        Meningkatkan akuntabilitas dan traceability dalam pengelolaan inventori material SAR
                    </p>
                </div>
                
                <div class="tujuan-card">
                    <div class="tujuan-number">05</div>
                    <h3 class="tujuan-title">Efisiensi Biaya</h3>
                    <p class="tujuan-description">
                        Mengurangi biaya operasional melalui pengelolaan inventori yang lebih efisien dan terstruktur
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Siap Mengoptimalkan Inventori Material?</h2>
                <p class="cta-description">
                    Akses sistem sekarang dan rasakan kemudahan pengelolaan inventori material SAR yang terdigitalisasi dan terintegrasi
                </p>
                <div class="cta-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-large">
                        <i class="fas fa-sign-in-alt"></i>
                        Login Sekarang
                    </a>
                    <a href="#tentang-sistem" class="btn btn-outline btn-large">
                        <i class="fas fa-feather-alt"></i>
                        Pelajari Fitur
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <img src="{{ asset('build/assets/images/Logo_PLN.png') }}" alt="PLN Logo" class="footer-logo">
                        <div class="footer-brand-text">
                            <h4>SINVOSAR</h4>
                            <p>Sistem Informasi Inventori Material SAR</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Platform digital terintegrasi untuk pengelolaan inventori material SAR yang efisien, real-time, dan akuntabel.
                    </p>
                </div>
                
                <div class="footer-section">
                    <h5 class="footer-title">Kontak</h5>
                    <div class="contact-info">
                        <p>PT. PLN (Persero) ULP Lumajang</p>
                        <p>Jl. Merdeka No.123, Lumajang</p>
                        <p><i class="fas fa-phone"></i> (0334) 881234</p>
                        <p><i class="fas fa-envelope"></i> inventori.sar@pln-lumajang.co.id</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <span id="current-year">{{ date('Y') }}</span> PT. PLN (Persero) ULP Lumajang. Semua Hak Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Vite JS -->
    @vite(['resources/js/landing-page.js'])
</body>
</html>