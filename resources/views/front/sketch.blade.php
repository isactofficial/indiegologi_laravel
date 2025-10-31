@extends('layouts.app')

@section('title', 'Painting Telling')

@section('content')
{{-- Iklan --}}
    <x-floating-ads
        topAdImage="assets/img/PROMOTION_WEBSITE.jpg"
        topAdLink="#"
        bottomAdImage="assets/img/KONSULTASI_GRATIS.jpg"
        bottomAdLink="/layanan" />
        
<div class="sketch-telling-section">
    <div class="container">
        {{-- Title and description with fade-down animations --}}
        <h1 class="section-title" data-aos="fade-down">Painting Telling</h1>
        <p class="section-description" data-aos="fade-down" data-aos-delay="150">Lihatlah kisah-kisah yang kami visualisasikan untuk inspirasi dan pemahaman yang
            lebih dalam tentang berbagai perjalanan hidup</p>

    @if($sketches->isEmpty())
    {{-- Message for no paintings with a zoom-in animation --}}
        <div class="text-center" data-aos="zoom-in">
              <p class="lead text-muted">Belum ada painting yang tersedia saat ini.</p>
        </div>
        @else
        <div id="sketch-list-wrapper" class="cards-grid">
            @foreach ($sketches as $sketch)
                {{-- Card gaya homepage --}}
                <a href="{{ route('front.sketches.detail', ['sketch' => $sketch->slug]) }}" class="paint-card" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 150 }}">
                    <p class="sketch-card-header">Painting Telling</p>
                    <div class="sketch-card-middle-content">
                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="sketch-circular-image">
                        <p class="sketch-card-description">{{ Str::limit(strip_tags($sketch->content), 120) }}</p>
                    </div>
                    <div class="sketch-card-brand">
                        <img src="{{ asset('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo">
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination with a fade-up animation --}}
        <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
            {{ $sketches->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
.sketch-telling-section {
    padding: 80px 0;
    text-align: center;
    background-color: #fff;
    padding-top: 120px;
    margin-top: 20px;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: #0C2C5A;
    margin-bottom: 10px;
}
.section-description {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 700px;
    margin: 0 auto 50px auto;
    line-height: 1.6;
}
 .cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    justify-content: center;
    padding-bottom: 50px;
}

/* Kartu bergaya sama seperti homepage */
#sketch-list-wrapper .paint-card {
    position: relative;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    width: 100%;
    min-height: 480px;
    padding: 25px;
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#sketch-list-wrapper .paint-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

#sketch-list-wrapper .sketch-card-header {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #0C2C5A;
    margin: 0 0 16px 0;
    text-align: center;
}

#sketch-list-wrapper .sketch-card-middle-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

#sketch-list-wrapper .sketch-circular-image {
    width: 190px;
    height: 190px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 12px auto 18px auto;
    -webkit-mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0.6) 80%, rgba(0,0,0,0) 88%);
    mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 70%, rgba(0,0,0,0.6) 80%, rgba(0,0,0,0) 88%);
    filter: drop-shadow(0 8px 16px rgba(0,0,0,0.12));
}

#sketch-list-wrapper .sketch-card-description {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #555;
    line-height: 1.6;
    width: 90%;
    margin-top: 10px;
    margin-bottom: 5px;
}

#sketch-list-wrapper .sketch-card-brand {
    margin-top: auto;
    padding-bottom: 10px;
    text-align: center;
}

#sketch-list-wrapper .sketch-card-brand img {
    max-height: 85px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.35));
}

@media (max-width: 992px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .section-title {
        font-size: 2.2rem;
    }
    .section-description {
        font-size: 1rem;
    }
}
@media (max-width: 768px) {
    .cards-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
    .sketch-telling-section {
        padding: 60px 0;
    }
    .section-title {
        font-size: 2rem;
    }
    .section-description {
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
{{-- SCRIPT UNTUK ANIMASI AOS --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        once: false,
        offset: 120,
    });

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
