@extends('layouts.app')

@section('title', 'Sketch Telling')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold text-center mb-5">Sketch Telling</h1>
        <p class="text-center text-muted mb-5">Lihatlah kisah-kisah yang kami visualisasikan dalam bentuk sketsa.</p>

        @if($sketches->isEmpty())
            <div class="text-center">
                <p class="lead text-muted">Belum ada sketsa yang tersedia saat ini.</p>
            </div>
        @else
            <div class="row">
                @foreach ($sketches as $sketch)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            {{-- Tautan untuk menuju detail sketsa --}}
                            <a href="{{ route('front.sketches.detail', ['sketch' => $sketch->slug]) }}">
                                <img src="{{ asset('storage/' . $sketch->thumbnail) }}" class="card-img-top" alt="{{ $sketch->title }}" style="height: 250px; object-fit: cover;">
                            </a>
                            <div class="card-body text-center">
                                <h5 class="fw-bold">{{ Str::limit($sketch->title, 50) }}</h5>
                                <a href="{{ route('front.sketches.detail', ['sketch' => $sketch->slug]) }}" class="stretched-link text-decoration-none fw-semibold">Lihat Detail</a>
                            </div>
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
@endsection
