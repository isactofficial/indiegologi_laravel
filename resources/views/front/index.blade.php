@extends('layouts.app')

@section('title', 'Indiegologi - Homepage')

@section('content')

    {{-- 2. Hero Section --}}
    <section class="container py-5 my-md-5">
        {{-- Menggunakan Bootstrap Carousel sebagai pengganti Hero Section statis --}}
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/carousel/christina-wocintechchat-com-eF7HN40WbAQ-unsplash.jpg') }}" class="d-block w-100" alt="carousel image 1">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/carousel/kobu-agency-7okkFhxrxNw-unsplash.jpg') }}" class="d-block w-100" alt="carousel image 2">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/carousel/maranda-vandergriff-7aakZdIl4vg-unsplash.jpg') }}" class="d-block w-100" alt="carousel image 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    {{-- 3. Artikel Populer Section (Carousel) --}}
    <section class="container py-5 my-5">
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
    <section class="py-5 my-5 bg-light">
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
    <section class="container py-5 my-5">
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
    <section class="container py-5 my-5">
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