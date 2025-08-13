<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Indiegologi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Libraries CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Custom CSS Files --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
    :root {
        --indiegologi-primary: #0C2C5A;
        --indiegologi-secondary: #6c757d;
        --indiegologi-light: #f8f9fa;
        --indiegologi-dark: #212529;
        --navbar-height: 80px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--indiegologi-light);
        padding-top: var(--navbar-height);
    }

    .navbar.fixed-top {
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: saturate(180%) blur(15px);
        -webkit-backdrop-filter: saturate(180%) blur(15px);
        border-bottom: 1px solid transparent;
        transition: transform 0.3s ease-in-out, background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    .navbar.scrolled {
        background-color: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid rgba(0, 0, 0, 0.07);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .navbar-hidden {
        transform: translateY(-100%);
    }

    .navbar-brand h1 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .navbar .nav-link {
        color: #343a40;
        font-weight: 500;
        position: relative;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .navbar .nav-link:hover {
        color: var(--indiegologi-primary);
        transform: scale(1.05);
    }

    .navbar .nav-link.active {
        color: var(--indiegologi-primary) !important;
        font-weight: 700;
    }
    
    .cart-badge {
        position: absolute;
        top: -5px;
        right: -8px;
        font-size: 0.6em;
        padding: 0.3em 0.5em;
        border: 2px solid white;
    }
    
    .navbar-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .nav-separator {
        height: 24px;
        width: 1px;
        background-color: #dee2e6;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }

    .navbar .container-fluid {
        padding-left: 3rem;
        padding-right: 3rem;
    }

    .navbar-toggler {
        border: none;
    }
    .navbar-toggler:focus {
        box-shadow: none;
    }
    .offcanvas-header {
        border-bottom: 1px solid #dee2e6;
    }
    .offcanvas-body .nav-link {
        font-size: 1.2rem;
        padding: 0.75rem 0;
    }
    .offcanvas-body .logout-btn {
        width: 100%;
        margin-top: 1rem;
        padding: 0.75rem;
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .navbar .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('front.index') }}">
                <h1 class="text-primary m-0 p-0">INDIEGOLOGI</h1>
            </a>
            
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar"
                aria-controls="mobileNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    {{-- ... menu items ... --}}
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.articles*') ? 'active' : '' }}" href="{{ route('front.articles') }}">BERITA</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.layanan*') ? 'active' : '' }}" href="{{ route('front.layanan') }}">LAYANAN</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.sketch*') ? 'active' : '' }}" href="{{ route('front.sketch') }}">SKETCH</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}">CONTACT US</a></li>
                    
                    <li class="nav-item d-none d-lg-block">
                        <div class="nav-separator"></div>
                    </li>

                    <li class="nav-item">
                        <div class="navbar-actions">
                            @auth
                                <a class="nav-link position-relative fs-5" href="{{ route('front.cart.view') }}" title="Keranjang">
                                    <i class="bi bi-cart"></i>
                                    @php
                                        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                                    @endphp
                                    {{-- [INI PERBAIKANNYA] Menambahkan id="cart-count-badge" --}}
                                    <span class="badge rounded-pill bg-danger cart-badge {{ $cartCount == 0 ? 'd-none' : '' }}" id="cart-count-badge">
                                        {{ $cartCount }}
                                    </span>
                                </a>
                                <div class="dropdown">
                                    <a class="nav-link dropdown-toggle fs-5" href="#" id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Profil">
                                        <i class="bi bi-person-circle"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarProfileDropdown">
                                        <li><a class="dropdown-item" href="{{ route('profile.index') }}">Lihat Profil</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">LOGOUT</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a class="btn btn-primary px-4" href="{{ route('login') }}">LOGIN</a>
                            @endguest
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- [BARU] Offcanvas untuk Tampilan Mobile --}}
    <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
        <div class="offcanvas-header">
            @auth
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-circle fs-2 me-2"></i>
                    <div>
                        <h5 class="offcanvas-title fw-bold" id="mobileNavbarLabel">{{ Auth::user()->name }}</h5>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                </div>
            @else
                <h5 class="offcanvas-title fw-bold" id="mobileNavbarLabel">Menu</h5>
            @endauth
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}">HOME</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.articles*') ? 'active' : '' }}" href="{{ route('front.articles') }}">BERITA</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.layanan*') ? 'active' : '' }}" href="{{ route('front.layanan') }}">LAYANAN</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.sketch*') ? 'active' : '' }}" href="{{ route('front.sketch') }}">SKETCH</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}">CONTACT US</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.index') }}">PROFIL SAYA</a></li>
                @endauth
            </ul>

            <div class="mt-auto">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger logout-btn">LOGOUT</button>
                    </form>
                @else
                    <a class="btn btn-primary logout-btn" href="{{ route('login') }}">LOGIN</a>
                @endguest
            </div>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- JS Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar.fixed-top');
            if (navbar) {
                let lastScrollTop = 0;
                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > 10) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }

                    if (window.innerWidth >= 992) {
                        if (scrollTop > lastScrollTop && scrollTop > navbar.offsetHeight) {
                            navbar.classList.add('navbar-hidden');
                        } else {
                            navbar.classList.remove('navbar-hidden');
                        }
                    }
                    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
