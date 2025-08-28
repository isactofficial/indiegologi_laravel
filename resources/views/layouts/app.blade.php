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

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        font-family: 'Playfair Display', serif;
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
        color: var(--indiegologi-primary) !important;
        transition: color 0.3s ease;
        font-family: 'Playfair Display';
    }

    .navbar .nav-link {
        color: #343a40;
        font-weight: 500;
        position: relative;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: color 0.3s ease, transform 0.3s ease;
        font-family: 'Playfair Display';
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

    /* Style untuk menu offcanvas baru */
    .offcanvas-body .nav-link {
        font-size: 1.1rem;
        padding: 0.85rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .offcanvas-body .nav-link i {
        font-size: 1.4rem;
        width: 25px;
        text-align: center;
        color: var(--indiegologi-secondary);
    }
    .offcanvas-body .nav-link.active i,
    .offcanvas-body .nav-link.active {
        color: var(--indiegologi-primary);
    }
    .offcanvas-body .logout-btn {
        width: 100%;
        margin-top: 1rem;
        padding: 0.75rem;
        font-weight: 600;
    }

    /* Kustomisasi Google Translate */
    /* Pastikan div translate memiliki display block dan dimensi di desktop */
    #google_translate_element_desktop {
        margin-left: 0.75rem;
        display: block;
        height: auto;
        min-height: 30px;
    }

    /* Mobile: sembunyikan widget desktop, tampilkan widget mobile */
    @media (max-width: 991.98px) {
        .navbar .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        #google_translate_element_desktop {
            display: none !important;
        }

        .mobile-translate-container {
            display: block !important;
            margin-top: 1rem;
            padding-left: 1rem;
            width: 100%;
            box-sizing: border-box;
            overflow: visible !important;
            min-height: 50px;
            border: 1px solid #eee;
            background-color: #fff;
        }

        #google_translate_element_mobile .goog-te-gadget {
            display: block !important;
            width: 100% !important;
            height: auto !important;
            min-height: 40px !important;
            overflow: visible !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .mobile-language-link {
            display: none !important;
        }
    }

    /* Gaya umum untuk widget Google Translate (desktop dan mobile) */
    .goog-te-gadget {
        font-family: 'Poppins', sans-serif !important;
        color: #343a40 !important;
        font-size: 1rem !important;
    }
    .goog-te-combo {
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
        padding: 0.25rem 0.5rem !important;
        background-color: #f8f8f8 !important;
        box-shadow: none !important;
    }
    .goog-te-gadget-simple {
        background-color: transparent !important;
        border: none !important;
        padding: 0 !important;
    }
    .goog-te-gadget-simple .goog-te-menu-value {
        text-decoration: none !important;
        color: #343a40 !important;
        font-weight: 500;
        padding: 0 !important;
    }
    .goog-te-gadget-simple .goog-te-menu-value:hover {
        color: var(--indiegologi-primary) !important;
    }
    .goog-te-gadget-simple .goog-te-menu-value span:last-child {
        display: none;
    }
    .goog-te-gadget-simple .goog-te-menu-value::after {
        content: '\F282';
        font-family: 'bootstrap-icons';
        margin-left: 0.25rem;
        color: #6c757d;
        vertical-align: middle;
        font-size: 0.8rem;
    }
    .goog-tooltip, .goog-tooltip:hover, .goog-text-highlight {
        display: none !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container-fluid">
            {{-- Brand/Logo --}}
            <a class="navbar-brand" href="{{ route('front.index') }}">
                <h1 class="text-primary m-0 p-0">Indiegologi</h1>
            </a>

            <div class="d-flex align-items-center d-lg-none">
                {{-- Keranjang untuk Mobile --}}
                <a class="nav-link position-relative fs-4 me-2" href="{{ route('front.cart.view') }}" title="Keranjang">
                    <i class="bi bi-cart"></i>
                    {{-- Badge di-handle oleh JavaScript, sembunyikan secara default --}}
                    <span class="badge rounded-pill bg-danger cart-badge d-none" id="cart-count-badge-mobile" style="top: -2px; right: -5px;">
                        0
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- Desktop Menu --}}
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.articles*') ? 'active' : '' }}" href="{{ route('front.articles') }}">Berita</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.layanan*') ? 'active' : '' }}" href="{{ route('front.layanan') }}">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.sketch*') ? 'active' : '' }}" href="{{ route('front.sketch') }}">Sketsa</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}">Kontak Kami</a></li>

                    {{-- Separator --}}
                    <li class="nav-item d-none d-lg-block"><div class="nav-separator"></div></li>

                    {{-- Actions (Cart, Profile, Login) for Desktop --}}
                    <li class="nav-item">
                        <div class="navbar-actions">
                            {{-- Keranjang untuk Desktop --}}
                            <a class="nav-link position-relative fs-5" href="{{ route('front.cart.view') }}" title="Keranjang">
                                <i class="bi bi-cart"></i>
                                {{-- Badge di-handle oleh JavaScript, sembunyikan secara default --}}
                                <span class="badge rounded-pill bg-danger cart-badge d-none" id="cart-count-badge-desktop">0</span>
                            </a>
                            @auth
                                <div class="dropdown">
                                    <a class="nav-link dropdown-toggle fs-5" href="#" id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Profil"><i class="bi bi-person-circle"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarProfileDropdown">
                                        <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <a class="btn px-4" href="{{ route('login') }}" style="background-color: #0C2C5A; color: #fff; border: none;">Login</a>
                            @endauth

                            {{-- Widget Google Translate bawaan untuk Desktop --}}
                            <div id="google_translate_element_desktop" class="d-none d-lg-block"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ========================================================================= --}}
    {{-- OFFCANVAS MENU (MOBILE)                                                   --}}
    {{-- ========================================================================= --}}
    <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
        <div class="offcanvas-header">
            @auth
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-circle fs-2 me-2"></i>
                    <div>
                        <h5 class="offcanvas-title fw-bold" id="mobileNavbarLabel" style="line-height: 1.2;">{{ Auth::user()->name }}</h5>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                </div>
            @else
                <h5 class="offcanvas-title fw-bold" id="mobileNavbarLabel">Menu</h5>
            @endauth
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            {{-- Main Navigation --}}
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}"><i class="bi bi-house"></i><span>Beranda</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.articles*') ? 'active' : '' }}" href="{{ route('front.articles') }}"><i class="bi bi-newspaper"></i><span>Berita</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.layanan*') ? 'active' : '' }}" href="{{ route('front.layanan') }}"><i class="bi bi-grid"></i><span>Layanan</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.sketch*') ? 'active' : '' }}" href="{{ route('front.sketch') }}"><i class="bi bi-pencil-square"></i><span>Sketsa</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}"><i class="bi bi-envelope"></i><span>Kontak Kami</span></a></li>
            </ul>
            <hr>

            {{-- Widget Google Translate bawaan untuk Mobile --}}
            <div class="mobile-translate-container">
                <div id="google_translate_element_mobile"></div>
            </div>
            <hr>

            {{-- User Actions --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    {{-- Keranjang di offcanvas --}}
                    <a class="nav-link" href="{{ route('front.cart.view') }}">
                        <i class="bi bi-cart"></i>
                        <span>Keranjang</span>
                        <span class="badge rounded-pill bg-danger ms-auto d-none" id="cart-count-badge-offcanvas">0</span>
                    </a>
                </li>
            </ul>
            @auth
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.index') }}"><i class="bi bi-person"></i><span>Profil Saya</span></a></li>
                </ul>
                <hr>
            @endauth

            {{-- Footer Actions (Login/Logout) --}}
            <div class="mt-auto">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                @else
                    <a class="btn btn-primary logout-btn" href="{{ route('login') }}" style="background-color: #0C2C5A; color: #fff; border: none;">Login</a>
                @endauth
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

    {{-- Google Translate Script --}}
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {pageLanguage: 'id', includedLanguages: 'id,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE},
                'google_translate_element_desktop'
            );
            new google.translate.TranslateElement(
                {pageLanguage: 'id', includedLanguages: 'id,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE},
                'google_translate_element_mobile'
            );
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    {{-- Existing JavaScript --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fungsi untuk mengambil data keranjang dari Local Storage
        function getTempCart() {
            try {
                const cart = localStorage.getItem('tempCart');
                return cart ? JSON.parse(cart) : {};
            } catch (e) {
                console.error("Failed to parse tempCart from localStorage", e);
                return {};
            }
        }

        // Fungsi untuk memperbarui badge keranjang di navbar
        function updateCartCount() {
            // Ambil jumlah item dari Local Storage
            const tempCart = getTempCart();
            const tempCartCount = Object.keys(tempCart).length;

            // Perbarui badge keranjang di offcanvas
            const offcanvasBadge = $('#cart-count-badge-offcanvas');
            if (offcanvasBadge.length) {
                if (tempCartCount > 0) {
                    offcanvasBadge.text(tempCartCount).removeClass('d-none');
                } else {
                    offcanvasBadge.text('0').addClass('d-none');
                }
            }

            // Perbarui badge keranjang di desktop
            const desktopBadge = $('#cart-count-badge-desktop');
            if (desktopBadge.length) {
                if (tempCartCount > 0) {
                    desktopBadge.text(tempCartCount).removeClass('d-none');
                } else {
                    desktopBadge.text('0').addClass('d-none');
                }
            }

            // Perbarui badge keranjang di mobile (di samping toggler)
            const mobileBadge = $('#cart-count-badge-mobile');
            if (mobileBadge.length) {
                if (tempCartCount > 0) {
                    mobileBadge.text(tempCartCount).removeClass('d-none');
                } else {
                    mobileBadge.text('0').addClass('d-none');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar.fixed-top');
            if (navbar) {
                let lastScrollTop = 0;
                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    if (scrollTop > 10) { navbar.classList.add('scrolled'); }
                    else { navbar.classList.remove('scrolled'); }
                    if (window.innerWidth >= 992) {
                        if (scrollTop > lastScrollTop && scrollTop > navbar.offsetHeight) { navbar.classList.add('navbar-hidden'); }
                        else { navbar.classList.remove('navbar-hidden'); }
                    }
                    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                });
            }

            // Panggil fungsi ini saat halaman pertama kali dimuat
            updateCartCount();

            // clear localStorage
            $('form.logout-form').on('submit', function() {
                localStorage.removeItem('tempCart');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
