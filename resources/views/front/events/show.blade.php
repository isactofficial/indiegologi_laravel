@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
    .event-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .event-detail-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 2rem;
    }

    .event-detail-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .event-detail-sidebar {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 2rem;
    }

    .event-detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .event-detail-item i {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: var(--indiegologi-primary);
        width: 30px;
    }

    .event-price-large {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--indiegologi-primary);
        text-align: center;
        margin: 1rem 0;
    }

    .event-price-large.free {
        color: #28a745;
    }

    .btn-register-large {
        background: var(--indiegologi-primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 1rem;
    }

    .btn-register-large:hover:not(:disabled) {
        background: #2c5282;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-register-large:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }

    .spots-info {
        text-align: center;
        margin-bottom: 1rem;
    }

    .spots-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .spots-badge.available {
        background: #d4edda;
        color: #155724;
    }

    .spots-badge.low {
        background: #fff3cd;
        color: #856404;
    }

    .spots-badge.full {
        background: #f8d7da;
        color: #721c24;
    }

    .registration-deadline {
        text-align: center;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 767.98px) {
        .event-detail-header {
            padding: 2rem 0;
        }

        .event-detail-image {
            height: 250px;
        }

        .event-price-large {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="event-detail-page">
    <section class="event-detail-header" data-aos="fade-down">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-5 fw-bold mb-3">{{ $event->title }}</h1>
                    <p class="lead mb-0">{{ $event->short_description ?? Str::limit($event->description, 150) }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-8">
                @if($event->thumbnail)
                <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="event-detail-image" data-aos="fade-up">
                @else
                <img src="https://placehold.co/800x400/4CAF50/ffffff?text=Event+Image" alt="{{ $event->title }}" class="event-detail-image" data-aos="fade-up">
                @endif

                <div class="event-detail-content" data-aos="fade-up">
                    <h3 class="mb-4">Tentang Event</h3>
                    <div class="event-description">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="event-detail-item">
                                <i class="bi bi-calendar-event"></i>
                                <div>
                                    <strong>Tanggal Event</strong><br>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="event-detail-item">
                                <i class="bi bi-clock"></i>
                                <div>
                                    <strong>Waktu</strong><br>
                                    {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="event-detail-item">
                                <i class="bi bi-geo-alt"></i>
                                <div>
                                    <strong>Lokasi</strong><br>
                                    {{ $event->session_type === 'online' ? 'Online Event' : $event->place }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="event-detail-item">
                                <i class="bi bi-people"></i>
                                <div>
                                    <strong>Kapasitas</strong><br>
                                    {{ $event->max_participants }} Peserta
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="event-detail-sidebar" data-aos="fade-left">
                    <div class="event-price-large {{ $event->price == 0 ? 'free' : '' }}">
                        {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                    </div>

                    <div class="spots-info">
                        @if($event->spots_left > 0)
                        <div class="spots-badge {{ $event->spots_left <= 5 ? 'low' : 'available' }}">
                            {{ $event->spots_left }} slot tersisa
                        </div>
                        @else
                        <div class="spots-badge full">
                            Event Penuh
                        </div>
                        @endif
                    </div>

                    <div class="registration-deadline">
                        <i class="bi bi-alarm"></i>
                        Pendaftaran ditutup: {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d M Y') }}
                    </div>

                    {{-- GANTI tombol di sidebar --}}
                    <a href="{{ route('events.book', $event->slug) }}"
                        class="btn-register-large {{ !$event->isAvailable() ? 'disabled' : '' }}"
                        {{ !$event->isAvailable() ? 'disabled' : '' }}>
                        @if(!$event->isAvailable())
                        {{ $event->spots_left <= 0 ? 'Event Penuh' : 'Pendaftaran Ditutup' }}
                        @else
                        Daftar Sekarang
                        @endif
                    </a>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i>
                            Data Anda aman dan terjamin
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init();

        const translations = {
            success: "Berhasil!",
            failure: "Gagal!",
            info: "Perhatian!",
            added_to_cart: "Event berhasil ditambahkan ke keranjang!",
            event_full: "Event sudah penuh.",
            registration_closed: "Pendaftaran event sudah ditutup.",
            login_required: "Anda harus login untuk mendaftar event.",
        };
    });
</script>
@endpush
@endsection