<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wedding Invitation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* === RESET & GLOBAL === */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            background-color: #fff;
        }

        a { text-decoration: none; color: inherit; }

        /* === LAYOUT UTAMA === */
        .main-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
            position: relative;
        }

        /* === HEADER NAVIGASI === */
        .global-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px; /* Tinggi fix header */
            display: flex;
            align-items: center; /* Vertikal center */
            padding: 0 5%; /* Padding kanan kiri */
            z-index: 100; /* Paling atas */
        }

        /* MENU TENGAH (KUNCI AGAR RAPI) */
        .nav-links {
            position: absolute;
            left: 50%;
            transform: translateX(-50%); /* Geser mundur 50% agar pas di tengah */
            
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .nav-links a {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #333; /* Warna dasar */
            text-transform: uppercase;
            position: relative;
            transition: color 0.3s;
            
            /* Shadow Putih: Agar teks tetap terbaca saat masuk ke area gambar */
            text-shadow: 0 0 15px rgba(255,255,255,0.8), 0 0 5px rgba(255,255,255,0.5);
        }

        .nav-links a:hover {
            color: #000;
        }

        /* Indikator Garis Bawah saat Hover */
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #111;
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after { width: 100%; }

        /* TOMBOL LOGIN (KANAN) */
        .auth-container {
            margin-left: auto; /* Dorong ke paling kanan */
        }

        .btn-login {
            background-color: #fff;
            color: #333;
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #f2f2f2;
            transform: translateY(-2px);
        }

        /* === BAGIAN KIRI (PUTIH SOLID) === */
        .left-section {
            width: 55%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 8%;
            padding-right: 2%; 
            position: relative;
            z-index: 10; 
        }

        /* === BAGIAN KANAN (GAMBAR) === */
        .right-section {
            width: 45%;
            position: relative;
            /* Ganti gambar background di sini jika perlu */
            background-image: url("{{ asset('img/bgpernikahan.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* === ELEMEN GELOMBANG === */
        .wave-container {
            position: absolute;
            top: 0;
            left: -1px; 
            height: 100%;
            width: 150px; 
            z-index: 20;
            pointer-events: none; 
        }

        .wave-svg {
            height: 100%;
            width: 100%;
            fill: #ffffff;
            transform: scaleX(1); 
        }

        /* === KONTEN TENGAH === */
        .content-box {
            max-width: 500px;
            /* Margin dihapus karena layout flex sudah center */
        }

        .pre-title {
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #666;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .main-title {
            font-family: 'Great Vibes', cursive;
            font-size: 6rem;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 30px;
            margin-left: -10px;
        }

        .btn-more {
            display: inline-block;
            background-color: #111;
            color: #fff;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: 0.3s;
            margin-bottom: 40px;
        }
        .btn-more:hover {
            background-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .description {
            font-size: 11px;
            line-height: 2;
            color: #888;
            letter-spacing: 1px;
            max-width: 350px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .socials {
            display: flex;
            gap: 25px;
            font-size: 18px; /* Sedikit diperbesar untuk icon */
            color: #333;
        }
        .socials a {
            transition: 0.3s;
        }
        .socials a:hover {
            color: #777;
            transform: translateY(-2px);
        }

        /* === RESPONSIVE (HP) === */
        @media (max-width: 900px) {
            .main-wrapper { flex-direction: column; }
            
            .global-header {
                position: relative; /* Tidak absolute di HP */
                height: auto;
                flex-direction: column;
                gap: 20px;
                padding: 20px;
                background: #fff;
                border-bottom: 1px solid #eee;
            }

            /* Reset posisi Navigasi di HP */
            .nav-links { 
                position: relative; 
                left: auto;
                transform: none;
                flex-wrap: wrap; 
                justify-content: center; 
                gap: 15px;
            }

            .auth-container { margin-left: 0; }

            .left-section { 
                width: 100%; 
                padding: 40px 20px 50px; 
                align-items: center; 
                text-align: center;
                order: 2; 
            }
            
            .right-section { 
                width: 100%; 
                height: 350px; 
                order: 1; 
            }

            .wave-container {
                width: 100%; height: 60px;
                top: auto; bottom: -1px; left: 0;
            }
            .wave-svg { transform: rotate(0deg); } 
            .main-title { font-size: 4rem; margin-left: 0; }
        }
    </style>
</head>
<body>

    <div class="main-wrapper">

        <header class="global-header">
            
            <nav class="nav-links">
                <a href="#">Our Story</a>
                <a href="#">Wedding Party</a>
                <a href="#">Gift Registry</a>
                <a href="#">Gallery</a>
            </nav>

            <div class="auth-container">
                @if (Route::has('login'))
                    @auth
                        <!-- Jika user sudah login, tombol menjadi Dashboard -->
                        <a href="{{ route('dashboard') }}" class="btn-login">Dashboard</a>
                    @else
                        <!-- Jika belum login, tombol menjadi Login -->
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                    @endauth
                @endif
            </div>
        </header>
        
        <section class="left-section">
            <div class="content-box">
                <p class="pre-title">You are invited to our</p>
                <h1 class="main-title">Wedding</h1>
                
                <a href="#" class="btn-more">More Info</a>

                <p class="description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Sed diam nonummy nibh euismod tincidunt ut laoreet dolore.
                </p>

                <div class="socials">
                    <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://pinterest.com" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>
        </section>

        <section class="right-section">
            <div class="wave-container">
                <!-- SVG Gelombang pembatas -->
                <svg class="wave-svg" viewBox="0 0 100 500" preserveAspectRatio="none">
                    <path d="M0,0 L0,500 L20,500 C80,350 90,150 20,0 Z" />
                </svg>
            </div>
        </section>

    </div>

</body>
</html>