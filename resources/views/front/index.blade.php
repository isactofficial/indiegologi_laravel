@extends('layouts.master')

@section('title', 'Indiegologi - Homepage')

@section('content')

    {{-- 1. Navbar --}}
    <nav class="navbar navbar-expand-lg bg-white py-3 sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">Indiegologi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active fw-medium" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#">Artikel</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#">Tentang Kami</a></li>
                </ul>
                <a href="#" class="btn btn-primary rounded-pill fw-medium px-4">Konsultasi Gratis</a>
            </div>
        </div>
    </nav>

    {{-- 2. Hero Section --}}
    <section class="container py-5 my-md-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Empowering Ideas Through Innovative Technology</h1>
                <p class="lead text-muted mb-4">We are a team of passionate developers, designers, and strategists dedicated to helping you achieve your goals.</p>
                <a href="#" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-medium">Konsultasi Gratis</a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <img src="{{ asset('assets/img/hero_image.png') }}" class="img-fluid" alt="Team discussing ideas">
            </div>
        </div>
    </section>

{{-- 3. Artikel Populer Section (Carousel) --}}
<section class="container py-5">
    <div class="text-center"><h2 class="fw-bold mb-5">Artikel Populer</h2></div>
    <div class="swiper popular-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($latest_articles as $article)
                <div class="swiper-slide pb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ Str::limit($article->title, 50) }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($article->description, 70) }}</p>
                            <a href="#" class="btn btn-outline-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide"><p class="text-muted text-center">Belum ada artikel.</p></div>
            @endforelse
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

    {{-- 4. Layanan Kami Section (Hanya 3) --}}
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">Layanan Kami</h2>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 mb-4"><div class="card border-0 shadow-sm h-100"><div class="card-body p-5"><h5 class="card-title fw-bold">Web Development</h5></div></div></div>
                <div class="col-lg-3 col-md-6 mb-4"><div class="card border-0 shadow-sm h-100"><div class="card-body p-5"><h5 class="card-title fw-bold">UI/UX Design</h5></div></div></div>
                <div class="col-lg-3 col-md-6 mb-4"><div class="card border-0 shadow-sm h-100"><div class="card-body p-5"><h5 class="card-title fw-bold">IT Consultant</h5></div></div></div>
            </div>
        </div>
    </section>

    {{-- 5. Suara Dari Mereka Section (Carousel) --}}
    <section class="container py-5">
        <div class="text-center"><h2 class="fw-bold mb-5">Suara Dari Mereka</h2></div>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                @for ($i = 0; $i < 5; $i++)
                    <div class="swiper-slide pb-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4 text-center"><p class="fst-italic">"Layanannya luar biasa, sangat membantu proyek kami!"</p><h6 class="fw-bold mt-3">- Klien {{ $i + 1 }}</h6></div></div>
                    </div>
                @endfor
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

{{-- 6. Sketch Telling Section (Carousel) --}}
<section class="container py-5">
    <div class="text-center"><h2 class="fw-bold mb-5">Sketch Telling</h2></div>
    <div class="swiper sketch-telling-swiper">
        <div class="swiper-wrapper">
             @forelse ($latest_sketches as $sketch)
                <div class="swiper-slide pb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" class="card-img-top" alt="{{ $sketch->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ Str::limit($sketch->title, 50) }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide"><p class="text-muted text-center">Belum ada sketsa.</p></div>
            @endforelse
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>
@endsection

@push('scripts')
    {{-- Inisialisasi Carousel spesifik untuk halaman ini --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperConfig = {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 40 },
                }
            };
            new Swiper('.popular-articles-swiper', swiperConfig);
            new Swiper('.testimonials-swiper', swiperConfig);
            new Swiper('.sketch-telling-swiper', swiperConfig);
        });
    </script>
@endpush