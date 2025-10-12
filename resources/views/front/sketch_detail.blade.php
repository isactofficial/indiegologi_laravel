@extends('layouts.app')

@section('title', $sketch->title)

@push('styles')
{{-- Import Font untuk Judul --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

{{-- CSS Kustom untuk Halaman Detail --}}
<style>
    .painting-detail-container {
        max-width: 800px;
        margin: auto;
    }
    .painting-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: #0C2C5A;
        line-height: 1.3;
    }
    .painting-meta-info {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
        padding: 15px 0;
        margin: 20px 0;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        color: #6c757d;
    }
    .meta-item {
        display: flex;
        align-items: center;
    }
    .meta-item i {
        margin-right: 8px;
        color: #0C2C5A;
    }
    .status-badge {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        font-size: 0.75rem;
        background-color: #e3e9f4;
        color: #0C2C5A;
    }
    .article-content {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        margin-top: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .article-content p {
        /* Menjaga format paragraf dari textarea */
        white-space: pre-wrap;
        /* MEMASTIKAN TEKS TANPA SPASI TETAP RAPI */
        overflow-wrap: break-word;
        /* Gaya tipografi untuk kenyamanan membaca */
        font-size: 1.1rem;
        line-height: 1.8;
        color: #343a40;
    }
</style>
@endpush


@section('content')
<div class="container py-5 pb-12 px-4">
    <div class="sketch-detail-container">

        {{-- Tombol Kembali --}}
        <div class="mb-4" data-aos="fade-right">
            <a href="{{ route('front.sketch') }}" class="btn px-4 py-2" style="background-color: #e3e9f4; color: #0C2C5A; border-radius: 8px;">
                <i class="fas fa-arrow-left me-2"></i>KEMBALI
            </a>
        </div>

        {{-- Judul Painting --}}
        <h1 class="painting-title mt-4" data-aos="fade-up">{{ $sketch->title }}</h1>

        {{-- Meta Info (Penulis, Tanggal, Status) --}}
        <div class="sketch-meta-info" data-aos="fade-up" data-aos-delay="100">
            <div class="meta-item">
                <i class="fas fa-user-edit"></i>
                <span>Oleh: <strong>{{ $sketch->author ?? 'Unknown' }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $sketch->created_at->format('d F Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="status-badge">{{ $sketch->status ?? 'Published' }}</span>
            </div>
        </div>

    {{-- Gambar Painting --}}
    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded-4 shadow-sm my-4"
             style="width: 100%; max-height: 500px; object-fit: cover;" data-aos="zoom-in-up" data-aos-delay="200">

        {{-- Konten Painting --}}
        <div class="article-content" data-aos="fade-up" data-aos-delay="300">
            <p>{{ $sketch->content }}</p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- SCRIPT UNTUK ANIMASI AOS --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        once: true, // Animasi hanya berjalan sekali agar tidak mengganggu saat scroll
        offset: 100,
    });
</script>
@endpush