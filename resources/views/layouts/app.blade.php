<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ThriftHunt') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar-brand {
            font-weight: 800; /* Lebih tebal */
            color: #212529 !important;
            transition: all 0.3s ease;
        }
        .navbar-brand:hover {
            color: #0d6efd !important;
        }
        .navbar .nav-link {
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500; /* Sedikit lebih tebal dari default */
        }
        .navbar .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px; /* Sedikit lebih tebal */
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #0d6efd;
            border-radius: 2px;
            transition: width 0.3s ease-out;
        }
        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after {
            width: calc(100% - 10px); /* Lebar underline, kurangi padding */
        }
        .navbar .nav-link.active {
            font-weight: 700; /* Font lebih tebal untuk link aktif */
        }
        .navbar-nav .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s ease;
        }
        .navbar-nav .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 102, 255, 0.2);
        }
        .dropdown-menu {
            border-radius: .75rem;
            box-shadow: 0 .75rem 2rem rgba(0,0,0,.1);
            border: none;
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item {
            transition: all 0.2s ease;
            padding: 0.75rem 1.25rem;
        }
        .dropdown-item:hover {
            background-color: #e9ecef;
            color: #0d6efd;
            transform: translateX(3px);
        }

        /* Gaya untuk section */
        section {
            padding: 80px 0; /* Padding vertikal lebih besar lagi */
        }
        h2.display-5 {
            position: relative;
            margin-bottom: 2.5rem;
            padding-bottom: 0.5rem;
            display: inline-block;
        }
        h2.display-5::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 80px;
            height: 4px;
            background-color: #0d6efd;
            border-radius: 2px;
        }
        .lead {
            font-size: 1.15rem;
            color: #6c757d;
        }

        /* === Custom CSS untuk Carousel === */
        .carousel-item {
            height: 600px; /* Tingkatkan tinggi carousel */
            max-height: 80vh; /* Sesuaikan maksimal tinggi untuk responsivitas */
            overflow: hidden;
            position: relative; /* Penting untuk pemosisian caption */
        }

        .carousel-img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.6); /* Sedikit lebih gelap agar teks putih lebih kontras */
            transition: transform 1s ease-in-out; /* Transisi lebih halus saat slide berubah */
        }

        /* Efek transisi saat slide berubah (opsional, untuk memperhalus) */
        .carousel-item.active .carousel-img {
            transform: scale(1.08); /* Sedikit zoom in saat aktif, lebih besar dari sebelumnya */
        }

        .carousel-caption {
            top: 0; /* Posisikan di paling atas container item */
            left: 0;
            right: 0;
            bottom: 0; /* Posisikan di paling bawah container item */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Pusatkan secara vertikal */
            align-items: center; /* Pusatkan secara horizontal */
            text-align: center; /* Rata tengah teks di dalamnya */
        }

        .carousel-content-wrapper {
            background-color: rgba(0, 0, 0, 0.5); /* Background semi-transparan, lebih gelap */
            padding: 40px 60px; /* Padding lebih besar */
            border-radius: 15px; /* Border radius lebih besar */
            backdrop-filter: blur(8px); /* Efek blur lebih kuat */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); /* Shadow yang lebih jelas */
            max-width: 800px; /* Batasi lebar agar tidak terlalu lebar di desktop */
            width: 90%; /* Responsif width */
            animation: fadeInScale 0.8s ease-out; /* Animasi custom untuk wrapper */
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Animasi untuk tombol 'Get Started' */
        .animated-button {
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .animated-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2); /* Efek kilau */
            transition: left 0.4s ease-out;
            transform: skewX(-20deg);
            z-index: -1;
        }
        .animated-button:hover::before {
            left: 100%;
        }

        /* Footer */
        footer {
            background-color: #212529 !important; /* Lebih gelap dari sebelumnya */
            color: #f8f9fa;
            padding: 40px 0;
        }
        footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        footer a:hover {
            color: #ffffff;
        }

        /* Media Queries untuk responsivitas */
        @media (max-width: 768px) {
            .carousel-item {
                height: 450px; /* Tinggi carousel di mobile */
            }
            .carousel-content-wrapper {
                padding: 25px 30px; /* Padding lebih kecil di mobile */
                width: 95%;
            }
            .carousel-caption h1 {
                font-size: 2rem !important; /* Ukuran h1 di mobile */
            }
            .carousel-caption p {
                font-size: 0.9rem !important; /* Ukuran p di mobile */
            }
            .animated-button {
                font-size: 0.9rem;
                padding: 10px 30px !important;
            }
        }
        body {
            background-color: #f0f2f5; /* Warna latar belakang body, sedikit abu-abu */
        }
        .login-page-container {
            padding: 0; /* Hilangkan padding bawaan container */
        }
        .login-promo-section {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7); /* Gradient biru untuk latar belakang promo */
            min-height: 100vh; /* Pastikan mengisi tinggi layar */
        }
        .login-card {
            max-width: 500px; /* Batasi lebar card login */
            width: 100%; /* Pastikan responsif */
            padding: 20px; /* Padding di dalam card */
        }
        .login-card .form-control-lg {
            padding: 0.85rem 1.5rem; /* Padding lebih besar untuk input */
        }
        .animated-button-social {
            background-color: #fff;
            border-color: #dee2e6;
            color: #495057;
            transition: all 0.3s ease;
        }
        .animated-button-social:hover {
            background-color: #e9ecef;
            border-color: #ced4da;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.08);
            color: #212529;
        }

        /* Hilangkan padding default main untuk halaman login/register */
        body.auth-page main {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        /* Media queries untuk responsivitas */
        @media (max-width: 991.98px) { /* Untuk ukuran layar < lg */
            .login-promo-section {
                display: none !important; /* Sembunyikan kolom promo di mobile */
            }
            .login-page-container .row {
                padding: 40px 0; /* Tambahkan padding vertikal di mobile */
            }
            .login-card {
                margin-top: 50px; /* Beri sedikit jarak dari navbar */
                margin-bottom: 50px;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        @include('components.navbar')

        <main class="py-4 mt-5">
            @yield('content')
        </main>

        <footer class="py-4 mt-5">
            <div class="container text-center">
                <p class="mb-2">&copy; {{ date('Y') }} {{ config('app.name', 'ThriftHunt') }}. All Rights Reserved.</p>
                <p class="mb-0">
                    <a href="#" class="mx-2">Kebijakan Privasi</a> |
                    <a href="#" class="mx-2">Syarat & Ketentuan</a>
                </p>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>