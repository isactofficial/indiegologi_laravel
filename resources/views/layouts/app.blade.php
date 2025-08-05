<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Indiegologi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- SwiperJS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    {{-- Gaya Kustom Global dan Variabel Warna --}}
    <style>
        /* Menggunakan warna dasar baru: #0C2C5A */
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-secondary: #6c757d;
            --indiegologi-light: #f8f9fa;
            --indiegologi-dark: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--indiegologi-light);
        }

        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\'%230C2C5A\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E') !important;
        }

        .btn-primary {
            background-color: var(--indiegologi-primary);
            border-color: var(--indiegologi-primary);
        }
        .btn-primary:hover {
            background-color: #082142; /* Sedikit lebih gelap dari warna primer */
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

        /* Gaya untuk alert */
        .alert-fixed {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Gaya Swiper dan Card (dari app.blade sebelumnya) */
        .swiper-slide { height: auto; }
        .card { display: flex; flex-direction: column; height: 100%; }
        .card-body { flex-grow: 1; }
        .swiper-button-next, .swiper-button-prev { color: var(--indiegologi-primary); }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

    {{-- Navigation Bar --}}
    <nav class="navbar navbar-expand-lg bg-transparent py-3 position-absolute top-0 start-0 w-100 z-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('front.index') }}">
                <img src="{{ asset('assets/img/logo5.png') }}" alt="Indiegologi Logo" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item"><a class="nav-link fw-medium active" href="{{ route('front.index') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.articles') }}">BERITA</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('front.galleries') }}">GALERI</a></li>
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
    {{-- End of Navigation Bar --}}

    {{-- Main Content Wrapper --}}
    <div class="main-wrapper d-flex flex-column min-vh-100">
        <div class="container alert-fixed">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="content flex-grow-1">
            @yield('content')
        </div>

        <footer class="bg-dark text-white-50 py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <img src="{{ asset('assets/img/logo5.png') }}" alt="Indiegologi Logo" style="height: 50px;" class="mb-3">
                        <p class="small">Indiegologi adalah platform yang didedikasikan untuk membantu Anda menemukan kedamaian batin dan potensi diri melalui berbagai artikel, sketsa, dan layanan konsultasi.</p>
                        <div class="social-icons mt-3">
                            <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white-50"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h6 class="text-white fw-bold">NAVIGASI</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('front.index') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                            <li><a href="{{ route('front.articles') }}" class="text-white-50 text-decoration-none">Artikel</a></li>
                            <li><a href="{{ route('front.galleries') }}" class="text-white-50 text-decoration-none">Galeri</a></li>
                            <li><a href="{{ route('front.events.index') }}" class="text-white-50 text-decoration-none">Event</a></li>
                            <li><a href="{{ route('front.contact') }}" class="text-white-50 text-decoration-none">Kontak</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="text-white fw-bold">LAYANAN</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white-50 text-decoration-none">Konsultasi Individu</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Konsultasi Kelompok</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Webinar & Workshop</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Layanan Sketch Telling</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="text-white fw-bold">HUBUNGI KAMI</h6>
                        <ul class="list-unstyled">
                            <li class="text-white-50"><i class="bi bi-geo-alt me-2"></i>Jl. Contoh No. 123, Jakarta</li>
                            <li class="text-white-50"><i class="bi bi-envelope me-2"></i>info@indiegologi.com</li>
                            <li class="text-white-50"><i class="bi bi-telephone me-2"></i>+62 812-3456-7890</li>
                        </ul>
                    </div>
                </div>
                <div class="text-center small mt-4 pt-4 border-top border-secondary">
                    &copy; 2025 Indiegologi. All Rights Reserved.
                </div>
            </div>
        </footer>
    </div>

    {{-- JS Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
