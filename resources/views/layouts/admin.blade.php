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
            --sidebar-width: 280px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
        }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--bg-sidebar);
            box-shadow: var(--shadow-md); /* Sedikit pertebal shadow */
            padding-top: 20px;
            overflow-y: auto;
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1045; /* Naikkan z-index */
            transition: all 0.3s ease;
        }
        .sidebar-content { height: 100%; display: flex; flex-direction: column; position: relative; padding-bottom: 80px; }
        .sidebar h4 { font-weight: 700; color: var(--text-dark); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; text-align: center; }
        .sidebar a { font-weight: 500; display: flex; align-items: center; color: var(--text-dark); padding: 12px 20px; margin: 8px 15px; text-decoration: none; transition: all 0.3s ease; border-radius: 10px; }
        .sidebar a i { margin-right: 10px; font-size: 18px; }
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
        }
        .btn-logout {
            bottom: 20px; left: 15px; right: 15px; position: absolute; width: calc(100% - 30px); background-color: #fff; border: 1px solid var(--primary-color); padding: 12px 20px; border-radius: 10px; color: var(--primary-color); font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; text-decoration: none;
        }
        .btn-logout:hover {
            background-color: rgba(12, 44, 90, 0.05);
            box-shadow: 0 2px 8px rgba(12, 44, 90, 0.2);
        }
        .btn-logout i { margin-right: 8px; }
        .logo-container { text-align: center; margin-bottom: 20px; padding: 0 20px; }
        .logo-container .logo {
            width: 60px; height: 60px; background-color: var(--active-bg); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;
            border: 1px solid rgba(12, 44, 90, 0.2);
        }
        .logo-container .logo i { font-size: 30px; color: var(--primary-color); }
        .nav-links { flex-grow: 1; }
        .mobile-header {
            display: none;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .mobile-header .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary-color);
        }

        /* --- PERUBAHAN CSS DIMULAI DI SINI --- */
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }
        .sidebar-header .logo {
            width: 50px; height: 50px; background-color: var(--active-bg); border-radius: 12px; display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(12, 44, 90, 0.2);
        }
        .sidebar-header .logo i { font-size: 24px; color: var(--primary-color); }
        .sidebar-header h4 {
            font-size: 1.2rem;
            margin: 0;
            padding: 0;
            border: none;
        }
        .btn-close-sidebar {
            background: none; border: none; font-size: 24px; color: var(--text-muted);
            display: none; /* Sembunyi di desktop */
        }
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none; /* Sembunyi secara default */
        }
        .sidebar-overlay.active {
            display: block; /* Tampil saat sidebar aktif */
        }
        /* --- AKHIR PERUBAHAN CSS --- */

        @media (max-width: 992px) {
            .sidebar { left: calc(-1 * var(--sidebar-width)); }
            .sidebar.active { left: 0; }
            .main-content-wrapper { margin-left: 0; width: 100%; }
            .mobile-header { display: flex; }
            .sidebar-header { display: none; } /* Sembunyikan header desktop di mobile */
            .logo-container { position: relative; } /* Tambahkan ini */
            .btn-close-sidebar { /* Tampilkan tombol close di mobile */
                display: block;
                position: absolute;
                top: 5px;
                right: 15px;
            }
        }
    </style>
</head>
<body>

{{-- [BARU] Tambahkan div untuk overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="wrapper" id="wrapper">
    <div class="sidebar" id="sidebar">
        <div class="sidebar-content">
            {{-- [DIUBAH] Struktur header dan logo --}}
            <div class="sidebar-header d-none d-lg-flex">
                <div class="logo"><i class="fas fa-layer-group"></i></div>
                <h4>Indiegologi</h4>
            </div>

            <div class="logo-container d-lg-none">
                <div class="logo"><i class="fas fa-layer-group"></i></div>
                <h4>Indiegologi</h4>
                {{-- [BARU] Tombol close untuk mobile --}}
                <button class="btn-close-sidebar" id="close-sidebar"><i class="fas fa-times"></i></button>
            </div>

            <div class="nav-links">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Manajemen Artikel
                </a>
                <a href="{{ route('admin.sketches.index') }}" class="{{ request()->routeIs('admin.sketches.*') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i> Manajemen Painting
                </a>
                 <a href="{{ route('admin.referral-codes.index') }}" class="{{ request()->routeIs('admin.referral-codes.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Manajemen Referral
                </a>
                <a href="{{ route('admin.consultation-services.index') }}" class="{{ request()->routeIs('admin.consultation-services.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i> Manajemen Layanan
                </a>
                <a href="{{ route('admin.consultation-bookings.index') }}" class="{{ request()->routeIs('admin.consultation-bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Manajemen Booking
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                   <i class="fas fa-comments"></i> Manajemen Testimoni
                </a>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content-wrapper">
        <div class="mobile-header">
            <button class="menu-toggle" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- [DIUBAH] Skrip JavaScript --}}
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