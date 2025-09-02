@extends('layouts.app')

@section('title', 'Hasil Pencarian untuk "' . e($query) . '"')

@push('styles')
<style>
    /* Desain & Brand Identity */
    .search-results-header {
        background-color: #fff;
        padding: 4rem 0;
        text-align: center;
    }
    .search-results-header h1 {
        font-weight: 800;
        color: var(--indiegologi-primary);
        font-family: 'Playfair Display', serif;
    }
    .search-results-header .lead {
        font-family: 'Poppins', sans-serif;
        color: #555;
    }
    .search-query {
        color: var(--indiegologi-primary);
        font-weight: 600;
    }

    .results-container {
        background-color: #f8f9fa;
        min-height: 50vh;
    }

    .result-category-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--indiegologi-dark);
        border-bottom: 3px solid var(--indiegologi-primary);
        display: inline-block;
        padding-bottom: 0.5rem;
        margin-bottom: 2.5rem;
    }

    /* Efek Hover "Timbul" */
    .result-card {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        background-color: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid transparent;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), 
                    box-shadow 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), 
                    border-color 0.3s ease;
        
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .result-card:hover {
        box-shadow: 0 5px 15px rgba(12, 44, 90, 0.08), 0 15px 40px rgba(12, 44, 90, 0.1);
        transform: translateY(-8px) scale(1.02); 
        border-color: rgba(12, 44, 90, 0.5);
    }

    .result-card-thumbnail {
        flex-shrink: 0;
        width: 150px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 1.5rem;
    }

    .result-card-body h5 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }
    .result-card-body h5 a {
        text-decoration: none;
        color: var(--indiegologi-dark);
        transition: color 0.3s ease;
    }
    .result-card-body h5 a:hover {
        color: var(--indiegologi-primary);
    }

    .result-card-body p {
        font-family: 'Poppins', sans-serif;
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .no-results-container {
        text-align: center;
        padding: 3rem 1rem 5rem 1rem;
        font-family: 'Poppins', sans-serif;
    }
    .no-results-container .lottie-animation {
        width: 250px;
        height: 250px;
        margin: 0 auto;
    }
    .no-results-container h3 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--indiegologi-dark);
        margin-top: 1rem;
    }
    
    @media (max-width: 767px) {
        .result-card {
            flex-direction: column;
        }
        .result-card-thumbnail {
            width: 100%;
            height: 180px;
            margin-right: 0;
            margin-bottom: 1.5rem;
        }
        .no-results-container .lottie-animation {
            width: 200px;
            height: 200px;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

</style>
@endpush

@section('content')
<div class="search-results-header">
    <div class="container">
        <h1 class="display-5">Hasil Pencarian</h1>
        <p class="lead mb-0">Menampilkan hasil untuk: <span class="search-query">"{{ e($query) }}"</span></p>
    </div>
</div>

<div class="container py-5 mt-4 results-container">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            @if($articles->isEmpty() && $sketches->isEmpty() && $services->isEmpty())
                <div class="no-results-container">
                    <lottie-player 
                        src="https://assets5.lottiefiles.com/packages/lf20_tmsiddoc.json" 
                        background="transparent" 
                        speed="1" 
                        class="lottie-animation"
                        loop 
                        autoplay>
                    </lottie-player>
                    <h3 class="mt-4">Konten Tidak Ditemukan</h3>
                    <p class="text-muted">Maaf, kami tidak menemukan konten yang cocok dengan pencarian Anda. <br> Coba gunakan kata kunci yang berbeda.</p>
                </div>
            @else
                
                {{-- Hasil dari Berita (Articles) --}}
                @unless ($articles->isEmpty())
                    <div class="mb-5">
                        <h2 class="result-category-title">Berita yang Ditemukan ({{ $articles->count() }})</h2>
                        <div class="results-list">
                            @foreach ($articles as $article)
                                <div class="result-card">
                                    <img src="{{ asset('storage/' . $article->getRawOriginal('thumbnail')) }}" alt="{{ strip_tags($article->getRawOriginal('title')) }}" class="result-card-thumbnail">
                                    <div class="result-card-body">
                                        <h5>
                                            <a href="{{ route('front.articles.show', $article->getRawOriginal('slug')) }}">{!! $article->title !!}</a>
                                        </h5>
                                        <p>{!! $article->description !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endunless

                {{-- Hasil dari Sketsa (Sketches) --}}
                @unless ($sketches->isEmpty())
                    <div class="mb-5">
                        <h2 class="result-category-title">Sketsa yang Ditemukan ({{ $sketches->count() }})</h2>
                        <div class="results-list">
                            @foreach ($sketches as $sketch)
                                <div class="result-card">
                                    <img src="{{ asset('storage/' . $sketch->getRawOriginal('thumbnail')) }}" alt="{{ strip_tags($sketch->getRawOriginal('title')) }}" class="result-card-thumbnail">
                                    <div class="result-card-body">
                                        <h5>
                                            <a href="{{ route('front.sketches.detail', $sketch->getRawOriginal('slug')) }}">{!! $sketch->title !!}</a>
                                        </h5>
                                        <p>{!! $sketch->content !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endunless

                {{-- [BARU] Hasil dari Layanan (Services) --}}
                @unless ($services->isEmpty())
                    <div class="mb-5">
                        <h2 class="result-category-title">Layanan yang Ditemukan ({{ $services->count() }})</h2>
                        <div class="results-list">
                            @foreach ($services as $service)
                                <div class="result-card">
                                    <img src="{{ asset('storage/' . $service->getRawOriginal('thumbnail')) }}" alt="{{ strip_tags($service->getRawOriginal('title')) }}" class="result-card-thumbnail">
                                    <div class="result-card-body">
                                        <h5>
                                            <a href="{{ route('front.layanan') }}">{!! $service->title !!}</a>
                                        </h5>
                                        <p>{!! $service->short_description !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endunless

            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultCards = document.querySelectorAll('.result-card');
        
        resultCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush