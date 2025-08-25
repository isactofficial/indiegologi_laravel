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
            box-shadow: var(--shadow-sm);
            padding-top: 30px;
            overflow-y: auto;
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .sidebar-content { height: 100%; display: flex; flex-direction: column; position: relative; padding-bottom: 80px; }
        .sidebar h4 { font-weight: 700; color: var(--text-dark); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef; text-align: center; }
        .sidebar a { font-weight: 500; display: flex; align-items: center; color: var(--text-dark); padding: 12px 20px; margin: 8px 15px; text-decoration: none; transition: all 0.3s ease; border-radius: 10px; }
        .sidebar a i { margin-right: 10px; font-size: 18px; }

        /* Gaya untuk link yang tidak aktif saat di-hover */
        .sidebar a:not(.active):hover {
            background-color: var(--active-bg);
            color: var(--active-text);
            transform: translateX(5px);
        }

        /* [DIUBAH] Gaya untuk link yang aktif agar sesuai gambar */
        .sidebar a.active {
            background-color: var(--primary-color); /* Latar belakang biru solid */
            color: #FFFFFF; /* Teks berwarna putih */
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(12, 44, 90, 0.3); /* Shadow yang lebih tegas */
        }
        .main-content-wrapper { margin-left: var(--sidebar-width); flex-grow: 1; padding: 30px; background-color: var(--bg-light); transition: all 0.3s ease; }
        .content { padding: 30px; background-color: var(--bg-light); border-radius: 15px; box-shadow: var(--shadow-md); }
        .btn-logout {
            bottom: 20px; left: 15px; right: 15px; position: absolute; width: calc(100% - 30px); background-color: #fff; border: 1px solid var(--primary-color); padding: 12px 20px; border-radius: 10px; color: var(--primary-color); font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; text-decoration: none;
        }
        .btn-logout:hover {
            background-color: rgba(12, 44, 90, 0.05);
            box-shadow: 0 2px 8px rgba(12, 44, 90, 0.2);
        }
        .btn-logout i { margin-right: 8px; }
        .logo-container { text-align: center; margin-bottom: 20px; }
        .logo-container .logo {
            width: 60px; height: 60px; background-color: var(--active-bg); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;
            border: 1px solid rgba(12, 44, 90, 0.2);
        }
        .logo-container .logo i { font-size: 30px; color: var(--primary-color); }
        .nav-links { flex-grow: 1; }
        @media (max-width: 768px) {
            .wrapper { margin-left: -var(--sidebar-width); }
            .main-content-wrapper { margin-left: 0; }
            .wrapper.toggled .sidebar { margin-left: 0; }
            .wrapper.toggled .main-content-wrapper { margin-left: var(--sidebar-width); }
        }
    </style>
</head>
<body>

<div class="wrapper" id="wrapper">
    <div class="sidebar">
        <div class="sidebar-content">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h4>Indiegologi</h4>
            </div>

            <div class="nav-links">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Manajemen Artikel
                </a>
                <a href="{{ route('admin.sketches.index') }}" class="{{ request()->routeIs('admin.sketches.*') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i> Manajemen Sketsa
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
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content-wrapper">
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
