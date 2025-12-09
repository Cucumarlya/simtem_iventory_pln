<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPMATSAR - Petugas</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #0d3c6f;
            --primary-medium: #1e6fd9;
            --primary-light: #4a90e2;
            --accent: #ff6b35;
            --white: #ffffff;
            --background: #f8fafc;
            --text-light: #6b7280;
            --shadow: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== NAVIGATION STYLES ===== */
        .main-nav {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            color: white;
            padding: 15px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: block;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
        }

        /* ===== MAIN CONTENT ===== */
        main {
            flex: 1;
            padding: 0;
        }

        /* ===== FOOTER STYLES ===== */
        .main-footer {
            background: var(--primary-dark);
            color: white;
            padding: 50px 0 20px;
            margin-top: auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--accent);
        }

        .footer-column p {
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .footer-links i {
            margin-right: 10px;
            width: 20px;
            color: var(--accent);
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .footer-social a {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: slideUp 0.8s ease-out;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 15px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .container {
                padding: 0 15px;
            }
        }
    </style>
    
    {{ $styles ?? '' }}
</head>
<body>
    <!-- Navigasi -->
    <nav class="main-nav">
        <div class="container">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-icon">PLN</div>
                    <div class="logo-text">SIPMATSAR</div>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('petugas.penerimaan') }}" class="{{ request()->routeIs('petugas.penerimaan') ? 'active' : '' }}">Penerimaan</a></li>
                    <li><a href="{{ route('petugas.riwayat') }}" class="{{ request()->routeIs('petugas.riwayat') ? 'active' : '' }}">Riwayat</a></li>
                </ul>
                <div class="user-info">
                    <div class="user-avatar">AS</div>
                    <span>Ahmad Surya</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>SIPMATSAR</h3>
                    <p>Sistem Permintaan dan Persetujuan Material PT. PLN (Persero) ULP Lumajang</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Layanan</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('petugas.penerimaan') }}">Penerimaan Material</a></li>
                        <li><a href="{{ route('petugas.riwayat') }}">Riwayat Transaksi</a></li>
                        <li><a href="#">Laporan Material</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Tautan Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('petugas.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('petugas.penerimaan') }}">Penerimaan</a></li>
                        <li><a href="{{ route('petugas.riwayat') }}">Riwayat</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Kontak</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Merdeka No. 123, Lumajang</li>
                        <li><i class="fas fa-phone"></i> (0334) 881234</li>
                        <li><i class="fas fa-envelope"></i> sipmatsar@pln-lumajang.co.id</li>
                        <li><i class="fas fa-clock"></i> Senin - Jumat: 8:00 - 16:00</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 PT PLN Persero ULP Lumajang. Semua Hak Dilindungi.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Simple navigation active state
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    {{ $scripts ?? '' }}
</body>
</html>