@extends('layouts.app')

@section('title', 'Indiegologi - Homepage')

@push('styles')
{{-- [BRAND] Import font yang elegan dan modern dari Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
/* 1. Hero Section Styles */
.hero-section {
    position: relative;
    height: 100vh;
    min-height: 650px;
    color: #fff;
}
.hero-section .carousel,
.hero-section .carousel-inner,
.hero-section .carousel-item {
    height: 100%;
}
.hero-section .carousel-item {
    background-size: cover;
    background-position: center;
}
.hero-section .overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
}
.hero-section .carousel-caption {
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: 100%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hero-section .carousel-caption .container {
    text-align: center;
}
.hero-title, .hero-subtitle {
    margin-left: auto;
    margin-right: auto;
}
.hero-title {
    font-size: clamp(2.25rem, 5vw, 3.75rem); /* Responsive font size */
    font-weight: 800;
    line-height: 1.2;
    max-width: 25ch;
    margin-bottom: 1rem;
}
.hero-subtitle {
    font-size: clamp(1rem, 2.5vw, 1.2rem); /* Responsive font size */
    color: rgba(255, 255, 255, 0.9);
    max-width: 55ch;
    margin-bottom: 2.25rem;
    font-family: 'Playfair Display', sans-serif;
}
.hero-button {
    font-size: 1rem;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 5px;
    font-family: 'Playfair Display';
    background-color: #0C2C5A;
    color: #ffffff;
    border: 1px solid #0C2C5A;
    transition: all 0.3s ease-out;
}
.hero-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    background-color: #0C2C5A;
    border-color: #0C2C5A;
    color: #ffffff;
}
.hero-section .carousel-indicators {
    display: flex !important;
    opacity: 1 !important;
    bottom: 5rem;
}
.hero-section .carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
    opacity: 1;
}
.hero-section .carousel-indicators .active {
    background-color: #fff;
}
.hero-section .carousel-control-prev,
.hero-section .carousel-control-next {
    display: none; /* Biasanya disembunyikan di mobile, atau disesuaikan */
}


/* 2. Featured Popular Article Slider Styles */
.featured-popular-article-swiper {
    position: relative;
    overflow: hidden !important;
}
.featured-popular-article-swiper .swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem 0;
}
.featured-popular-card {
    width: 100%;
    max-width: 1050px;
    min-height: 400px;
    padding: 48px 56px;
    position: relative;
    border-radius: 10px;
}
.featured-popular-img-wrap {
    width: 100%;
    max-width: 380px;
}
.featured-popular-img {
    height: 340px;
    width: 100%;
    object-fit: cover;
    border-radius: 6px;
}
.featured-popular-title {
    font-size: 2.6rem;
    font-weight: 700;
    color: #18305b;
    line-height: 1.2;
}
.featured-popular-desc {
    font-family: 'Playfair Display', sans-serif;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.featured-popular-wrapper .swiper-button-next,
.featured-popular-wrapper .swiper-button-prev {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    width: 48px;
    height: 48px;
    color: #555;
    top: 50%;
    transform: translateY(-50%);
    opacity: 1;
    transition: opacity 0.2s, box-shadow 0.2s, left 0.3s, right 0.3s;
    z-index: 2;
}
.featured-popular-wrapper .swiper-button-prev {
    left: -25px !important;
}
.featured-popular-wrapper .swiper-button-next {
    right: -25px !important;
}
.featured-popular-wrapper .swiper-button-next:after,
.featured-popular-wrapper .swiper-button-prev:after {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}
.featured-popular-wrapper .swiper-button-disabled {
    opacity: 0;
    pointer-events: none;
}

/* 3. Testimonial Styles */
.testimonial-square-card {
    width: 340px;
    height: 340px;
    border-radius: 5px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
    position: relative;
    overflow: hidden;
    margin: 0 auto 20px;
}
.testimonial-bg-img {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    object-fit: cover; z-index: 1;
    filter: blur(1px) brightness(0.7);
}
.testimonial-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 2;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.25) 60%, rgba(0, 0, 0, 0.45) 100%);
}
.testimonial-content {
    position: absolute;
    z-index: 3; bottom: 20px; left: 0; right: 0;
    padding: 0 20px;
    font-family: 'Playfair Display', sans-serif;
}
.testimonial-name {
    font-family: 'Playfair Display', serif;
}
.testimonial-quote-icon {
    position: absolute;
    font-size: 2.2rem;
    z-index: 4; opacity: 0.7; font-family: serif;
}
.testimonial-quote-top-left {
    top: 15px; left: 20px;
}
.testimonial-quote-bottom-right {
    bottom: 20px; right: 20px;
}

/* 4. CSS Umum untuk SEMUA Tombol Panah Slider Lainnya */
.swiper-button-next,
.swiper-button-prev {
    top: 50%;
    transform: translateY(-50%);
    background-color: white;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    color: #18305b !important;
    transition: opacity 0.2s;
}
.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 1.2rem;
    font-weight: 900;
}
.swiper-button-disabled {
    opacity: 0;
    pointer-events: none;
}
.card {
    border-radius: 6px;
}
.card .article-img-container {
    padding-top: 56.25%;
    position: relative;
    overflow: hidden;
    background-color: #f0f0f0;
}
.card .article-img-container img {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    object-fit: cover;
}
.card-hover-zoom {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}
.card-hover-zoom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.5em;
}
.featured-popular-title.line-clamp-2 {
    min-height: 2.4em;
}

/* RESPONSIVE MEDIA QUERIES */
@media (max-width: 1200px) {
    .featured-popular-wrapper .swiper-button-prev {
        left: 0 !important;
    }
    .featured-popular-wrapper .swiper-button-next {
        right: 0 !important;
    }
}

@media (max-width: 991.98px) {
    .featured-popular-card {
        flex-direction: column !important;
        text-align: center !important;
        padding: 40px;
    }
    .featured-popular-card .me-lg-5 {
        margin-right: 0 !important;
    }
    .featured-popular-img-wrap {
        max-width: 100%;
    }
}

@media (max-width: 767.98px) {
    .hero-section {
        min-height: 600px;
    }
    .hero-section .carousel-caption {
        align-items: flex-end;
        padding-bottom: 6rem;
    }
    .featured-popular-card {
        padding: 24px;
        min-height: auto;
    }
    .featured-popular-title {
        font-size: 1.8rem; /* Adjusted for smaller screens */
    }
    .featured-popular-desc {
        font-size: 1rem; /* Adjusted for smaller screens */
    }
    .featured-popular-link {
        font-size: 1rem;
    }
    .featured-popular-wrapper .swiper-button-prev,
    .featured-popular-wrapper .swiper-button-next {
        width: 40px;
        height: 40px;
    }
    .featured-popular-wrapper .swiper-button-prev {
        left: 5px !important;
    }
    .featured-popular-wrapper .swiper-button-next {
        right: 5px !important;
    }
    .featured-popular-wrapper .swiper-button-next:after,
    .featured-popular-wrapper .swiper-button-prev:after {
        font-size: 1.2rem;
    }
    /* Adjustments for general section titles on mobile */
    h2.fw-bold.mb-3, h2.fw-bold.mb-0 {
        font-size: 1.8rem !important; /* Ensure main section titles scale down */
    }
    p.text-center.mb-5, p.lead.text-muted {
        font-size: 0.95rem !important; /* Adjust paragraph text */
    }
}
</style>
@endpush

@section('content')

{{-- 1. Hero Section (Tidak perlu animasi scroll karena sudah terlihat saat load) --}}
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            {{-- Slide 1 --}}
            <div class="carousel-item active"
                style="background-image: url('{{ asset('assets/carousel/christina-wocintechchat-com-eF7HN40WbAQ-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Temukan Diri Sejati Anda dengan Hypno Healing</h1>
                        <p class="hero-subtitle">Jalani proses penyembuhan diri yang penuh perhatian dan nyaman bersama kami, untuk menemukan kedamaian batin yang mendalam.</p>
                        <a href="{{ route('front.layanan') }}" class="btn hero-button">Mulai Perjalanan Anda</a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="carousel-item"
                style="background-image: url('{{ asset('assets/carousel/kobu-agency-7okkFhxrxNw-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Jurnal Reflektif: Kisah Anda, Karya Anda</h1>
                        <p class="hero-subtitle">Ekspresikan perjalanan emosional Anda dengan elegan dan personal, mendokumentasikan setiap langkah menuju cinta diri.</p>
                        <a href="{{ route('front.sketch') }}" class="btn btn hero-button">Pelajari Tentang Kami</a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="carousel-item"
                style="background-image: url('{{ asset('assets/carousel/maranda-vandergriff-7aakZdIl4vg-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Seni Menghargai Setiap Proses Hidup</h1>
                        <p class="hero-subtitle">Panduan penuh kasih untuk membantu Anda menemukan keindahan dan kekuatan di setiap tahap kehidupan.</p>
                        <a href="{{ route('front.articles') }}" class="btn btn hero-button">Temukan Artikel Kami</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
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
<section class="container py-5 my-5" style="margin-top: 80px;"><br><br><br>
    <div data-aos="fade-down" data-aos-duration="1000">
        <h2 class="text-center fw-bold mb-3" style="color: #0C2C5A; font-size:2.3rem;">Artikel Pilihan Kami untuk Anda</h2>
        <p class="text-center mb-5" style="color:#6c757d; font-family: 'Playfair Display', sans-serif;">Jelajahi kisah-kisah penuh inspirasi dan temukan wawasan yang paling menyentuh hati komunitas kami.</p>
    </div>

    <div class="featured-popular-wrapper" style="position: relative;" data-aos="zoom-in-up" data-aos-duration="1000" data-aos-delay="200">
        <div class="swiper featured-popular-article-swiper">
            <div class="swiper-wrapper">
                @forelse ($popular_articles as $article)
                <div class="swiper-slide">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white shadow-sm border border-light featured-popular-card">
                        <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail Artikel Populer"
                                class="img-fluid featured-popular-img">
                        </div>
                        <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                            <h3 class="fw-bold mb-3 featured-popular-title line-clamp-2" style="color: #0C2C5A;">
                                {{ $article->title }}
                            </h3>
                            <p class="mb-3 featured-popular-desc line-clamp-3" style="color:#4a5a6a; font-size:1.15rem;">
                                {{ strip_tags($article->description) }}
                            </p>
                            <p class="text-secondary mb-4 featured-popular-date" style="font-family: 'Playfair Display', sans-serif; font-size: 0.9rem;">
                                {{ optional($article->created_at)->format('d F Y') }}</p>
                            <a href="{{ route('front.articles.show', $article->slug) }}"
                                class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link stretched-link"
                                style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <p class="text-center text-muted">Belum ada artikel pilihan.</p>
                </div>
                @endforelse
            </div>
        </div>
        <div class="swiper-button-prev featured-popular-prev"></div>
        <div class="swiper-button-next featured-popular-next"></div>
    </div>
</section>


{{-- 3. Artikel Terbaru (Slider) --}}
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;" data-aos="fade-right" data-aos-duration="800">Wawasan Terbaru untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0"
            style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;" data-aos="fade-left" data-aos-duration="800">Lihat Semua Artikel<i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="swiper latest-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($latest_articles as $article)
            <div class="swiper-slide pb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                <div class="card border-0 shadow-sm h-100 card-hover-zoom">
                    <div class="article-img-container">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                            class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold line-clamp-2" style="color: #0C2C5A;">{{ $article->title }}</h5>
                        <p class="text-secondary small mb-2" style="font-family: 'Playfair Display', sans-serif;">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3 line-clamp-2" style="color:#4a5a6a; font-family: 'Playfair Display', sans-serif;">
                            {{ strip_tags($article->description) }}
                        </p>
                        <a href="{{ route('front.articles.show', $article->slug) }}"
                            class="mt-auto text-decoration-none fw-semibold stretched-link" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-center text-muted">Belum ada artikel terbaru.</p>
            </div>
            @endforelse
        </div>
        <div class="swiper-button-next latest-articles-next"></div>
        <div class="swiper-button-prev latest-articles-prev"></div>
    </div>
</section>

{{-- 4. Artikel Populer (Slider 3 Kolom) --}}
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;" data-aos="fade-right" data-aos-duration="800">Artikel Pilihan untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0"
            style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;" data-aos="fade-left" data-aos-duration="800">Jelajahi Semua<i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="swiper popular-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($popular_articles as $article)
            <div class="swiper-slide pb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                <div class="card border-0 shadow-sm h-100 card-hover-zoom">
                    <div class="article-img-container" style="padding-top: 56.25%;">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                            class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold line-clamp-2" style="color: #0C2C5A;">{{ $article->title }}</h5>
                        <p class="text-secondary small mb-2" style="font-family: 'Playfair Display', sans-serif;">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3 line-clamp-2" style="color:#4a5a6a; font-family: 'Playfair Display', sans-serif;">
                            {{ strip_tags($article->description) }}
                        </p>
                        <a href="{{ route('front.articles.show', $article->slug) }}"
                            class="mt-auto text-decoration-none fw-semibold stretched-link" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-center text-muted">Belum ada artikel populer.</p>
            </div>
            @endforelse
        </div>
        <div class="swiper-button-next popular-articles-next"></div>
        <div class="swiper-button-prev popular-articles-prev"></div>
    </div>
    <div class="text-center mt-4" data-aos="zoom-in" data-aos-delay="300">
        <a href="{{ route('front.articles') }}" class="btn btn-primary px-4 py-2" style="background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Lihat Semua Artikel</a>
    </div>
</section>

{{-- 5. Testimoni Section --}}
<section class="container py-5 my-5">
    <div class="text-center" data-aos="fade-up">
        <h2 class="fw-bold mb-3" style="color: #0C2C5A; font-size:2.3rem;">Kisah Inspiratif dari Keluarga Indiegologi</h2>
        <p style="font-size:1.15rem; color:#6c757d; font-family: 'Playfair Display', sans-serif;">Dengarkan cerita tulus dari mereka yang telah berproses bersama kami, menemukan kebahagiaan, kejelasan, dan **cinta diri** yang sejati.</p>
    </div>
    <div class="swiper testimonials-swiper mt-5">
        <div class="swiper-wrapper">
            @for ($i = 0; $i < 3; $i++)
            <div class="swiper-slide" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="1200" data-aos-delay="{{ $i * 200 }}">
                <div class="testimonial-square-card position-relative overflow-hidden shadow-sm mx-auto">
                    <img src="{{ asset('assets/testimoni/testimoni.jpg') }}" alt="Testimoni" class="testimonial-bg-img">
                    <div class="testimonial-overlay"></div>
                    <div class="testimonial-quote-icon testimonial-quote-top-left" style="color: #fff;">&#10077;</div>
                    <div class="testimonial-content text-center text-white w-100 px-4 position-absolute bottom-0 start-50 translate-middle-x pb-4">
                        <div class="testimonial-name fw-bold" style="font-size: 1.8rem;">Haekal</div>
                        <div class="testimonial-job mb-2" style="font-size: 1.1rem; font-style: italic; opacity: 0.8;">
                            Pengusaha
                        </div>
                        <div class="testimonial-text fw-semibold fst-italic" style="font-size: 0.95rem; line-height: 1.4;">
                            "Pendekatan yang personal dan efektif. Saya melihat perubahan positif yang signifikan dalam hidup saya."
                        </div>
                    </div>
                    <div class="testimonial-quote-icon testimonial-quote-bottom-right" style="color: #fff;">&#10078;</div>
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
    <div class="w-100 text-center" data-aos="fade-up">
        <h2 class="fw-bold mb-1 text-center" style="color: #0C2C5A;">Sketch Telling</h2>
        <p class="text-muted mb-0 text-center" style="font-family: 'Playfair Display', sans-serif;">Lihatlah kisah-kisah yang kami visualisasikan</p>
    </div>
    <div class="swiper sketch-telling-swiper mt-4">
        <div class="swiper-wrapper">
            @forelse ($latest_sketches as $sketch)
            <div class="swiper-slide pb-3" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                <a href="{{ route('front.sketches.detail', $sketch->slug) }}" class="text-decoration-none d-block h-100">
                    <div class="card border-0 shadow-sm h-100 card-hover-zoom">
                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" class="card-img-top"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold line-clamp-2" style="color: #0C2C5A;">{{ $sketch->title }}</h5>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-center text-muted">Belum ada sketsa.</p>
            </div>
            @endforelse
        </div>
        <div class="swiper-button-next sketch-next"></div>
        <div class="swiper-button-prev sketch-prev"></div>
    </div>
    <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
        <a href="{{ route('front.sketch') }}" class="btn btn-primary px-4 py-2 text-center" style=" background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Lihat Semua Sketsa</a>
    </div>
</section>

<section class="py-5 my-5 bg-light">
    <div class="container text-center" data-aos="fade-down">
        <h2 class="fw-bold mb-1 text-center" style="color: #0C2C5A;">Layanan Unggulan Kami</h2>
        <p class="text-muted mb-0 text-center" style="font-family: 'Playfair Display', sans-serif;">Kami menawarkan berbagai layanan yang dirancang untuk mendukung perjalanan Anda.</p>
    </div>
    <div class="container mt-5">
        <div class="row g-4 justify-content-center">
            @forelse ($services as $service)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                <div class="card border-0 shadow-sm h-100 text-center card-hover-zoom">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <i class="bi bi-star-fill fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $service->title }}</h5>
                        <p class="text-muted small" style="font-family: 'Playfair Display', sans-serif;">{{ Str::limit($service->short_description, 100) }}</p>
                        <p class="fw-bold fs-4 my-3" style="color: #0C2C5A;">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                        <a href="{{ route('front.layanan') }}" class="btn btn-outline-primary mt-auto stretched-link" style="color:#f8f9fa; background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Pilih Paket Ini</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <p class="text-muted text-center">Saat ini belum ada layanan yang tersedia.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-5" data-aos="zoom-in" data-aos-delay="300">
            <a href="{{ route('front.layanan') }}" class="btn btn-primary px-4 py-2" style=" background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Lihat Semua Layanan</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
{{-- Skrip untuk Swiper.js (slider) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Swiper untuk Artikel Terpopuler Pilihan Kami (Hanya satu slide terlihat)
    new Swiper('.featured-popular-article-swiper', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        centeredSlides: true,
        navigation: {
            nextEl: '.featured-popular-next',
            prevEl: '.featured-popular-prev',
        },
        autoHeight: true,
    });

    // Swiper untuk Artikel Terbaru (3 kolom)
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

    // Inisialisasi Swiper BARU untuk Artikel Populer (3 kolom)
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

{{-- SCRIPT UNTUK ANIMASI AOS DENGAN LOGIKA KUSTOM --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    // Inisialisasi AOS
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        // PENTING: once harus false agar kita bisa memanipulasi animasinya
        once: false, 
        offset: 120,
    });

    // --- SCRIPT KUSTOM UNTUK PERILAKU ANIMASI SPESIFIK ---
    
    // Variabel untuk menyimpan posisi scroll terakhir
    let lastScrollTop = 0;
    // Ambil semua elemen yang memiliki atribut data-aos
    const allAosElements = document.querySelectorAll('[data-aos]');

    // Tambahkan event listener saat window di-scroll
    window.addEventListener('scroll', function() {
        // Dapatkan posisi scroll saat ini
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop < lastScrollTop) {
            // JIKA ARAH SCROLL KE ATAS
            allAosElements.forEach(function(element) {
                // Cek jika posisi atas elemen berada DI BAWAH layar (tidak terlihat)
                if (element.getBoundingClientRect().top > window.innerHeight) {
                    // Jika ya, hapus kelas 'aos-animate' untuk "mereset"
                    // dan menyembunyikannya kembali sesuai permintaan.
                    element.classList.remove('aos-animate');
                }
            });
        } 
        // Saat scrolling ke bawah, kita tidak melakukan apa-apa.
        // Biarkan AOS yang bekerja secara normal untuk memunculkan elemen.

        // Update posisi scroll terakhir
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);

</script>
@endpush