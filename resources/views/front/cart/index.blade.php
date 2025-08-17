@extends('layouts.app')

@section('title', __('cart.page_title'))

@push('styles')
{{-- [BRAND] Import font yang elegan dan modern dari Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* === [BRAND IDENTITY] Cart Page Styles V3 === */
    /* === (Classy, Pointed, Memorable, Comfortable) === */

    /* [BRAND] Definisi Variabel Warna & Font Sesuai Brand */
    :root {
        --brand-primary: #0C2C5A;
        --brand-text: #212529;
        --brand-text-muted: #6c757d;
        --brand-background: #f8f9fa;
        --brand-surface: #ffffff;
        --brand-border: #dee2e6;
        --brand-danger: #dc3545;
        --font-main: 'Poppins', sans-serif;
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

    /* [UBAH] Desain Kartu yang Lebih Berkelas dan Tajam */
    .cart-item-card, .summary-card {
        background-color: var(--brand-surface);
        border: 1px solid var(--brand-border);
        border-radius: 8px; /* Sudut lebih tajam untuk kesan "pointed" */
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
        color: var(--brand-primary); /* Ikon menggunakan warna utama */
        margin-right: 0.75rem; width: 16px;
    }

    /* [UBAH] Tombol Aksi "Pesan Lagi" Sesuai Brand Identity */
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

    /* [UBAH] Tombol Checkout Utama yang Menonjol */
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
        background-color: #082142; /* Sedikit lebih gelap saat hover */
        color: var(--brand-surface);
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(12, 44, 90, 0.3);
    }

    .price-details-list .list-group-item {
        padding: 0.5rem 0; border: 0; background-color: transparent;
    }

    /* [RESPONSIVE] Tambahan CSS untuk Tampilan Mobile */
    @media (max-width: 767.98px) {
        .cart-header .section-title {
            font-size: 2rem; /* Perkecil judul utama di mobile */
        }
        .summary-card h4 {
            font-size: 1.25rem; /* Perkecil judul "Ringkasan Pesanan" */
        }
        .summary-card .fs-5 {
            font-size: 1.1rem !important; /* Perkecil "Grand Total Akhir" */
        }
        .summary-card .fs-4 {
            font-size: 1.2rem !important; /* Perkecil "Total Bayar Sekarang" */
        }
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="container" style="padding-top: 6rem; padding-bottom: 6rem;">

        @if ($cartItems->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                <h2 class="fw-bold mt-4" style="color: var(--brand-text);">{{ __('cart.empty_cart_title') }}</h2>
                <p class="fs-5 text-muted">{{ __('cart.empty_cart_subtitle') }}</p>
                <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">{{ __('cart.empty_cart_button') }}</a>
            </div>
        @else
            <div class="text-center mb-5 cart-header">
                <h1 class="section-title">{{ __('cart.page_title') }}</h1>
                <p class="lead text-muted">{{ __('cart.page_subtitle') }}</p>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" id="cart-form">
                @csrf
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-7">
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all" checked>
                                    <label class="form-check-label fw-bold" for="select-all" style="color: var(--brand-text);">
                                        {{ __('cart.select_all') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        @foreach ($cartItems as $item)
                            <div class="card mb-3 cart-item-card"> 
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
                                                        <li>
                                                            <i class="bi bi-camera-video"></i>
                                                            {{ $item->session_type == 'Online' ? __('cart.session_online') : __('cart.session_offline') }} & {{ $item->contact_preference == 'chat_only' ? __('cart.contact_chat_only') : __('cart.contact_chat_and_call') }}
                                                        </li>
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
                                                    <span class="text-muted">{{ __('cart.base_price') }}</span>
                                                    <span class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </li>
                                                @if ($item->hours > 0)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span class="text-muted">{{ __('cart.hourly_price', ['hours' => $item->hours]) }}</span>
                                                    <span class="text-muted">Rp {{ number_format($item->hourly_price * $item->hours, 0, ',', '.') }}</span>
                                                </li>
                                                @endif
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span class="text-muted">{{ __('cart.subtotal') }}</span>
                                                    <span class="text-muted">Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}</span>
                                                </li>
                                                @php $itemDiscount = $item->item_subtotal - $item->final_item_price; @endphp
                                                @if ($itemDiscount > 0)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span style="color: var(--brand-danger);">{{ __('cart.discount') }}</span>
                                                    <span style="color: var(--brand-danger);">- Rp {{ number_format($itemDiscount, 0, ',', '.') }}</span>
                                                </li>
                                                @endif
                                            </ul>
                                            <hr class="my-2" style="border-color: var(--brand-border);">
                                            <p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);">
                                                <span>{{ __('cart.total_item') }}</span>
                                                <span>Rp {{ number_format($item->final_item_price, 0, ',', '.') }}</span>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('front.layanan') }}" class="btn btn-brand-outline">{{ __('cart.order_again_button') }}</a>
                                                <button type="button" class="btn btn-link p-0 remove-btn" data-id="{{ $item->id }}" title="{{ __('cart.remove_item_title') }}">
                                                    <i class="bi bi-trash3-fill fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-lg-5">
                        <div class="summary-card sticky-summary">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4" style="color: var(--brand-primary);">{{ __('cart.summary_title') }}</h4>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ __('cart.summary_subtotal') }}</span>
                                    <span class="text-muted" id="summary-subtotal">Rp {{ $subtotal }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">{{ __('cart.summary_total_discount') }}</span>
                                    <span class="text-danger" id="summary-discount">- Rp {{ $totalDiscount }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 mb-3" style="color: var(--brand-text);">
                                    <span>{{ __('cart.summary_grand_total') }}</span>
                                    <span id="summary-grandtotal">Rp {{ $grandTotal }}</span>
                                </div>
                                <hr style="border-color: var(--brand-border);">
                                <div class="mb-3">
                                    <label for="payment-type-select" class="form-label fw-bold" style="color: var(--brand-text);">{{ __('cart.summary_payment_type') }}</label>
                                    <select class="form-select" id="payment-type-select" name="global_payment_type">
                                        <option value="full_payment" selected>{{ __('cart.payment_full') }}</option>
                                        <option value="dp">{{ __('cart.payment_dp') }}</option>
                                    </select>
                                </div>
                                <hr style="border-color: var(--brand-border);">
                                <div class="d-flex justify-content-between fw-bolder fs-4 mt-3" style="color: var(--brand-primary);">
                                    <span style="white-space: nowrap; margin-right: 1rem;">{{ __('cart.summary_total_to_pay') }}</span>
                                    <span id="summary-totalpay">Rp {{ $totalToPayNow }}</span>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-checkout">{{ __('cart.summary_checkout_button') }}</button>
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
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
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
    updateSummary(); // Initial calculation
});
</script>
@endpush