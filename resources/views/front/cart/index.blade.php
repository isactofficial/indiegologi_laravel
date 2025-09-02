@extends('layouts.app')

@section('title', 'Keranjang Belanja Anda')

@push('styles')
{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Your existing CSS remains unchanged */
    :root {
        --brand-primary: #0C2C5A;
        --brand-text: #212529;
        --brand-text-muted: #6c757d;
        --brand-background: #f8f9fa;
        --brand-surface: #ffffff;
        --brand-border: #dee2e6;
        --brand-danger: #dc3545;
        --font-main: 'Playfair Display', sans-serif;
    }
    body {
        font-family: var(--font-main);
        background-color: var(--brand-background);
    }
    .cart-page {
        color: var(--brand-text);
    }
    .cart-header .section-title {
        font-weight: 700;
        color: var(--brand-primary);
    }
    .cart-item-card, .summary-card {
        background-color: var(--brand-surface);
        border: 1px solid var(--brand-border);
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .cart-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(12, 44, 90, 0.1);
    }
    .item-details-list {
        list-style: none; padding: 0; margin: 0; font-size: 0.9rem;
    }
    .item-details-list li {
        display: flex; align-items: center; margin-bottom: 0.6rem; color: var(--brand-text-muted);
    }
    .item-details-list i {
        color: var(--brand-primary);
        margin-right: 0.75rem; width: 16px;
    }
    .btn-brand-outline {
        background-color: transparent;
        color: var(--brand-primary);
        border: 1px solid var(--brand-primary);
        font-weight: 500;
        border-radius: 6px;
        padding: 0.4rem 0.9rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
    }
    .btn-brand-outline:hover {
        background-color: var(--brand-primary);
        color: var(--brand-surface);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(12, 44, 90, 0.2);
    }
    .remove-btn {
        color: var(--brand-text-muted); transition: color 0.2s ease;
    }
    .remove-btn:hover { color: var(--brand-danger); }
    .remove-btn i { transition: transform 0.2s ease-in-out; }
    .remove-btn:hover i { transform: scale(1.15); }
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 120px;
        }
    }
    .btn-checkout {
        background-color: var(--brand-primary);
        color: var(--brand-surface);
        font-weight: 600;
        font-size: 1.1rem;
        padding: 0.9rem 1.5rem;
        border-radius: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(12, 44, 90, 0.2);
    }
    .btn-checkout:hover {
        background-color: #082142;
        color: var(--brand-surface);
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(12, 44, 90, 0.3);
    }
    .price-details-list .list-group-item {
        padding: 0.5rem 0; border: 0; background-color: transparent;
    }
    @media (max-width: 767.98px) {
        .cart-header .section-title {
            font-size: 2rem;
        }
        .summary-card h4 {
            font-size: 1.25rem;
        }
        .summary-card .fs-5 {
            font-size: 1.1rem !important;
        }
        .summary-card .fs-4 {
            font-size: 1.2rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="container" style="padding-top: 6rem; padding-bottom: 6rem;">

        @auth
            @if ($cartItems->isEmpty())
                {{-- Empty cart message with zoom-in animation --}}
                <div class="text-center py-5" data-aos="zoom-in">
                    <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                    <h2 class="fw-bold mt-4" style="color: var(--brand-text);">Keranjang Anda Kosong</h2>
                    <p class="fs-5 text-muted">Mari ciptakan momen berkesan dengan layanan kami.</p>
                    <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">Jelajahi Layanan</a>
                </div>
            @else
                {{-- Cart header with fade-down animation --}}
                <div class="text-center mb-5 cart-header" data-aos="fade-down">
                    <h1 class="section-title">Keranjang Belanja Anda</h1>
                    <p class="lead text-muted">Satu langkah lagi untuk menjadi versi terbaik Anda.</p>
                </div>
                <form action="{{ route('checkout.process') }}" method="POST" id="cart-form-logged-in">
                    @csrf
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-7">
                            {{-- "Select All" card with fade-right animation --}}
                            <div class="card mb-3 shadow-sm border-0" data-aos="fade-right">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all" checked>
                                        <label class="form-check-label fw-bold" for="select-all" style="color: var(--brand-text);">
                                            Pilih Semua Layanan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @foreach ($cartItems as $item)
                                {{-- Each cart item with a staggered fade-right animation --}}
                                <div class="card mb-3 cart-item-card" data-service-id="{{ $item->service_id }}" data-aos="fade-right" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-12 col-md-7 mb-4 mb-md-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check me-3 pt-1">
                                                        <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="item-{{ $item->id }}" checked>
                                                    </div>
                                                    <img src="{{ asset('storage/' . $item->service->thumbnail) }}" alt="{{ $item->service->title }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fw-bold mb-2 fs-6" style="color: var(--brand-text);">{{ $item->service->title }}</h5>
                                                        <ul class="item-details-list">
                                                            <li><i class="bi bi-calendar-check"></i>{{ \Carbon\Carbon::parse($item->booked_date)->translatedFormat('d M Y') }}, {{ $item->booked_time }}</li>
                                                            <li><i class="bi bi-camera-video"></i>{{ $item->session_type }} & {{ $item->contact_preference == 'chat_only' ? 'Chat' : 'Chat & Call' }}</li>
                                                            @if($item->offline_address)
                                                                <li><i class="bi bi-geo-alt"></i>{{ $item->offline_address }}</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-5 border-md-start ps-md-4 mt-4 mt-md-0 pt-4 pt-md-0 border-top border-md-0">
                                                <ul class="list-group list-group-flush price-details-list small">
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Harga Dasar:</span>
                                                        <span class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    </li>
                                                    @if ($item->hours > 0)
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Per Jam ({{ $item->hours }} jam):</span>
                                                        <span class="text-muted">Rp {{ number_format($item->hourly_price * $item->hours, 0, ',', '.') }}</span>
                                                    </li>
                                                    @endif
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Subtotal:</span>
                                                        <span class="text-muted">Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}</span>
                                                    </li>
                                                    @php $itemDiscount = $item->item_subtotal - $item->final_item_price; @endphp
                                                    @if ($itemDiscount > 0)
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span style="color: var(--brand-danger);">Diskon:</span>
                                                        <span style="color: var(--brand-danger);">- Rp {{ number_format($itemDiscount, 0, ',', '.') }}</span>
                                                    </li>
                                                    @endif
                                                </ul>
                                                <hr class="my-2" style="border-color: var(--brand-border);">
                                                <p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);">
                                                    <span>Total Item:</span>
                                                    <span>Rp {{ number_format($item->final_item_price, 0, ',', '.') }}</span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a href="{{ route('front.layanan') }}" class="btn btn-brand-outline">Pesan Lagi</a>
                                                    <button type="button" class="btn btn-link p-0 remove-btn" data-id="{{ $item->id }}" title="Hapus item">
                                                        <i class="bi bi-trash3-fill fs-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Summary card with fade-left animation --}}
                        <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                            <div class="summary-card sticky-summary">
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-4" style="color: var(--brand-primary);">Ringkasan Pesanan</h4>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal Semua Item</span>
                                        <span class="text-muted" id="summary-subtotal">Rp {{ $subtotal }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Total Diskon</span>
                                        <span class="text-danger" id="summary-discount">- Rp {{ $totalDiscount }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3" style="color: var(--brand-text);">
                                        <span>Grand Total Akhir</span>
                                        <span id="summary-grandtotal">Rp {{ $grandTotal }}</span>
                                    </div>
                                    <hr style="border-color: var(--brand-border);">
                                    <div class="mb-3">
                                        <label for="payment-type-select" class="form-label fw-bold" style="color: var(--brand-text);">Pilih Tipe Pembayaran</label>
                                        <select class="form-select" id="payment-type-select" name="global_payment_type">
                                            <option value="full_payment" selected>Pembayaran Penuh</option>
                                            <option value="dp">DP (50%)</option>
                                        </select>
                                    </div>
                                    <hr style="border-color: var(--brand-border);">
                                    <div class="d-flex justify-content-between fw-bolder fs-4 mt-3" style="color: var(--brand-primary);">
                                        <span style="white-space: nowrap; margin-right: 1rem;">Total Bayar Sekarang</span>
                                        <span id="summary-totalpay">Rp {{ $totalToPayNow }}</span>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-checkout">Lanjutkan ke Pembayaran</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="remove-item-form" action="{{ route('front.cart.remove') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="id" id="remove-item-id">
                </form>
            @endif
        @else
            <div id="temp-cart-display">
                <div class="text-center py-5 empty-cart-message" data-aos="zoom-in">
                    <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                    <h2 class="fw-bold mt-4" style="color: var(--brand-text);">Keranjang Anda Kosong</h2>
                    <p class="fs-5 text-muted">Mari ciptakan momen berkesan dengan layanan kami.</p>
                    <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">Jelajahi Layanan</a>
                </div>
            </div>
        @endauth

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
        once: false,
        offset: 100,
    });

    let lastScrollTop = 0;
    const allAosElements = document.querySelectorAll('[data-aos]');
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop < lastScrollTop) {
            allAosElements.forEach(function(element) {
                if (element.getBoundingClientRect().top > window.innerHeight) {
                    element.classList.remove('aos-animate');
                }
            });
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    /* Your existing JavaScript logic remains unchanged */
    const translations = {
        success: "Berhasil!",
        failure: "Gagal!",
        info: "Perhatian!",
        referral_applied: "Kode referral berhasil diterapkan!",
        referral_invalid: "Kode referral tidak valid atau sudah kadaluarsa.",
        referral_not_found: "Kode referral tidak ditemukan.",
        referral_enter_first: "Silakan masukkan kode referral terlebih dahulu.",
        generic_error: "Terjadi kesalahan, silakan coba lagi.",
        validation_fails: "Validasi gagal. Silakan periksa input Anda.",
        schedule_unavailable: "Jadwal tidak tersedia. Silakan pilih waktu lain.",
        schedule_check_error: "Terjadi kesalahan saat memeriksa jadwal. Coba lagi.",
    };

    function getTempCart() {
        try {
            const cart = localStorage.getItem('tempCart');
            return cart ? JSON.parse(cart) : {};
        } catch (e) {
            console.error("Failed to parse tempCart from localStorage", e);
            return {};
        }
    }

    function saveTempCart(cart) {
        try {
            localStorage.setItem('tempCart', JSON.stringify(cart));
        } catch (e) {
            console.error("Failed to save tempCart to localStorage", e);
        }
    }

    function calculateSummary(cartItems, paymentType) {
        let subtotal = 0;
        let totalDiscount = 0;
        let grandTotal = 0;

        for (const id in cartItems) {
            const item = cartItems[id];
            const itemSubtotal = (parseFloat(item.price) || 0) + (parseFloat(item.hourly_price) || 0) * (parseInt(item.hours) || 0);
            const discountAmount = (itemSubtotal * (parseFloat(item.discount_percentage) || 0)) / 100;
            const finalItemPrice = itemSubtotal - discountAmount;

            subtotal += itemSubtotal;
            totalDiscount += discountAmount;
            grandTotal += finalItemPrice;
        }

        let totalToPayNow = grandTotal;
        if (paymentType === 'dp') {
            totalToPayNow = grandTotal * 0.5;
        }

        return {
            subtotal: subtotal.toLocaleString('id-ID'),
            totalDiscount: totalDiscount.toLocaleString('id-ID'),
            grandTotal: grandTotal.toLocaleString('id-ID'),
            totalToPayNow: totalToPayNow.toLocaleString('id-ID')
        };
    }

    function renderTempCart(cartItems) {
        const container = $('#temp-cart-display');
        container.empty();

        if (Object.keys(cartItems).length === 0) {
            container.html(`
                <div class="text-center py-5" data-aos="zoom-in">
                    <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                    <h2 class="fw-bold mt-4" style="color: var(--brand-text);">Keranjang Anda Kosong</h2>
                    <p class="fs-5 text-muted">Mari ciptakan momen berkesan dengan layanan kami.</p>
                    <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">Jelajahi Layanan</a>
                </div>
            `);
            // Re-initialize AOS for the newly added empty cart message
            AOS.refresh();
            return;
        }

        const cartHeader = `
            <div class="text-center mb-5 cart-header" data-aos="fade-down">
                <h1 class="section-title">Keranjang Belanja Anda</h1>
                <p class="lead text-muted">Satu langkah lagi untuk menjadi versi terbaik Anda.</p>
            </div>
            <div class="alert alert-info" role="alert" data-aos="fade-in" data-aos-delay="100">
                <h5 class="alert-heading">Keranjang Sementara</h5>
                <p>Layanan yang Anda pilih saat ini disimpan secara sementara di perangkat Anda. <a href="{{ route('login') }}">Silakan login</a> untuk menyimpannya secara permanen dan melanjutkan ke pembayaran.</p>
            </div>
        `;

        container.append(cartHeader);

        const row = $('<div class="row g-4 g-lg-5"></div>');
        const cartListCol = $('<div class="col-lg-7"></div>');

        let checkedItems = [];

        Object.keys(cartItems).forEach((serviceId, index) => {
            const item = cartItems[serviceId];
            checkedItems.push(serviceId);

            const itemSubtotal = (parseFloat(item.price) || 0) + (parseFloat(item.hourly_price) || 0) * (parseInt(item.hours) || 0);
            const discountAmount = (itemSubtotal * (parseFloat(item.discount_percentage) || 0)) / 100;
            const finalItemPrice = itemSubtotal - discountAmount;

            const itemHtml = `
                <div class="card mb-3 cart-item-card" data-service-id="${serviceId}" data-aos="fade-right" data-aos-delay="${index * 100}">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-12 col-md-7 mb-4 mb-md-0">
                                <div class="d-flex align-items-start">
                                    <div class="form-check me-3 pt-1">
                                        <input class="form-check-input item-checkbox-temp" type="checkbox" data-id="${serviceId}" checked>
                                    </div>
                                    <img src="{{ asset('storage/') }}/${item.service_thumbnail}" alt="${item.service_title}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-2 fs-6" style="color: var(--brand-text);">${item.service_title}</h5>
                                        <ul class="item-details-list">
                                            <li><i class="bi bi-calendar-check"></i>${new Date(item.booked_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}, ${item.booked_time}</li>
                                            <li><i class="bi bi-camera-video"></i>${item.session_type} & ${item.contact_preference === 'chat_only' ? 'Chat' : 'Chat & Call'}</li>
                                            ${item.offline_address ? `<li><i class="bi bi-geo-alt"></i>${item.offline_address}</li>` : ''}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 border-md-start ps-md-4 mt-4 mt-md-0 pt-4 pt-md-0 border-top border-md-0">
                                <ul class="list-group list-group-flush price-details-list small">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted">Harga Dasar:</span>
                                        <span class="text-muted">Rp ${ (parseFloat(item.price) || 0).toLocaleString('id-ID') }</span>
                                    </li>
                                    ${item.hours > 0 ? `
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted">Per Jam (${item.hours} jam):</span>
                                        <span class="text-muted">Rp ${ (parseFloat(item.hourly_price) * item.hours).toLocaleString('id-ID') }</span>
                                    </li>` : ''}
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-muted">Subtotal:</span>
                                        <span class="text-muted">Rp ${ itemSubtotal.toLocaleString('id-ID') }</span>
                                    </li>
                                    ${discountAmount > 0 ? `
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span style="color: var(--brand-danger);">Diskon:</span>
                                        <span style="color: var(--brand-danger);">- Rp ${ discountAmount.toLocaleString('id-ID') }</span>
                                    </li>` : ''}
                                </ul>
                                <hr class="my-2" style="border-color: var(--brand-border);">
                                <p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);">
                                    <span>Total Item:</span>
                                    <span>Rp ${ finalItemPrice.toLocaleString('id-ID') }</span>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('front.layanan') }}" class="btn btn-brand-outline">Pesan Lagi</a>
                                    <button type="button" class="btn btn-link p-0 remove-btn-temp" data-id="${serviceId}" title="Hapus item">
                                        <i class="bi bi-trash3-fill fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            cartListCol.append(itemHtml);
        });

        const summary = calculateSummary(cartItems, 'full_payment');
        const summaryCol = `
            <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                <div class="summary-card sticky-summary">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4" style="color: var(--brand-primary);">Ringkasan Pesanan</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Semua Item</span>
                            <span class="text-muted" id="summary-subtotal-temp">Rp ${summary.subtotal}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-danger" id="summary-discount-temp">- Rp ${summary.totalDiscount}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3" style="color: var(--brand-text);">
                            <span>Grand Total Akhir</span>
                            <span id="summary-grandtotal-temp">Rp ${summary.grandTotal}</span>
                        </div>
                        <hr style="border-color: var(--brand-border);">
                        <div class="mb-3">
                            <label for="payment-type-select-temp" class="form-label fw-bold" style="color: var(--brand-text);">Pilih Tipe Pembayaran</label>
                            <select class="form-select" id="payment-type-select-temp">
                                <option value="full_payment" selected>Pembayaran Penuh</option>
                                <option value="dp">DP (50%)</option>
                            </select>
                        </div>
                        <hr style="border-color: var(--brand-border);">
                        <div class="d-flex justify-content-between fw-bolder fs-4 mt-3" style="color: var(--brand-primary);">
                            <span style="white-space: nowrap; margin-right: 1rem;">Total Bayar Sekarang</span>
                            <span id="summary-totalpay-temp">Rp ${summary.totalToPayNow}</span>
                        </div>
                        <div class="d-grid mt-4">
                            <a href="{{ route('login') }}" class="btn btn-checkout">Login untuk Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        row.append(cartListCol).append(summaryCol);
        container.append(row);

        // Re-initialize AOS for all the newly added elements
        AOS.refresh();

        $('#payment-type-select-temp').on('change', function() {
            const tempCart = getTempCart();
            const summary = calculateSummary(tempCart, $(this).val());
            $('#summary-subtotal-temp').text('Rp ' + summary.subtotal);
            $('#summary-discount-temp').text('- Rp ' + summary.totalDiscount);
            $('#summary-grandtotal-temp').text('Rp ' + summary.grandTotal);
            $('#summary-totalpay-temp').text('Rp ' + summary.totalToPayNow);
        });

        $('.remove-btn-temp').on('click', function() {
            const serviceId = $(this).data('id');
            const tempCart = getTempCart();
            delete tempCart[serviceId];
            saveTempCart(tempCart);
            renderTempCart(tempCart);
        });
    }

    $(document).ready(function() {
        @auth
            localStorage.removeItem('tempCart');

            function updateSummary() {
                let selectedIds = [];
                $('.item-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                let selectedPaymentType = $('#payment-type-select').val();
                $.ajax({
                    url: '{{ route("front.cart.updateSummary") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds,
                        payment_type: selectedPaymentType
                    },
                    success: function(response) {
                        $('#summary-subtotal').text('Rp ' + response.subtotal);
                        $('#summary-discount').text('- Rp ' + response.totalDiscount);
                        $('#summary-grandtotal').text('Rp ' + response.grandTotal);
                        $('#summary-totalpay').text('Rp ' + response.totalToPayNow);
                        $('button[type="submit"]').prop('disabled', selectedIds.length === 0);
                    }
                });
            }
            $('.item-checkbox, #select-all, #payment-type-select').on('change', function() {
                if ($(this).is('#select-all')) {
                    $('.item-checkbox').prop('checked', this.checked);
                } else {
                    $('#select-all').prop('checked', $('.item-checkbox:checked').length === $('.item-checkbox').length);
                }
                updateSummary();
            });
            $('.remove-btn').on('click', function() {
                let itemId = $(this).data('id');
                $('#remove-item-id').val(itemId);
                $('#remove-item-form').submit();
            });
            updateSummary();
        @else
            const tempCart = getTempCart();
            renderTempCart(tempCart);
        @endauth
    });
</script>
@endpush
