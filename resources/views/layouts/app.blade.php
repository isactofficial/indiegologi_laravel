<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Indiegologi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Libraries CSS (Bootstrap, Font Awesome, etc.) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Custom CSS Files --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    {{-- Gaya Kustom Global dan Perbaikan Navbar --}}
    <style>
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-secondary: #6c757d;
            --indiegologi-light: #f8f9fa;
            --indiegologi-dark: #212529;
            --navbar-height: 5px; /* Definisikan tinggi navbar di sini */
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--indiegologi-light);
            /* (PENTING) Beri padding atas agar konten tidak tertutup navbar fixed */
            padding-top: var(--navbar-height);
        }

        /* === GAYA NAVBAR BARU: FROSTED GLASS (SESUAI FIGMA) === */
        .navbar {
            /* Efek kaca buram */
            background-color: rgba(255, 255, 255, 0.85); /* Warna putih semi-transparan */
            backdrop-filter: saturate(180%) blur(5px);
            -webkit-backdrop-filter: saturate(180%) blur(15px); /* Untuk support Safari */
            
            /* Posisi tetap di atas */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link {
            color: var(--indiegologi-dark); /* Warna teks link menjadi gelap */
            font-weight: 500;
            position: relative;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--indiegologi-primary) !important; /* Warna saat aktif/hover */
        }

        .navbar .btn-outline-primary {
            /* Sesuaikan style tombol dengan desain baru */
        }

        /* Hilangkan underline, fokus pada perubahan warna saja agar lebih clean */
        .navbar .nav-link::after {
            display: none;
        }
        /* === AKHIR GAYA NAVBAR BARU === */

        .btn-primary {
            background-color: var(--indiegologi-primary);
            border-color: var(--indiegologi-primary);
        }
        .btn-primary:hover {
            background-color: #082142;
            border-color: #082142;
        }
        .btn-outline-primary {
            color: var(--indiegologi-primary);
            border-color: var(--indiegologi-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--indiegologi-primary);
            color: #fff;
        }
        .text-primary {
            color: var(--indiegologi-primary) !important;
        }
        .alert-fixed {
            position: fixed;
            top: calc(var(--navbar-height) + 20px); /* Posisikan alert di bawah navbar */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navbar (HTML tetap sama, hanya CSS yang berubah) --}}
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('front.index') }}">
                <img src="{{ asset('assets/img/logo5.png') }}" alt="Indiegologi Logo" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.index') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.articles') }}">BERITA</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.galleries') }}">LAYANAN</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.events.index') }}">EVENT</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.contact') }}">CONTACT US</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('profile.index') }}">PROFILE</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('login') }}">LOGIN</a></li>
                    @else
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary ms-lg-3">LOGOUT</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    {{-- Tidak perlu wrapper khusus, biarkan body yang diberi padding --}}
    @yield('content')
    
    {{-- Footer tetap di bawah --}}
    
        @include('layouts.footer')
    


    {{-- JS Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk mengubah navbar saat scroll sudah dihapus.
            // Hanya menyisakan logika untuk link aktif.
            
            const currentLocation = window.location.href;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.href === currentLocation) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
</body>
</html>