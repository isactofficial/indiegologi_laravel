@extends('layouts.master')

@section('title', 'Indiegologi - Homepage')

@section('content')

    {{-- 1. Hero Section --}}
    <section class="container py-5 my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Empowering Ideas Through Innovative Technology</h1>
                <p class="lead text-muted mb-4">
                    We are a team of passionate developers, designers, and strategists dedicated to helping you achieve your goals.
                </p>
                <a href="#" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-medium">Konsultasi Gratis</a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                {{-- Pastikan Anda memiliki gambar ini di folder public/assets/img/ --}}
                <img src="{{ asset('assets/img/hero_image.png') }}" class="img-fluid" alt="Team discussing ideas">
            </div>
        </div>
    </section>

    {{-- 2. Layanan Section --}}
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4 mt-n5">
                               <i class="bi bi-code-slash"></i>
                            </div>
                            <h5 class="card-title fw-bold">Web Development</h5>
                            <p class="card-text text-muted">Membangun website modern dan responsif untuk kebutuhan bisnis Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4 mt-n5">
                                <i class="bi bi-palette-fill"></i>
                            </div>
                            <h5 class="card-title fw-bold">UI/UX Design</h5>
                            <p class="card-text text-muted">Desain antarmuka yang intuitif dan pengalaman pengguna yang menyenangkan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4 mt-n5">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h5 class="card-title fw-bold">IT Consultant</h5>
                            <p class="card-text text-muted">Memberikan solusi dan strategi teknologi terbaik untuk perusahaan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Artikel Terbaru Section --}}
    <section class="container py-5 my-5">
        <div class="text-center">
            <h2 class="fw-bold mb-5">Artikel Terbaru</h2>
        </div>
        <div class="row">
            @forelse ($latest_articles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ Str::limit($article->title, 50) }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($article->description, 100) }}</p>
                            <a href="#" class="btn btn-outline-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col text-center">
                    <p class="text-muted">Belum ada artikel untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </section>

@endsection

{{-- Menambahkan beberapa style dasar untuk ikon --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        font-size: 2rem;
    }
</style>
@endpush