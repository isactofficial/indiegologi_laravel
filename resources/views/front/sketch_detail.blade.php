@extends('layouts.app')

@section('title', $sketch->title)

@section('content')
<div class="container py-5 pb-12 px-4 mx-auto"><br><br>
    {{-- Tombol kembali --}}
    <div class="mb-3">
        <a href="{{ route('front.sketch') }}" class="btn px-4 py-2" style="background-color: #e3e9f4; color: #0C2C5A; border-radius: 8px;">
                    <i class="fas fa-arrow-left me-2"></i>KEMBALI
        </a>
    </div>

    {{-- Judul (Konten Dinamis - Tidak Diterjemahkan) --}}
    <h1 style="color: #0C2C5A;" class="fw-bold mb-2">{{ $sketch->title }}</h1>

    {{-- Informasi Author dan Tanggal --}}
    <div class="text-muted mb-4 d-flex align-items-center gap-2">
        <span>Published</span>
        {{-- Tanggal akan otomatis diterjemahkan oleh Carbon/Laravel --}}
        <span>{{ $sketch->created_at->format('d F Y') }}</span>
    </div>
    <div>
        {{-- Author (Konten Dinamis - Tidak Diterjemahkan) --}}
        <span>{{ $sketch->author ?? 'Unknown' }}</span><br>
    </div>

    {{-- Gambar Sketsa --}}
    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded mb-4"
         style="width: 100%; max-height: 600px; object-fit: cover;">

    {{-- Deskripsi (Konten Dinamis - Tidak Diterjemahkan) --}}
    <div style="background-color: #0C2C5A; color: white; padding: 16px; border-radius: 4px;">
        <p class="mb-0">
            {!! nl2br(e($sketch->content)) !!}
        </p>
    </div>

</div>
@endsection