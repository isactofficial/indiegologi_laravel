@extends('layouts.app')

@section('title', 'Indiegologi - Homepage')

@section('content')

{{-- 1. Hero Section --}}
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            {{-- Slide 1 --}}
            <div class="carousel-item active"
                style="background-image: url('{{ asset('assets/carousel/christina-wocintechchat-com-eF7HN40WbAQ-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        {{-- Menghapus <br> agar lebih responsif --}}
                        <h1 class="hero-title">Temukan Diri Sejati Anda dengan Hypno Healing</h1>
                        <p class="hero-subtitle">Jalani proses penyembuhan diri yang penuh perhatian dan nyaman bersama kami, untuk menemukan kedamaian batin yang mendalam.</p>
                        <a href="#" class="btn btn-light hero-button">Mulai Perjalanan Anda</a>
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
                        <a href="#" class="btn btn-light hero-button">Pelajari Tentang Kami</a>
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
                        <a href="#" class="btn btn-light hero-button">Temukan Artikel Kami</a>
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

<div style="overflow-x: hidden;">

    {{-- 2. Artikel Populer (Slider Satu Artikel Menonjol) --}}
    <section class="container py-5 my-5" style="margin-top: 80px;"><br><br><br>
        <h2 class="text-center fw-bold mb-3" style="color: #0C2C5A; font-size:2.3rem;">Artikel Pilihan Kami untuk Anda
        </h2>
        <p class="text-center mb-5" style="color:#6c757d;">Jelajahi kisah-kisah penuh inspirasi dan temukan wawasan yang
            paling menyentuh hati komunitas kami.</p>
        <div class="swiper featured-popular-article-swiper">
            <div class="swiper-wrapper">
                @forelse ($popular_articles as $article)
                <div class="swiper-slide">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white rounded-4 shadow-sm border border-light featured-popular-card">
                        <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail Artikel Populer"
                                class="img-fluid rounded-3 featured-popular-img">
                        </div>
                        <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                            <h3 class="fw-bold mb-2 featured-popular-title" style="color: #0C2C5A;">
                                {{ Str::limit($article->title, 60) }}</h3>

                            <p class="mb-3 featured-popular-desc" style="color:#4a5a6a; font-size:1.15rem;">
                                {{ Str::words(strip_tags($article->description), 15, '...') }}</p>

                            <p class="text-secondary mb-2 featured-popular-date">
                                {{ optional($article->created_at)->format('d F Y') }}</p>
                            <a href="{{ route('front.articles.show', $article->slug) }}"
                                class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link"
                                style="color: #0C2C5A;">Baca Lebih Lanjut <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white rounded-4 shadow-sm border border-light featured-popular-card">
                        <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                            <img src="https://via.placeholder.com/600x400.png?text=Artikel+Pilihan" alt="Placeholder"
                                class="img-fluid rounded-3 featured-popular-img">
                        </div>
                        <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                            <h3 class="fw-bold mb-2 featured-popular-title" style="color: #0C2C5A;">Judul Artikel
                                Pilihan Kami</h3>

                            <p class="mb-3 featured-popular-desc" style="color:#4a5a6a; font-size:1.15rem;">Deskripsi
                                singkat yang menginspirasi dan tulus untuk Anda.</p>

                            <p class="text-secondary mb-2 featured-popular-date">Tanggal</p>
                            <a href="#" class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link"
                                style="color: #0C2C5A;">Baca Lebih Lanjut <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="swiper-button-prev featured-popular-prev" style="left:-30px;"></div>
            <div class="swiper-button-next featured-popular-next" style="right:-30px;"></div>
        </div>
    </section>
</div>

{{-- 3. Artikel Terbaru (Slider) --}}
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;">Wawasan Terbaru untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0"
            style="color: #0C2C5A;">Lihat Semua Artikel <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="swiper latest-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($latest_articles as $article)
            <div class="swiper-slide pb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="article-img-container">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                            class="card-img-top">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold" style="color: #0C2C5A;">{{ Str::limit($article->title, 50) }}</h5>
                        <p class="text-secondary small mb-2">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3" style="color:#4a5a6a;">
                            {{ Str::words(strip_tags($article->description), 15, '...') }}</p>
                        <a href="{{ route('front.articles.show', $article->slug) }}"
                            class="mt-auto text-decoration-none fw-semibold" style="color: #0C2C5A;">Baca Lebih
                            Lanjut</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <div class="card border-0 shadow-sm h-100">
                    <div class="article-img-container">
                        <img src="https://via.placeholder.com/600x300.png?text=Artikel+Terbaru" alt="Placeholder"
                            class="card-img-top">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold" style="color: #0C2C5A;">Judul Artikel Terbaru</h5>
                        <p class="text-secondary small mb-2">Tanggal</p>
                        <p class="card-text mb-3" style="color:#4a5a6a;">Deskripsi singkat tentang artikel terbaru ini.
                            Konten yang elegan dan menawan untuk Anda.</p>
                        <a href="#" class="mt-auto text-decoration-none fw-semibold" style="color: #0C2C5A;">Baca Lebih
                            Lanjut</a>
                    </div>
                </div>
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
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;">Artikel Pilihan untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0"
            style="color: #0C2C5A;">Jelajahi Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="swiper latest-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($popular_articles as $article)
            <div class="swiper-slide pb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="article-img-container" style="padding-top: 56.25%;">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                            class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold" style="color: #0C2C5A;">{{ Str::limit($article->title, 50) }}</h5>
                        <p class="text-secondary small mb-2">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3" style="color:#4a5a6a;">
                            {{ Str::words(strip_tags($article->description), 15, '...') }}</p>
                        <a href="{{ route('front.articles.show', $article->slug) }}"
                            class="mt-auto text-decoration-none fw-semibold" style="color: #0C2C5A;">Baca Lebih
                            Lanjut</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <div class="card border-0 shadow-sm h-100">
                    <div class="article-img-container" style="padding-top: 56.25%;">
                        <img src="https://via.placeholder.com/600x338.png?text=Artikel+Populer" alt="Placeholder"
                            class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold" style="color: #0C2C5A;">Judul Artikel Populer</h5>
                        <p class="text-secondary small mb-2">Tanggal</p>
                        <p class="card-text mb-3" style="color:#4a5a6a;">Deskripsi singkat tentang artikel terpopuler
                            ini, sebuah inspirasi yang tulus untuk Anda.</p>
                        <a href="#" class="mt-auto text-decoration-none fw-semibold" style="color: #0C2C5A;">Baca Lebih
                            Lanjut</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-button-next latest-articles-next"></div>
        <div class="swiper-button-prev latest-articles-prev"></div>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('front.articles') }}" class="btn btn-primary px-4 py-2" style="background-color: #0C2C5A; border-color: #0C2C5A;">Lihat Semua Artikel</a>
    </div>
</section>

{{-- 5. Testimoni Section --}}
<section class="py-5 my-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-1 text-center" style="color: #0C2C5A;">Layanan Kami</h2>
        <p class="text-muted mb-0 text-center">Pilihan sesi konseling yang fleksibel dan disesuaikan dengan kebutuhan Anda</p>
    </div>
    <div class="container mt-5">
        <div class="row g-4 justify-content-center">
            @forelse ($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center card-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <i class="bi bi-star-fill fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $service->title }}</h5>
                        <p class="text-muted small">{{ Str::limit($service->short_description, 100) }}</p>
                        <p class="fw-bold fs-4 my-3" style="color: #0C2C5A;">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                        <a href="{{ route('front.layanan') }}" class="btn btn-outline-primary mt-auto stretched-link" style="color:#f8f9fa; background-color: #0C2C5A; border-color: #0C2C5A;">Pilih Paket Ini</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-muted text-center">Belum ada layanan tersedia.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            {{-- Tombol ini tetap solid/penuh --}}
            <a href="{{ route('front.layanan') }}" class="btn btn-primary px-4 py-2" style=" background-color: #0C2C5A; border-color: #0C2C5A;">Lihat Semua Layanan</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Swiper untuk Artikel Terpopuler Pilihan Kami (Hanya satu slide terlihat)
    new Swiper('.featured-popular-article-swiper', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        navigation: {
            nextEl: '.featured-popular-next',
            prevEl: '.featured-popular-prev',
        },
        autoHeight: true,
    });

    // Swiper untuk Artikel Terbaru dan Populer (3 kolom)
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
/* 1. [FINAL REVISION] Hero Section Styles */
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
    background: linear-gradient(90deg, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%);
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
}

.hero-section .carousel-caption .container {
    text-align: left;
}

.hero-title {
    font-size: clamp(2.25rem, 5vw, 3.75rem);
    font-weight: 700;
    line-height: 1.2;
    max-width: 20ch;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    color: rgba(255, 255, 255, 0.85);
    max-width: 55ch;
    margin-bottom: 2.25rem;
}

.hero-button {
    font-size: 1rem;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 8px;
}

.hero-section .carousel-indicators {
    bottom: 1.5rem;
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
    display: none;
}


/* 2. Featured Popular Article Slider Styles */
.featured-popular-article-swiper {
    position: relative;
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

/* 3. Testimonial Styles */
.testimonial-square-card {
    width: 340px;
    height: 340px;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
    position: relative;
    overflow: hidden;
    margin: 0 auto 20px;
    padding-bottom: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.testimonial-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    filter: blur(1px) brightness(0.7);
}

.testimonial-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.25) 60%, rgba(0, 0, 0, 0.45) 100%);
}

.testimonial-content {
    z-index: 3;
    top: auto;
    bottom: 50px;
    left: 50%;
    transform: translate(-50%, 0);
    width: 100%;
}

.testimonial-name {
    font-family: 'Poppins', Arial, sans-serif;
    letter-spacing: 0;
}

.testimonial-job {
    color: #fff;
    opacity: 0.85;
}

.testimonial-text {
    color: #fff;
    font-size: 0.95rem;
    font-style: italic;
    line-height: 1.4;
}

.testimonial-quote-icon {
    position: absolute;
    font-size: 2.2rem;
    color: #212529;
    z-index: 4;
    opacity: 0.7;
    font-family: serif;
}

.testimonial-quote-top-left {
    top: 15px;
    left: 20px;
}

.testimonial-quote-bottom-right {
    bottom: 20px;
    right: 20px;
}

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
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
}

/* 4. [FINAL REVISION] CSS Umum untuk SEMUA Tombol Panah Slider */
.swiper-button-next,
.swiper-button-prev {
    top: 50%;
    transform: translateY(-50%);
    background-color: white;
    width: 44px;
    height: 44px;
    border-radius: 50%;
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

/* 5. Posisi panah untuk slider Featured Popular */
.featured-popular-prev {
    left: -20px;
}

.featured-popular-next {
    right: -20px;
}

.card .article-img-container {
    padding-top: 65.25%;
    position: relative;
    overflow: hidden;
    background-color: #f0f0f0;
}

.card .article-img-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ============== [FINAL REVISION] RESPONSIVE MEDIA QUERIES ============== */

@media (max-width: 992px) {
    .featured-popular-article-swiper .d-flex {
        flex-direction: column !important;
        text-align: center !important;
    }

    .featured-popular-article-swiper .me-lg-5 {
        margin-right: 0 !important;
    }

    .testimonial-square-card {
        width: 90vw;
        max-width: 340px;
        height: 340px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        min-height: 600px;
    }

    .hero-section .overlay {
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 60%);
    }

    .hero-section .carousel-caption {
        align-items: flex-end;
    }

    .hero-section .carousel-caption .container {
        text-align: center;
        padding-bottom: 6rem; /* Naikkan posisi teks dari bawah */
    }

    .hero-title,
    .hero-subtitle {
        margin-l    eft: auto;
        margin-right: auto;
        max-width: 95%; /* Izinkan teks sedikit lebih lebar di mobile */
    }

    .hero-subtitle {
        margin-bottom: 2.5rem;
    }
}
</style>
@endpush