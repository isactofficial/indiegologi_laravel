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
                            <h1 class="display-4 fw-bold">Hypno Healing: Menemukan <br>Kedamaian Batin yang Mendalam</h1>
                            <p class="mb-2">Teknik relaksasi dan sugesti positif untuk mengatasi kecemasan, fobia, dan trauma, membuka potensi penyembuhan diri.</p>
                            <br>
                            <br>
                            <a href="#" class="btn btn-primary rounded-pill px-4 py-2">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/kobu-agency-7okkFhxrxNw-unsplash.jpg') }}');">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container text-start text-white">
                            <h1 class="display-4 fw-bold">Jelajahi Diri Anda Melalui Jurnal Reflektif</h1>
                            <p class="mb-2">Indiegologi</p>
                            <br>
                            <br>
                            <a href="#" class="btn btn-primary rounded-pill px-4 py-2">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/maranda-vandergriff-7aakZdIl4vg-unsplash.jpg') }}');">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container text-start text-white">
                            <h1 class="display-4 fw-bold">Seni Menghargai Proses Hidup</h1>
                            <p class="mb-2">Indiegologi</p>
                            <br>
                            <br>
                            <a href="#" class="btn btn-primary rounded-pill px-4 py-2">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tambahkan navigasi titik-titik di sini --}}
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    {{-- 2. Artikel Populer (Slider Satu Artikel Menonjol) --}}
    <section class="container py-5 my-5" style="margin-top: 80px;">
        <h2 class="text-center fw-bold mb-3" style="color: #0C2C5A; font-size:2.3rem;">Artikel Terpopuler Pilihan Kami</h2>
        <p class="text-center mb-5" style="color:#4a5a6a;">Lihat artikel-artikel yang paling banyak dibaca dan disukai oleh komunitas Indiegologi.</p>
        <div class="swiper featured-popular-article-swiper">
            <div class="swiper-wrapper">
                @forelse ($popular_articles as $article)
                    <div class="swiper-slide">
                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white rounded-4 shadow-sm border border-light featured-popular-card">
                            <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail Artikel Populer" class="img-fluid rounded-3 featured-popular-img">
                            </div>
                            <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                                <h3 class="fw-bold mb-2 featured-popular-title">{{ Str::limit($article->title, 60) }}</h3>
                                <p class="text-muted mb-2 featured-popular-date">{{ optional($article->created_at)->format('d F Y') }}</p>
                                <p class="mb-3 featured-popular-desc">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                                <a href="{{ route('front.articles.show', $article->slug) }}" class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white rounded-4 shadow-sm border border-light featured-popular-card">
                            <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                                <img src="https://via.placeholder.com/600x400.png?text=Artikel+Populer" alt="Placeholder" class="img-fluid rounded-3 featured-popular-img">
                            </div>
                            <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                                <h3 class="fw-bold mb-2 featured-popular-title">Judul Artikel Populer</h3>
                                <p class="text-muted mb-2 featured-popular-date">Tanggal</p>
                                <p class="mb-3 featured-popular-desc">Deskripsi singkat tentang artikel populer. Konten ini memberikan gambaran tentang apa yang dibahas dalam artikel tersebut.</p>
                                <a href="#" class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-button-prev featured-popular-prev" style="left:-30px;"></div>
            <div class="swiper-button-next featured-popular-next" style="right:-30px;"></div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.featured-popular-article-swiper', {
                loop: true,
                navigation: {
                    nextEl: '.featured-popular-next',
                    prevEl: '.featured-popular-prev',
                },
                slidesPerView: 1,
                spaceBetween: 0,
                autoHeight: true,
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .featured-popular-article-swiper .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }
        .featured-popular-article-swiper {
            overflow: hidden !important;
        }
        .featured-popular-card {
            width: 100%;
            max-width: 1050px;
            min-height: 400px;
            padding: 48px 56px;
        }
        .featured-popular-img-wrap {
            max-width: 380px;
        }
        .featured-popular-img {
            max-height: 340px;
            width: 100%;
            object-fit: cover;
            background: #f7f7f7;
        }
        .featured-popular-content {
            padding-left: 0;
            padding-right: 0;
        }
        .featured-popular-title {
            font-size: 2.6rem;
            color: #18305b;
            line-height: 1.1;
        }
        .featured-popular-date {
            font-size: 1.2rem;
        }
        .featured-popular-desc {
            color: #4a5a6a;
            font-size: 1.25rem;
        }
        .featured-popular-link {
            color: #b0b0b0;
            font-size: 1.4rem;
        }
        .featured-popular-article-swiper {
            overflow: visible !important;
        }
        .featured-popular-article-swiper .swiper-button-next,
        .featured-popular-article-swiper .swiper-button-prev {
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            width: 48px;
            height: 48px;
            color: #555;
            border: 4px solid #666;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.2s, box-shadow 0.2s;
            z-index: 2;
        }
        .featured-popular-article-swiper .swiper-button-prev {
            left: -60px !important;
        }
        .featured-popular-article-swiper .swiper-button-next {
            right: -60px !important;
        }
        .featured-popular-article-swiper .swiper-button-next:after,
        .featured-popular-article-swiper .swiper-button-prev:after {
            font-size: 2rem;
            font-weight: bold;
            color: #555;
        }
        .featured-popular-article-swiper .swiper-button-next:hover,
        .featured-popular-article-swiper .swiper-button-prev:hover {
            opacity: 0.7;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        }
        @media (max-width: 991.98px) {
            .featured-popular-article-swiper .d-flex {
                flex-direction: column !important;
                text-align: center !important;
            }
            .featured-popular-article-swiper .me-lg-5 {
                margin-right: 0 !important;
            }
        }
    </style>
    @endpush

    {{-- 3. Artikel Terbaru (Slider) --}}
    <section class="container py-5 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- <h2 class="fw-bold mb-0">Artikel Terbaru</h2>
            <a href="{{ route('front.articles') }}" class="btn btn-link text-primary fw-semibold text-decoration-none">Lihat Semua Artikel</a> -->
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
                                <a href="{{ route('front.articles.show', $article->slug) }}" class="stretched-link text-decoration-none fw-semibold">Baca Selengkapnya</a>
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
            <!-- <h2 class="fw-bold mb-0">Artikel Populer</h2> -->
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
                            <a href="{{ route('front.articles.show', $article->slug) }}" class="stretched-link text-decoration-none fw-semibold">Baca Selengkapnya</a>
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
        <div class="text-center mt-4">
            <a href="{{ route('front.articles') }}" class="btn btn-primary rounded-pill px-4 py-2">Lihat Semua Artikel</a>
        </div>
    </section>

    {{-- 5. Testimoni Section --}}
    <section class="container py-5 my-5">
        <div class="text-center">
            <h2 class="fw-bold mb-3" style="color: #0C2C5A;">Suara dari Mereka yang Telah Berproses Bersama Kami</h2>
            <p>Dengarkan pengalaman otentik dari klien kami yang telah menemukan kebahagiaan, kejelasan, dan keseimbangan hidup <br>
            yang nyaman dan berkesan bersama Indiegologi.</p>
        </div>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
            @for ($i = 0; $i < 3; $i++)
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
            <div class="w-100 text-center">
                <h2 class="fw-bold mb-1 text-center" style="color: #0C2C5A;">Sketch Telling</h2>
                <p class="text-muted mb-0 text-center">Lihatlah kisah-kisah yang kami visualisasikan</p>
            </div>
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
                <div class="text-center mt-4">
                    <a href="{{ route('front.sketch') }}" class="btn btn-primary rounded-pill px-4 py-2 text-center mt-4">Lihat Semua Sketch</a>
                </div>
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
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary rounded-pill px-4 py-2">Lihat Semua Promo</a>
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
        margin-bottom: 250px;
    }

    .hero-section .carousel-item {
        height: 100vh;
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
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
        text-align: center;
        transform: none;
    }

    .hero-section .carousel-caption .container {
        text-align: center;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }

    .hero-section .carousel-caption h1 {
        font-size: 3.5rem;

    }

    /* Mengatur posisi dan style indikator */
    .hero-section .carousel-indicators {
        position: absolute;
        top: 140%;
        margin-bottom: 0;
    }

    .hero-section .carousel-indicators button {
        width: 8px; /* Ukuran titik */
        height: 8px; /* Ukuran titik */
        margin: 0 4px;
        border-radius: 50%; /* Membuat bentuk bulat */
        background-color: #fff;
        opacity: 0.5; /* Transparansi titik yang tidak aktif */
        transition: opacity 0.3s ease;
        border: none; /* Menghilangkan border */
    }

    .hero-section .carousel-indicators .active {
        opacity: 1; /* Titik aktif menjadi solid */
    }

    /* Untuk menghilangkan panah navigasi */
    .hero-section .carousel-control-prev,
    .hero-section .carousel-control-next {
        display: none;
    }

    .btn

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
