@extends('layouts.app')

@section('title', __('sketch.page_title'))

@section('content')
<div class="sketch-telling-section">
    <div class="container">
        <h1 class="section-title">{{ __('sketch.section_title') }}</h1>
        <p class="section-description">{{ __('sketch.section_subtitle') }}</p>

        @if($sketches->isEmpty())
        <div class="text-center">
            <p class="lead text-muted">{{ __('sketch.no_sketches_available') }}</p>
        </div>
        @else
        <div class="cards-grid">
            @foreach ($sketches as $sketch)
            <div class="card">
                <a href="{{ route('front.sketches.detail', ['sketch' => $sketch->slug]) }}">
                    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" class="card-image"
                         alt="{{ $sketch->title }}">
                </a>
                <div class="card-content">
                    {{-- Konten Dinamis (Tidak Diterjemahkan) --}}
                    <h3 class="card-title">{{ Str::limit($sketch->title, 50) }}</h3>
                    <a href="{{ route('front.sketches.detail', ['sketch' => $sketch->slug]) }}" class="read-more">
                        {{ __('sketch.view_details_button') }} <span class="arrow">›</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bagian Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $sketches->links() }}
        </div>
        @endif
    </div>
</div>

<style>
/* Semua CSS Anda tetap sama, tidak perlu diubah */
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    justify-content: center;
    padding-bottom: 50px;
}
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
.card-content {
    padding: 20px;
    text-align: left;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.card-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #0C2C5A;
    margin-top: 0;
    margin-bottom: 15px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.read-more {
    display: flex;
    align-items: center;
    color: #0C2C5A;
    font-weight: 600;
    text-decoration: none;
    margin-top: auto;
}
.read-more .arrow {
    margin-left: 5px;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}
.read-more:hover .arrow {
    transform: translateX(5px);
}
.read-more:hover {
    text-decoration: underline;
}
@media (max-width: 992px) {
    .cards-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    .section-title {
        font-size: 2.2rem;
    }
    .section-description {
        font-size: 1rem;
    }
    .card-title {
        font-size: 1.2rem;
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
@endsection