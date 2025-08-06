@extends('layouts.app')

@section('title', 'Indiegologi - Homepage')

@section('content')

    {{-- 1. Hero Section --}}
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" style="background-image: url('{{ asset('assets/carousel/christina-wocintechchat-com-eF7HN40WbAQ-unsplash.jpg') }}');">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container text-start text-white">
                            <p class="mb-2">Indiegologi</p>
                            <h1 class="display-4 fw-bold">Healing Healing Menemukan Kedamaian Batin yang Mendalam</h1>
                            <a href="#" class="btn btn-light rounded-pill px-4 mt-3">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/kobu-agency-7okkFhxrxNw-unsplash.jpg') }}');">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container text-start text-white">
                            <p class="mb-2">Indiegologi</p>
                            <h1 class="display-4 fw-bold">Jelajahi Diri Anda Melalui Jurnal Reflektif</h1>
                            <a href="#" class="btn btn-light rounded-pill px-4 mt-3">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/maranda-vandergriff-7aakZdIl4vg-unsplash.jpg') }}');">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container text-start text-white">
                            <p class="mb-2">Indiegologi</p>
                            <h1 class="display-4 fw-bold">Seni Menghargai Proses Hidup</h1>
                            <a href="#" class="btn btn-light rounded-pill px-4 mt-3">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <div class="carousel-indicators mt-3">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>
        </div>
    </section>

    {{-- 2. Artikel Populer (Satu Artikel Menonjol) --}}
    <section class="container py-5 my-5" style="margin-top: 150px;">
        <h2 class="text-center fw-bold mb-5">Artikel Terpopuler Pilihan Kami</h2>
        <div class="row align-items-center bg-white rounded-3 p-4 shadow-sm border border-light">
            <div class="col-lg-5 mb-4 mb-lg-0">
                @if(isset($popular_articles[0]))
                    <img src="{{ asset('storage/' . $popular_articles[0]->thumbnail) }}" alt="Thumbnail Artikel Populer" class="img-fluid rounded-3">
                @else
                    <img src="https://via.placeholder.com/600x400.png?text=Artikel+Populer" alt="Placeholder" class="img-fluid rounded-3">
                @endif
            </div>
            <div class="col-lg-7">
                <h3 class="fw-bold fs-4">
                    {{ isset($popular_articles[0]) ? Str::limit($popular_articles[0]->title, 80) : 'Judul Artikel Populer' }}
                </h3>
                <p class="text-muted small">
                    {{ isset($popular_articles[0]) ? optional($popular_articles[0]->created_at)->format('d F Y') : 'Tanggal' }}
                </p>
                <p>
                    {{ isset($popular_articles[0]) ? Str::limit($popular_articles[0]->content, 200) : 'Deskripsi singkat tentang artikel populer. Konten ini memberikan gambaran tentang apa yang dibahas dalam artikel tersebut.' }}
                </p>
                <a href="#" class="btn btn-link text-decoration-none fw-semibold p-0 text-primary">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    {{-- 3. Artikel Terbaru (Slider) --}}
    <section class="container py-5 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Artikel Terbaru</h2>
            <a href="#" class="btn btn-link text-primary fw-semibold text-decoration-none">Lihat Semua Artikel</a>
        </div>
        <div class="swiper latest-articles-swiper">
            <div class="swiper-wrapper">
                @forelse ($latest_articles as $article)
                    <div class="swiper-slide pb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ Str::limit($article->title, 50) }}</h5>
                                <p class="text-muted small">{{ optional($article->created_at)->format('d F Y') }}</p>
                                <a href="#" class="stretched-link text-decoration-none fw-semibold">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center text-muted">Belum ada artikel terbaru.</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next latest-articles-next"></div>
            <div class="swiper-button-prev latest-articles-prev"></div>
        </div>
    </section>

    {{-- 4. Artikel Populer (Slider 3 Kolom) --}}
    <section class="container py-5 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Artikel Populer</h2>
            <a href="#" class="btn btn-link text-primary fw-semibold text-decoration-none">Lihat Semua Artikel</a>
        </div>
        <div class="swiper popular-articles-swiper">
            <div class="swiper-wrapper">
                @forelse ($popular_articles as $article)
                    <div class="swiper-slide pb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ Str::limit($article->title, 50) }}</h5>
                                <p class="text-muted small">{{ optional($article->created_at)->format('d F Y') }}</p>
                                <a href="#" class="stretched-link text-decoration-none fw-semibold">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center text-muted">Belum ada artikel populer.</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next popular-articles-next"></div>
            <div class="swiper-button-prev popular-articles-prev"></div>
        </div>
    </section>

    {{-- 5. Testimoni Section --}}
    <section class="container py-5 my-5">
        <div class="text-center">
            <h2 class="fw-bold mb-3">Suara dari Mereka yang Telah Berproses Bersama Kami</h2>
            <p>Dengarkan pengalaman otentik dari klien kami yang telah menemukan kebahagiaan, kejelasan, dan keseimbangan hidup <br>
            yang nyaman dan berkesan bersama Indiegologi.</p>
        </div>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                @for ($i = 0; $i < 5; $i++)
                    <div class="swiper-slide pb-3">
                        <div class="card border-0 shadow-sm h-100 p-4 testimonial-card">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('assets/avatar.png') }}" width="60" height="60" class="rounded-circle me-3 border border-2 border-primary">
                                <div>
                                    <h6 class="fw-bold mb-0">Haekal</h6>
                                    <small class="text-muted">Pemulih Jiwa</small>
                                </div>
                            </div>
                            <p class="fst-italic mb-0">"Pendekatan yang personal dan efektif. Saya melihat perubahan besar dalam diri saya. Indiegologi benar-benar membantu saya menemukan jalan."</p>
                            <i class="bi bi-quote quote-icon"></i>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="swiper-button-next testimonials-next"></div>
            <div class="swiper-button-prev testimonials-prev"></div>
        </div>
    </section>

    {{-- 6. Sketch Telling (Slider) --}}
    <section class="container py-5 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Sketch Telling</h2>
                <p class="text-muted mb-0">Lihatlah kisah-kisah yang kami visualisasikan</p>
            </div>
            <a href="#" class="btn btn-link text-primary fw-semibold text-decoration-none">Lihat Semua Sketch</a>
        </div>
        <div class="swiper sketch-telling-swiper">
            <div class="swiper-wrapper">
                @forelse ($latest_sketches as $sketch)
                    <div class="swiper-slide pb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="{{ asset('storage/' . $sketch->thumbnail) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="fw-bold">{{ Str::limit($sketch->title, 50) }}</h5>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center text-muted">Belum ada sketsa.</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next sketch-next"></div>
            <div class="swiper-button-prev sketch-prev"></div>
        </div>
    </section>

    {{-- 7. Layanan Kami --}}
    <section class="py-5 my-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">Layanan Kami</h2>
            <div class="row justify-content-center">
                @forelse ($services as $service)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <i class="bi bi-star-fill text-primary mb-3 fs-3"></i>
                                <h5 class="fw-bold mb-3">{{ $service->title }}</h5>
                                <p class="text-muted">{{ Str::limit($service->short_description, 100) }}</p>
                                <a href="#" class="btn btn-outline-primary mt-3">Pilih Paket Ini</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada layanan tersedia.</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="#" class="btn btn-primary">Lihat Semua Promo</a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Swiper untuk Artikel Terbaru
            new Swiper('.latest-articles-swiper', {
                loop: true,
                navigation: {
                    nextEl: '.latest-articles-next',
                    prevEl: '.latest-articles-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 40 },
                }
            });

            // Swiper untuk Artikel Populer
            new Swiper('.popular-articles-swiper', {
                loop: true,
                navigation: {
                    nextEl: '.popular-articles-next',
                    prevEl: '.popular-articles-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 40 },
                }
            });

            // Swiper untuk Testimoni
            new Swiper('.testimonials-swiper', {
                loop: true,
                navigation: {
                    nextEl: '.testimonials-next',
                    prevEl: '.testimonials-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 40 },
                }
            });

            // Swiper untuk Sketch Telling
            new Swiper('.sketch-telling-swiper', {
                loop: true,
                navigation: {
                    nextEl: '.sketch-next',
                    prevEl: '.sketch-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 40 },
                }
            });
        });
    </script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    .hero-section {
        margin-bottom: 180px;
    }

    .hero-section .carousel-item {
        height: 90vh;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .hero-section .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.2), transparent);
    }

    .hero-section .carousel-caption {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: start;
        padding-left: 15%;
        padding-right: 15%;
        text-align: left;
        transform: none;
    }

    .hero-section .carousel-caption h1 {
        font-size: 3.5rem;
    }

    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .carousel-indicators .active {
        opacity: 1;
    }

    .hero-section .carousel-indicators {
        bottom: -300px;
    }

    /* Untuk menghilangkan panah navigasi */
.hero-section .carousel-control-prev,
.hero-section .carousel-control-next {
    display: none;
}

    .swiper-button-next, .swiper-button-prev {
        color: #ccc !important;
        --swiper-navigation-size: 20px;
    }

    .testimonial-card {
        position: relative;
        text-align: left;
    }

    .testimonial-card img {
        object-fit: cover;
    }

    .testimonial-card .quote-icon {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        font-size: 3rem;
        color: #f0f0f0;
    }
</style>
@endpush
