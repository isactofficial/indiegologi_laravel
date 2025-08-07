@extends('layouts.app')

@section('title', $sketch->title)

@section('content')
<div class="container py-5 pb-12 px-4 mx-auto"><br><br>
    {{-- Tombol kembali --}}
    <div class="mb-3">
        <a href="{{ route('front.sketch') }}" class="text-decoration-none text-primary fw-semibold">
            ← KEMBALI
        </a>
    </div>

    {{-- Judul --}}
    <h1 style="color: #0C2C5A;" class="fw-bold mb-2">{{ $sketch->title }}</h1>

    {{-- Informasi Author dan Tanggal --}}
    <div class="text-muted mb-4 d-flex align-items-center gap-2">
        <span>Published</span>
        <span>{{ $sketch->created_at->format('d F Y') }}</span>
    </div>
    <div>
        <span>{{ $sketch->author ?? 'Unknown' }}</span><br>
    </div>

    {{-- Gambar Sketsa --}}
    <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="img-fluid rounded mb-4"
        style="width: 100%; max-height: 600px; object-fit: cover;">

    {{-- Deskripsi --}}
    <div style="background-color: #0C2C5A; color: white; padding: 16px; border-radius: 4px;"">
        <p class=" mb-0">
        {!! nl2br(e($sketch->content)) !!}
        </p>
    </div>

</div>
@endsection