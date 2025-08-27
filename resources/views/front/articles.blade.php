@extends('layouts.app')

@section('content')

{{-- The main container now has a fade-in animation --}}
<div class="container py-5 mt-5 pt-lg-0" data-aos="fade-in">
    {{-- Title and paragraph with fade-down animations --}}
    <h2 class="fw-bold mb-4 text-center section-title-articles" data-aos="fade-down">Wawasan & Inspirasi Terbaru dari Indiegologi</h2>
    <p class="text-center mb-5 text-muted" data-aos="fade-down" data-aos-delay="150">Jelajahi kumpulan artikel inspiratif dan informatif yang kami kurasi, dirancang untuk mendukung perjalanan kesejahteraan dan pertumbuhan pribadi Anda.</p>

    {{-- Filter dropdown with a fade-left animation --}}
    <div class="mb-4 d-flex justify-content-end" data-aos="fade-left" data-aos-delay="300">
        <form action="{{ route('front.articles') }}" method="GET">
            <select name="filter" class="form-select border-primary-custom text-primary-custom" onchange="this.form.submit()">
                <option value="">Urutkan</option>
                <option value="latest" {{ request('filter') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="popular" {{ request('filter') == 'popular' ? 'selected' : '' }}>Populer</option>
                <option value="author" {{ request('filter') == 'author' ? 'selected' : '' }}>Penulis</option>
            </select>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 g-md-4">
        @forelse($articles as $article)
            {{-- Each article card has a fade-up animation with a staggered delay --}}
            <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 150 }}">
                <a href="{{ route('front.articles.show', $article->slug) }}" class="article-link">
                    <div class="card h-100 border-0 shadow-sm card-hover-zoom overflow-hidden">
                        <div class="ratio ratio-4x3 article-thumbnail-wrapper">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top object-fit-cover article-thumbnail" alt="{{ $article->title }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold title-text text-primary-custom mb-1">{{ Str::words(strip_tags($article->title), 8, '...') }}</h5>
                            <p class="card-text description-text text-muted mb-2">{{ Str::words(strip_tags($article->description), 15, '...') }}</p>
                            <small class="text-secondary mt-auto">{{ optional($article->created_at)->format('d F Y') }}</small>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12" data-aos="zoom-in">
                <p class="text-center text-primary-custom">Tidak ada artikel yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination with a fade-up animation --}}
    <div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
        {{ $articles->links() }}
    </div>

</div>

@endsection

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --indiegologi-primary: #0C2C5A;
        --indiegologi-secondary: #f8f9fa;
        --indiegologi-dark: #212529;
        --indiegologi-muted: #6c757d;
    }
    body {
        font-family: 'Playfair Display', sans-serif;
        color: var(--indiegologi-dark);
        background-color: var(--indiegologi-secondary);
    }
    a {
        text-decoration: none;
        color: var(--indiegologi-primary);
    }
    .text-primary-custom { color: var(--indiegologi-primary) !important; }
    .text-muted { color: var(--indiegologi-muted) !important; }
    .text-secondary { color: var(--indiegologi-muted) !important; }
    .section-title-articles {
        font-family: 'Playfair Display', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        color: var(--indiegologi-primary);
        margin-top: 8rem;
        position: relative;
        padding-bottom: 15px;
    }
    .section-title-articles::after {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 0;
        width: 80px;
        height: 5px;
        background-color: var(--indiegologi-primary);
        border-radius: 2px;
    }
    .form-select {
        width: 200px;
        display: inline-block;
        border: 1px solid var(--indiegologi-primary);
        color: var(--indiegologi-primary);
        font-weight: 500;
    }
    .form-select:focus {
        border-color: var(--indiegologi-primary);
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }
    .article-link {
        display: block;
    }
    .card {
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .card-hover-zoom {
        transition: transform 0.4s ease, box-shadow 0.3s ease;
    }
    .card-hover-zoom:hover {
        transform: scale(1.03);
        z-index: 2;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    .article-thumbnail-wrapper {
        background-color: var(--indiegologi-primary);
    }
    .article-thumbnail {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    .card-body { padding: 1.25rem; }
    .card-title {
        font-family: 'Playfair Display', sans-serif;
        font-weight: 600;
        color: var(--indiegologi-primary);
    }
    .card-text.description-text { color: var(--indiegologi-muted); }
    .title-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.5rem;
    }
    .description-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0;
    }
    .pagination {
        list-style: none;
        display: flex;
        justify-content: center;
        padding: 0;
        margin: 0;
        --pagination-link-color: var(--indiegologi-primary);
        --pagination-active-bg: var(--indiegologi-primary);
        --pagination-active-color: white;
        --pagination-hover-bg: rgba(12, 44, 90, 0.8);
        --pagination-hover-color: white;
        --pagination-border-color: var(--indiegologi-primary);
        --pagination-disabled-color: rgba(12, 44, 90, 0.4);
        --pagination-disabled-border: rgba(12, 44, 90, 0.2);
    }
    .pagination .page-item { margin: 0 5px; }
    .pagination .page-link {
        border-radius: 8px;
        padding: 10px 15px;
        min-width: 40px;
        text-align: center;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        color: var(--pagination-link-color);
        background-color: transparent;
        border: 1px solid var(--pagination-border-color);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--pagination-active-bg);
        border-color: var(--pagination-active-bg);
        color: var(--pagination-active-color);
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
    }
    .pagination .page-link:hover:not(.active) {
        background-color: var(--pagination-hover-bg);
        border-color: var(--pagination-hover-bg);
        color: var(--pagination-hover-color);
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .pagination .page-item.disabled .page-link {
        color: var(--pagination-disabled-color);
        pointer-events: none;
        border-color: var(--pagination-disabled-border);
        background-color: transparent;
        transform: none;
        box-shadow: none;
    }
    @media (max-width: 767.98px) {
        .section-title-articles { font-size: 1.8rem; margin-top: 5rem; }
        .section-title-articles::after { width: 60px; height: 3px; }
        .title-text { font-size: 16px; -webkit-line-clamp: 3; }
        .description-text { -webkit-line-clamp: 2; font-size: 12px; }
        .form-select { width: 100%; }
        .d-flex.justify-content-end { justify-content: center !important; }
    }
    @media (max-width: 575.98px) {
        .hero-section-articles { padding-bottom: 2rem; }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    // Inisialisasi AOS
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        // once harus false agar kita bisa memanipulasi animasinya
        once: false,
        offset: 120,
    });

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

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
</script>
@endpush
