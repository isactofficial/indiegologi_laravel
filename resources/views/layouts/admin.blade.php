<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Indiegologi Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('favicon/favicon-light.png') }}" media="(prefers-color-scheme: light)" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon-dark.png') }}" media="(prefers-color-scheme: dark)" type="image/png">

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #0C2C5A;
            --secondary-color: #00617a;
            --accent-color: #f4b704;
            --text-dark: #343a40;
            --text-muted: #6c757d;
            --bg-light: #F8F8FF;
            --bg-sidebar: #FFFFFF;
            --active-bg: rgba(12, 44, 90, 0.1);
            --active-text: var(--primary-color);
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --sidebar-width: 300px;
        }

        /* --- Kustomisasi Scrollbar Elegan --- */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
            border: 2px solid #f1f1f1;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(12, 44, 90, 0.2);
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--bg-sidebar);
            box-shadow: var(--shadow-md);
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1045;
            transition: all 0.3s ease;
        }

        .sidebar-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
        }

        .sidebar-header {
            padding: 25px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
        }

        .sidebar-header .logo {
            width: 45px;
            height: 45px;
            background-color: var(--active-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            border: 1px solid rgba(12, 44, 90, 0.2);
        }

        .sidebar-header .logo i {
            font-size: 22px;
            color: var(--primary-color);
        }

        .sidebar-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .sidebar-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px 0;
        }

        /* --- Collapsible Group Styling --- */
        .nav-group {
            margin-bottom: 5px;
        }

        .nav-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 25px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-group-header:hover {
            color: var(--primary-color);
        }

        .nav-group-header i.chevron {
            font-size: 0.7rem;
            transition: transform 0.3s;
        }

        .nav-group-header[aria-expanded="true"] i.chevron {
            transform: rotate(180deg);
        }

        .sidebar a {
            font-weight: 500;
            display: flex;
            align-items: center;
            color: var(--text-dark);
            padding: 10px 20px;
            margin: 2px 15px 2px 30px; /* Menjorok ke dalam */
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar a:not(.active):hover {
            background-color: var(--active-bg);
            color: var(--active-text);
            transform: translateX(5px);
        }

        .sidebar a.active {
            background-color: var(--primary-color);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(12, 44, 90, 0.3);
        }

        .sidebar-footer {
            padding: 20px 15px;
            border-top: 1px solid #f1f1f1;
            background-color: var(--bg-sidebar);
            margin-top: auto;
        }

        .btn-logout {
            width: 100%;
            background-color: #fff;
            border: 1.5px solid #dc3545;
            padding: 12px;
            border-radius: 10px;
            color: #dc3545;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #dc3545;
            color: #fff;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        .btn-logout i {
            margin-right: 8px;
        }

        .main-content-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 20px;
            background-color: var(--bg-light);
            transition: margin-left 0.3s ease;
        }

        .content {
            padding: 30px;
            background-color: #FFFFFF;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            min-height: calc(100vh - 40px);
        }

        .mobile-header {
            display: none;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            border-radius: 10px;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-header .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary-color);
        }

        .btn-close-sidebar {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-muted);
            display: none;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.active {
                left: 0;
            }

            .main-content-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .mobile-header {
                display: flex;
            }

            .btn-close-sidebar {
                display: block;
                position: absolute;
                top: 20px;
                right: 20px;
                z-index: 1050;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="wrapper" id="wrapper">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <button class="btn-close-sidebar" id="close-sidebar"><i class="fas fa-times"></i></button>

                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <div class="logo"><i class="fas fa-layer-group"></i></div>
                    <a href="/" style="text-decoration: none;"><h4>Indiegologi</h4></a>
                </div>

                <!-- Nav Links with Collapsible Dropdowns -->
                <div class="sidebar-scroll">
                    
                    <!-- Group: Utama -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupUtama" aria-expanded="true">
                            <span>Utama</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupUtama">
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-th-large"></i> Dashboard
                            </a>
                            <a href="{{ url('/') }}" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Lihat Situs
                            </a>
                        </div>
                    </div>

                    <!-- Group: Manajemen Konten -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupKonten" aria-expanded="true">
                            <span>Manajemen Konten</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupKonten">
                            <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.index') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i> Artikel
                            </a>
                            <a href="{{ route('admin.sketches.index') }}" class="{{ request()->routeIs('admin.sketches.*') ? 'active' : '' }}">
                                <i class="fas fa-paint-brush"></i> Painting & Karakter
                            </a>
                            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                                <i class="fas fa-comment-dots"></i> Testimoni
                            </a>
                        </div>
                    </div>

                    <!-- Group: Layanan & Promo -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupLayanan" aria-expanded="true">
                            <span>Layanan & Promo</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupLayanan">
                            <a href="{{ route('admin.consultation-services.index') }}" class="{{ request()->routeIs('admin.consultation-services.*') ? 'active' : '' }}">
                                <i class="fas fa-handshake"></i> Layanan Konsultasi
                            </a>
                            <a href="{{ route('admin.free-consultation.types.index') }}" class="{{ request()->routeIs('admin.free-consultation.types.*') ? 'active' : '' }}">
                                <i class="fas fa-hand-holding-heart"></i> Tipe Konsultasi Gratis
                            </a>
                            <a href="{{ route('admin.free-consultation.schedules.index') }}" class="{{ request()->routeIs('admin.free-consultation.schedules.*') ? 'active' : '' }}">
                                <i class="fas fa-clock"></i> Jadwal Gratis
                            </a>
                            <a href="{{ route('admin.referral-codes.index') }}" class="{{ request()->routeIs('admin.referral-codes.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i> Kode Referral
                            </a>
                        </div>
                    </div>

                    <!-- Group: Pemesanan -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupPemesanan" aria-expanded="true">
                            <span>Pemesanan</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupPemesanan">
                            <a href="{{ route('admin.consultation-bookings.index') }}" class="{{ request()->routeIs('admin.consultation-bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar"></i> Booking Layanan
                            </a>
                            <a href="{{ route('admin.free-consultation-bookings.index') }}" class="{{ request()->routeIs('admin.free-consultation-bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i> Booking Gratis
                            </a>
                            <a href="{{ route('admin.event-bookings.index') }}" class="{{ request()->routeIs('admin.event-bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-ticket-alt"></i> Booking Event
                            </a>
                        </div>
                    </div>

                    <!-- Group: Agenda -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupAgenda" aria-expanded="true">
                            <span>Agenda</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupAgenda">
                            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt"></i> Manajemen Event
                            </a>
                        </div>
                    </div>

                    <!-- Group: Sistem -->
                    <div class="nav-group">
                        <div class="nav-group-header" data-bs-toggle="collapse" data-bs-target="#groupSistem" aria-expanded="true">
                            <span>Sistem</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </div>
                        <div class="collapse show" id="groupSistem">
                            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                <i class="fas fa-user-circle"></i> Profil
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Fixed Logout Footer -->
                <div class="sidebar-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content-wrapper">
            <div class="mobile-header">
                <button class="menu-toggle" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="fw-bold" style="color: var(--primary-color)">Indiegologi Admin</div>
                <div style="width: 24px;"></div>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('menu-toggle');
            const closeBtn = document.getElementById('close-sidebar');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('active');
                if (overlay) overlay.classList.add('active');
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
            }

            if (openBtn) {
                openBtn.addEventListener('click', openSidebar);
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
        });
    </script>

    @stack('scripts')

</body>

</html>