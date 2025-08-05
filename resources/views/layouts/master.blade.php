<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Indiegologi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SwiperJS CSS (Dipindahkan ke sini) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Google Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- CSS Global Anda --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    {{-- Style untuk Carousel (Dipindahkan ke sini) --}}
    <style>
        .swiper-slide { height: auto; }
        .card { display: flex; flex-direction: column; height: 100%; }
        .card-body { flex-grow: 1; }
        .swiper-button-next, .swiper-button-prev { color: #0d6efd; }
    </style>

    @stack('styles')
</head>
<body style="font-family: 'Poppins', sans-serif">

    <div class="main-wrapper d-flex flex-column min-vh-100">
        @yield('content')
    </div>

    {{-- Bootstrap & Font Awesome JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    {{-- SwiperJS (Dipindahkan ke sini) --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')

</body>
</html>