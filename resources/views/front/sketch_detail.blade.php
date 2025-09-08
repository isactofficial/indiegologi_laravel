@extends('layouts.app')

@section('title', $sketch->title)

@section('content')
{{-- Kontainer utama dengan animasi fade-in --}}
<div class="container py-5 pb-12 px-4 mx-auto" data-aos="fade-in"><br><br>
    {{-- Tombol kembali dengan animasi fade-right --}}
    <div class="mb-4" data-aos="fade-right">
        <a href="{{ route('front.sketch') }}" class="btn px-4 py-2" style="background-color: #e3e9f4; color: #0C2C5A; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i>KEMBALI
        </a>
    </div>

    {{-- Bagian Atas: Status di Kiri, Tanggal di Kanan dengan animasi fade-up --}}
    <div class="d-flex justify-content-between align-items-center mb-3" data-aos="fade-up" data-aos-delay="100">
        {{-- Status di Kiri --}}
        <span class="fw-semibold px-3 py-1 text-uppercase" style="background-color: #e3e9f4; color: #0C2C5A; border-radius: 999px; font-size: 0.85rem; letter-spacing: 0.5px;">
            {{-- Menggunakan data status dari model, jika ada. Default ke 'Published' jika tidak ada. --}}
            {{ $sketch->status ?? 'Published' }}
        </span>
        {{-- Tanggal di Kanan --}}
        <span class="text-muted">
            <i class="fas fa-calendar-alt me-2"></i>{{ $sketch->created_at->format('d F Y') }}
        </span>
    </div>

    {{-- Judul dengan animasi fade-up dan delay --}}
    <h1 style="color: #0C2C5A;" class="fw-bold mb-2" data-aos="fade-up" data-aos-delay="200">{{ $sketch->title }}</h1>

    {{-- Nama Penulis dengan animasi fade-up dan delay --}}
    <p class="mb-4" data-aos="fade-up" data-aos-delay="300">
        <span class="text-muted">Oleh:</span>
        <span class="fw-semibold" style="color: #0C2C5A;">{{ $sketch->author ?? 'Unknown' }}</span>
    </p>

    {{-- Gambar Sketsa dengan animasi zoom-in-up --}}
    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded-4 shadow-sm mb-4"
         style="width: 100%; max-height: 600px; object-fit: cover;" data-aos="zoom-in-up" data-aos-delay="400">

    {{-- Deskripsi dengan animasi fade-up --}}
    <div style="background-color: #0C2C5A; color: white; padding: 16px; border-radius: 4px;" data-aos="fade-up" data-aos-delay="100">
        <p class="mb-0">
            {!! nl2br(e($sketch->content)) !!}
        </p>
    </div>
</div>
@endsection

@push('styles')
{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
@endpush

@push('scripts')
{{-- SCRIPT UNTUK ANIMASI AOS --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    // Inisialisasi AOS
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        // 'once' diatur ke false agar animasi bisa di-reset saat scroll ke atas
        once: false,
        offset: 120,
    });

    // --- SCRIPT KUSTOM UNTUK PERILAKU ANIMASI SPESIFIK ---
    let lastScrollTop = 0;
    const allAosElements = document.querySelectorAll('[data-aos]');

    // Tambahkan event listener saat window di-scroll
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop < lastScrollTop) {
            // JIKA ARAH SCROLL KE ATAS
            allAosElements.forEach(function(element) {
                // Cek jika elemen berada di bawah viewport (tidak terlihat)
                if (element.getBoundingClientRect().top > window.innerHeight) {
                    // "Reset" animasi dengan menghapus kelas 'aos-animate'
                    element.classList.remove('aos-animate');
                }
            });
        }
        // Biarkan AOS menangani animasi secara normal saat scroll ke bawah

        // Update posisi scroll terakhir
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
</script>
@endpush
