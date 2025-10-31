@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0)
        }

        to {
            opacity: 1;
            transform: translate3d(0, 0, 0)
        }
    }

    .stagger-item {
        opacity: 0
    }

    .accordion-collapse.show .stagger-item {
        animation: fadeInUp .5s ease-out forwards
    }

    .accordion-collapse.show .stagger-item:nth-child(1) {
        animation-delay: .05s
    }

    .accordion-collapse.show .stagger-item:nth-child(2) {
        animation-delay: .1s
    }

    .accordion-collapse.show .stagger-item:nth-child(3) {
        animation-delay: .15s
    }

    .accordion-collapse.show .stagger-item:nth-child(4) {
        animation-delay: .2s
    }

    .accordion-collapse.show .stagger-item:nth-child(5) {
        animation-delay: .25s
    }

    /* Event card styling - matching services layout */
    .event-highlight {
        background: white !important;
        border: 3px solid var(--indiegologi-primary) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        position: relative;
    }

    .event-highlight .accordion-button {
        background: white !important;
        padding: 2rem !important;
    }

    .event-highlight .accordion-button h5 {
        font-size: 1.5rem !important;
        color: var(--indiegologi-primary) !important;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .event-highlight .accordion-button p {
        font-size: 1.1rem !important;
        color: #6c757d !important;
    }

    /* Tombol "Daftar Sekarang" matching services */
    .event-highlight .btn-register-now {
        background: var(--indiegologi-primary) !important;
        color: white !important;
        font-weight: 600;
        padding: 0.75rem 1.5rem !important;
        border-radius: 25px !important;
        font-size: 0.9rem !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        white-space: nowrap;
        border: none;
    }

    .event-highlight .btn-register-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Badge event */
    .event-badge {
        position: absolute;
        top: -10px;
        right: 15px;
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        z-index: 10;
    }

    /* Event details styling */
    .event-details {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .event-details.selected {
        background: #e3f2fd;
        border-color: var(--indiegologi-primary);
    }

    .event-meta {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .event-meta.show {
        display: block;
    }

    .event-detail-item {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .event-detail-item:hover {
        border-color: var(--indiegologi-primary);
        background: #f8f9fa;
    }

    .event-detail-item.selected {
        border-color: var(--indiegologi-primary);
        background: #e3f2fd;
    }

    /* Responsive untuk mobile */
    @media (max-width:767.98px) {
        .event-highlight .accordion-button {
            padding: 1.5rem !important;
            flex-direction: column;
            align-items: flex-start !important;
        }

        .event-highlight .accordion-button h5 {
            font-size: 1.3rem !important;
        }

        /* Tombol "Daftar Sekarang" versi mobile */
        .event-highlight .btn-register-now {
            width: 100%;
            margin-top: 1rem;
            padding: 0.8rem 1.5rem !important;
        }

        /* Penyesuaian gambar dan teks di mobile */
        .event-highlight .event-info-wrapper {
            width: 100%;
        }

        .accordion-button .event-header-mobile {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%
        }

        .accordion-button .event-header-mobile-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%
        }

        .accordion-button .event-header-mobile .event-thumbnail-mobile {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem
        }

        .accordion-button h5 {
            font-size: 1.1rem
        }

        .accordion-button p {
            font-size: .85rem
        }

        .btn-details-toggle {
            font-size: 0;
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            background-color: #f1f3f5;
            color: var(--indiegologi-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            flex-shrink: 0
        }

        .btn-details-toggle::after {
            content: '\F285';
            font-family: 'bootstrap-icons';
            font-size: 1rem;
            transition: transform .3s ease
        }

        .accordion-button:not(.collapsed) .btn-details-toggle::after {
            transform: rotate(90deg)
        }
    }

    /* Price display matching services */
    .final-price-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--indiegologi-primary);
    }

    .final-price.free {
        color: #28a745;
    }
</style>
@endpush

@section('content')
<div class="service-details-page">
    {{-- Iklan --}}
    <x-floating-ads
        topAdImage="assets/img/PROMOTION_WEBSITE.jpg"
        topAdLink="#"
        bottomAdImage="assets/img/KONSULTASI_GRATIS.jpg"
        bottomAdLink="/layanan" />

    <section class="container container-title mb-5" data-aos="fade-down">
        <div class="row">
            <h1 class="section-title">Event & Workshop <span class="ampersand-style">&</span> Komunitas</h1>
            <p class="section-desc">Temukan event menarik dan workshop eksklusif untuk pengembangan diri dan komunitas.</p>
        </div>
    </section>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-11">

                {{-- Daftar Events dari Database --}}
                <div class="accordion" id="eventsAccordion">
                    @forelse($events as $event)
                    <div class="accordion-item mb-3 rounded-4 shadow-sm" data-aos="fade-up" data-aos-duration="800">
                        <h2 class="accordion-header" id="heading-{{ $event->id }}">
                            <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $event->id }}">
                                <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                    <div class="d-flex align-items-center">
                                        @if($event->thumbnail)
                                        <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="rounded-3 me-3" style="width:100px;height:100px;object-fit:cover">
                                        @else
                                        <img src="https://placehold.co/100x100/4CAF50/ffffff?text=Event" alt="{{ $event->title }}" class="rounded-3 me-3" style="width:100px;height:100px;object-fit:cover">
                                        @endif
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                            <p class="text-muted mb-0">{{ Str::limit($event->description, 70) }}</p>
                                        </div>
                                    </div>
                                    <button class="btn-details-toggle" type="button">Baca Selengkapnya</button>
                                </div>
                                <div class="d-flex d-md-none event-header-mobile">
                                    @if($event->thumbnail)
                                    <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="{{ $event->title }}" class="event-thumbnail-mobile">
                                    @else
                                    <img src="https://placehold.co/400x200/4CAF50/ffffff?text=Event" alt="{{ $event->title }}" class="event-thumbnail-mobile">
                                    @endif
                                    <div class="event-header-mobile-top">
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                            <p class="text-muted mb-0">{{ Str::limit($event->description, 45) }}</p>
                                        </div>
                                        <button class="btn-details-toggle" type="button"></button>
                                    </div>
                                </div>
                            </div>
                        </h2>
                        <div id="collapse-{{ $event->id }}" class="accordion-collapse collapse" data-bs-parent="#eventsAccordion">
                            <div class="accordion-body p-4 rounded-4">
                                <div class="event-block" data-event-id="{{ $event->id }}">
                                    <div class="stagger-item">
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-judul">Deskripsi Event:</h6>
                                                <p>{{ $event->description }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="stagger-item">
                                        <div class="form-section mb-4">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <h6 class="fw-bold">Detail Event:</h6>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Event:</label>
                                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu:</label>
                                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }} WIB" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Lokasi:</label>
                                                        <input type="text" class="form-control" value="{{ $event->session_type === 'online' ? 'Online Event' : $event->place }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipe Event</label>
                                                        <input type="text" class="form-control" value="{{ $event->session_type === 'online' ? 'Online' : 'Offline' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="stagger-item">
                                        <hr class="my-5">
                                    </div>

                                    <div class="stagger-item">
                                        <div class="row justify-content-between align-items-start mb-3">
                                            <div class="col-auto">
                                                <div class="final-price-display">
                                                    <span class="final-price {{ $event->price == 0 ? 'free' : '' }}">
                                                        {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                @auth
                                                <a href="{{ route('events.book', $event->slug) }}"
                                                    class="btn btn-primary px-4 py-2">
                                                    Daftar Event
                                                </a>
                                                @else
                                                <a href="{{ route('guest.events.book', $event->slug) }}"
                                                    class="btn btn-primary px-4 py-2">
                                                    Daftar Event (Tanpa Login)
                                                </a>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Event kami akan segera tersedia!</p>
                    </div>
                    @endforelse
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
            login_required: "Anda harus login untuk mendaftar event.",
            validation_fails: "Validasi gagal. Pastikan semua kolom terisi dengan benar.",
        };

        // Helper functions for cart management
        function getTempCart() {
            try {
                return JSON.parse(localStorage.getItem('tempCart')) || {};
            } catch (e) {
                console.error('Error parsing temp cart:', e);
                localStorage.removeItem('tempCart');
                return {};
            }
        }

        function saveTempCart(cart) {
            try {
                localStorage.setItem('tempCart', JSON.stringify(cart));
            } catch (e) {
                console.error('Error saving temp cart:', e);
            }
        }

        function updateCartCount() {
            @guest
            const tempCart = getTempCart();
            const cartCount = Object.keys(tempCart).length;
            const cartBadge = document.querySelector('.cart-count, .badge-cart, [data-cart-count]');
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.style.display = cartCount > 0 ? 'inline' : 'none';
            }
            @endguest
        }

        // Handle referral code application
        $('.apply-referral-btn').on('click', function() {
            const eventId = $(this).data('event-id');
            const block = $(this).closest('.event-block');
            const referralCode = block.find('.referral-code-input').val().trim();

            if (!referralCode) {
                return Swal.fire(translations.info, 'Masukkan kode referral terlebih dahulu.', 'info');
            }

            @guest
            Swal.fire(translations.info, 'Kode referral akan divalidasi saat checkout. Silakan login untuk melanjutkan.', 'info');
            return;
            @endguest

            @auth
            Swal.fire({
                title: translations.success,
                text: `Kode referral "${referralCode}" akan diterapkan saat checkout.`,
                icon: 'success',
                confirmButtonText: 'OK'
            });

            // Change button text to indicate code is applied
            $(this).text('Diterapkan').addClass('btn-success').removeClass('btn-outline-primary');
            block.find('.referral-code-input').prop('readonly', true);
            @endauth
        });

        // Initialize cart count on page load
        updateCartCount();
    });
</script>
@endpush
@endsection