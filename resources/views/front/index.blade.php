@extends('layouts.app')

@section('title', 'Indiegologi - Homepage')

@push('styles')
{{-- Font dan AOS --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gotham:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
/* CSS UMUM - PERBAIKAN UNTUK MOBILE */
body, html {
    overflow-x: hidden;
}

/* 1. Hero Section Styles */
.hero-section {
    position: relative;
    height: 100vh;
    height: var(--app-height);
    min-height: 600px;
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
    background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.75) 100%);
}
.hero-section .carousel-caption {
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: 100%;
    padding: 0 1rem;
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
    font-family: 'Gotham', sans-serif;
    font-size: clamp(2.2rem, 5.5vw, 3.8rem);
    font-weight: 800;
    line-height: 1.25;
    max-width: 25ch;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
}
.hero-subtitle {
    font-family: 'Gotham', sans-serif;
    font-size: clamp(1rem, 2.5vw, 1.15rem);
    color: rgba(255, 255, 255, 0.95);
    max-width: 55ch;
    margin-bottom: 2.5rem;
    line-height: 1.6;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
}
.hero-button {
    font-size: 1rem;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 5px;
    font-family: 'Playfair Display', serif;
    background-color: #0C2C5A;
    color: #ffffff;
    border: 1px solid #0C2C5A;
    transition: all 0.3s ease-out;
}
.hero-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    background-color: #154280;
    border-color: #154280;
    color: #ffffff;
}
.hero-section .carousel-indicators {
    display: flex !important;
    opacity: 1 !important;
    position: absolute;
    bottom: 3rem;
    left: 50%;
    transform: translateX(-50%);
    padding: 0;
    margin: 0;
    justify-content: center;
    z-index: 15;
    list-style: none;
    width: auto;
}
.hero-section .carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.7);
    opacity: 1;
    margin: 0 5px;
    transition: all 0.3s ease;
}
.hero-section .carousel-indicators .active {
    background-color: #fff;
    border-color: #fff;
    transform: scale(1.2);
}
.hero-section .carousel-control-prev,
.hero-section .carousel-control-next {
    display: none;
}

/* ======================================================= */
/* ===== CSS UNTUK ANIMASI FLIP PADA TESTIMONI ===== */
/* ======================================================= */
.testimonial-flip-container {
    perspective: 1000px;
    cursor: pointer;
    width: 100%;
    max-width: 350px;
    height: 380px;
    margin: 0 auto;
    -webkit-tap-highlight-color: transparent;
}
.testimonial-flipper {
    transition: transform 0.6s, box-shadow 0.3s;
    transform-style: preserve-3d;
    position: relative;
    width: 100%;
    height: 100%;
}
.testimonial-flip-container.is-flipped .testimonial-flipper {
    transform: rotateY(180deg);
}
.testimonial-card-front,
.testimonial-card-back {
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.testimonial-card-front {
    z-index: 2;
    transform: rotateY(0deg);
}
.testimonial-card-back {
    transform: rotateY(180deg);
    background-color: #0C2C5A;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
}

/* TESTIMONI STYLES */
.testimonial-card-link {
    display: block;
    color: inherit;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 10px;
}
.testimonial-card-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.testimonial-square-card {
    width: 100%;
    max-width: 350px;
    height: 380px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}
img.testimonial-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 10%;
    z-index: 1;
    filter: brightness(0.7);
    transition: transform 0.4s ease;
}
.testimonial-card-link:hover img.testimonial-bg-img {
    transform: scale(1.05);
}
.testimonial-square-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 40%, transparent 60%);
    border-radius: 10px;
}
.testimonial-content {
    position: relative;
    z-index: 3;
    font-family: 'Gotham', sans-serif;
    padding: 25px;
    color: #fff;
    text-align: left;
}
.testimonial-quote {
    font-size: 0.95rem;
    font-weight: 400;
    line-height: 1.6;
    margin-bottom: 1rem;
    font-style: italic;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 150px;
}
.testimonial-author {
    border-top: 1px solid rgba(255,255,255,0.3);
    padding-top: 1rem;
}
.testimonial-name {
    font-family: 'Gotham', sans-serif;
    font-weight: 700;
    font-size: 1.2rem;
    margin: 0;
}
.testimonial-details {
    font-family: 'Gotham', sans-serif;
    font-size: 0.85rem;
    opacity: 0.8;
    margin: 0;
}

/* MODAL TESTIMONI */
#testimonialModal .modal-content {
    background-color: #f8f9fa;
    border-radius: 10px;
}
#testimonialModal .modal-header {
    border-bottom: none;
}
#testimonialModal .modal-body {
    padding: 1.5rem;
}
#testimonialModal img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 1rem;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
#testimonialModal h5 {
    font-family: 'Gotham', sans-serif;
    font-weight: 700;
}
#testimonialModal p {
    font-family: 'Gotham', sans-serif;
}
#testimonialModal .quote-full {
    font-style: italic;
    color: #343a40;
    line-height: 1.7;
    white-space: pre-wrap;
}

/* ARTIKEL STYLES */
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

/* Efek hover zoom untuk kartu */
.card-hover-zoom {
    transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
}

.card-hover-zoom:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
}

.latest-articles-swiper{
    padding: 2rem 1rem;
}

.popular-articles-swiper{
    padding: 2rem 1rem;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.5em;
}

/* =================================================================== */
/* ===== CSS SKETCH TELLING GALLERY - ANTI KONFLIK ===== */
/* =================================================================== */
#sketch-gallery-wrapper .carousel-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
}
#sketch-gallery-wrapper .carousel-title {
    font-size: 2.5rem;
    margin-bottom: 8px;
    font-weight: 800;
    color: #0C2C5A;
    font-family: 'Gotham', sans-serif;
}
#sketch-gallery-wrapper .carousel-subtitle {
    color: #6c757d;
    margin-bottom: 30px;
    font-size: 1.15rem;
    font-family: 'Playfair Display', serif;
}
#sketch-gallery-wrapper .gallery-carousel {
    padding-top: 30px;
    position: relative;
    height: 480px;
    perspective: 1000px;
    transform-style: preserve-3d;
}
#sketch-gallery-wrapper .gallery-carousel-images {
    position: relative;
    height: 100%;
    width: 100%;
    transform-style: preserve-3d;
}
#sketch-gallery-wrapper .gallery-image-item {
    position: absolute !important;
    display: block !important;
    visibility: visible !important;
    width: 50%;
    height: 480px;
    left: 25%;
    padding: 10px;
    background-color: white;
    transition: all 0.5s ease;
    cursor: pointer;
    transform-style: preserve-3d;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
}
#sketch-gallery-wrapper .gallery-image-item:hover {
    text-decoration: none;
    color: inherit;
}
#sketch-gallery-wrapper .gallery-image-item img {
    width: 100%;
    height: calc(100% - 50px);
    object-fit: cover;
    border-radius: 10px;
}
#sketch-gallery-wrapper .gallery-image-item h1 {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: white;
    color: #0C2C5A;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    margin: 0;
    font-family: 'Gotham', sans-serif;
}
#sketch-gallery-wrapper .gallery-image-item.active {
    z-index: 10;
    opacity: 1;
    transform: translateX(0) scale(1);
}
#sketch-gallery-wrapper .gallery-image-item.prev {
    z-index: 5;
    opacity: 1;
    transform: translateX(-20%) scale(0.85);
}
#sketch-gallery-wrapper .gallery-image-item.next {
    z-index: 5;
    opacity: 1;
    transform: translateX(20%) scale(0.85);
}
#sketch-gallery-wrapper .gallery-image-item.prev-hidden {
    z-index: 4;
    opacity: 1;
    transform: translateX(-40%) scale(0.7);
}
#sketch-gallery-wrapper .gallery-image-item.next-hidden {
    z-index: 4;
    opacity: 1;
    transform: translateX(40%) scale(0.7);
}
#sketch-gallery-wrapper .gallery-image-item.hidden {
    opacity: 0;
    transform: translateX(-200%) scale(0.7);
}
#sketch-gallery-wrapper .gallery-nav-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: #fff;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    color: #0C2C5A;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}
#sketch-gallery-wrapper .gallery-nav-button:hover {
    background: white;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
}
#sketch-gallery-wrapper .gallery-nav-left {
    left: 7%;
}
#sketch-gallery-wrapper .gallery-nav-right {
    right: 7%;
}

/* RESPONSIVE */
@media (min-width: 768px) {
    #testimonialModal .modal-dialog {
        max-width: 600px;
    }
    #testimonialModal .modal-body {
        padding: 2.5rem;
    }
    #testimonialModal img {
        width: 140px;
        height: 140px;
        margin-bottom: 1.5rem;
    }
}

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
    .hero-title {
        font-size: clamp(1.8rem, 8vw, 2.8rem);
        max-width: 20ch;
        line-height: 1.3;
    }
    .hero-subtitle {
        font-size: clamp(0.9rem, 3.5vw, 1.05rem);
        max-width: 45ch;
        line-height: 1.5;
        margin-bottom: 2rem;
    }
    .hero-button {
        padding: 0.6rem 1.75rem;
        font-size: 0.9rem;
    }
    .hero-section .carousel-indicators {
        bottom: 2.5rem;
    }
    .featured-popular-card {
        padding: 24px;
        min-height: auto;
    }
    .featured-popular-title {
        font-size: 1.8rem;
    }
    .featured-popular-desc {
        font-size: 1rem;
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
    h2.fw-bold.mb-3, h2.fw-bold.mb-0 {
        font-size: 1.8rem !important;
    }
    p.text-center.mb-5, p.lead.text-muted {
        font-size: 0.95rem !important;
    }

    /* Sketch Gallery Mobile */
    #sketch-gallery-wrapper .gallery-carousel {
        height: 350px;
    }
    #sketch-gallery-wrapper .gallery-image-item {
        height: 350px;
        width: 60%;
        left: 20%;
    }
    #sketch-gallery-wrapper .gallery-nav-left {
        left: 2%;
    }
    #sketch-gallery-wrapper .gallery-nav-right {
        right: 2%;
    }
}
</style>
@endpush

@section('content')

{{-- 1. Hero Section --}}
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('{{ asset('assets/carousel/christina-wocintechchat-com-eF7HN40WbAQ-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">Apa itu Indiegologi?</h1>
                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                            Ruang untuk dapat lebih mengerti tentang gambaran diri, <br>
                            yang sering tidak kita sadari dikarenakan banyaknya distraksi <br>
                            serta ambisi diri yang membuat kita kurang memahami kebutuhan diri
                        </p>
                        <a href="{{ route('front.layanan') }}" class="btn hero-button" data-aos="fade-up" data-aos-delay="300">Mulai Perjalanan Anda</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/kobu-agency-7okkFhxrxNw-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Tumbuh Kembang Anak</h1>
                        <p class="hero-subtitle">
                            Buah hati bagaikan bayangan dari pantulan cermin disekitarnya<br>
                            Sudahkah kita memahami pantulan dari cermin apa?<br>
                            Mengenali jenis cermin yang mereka gunakan akan<br>
                            dapat mengerti bagaimana wujud bayangan yang dihasilkan.
                        </p>
                        <a href="{{ route('front.sketch') }}" class="btn hero-button">Kenali Potensi Anak Anda</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/maranda-vandergriff-7aakZdIl4vg-unsplash.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Layanan HRD</h1>
                        <p class="hero-subtitle">
                            Ilmu mengenai psikologi saat ini sangat mudah untuk diakses,<br>
                            sehingga banyak calon karyawan dengan mudah lulus rekrutmen.<br>
                            Apakah perusahaanmu salah satu yang pernah terkecoh?<br>
                            Tertarik mencoba dengan metode metafisika atau <i>sixsence</i> yang digabungkan dengan disiplin ilmu psikologi?<br>
                            Analisis berdasarkan <i>sixsence</i> merupakan keahlian kami<br>
                            dalam rekrutmen maupun pengembangan human resource.
                        </p>
                        <a href="{{ route('front.articles') }}" class="btn hero-button">Optimalkan Tim Anda</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/1-2-300x214.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Personal konseling</h1>
                        <p class="hero-subtitle">
                            Alur hidup yang dilalui oleh setiap individu akan menciptakan keunikan potensi.<br>
                            Memahami alam bawah sadar yang terbentuk oleh alur hidup<br>
                            akan dapat merubah <i>negative behaviour</i> dalam diri<br>
                            untuk menjadi potensi unik yang juga merupakan <i>value</i> diri.
                        </p>
                        <a href="{{ route('front.articles') }}" class="btn hero-button">Temukan Potensi Diri Anda</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('assets/carousel/2-6-300x214.jpg') }}');">
                <div class="overlay"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="hero-title">Konsultasi properti</h1>
                        <p class="hero-subtitle">
                            Beberapa Agama dan budaya mengajarkan hal mengenai properti<br>
                            berhati - hati dalam memiliki properti sebagai aset diri<br>
                            karena bisa jadi properti yang kita akan miliki berenergi negatif<br>
                            Ubah keberuntungan bisa melalui properti, tertarik?
                        </p>
                        <a href="{{ route('front.articles') }}" class="btn hero-button">Konsultasi Properti Anda</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
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

{{-- 2. Artikel Pilihan --}}
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
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center bg-white shadow-sm border border-light featured-popular-card">
                        <div class="flex-shrink-0 mb-4 mb-lg-0 me-lg-5 featured-popular-img-wrap">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail Artikel Populer" class="img-fluid featured-popular-img">
                        </div>
                        <div class="flex-grow-1 text-lg-start text-center featured-popular-content">
                            <h3 class="fw-bold mb-3 featured-popular-title line-clamp-2" style="color: #0C2C5A;">{{ $article->title }}</h3>
                            <p class="mb-3 featured-popular-desc line-clamp-3" style="color:#4a5a6a; font-size:1.15rem;">{{ strip_tags($article->description) }}</p>
                            <p class="text-secondary mb-4 featured-popular-date" style="font-family: 'Playfair Display', sans-serif; font-size: 0.9rem;">{{ optional($article->created_at)->format('d F Y') }}</p>
                            <a href="{{ route('front.articles.show', $article->slug) }}" class="btn btn-link text-decoration-none fw-semibold p-0 featured-popular-link stretched-link" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut <i class="bi bi-arrow-right"></i></a>
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

{{-- 3. Artikel Terbaru --}}
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;" data-aos="fade-right" data-aos-duration="800">Wawasan Terbaru untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;" data-aos="fade-left" data-aos-duration="800">Lihat Semua Artikel<i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="swiper latest-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($latest_articles as $article)
            <div class="swiper-slide pb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                <div class="card border-0 shadow-sm h-100 card-hover-zoom">
                    <div class="article-img-container">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold line-clamp-2" style="color: #0C2C5A;">{{ $article->title }}</h5>
                        <p class="text-secondary small mb-2" style="font-family: 'Playfair Display', sans-serif;">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3 line-clamp-2" style="color:#4a5a6a; font-family: 'Playfair Display', sans-serif;">{{ strip_tags($article->description) }}</p>
                        <a href="{{ route('front.articles.show', $article->slug) }}" class="mt-auto text-decoration-none fw-semibold stretched-link" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut</a>
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

{{-- 4. Artikel Populer --}}
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #0C2C5A;" data-aos="fade-right" data-aos-duration="800">Artikel Pilihan untuk Anda</h2>
        <a href="{{ route('front.articles') }}" class="btn btn-link text-decoration-none fw-semibold p-0" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;" data-aos="fade-left" data-aos-duration="800">Jelajahi Semua<i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="swiper popular-articles-swiper">
        <div class="swiper-wrapper">
            @forelse ($popular_articles as $article)
            <div class="swiper-slide pb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                <div class="card border-0 shadow-sm h-100 card-hover-zoom">
                    <div class="article-img-container" style="padding-top: 56.25%;">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="card-img-top">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold line-clamp-2" style="color: #0C2C5A;">{{ $article->title }}</h5>
                        <p class="text-secondary small mb-2" style="font-family: 'Playfair Display', sans-serif;">{{ optional($article->created_at)->format('d F Y') }}</p>
                        <p class="card-text mb-3 line-clamp-2" style="color:#4a5a6a; font-family: 'Playfair Display', sans-serif;">{{ strip_tags($article->description) }}</p>
                        <a href="{{ route('front.articles.show', $article->slug) }}" class="mt-auto text-decoration-none fw-semibold stretched-link" style="color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Baca Lebih Lanjut</a>
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
@php
$testimonials = [
    [
        'name' => 'Wira',
        'age' => 27,
        'occupation' => 'Public university staff',
        'quote' => "As a person who's currently entering my 20's, I have to know myself more and discover my best potential. With \"indiegologi\", I can evolve to a better version of myself. With their sketch, I'm stongly reminded of who I was at that moment and what traits of me that can be developed more to reach my best potential. This helps me to achieve more in life as I grow to be the best version of me. Thank you, indiegologi!",
        'image' => 'assets/testimoni/Wira.jpeg'
    ],
    [
        'name' => 'Vika Matin',
        'age' => 32,
        'occupation' => 'Entrepreneur',
        'quote' => 'Bingung banget punya anak 2 deketan usia dengan segala dramanya tapi sulit nemu solusi tiap masalahnya. Takut salah treatment ke anak, karena setiap anak itu berbeda. Enak sama indiegologi ga perlu cerita. Tinggal pasang muka dia dah beberin semua hal yg kita bahkan ga sadar. thanks mas. You become a solution.',
        'image' => 'assets/testimoni/Vika.jpg'
    ],
    [
        'name' => 'Oghie',
        'age' => 35,
        'occupation' => 'Construction Business Owner',
        'quote' => 'Sebagai owner ada aja hal teknis yang sebenernya gampang tapi sulit ditentukan. Jadi kami punya peluang untuk mengisi jabatan strategis malah bimbang untuk milih siapa yang pas pada posisi tersebut. Alhamdulillah ketemu juga solusinya tidak puyeng karena pasti ada efeknya jika menempatkan orang yang mungkin pas tapi hasilnya zonk. Makasih loh udah di filter karyawan ku. Jadi gak bimbang naro orang lagi ini.',
        'image' => 'assets/testimoni/Ophie.jpeg'
    ],
    [
        'name' => 'Dean',
        'age' => 36,
        'occupation' => 'Architect',
        'quote' => 'I consult with indiego about many things, ranging from projects, family, friendships, even finances. The counseling is very helpful in making decisions, even though the sharing method is unique to me. We tell a little story and they immediately respond with real time character sketches, what kind of face are we "presenting" which is our condition at that time that will be examined. For those who are curious, please try it, you will be surprised with a "how come he knows" respond and they will help to find a solution. Good luck for "indiegologi" through "cerita indiegologi".',
        'image' => 'assets/testimoni/Dean.jpg'
    ],
    [
        'name' => 'Dienar',
        'age' => 32,
        'occupation' => 'Entrepreneur',
        'quote' => 'Konseling sama indiegologi itu enak banget, konseling tapi rasanya kaya curhat ke temen. Pendengar yg sangat baik, solving problem nya tidak menggurui, saran yang diberikan praktikal semua , bukan cuma sekedar teori yg bikin kita bingung harus mulai dari mana . Rasanya nyaman, karena tidak ada judgement, pendekatannya sesuai dengan karakter kita. Dan gak terburu-buru. Selalu menguatkan, bahwa ini semua adalah proses. Thanks a lot Mas, banyak hal yang udah di sharingkan ke aku, dan semuanya ngena banget.',
        'image' => 'assets/testimoni/Dienar 1.jpeg'
    ],
    [
        'name' => 'Dhiana',
        'age' => 42,
        'occupation' => 'HR & GA manager',
        'quote' => 'I am amazed, they can define our personal strengths and weaknesses from facial sketches. So its easier to develop and focus on the abilities that we have, the gifts from God. Great talent!',
        'image' => 'assets/testimoni/Dhiana 1.jpeg'
    ],
];
@endphp
<section class="container py-5 my-5">
    <div class="text-center" data-aos="fade-up">
        <h2 class="fw-bold mb-3" style="color: #0C2C5A; font-size:2.3rem;">Kisah Testimoni dari Keluarga Indiegologi</h2>
        <p style="font-size:1.15rem; color:#6c757d; font-family: 'Playfair Display', sans-serif;">Kumpulan cerita tulus dari mereka yang telah berproses dengan indielogi untuk menemukan kedamaian,kebahagiaan dan cinta diri</p>
    </div>
    <div class="swiper testimonials-swiper mt-5">
        <div class="swiper-wrapper py-4">
            @foreach ($testimonials as $testi)
            <div class="swiper-slide" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="testimonial-flip-container"
                   data-name="{{ $testi['name'] }}"
                   data-details="{{ $testi['age'] }} Tahun, {{ $testi['occupation'] }}"
                   data-quote="{{ $testi['quote'] }}"
                   data-image="{{ asset($testi['image']) }}">
                    <div class="testimonial-flipper">
                        {{-- SISI DEPAN KARTU --}}
                        <div class="testimonial-card-front">
                            <div class="testimonial-square-card">
                                <img src="{{ asset($testi['image']) }}" alt="Foto {{ $testi['name'] }}" class="testimonial-bg-img">
                                <div class="testimonial-content">
                                    <p class="testimonial-quote">"{{ $testi['quote'] }}"</p>
                                    <div class="testimonial-author">
                                        <h5 class="testimonial-name">{{ $testi['name'] }}</h5>
                                        <p class="testimonial-details">{{ $testi['age'] }} Tahun, {{ $testi['occupation'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- SISI BELAKANG KARTU --}}
                        <div class="testimonial-card-back">
                            <i class="bi bi-chat-quote-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-button-next testimonials-next"></div>
        <div class="swiper-button-prev testimonials-prev"></div>
    </div>
</section>

{{-- 6. SKETCH TELLING (STYLE KERSA) --}}
<section id="sketch-gallery-wrapper" class="container py-5 my-5">
    <div class="carousel-container">
        <h2 class="carousel-title" data-aos="fade-down">Sketch Telling</h2>
        <p class="carousel-subtitle">Lihatlah kisah-kisah yang kami visualisasikan</p>
        <div class="gallery-carousel" data-aos="fade-up">
            <button class="gallery-nav-button gallery-nav-left">&#10094;</button>
            <div class="gallery-carousel-images" data-aos="zoom-in" data-aos-duration="1000">
                @forelse ($latest_sketches as $sketch)
                <a href="{{ route('front.sketches.detail', $sketch->slug) }}" class="gallery-image-item">
                    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" />
                    <h1>{{ Str::limit($sketch->title, 30) }}</h1>
                </a>
                @empty
                <div class="d-flex justify-content-center align-items-center h-100">
                    <p class="text-center text-muted">Belum ada sketsa.</p>
                </div>
                @endforelse
            </div>
            <button class="gallery-nav-button gallery-nav-right">&#10095;</button>
        </div>
    </div>
    <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="300">
        <a href="{{ route('front.sketch') }}" class="btn btn-primary px-4 py-2" style="background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Lihat Semua Sketsa</a>
    </div>
</section>

{{-- 7. Layanan Unggulan --}}
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
            <a href="{{ route('front.layanan') }}" class="btn btn-primary px-4 py-2" style="background-color: #0C2C5A; border-color: #0C2C5A; font-family: 'Playfair Display', sans-serif;">Lihat Semua Layanan</a>
        </div>
    </div>
</section>

{{-- Modal Testimoni --}}
<div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" id="modal-image" alt="Foto Testimoni">
                <h5 class="modal-title" id="modal-name">Nama Klien</h5>
                <p id="modal-details" class="text-muted mb-3">Detail Klien</p>
                <p id="modal-quote" class="quote-full">Isi testimoni lengkap akan muncul di sini.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Swiper.js Configurations --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        new Swiper('.testimonials-swiper', {
            loop: true,
            navigation: {
                nextEl: '.testimonials-next',
                prevEl: '.testimonials-prev',
            },
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                768: { slidesPerView: 2, spaceBetween: 30 },
                1024: { slidesPerView: 3, spaceBetween: 40 },
            }
        });
    });
</script>

{{-- AOS Animation --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

{{-- Sketch Gallery JavaScript (Style Kersa) --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const galleryWrapper = document.getElementById("sketch-gallery-wrapper");
    if (galleryWrapper) {
        const images = galleryWrapper.querySelectorAll(".gallery-image-item");
        const leftButton = galleryWrapper.querySelector(".gallery-nav-left");
        const rightButton = galleryWrapper.querySelector(".gallery-nav-right");
        const carousel = galleryWrapper.querySelector(".gallery-carousel");

        if (images.length === 0 || !leftButton || !rightButton) {
            return;
        }

        let currentIndex = 0;
        let autoSlideInterval;

        function updateClasses() {
            images.forEach((image, index) => {
                image.classList.remove("active", "prev", "next", "prev-hidden", "next-hidden", "hidden");

                if (index === currentIndex) {
                    image.classList.add("active");
                } else if (index === (currentIndex - 1 + images.length) % images.length) {
                    image.classList.add("prev");
                } else if (index === (currentIndex + 1) % images.length) {
                    image.classList.add("next");
                } else if (index === (currentIndex - 2 + images.length) % images.length) {
                    image.classList.add("prev-hidden");
                } else if (index === (currentIndex + 2) % images.length) {
                    image.classList.add("next-hidden");
                } else {
                    image.classList.add("hidden");
                }
            });
        }

        function playSlide() {
            currentIndex = (currentIndex + 1) % images.length;
            updateClasses();
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(playSlide, 5000);
        }

        rightButton.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % images.length;
            updateClasses();
            resetAutoSlide();
        });

        leftButton.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateClasses();
            resetAutoSlide();
        });

        if (carousel) {
            carousel.addEventListener("mouseenter", () => clearInterval(autoSlideInterval));
            carousel.addEventListener("mouseleave", () => resetAutoSlide());
        }

        // Initialize
        updateClasses();
        resetAutoSlide();
    }
});
</script>

{{-- Custom Scripts --}}
<script>
    // App height untuk mobile
    const setAppHeight = () => {
        const doc = document.documentElement;
        doc.style.setProperty('--app-height', `${window.innerHeight}px`);
    };
    window.addEventListener('resize', setAppHeight);
    setAppHeight();

    // Modal testimoni dengan flip animation
    const testimonialModal = document.getElementById('testimonialModal');

    if (testimonialModal) {
        const modalInstance = new bootstrap.Modal(testimonialModal);
        const testimonialCards = document.querySelectorAll('.testimonial-flip-container');

        testimonialCards.forEach(card => {
            card.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                // 1. Tambahkan kelas 'is-flipped' untuk memicu animasi CSS
                this.classList.add('is-flipped');

                // 2. Ambil data dari atribut data-* kartu yang diklik
                const name = this.getAttribute('data-name');
                const details = this.getAttribute('data-details');
                const quote = this.getAttribute('data-quote');
                const image = this.getAttribute('data-image');

                // 3. Tunggu animasi flip selesai (600ms), baru tampilkan modal
                setTimeout(() => {
                    // Update konten di dalam modal dengan data yang baru diambil
                    const modalImage = testimonialModal.querySelector('#modal-image');
                    const modalName = testimonialModal.querySelector('#modal-name');
                    const modalDetails = testimonialModal.querySelector('#modal-details');
                    const modalQuote = testimonialModal.querySelector('#modal-quote');

                    if (modalImage) modalImage.src = image;
                    if (modalName) modalName.textContent = name;
                    if (modalDetails) modalDetails.textContent = details;
                    if (modalQuote) modalQuote.textContent = `"${quote}"`;

                    // Tampilkan modal
                    modalInstance.show();
                }, 600); // Durasi sama dengan transition pada .testimonial-flipper di CSS
            });
        });

        // 4. Tambahkan event listener untuk membalikkan kartu saat modal ditutup
        testimonialModal.addEventListener('hidden.bs.modal', () => {
            // Cari kartu yang sedang dalam keadaan 'flipped' dan kembalikan
            const flippedCard = document.querySelector('.testimonial-flip-container.is-flipped');
            if (flippedCard) {
                flippedCard.classList.remove('is-flipped');
            }
        });
    }

    // AOS initialization
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        once: false,
        offset: 120,
    });

    // AOS scroll behavior
    let lastScrollTop = 0;
    const allAosElements = document.querySelectorAll('[data-aos]');
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop < lastScrollTop) {
            allAosElements.forEach(function(element) {
                if (element.getBoundingClientRect().top > window.innerHeight) {
                    element.classList.remove('aos-animate');
                }
            });
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
</script>
@endpush
