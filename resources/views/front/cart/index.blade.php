@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    /* === [BRAND IDENTITY] Cart Page Styles === */
    .cart-page {
        background-color: #f9fafb;
    }
    .cart-header .section-title {
        font-weight: 700;
        color: var(--indiegologi-primary);
    }
    .cart-item-card, .summary-card {
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
    }
    .cart-item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(12, 44, 90, 0.08) !important;
    }
    .item-details-list {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
    }
    .item-details-list li {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: #6b7280;
    }
    .item-details-list i {
        color: var(--indiegologi-primary);
        margin-right: 0.75rem;
        width: 16px;
    }
    .remove-btn {
        color: var(--indiegologi-secondary);
        transition: color 0.2s ease;
    }
    .remove-btn:hover {
        color: var(--bs-danger);
    }
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 120px;
        }
    }
    .btn-checkout {
        background-color: var(--indiegologi-primary);
        color: #fff;
        font-weight: 600;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
    }
    .btn-checkout:hover {
        background-color: #082142;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="container" style="padding-top: 6rem; padding-bottom: 6rem;">
        
        @if ($cartItems->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
                <h2 class="fw-bold mt-4">Keranjang Anda Kosong</h2>
                <p class="fs-5 text-muted">Sepertinya Anda belum menambahkan layanan apapun.</p>
                <a href="{{ route('front.layanan') }}" class="btn btn-primary btn-lg mt-3">Jelajahi Layanan</a>
            </div>
        @else
            <div class="text-center mb-5 cart-header">
                <h1 class="section-title">Keranjang Belanja</h1>
                <p class="lead text-muted">Periksa kembali detail pesanan Anda sebelum melanjutkan.</p>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" id="cart-form">
                @csrf
                <div class="row g-4 g-lg-5">
                    {{-- Daftar Item Keranjang --}}
                    <div class="col-lg-8">
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all" checked>
                                    <label class="form-check-label fw-bold" for="select-all">
                                        Pilih Semua Layanan
                                    </label>
                                </div>
                            </div>
                        </div>

                        @foreach ($cartItems as $item)
                            <div class="card mb-3 shadow-sm border-0 cart-item-card">
                                <div class="card-body p-3 p-md-4">
                                    <div class="row">
                                        {{-- Kolom Detail Layanan --}}
                                        <div class="col-md-7">
                                            <div class="d-flex">
                                                <div class="form-check me-3 pt-1">
                                                    <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="item-{{ $item->id }}" checked>
                                                </div>
                                                <img src="{{ asset('storage/' . $item->service->thumbnail) }}" alt="{{ $item->service->title }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold mb-2 fs-6">{{ $item->service->title }}</h5>
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
                                        {{-- Kolom Rincian Biaya & Aksi --}}
                                        <div class="col-md-5 mt-3 mt-md-0 border-md-start ps-md-4">
                                            <div class="small text-muted">
                                                <p class="mb-0 d-flex justify-content-between"><span>Harga Dasar:</span> <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span></p>
                                                @if ($item->hours > 0)
                                                    <p class="mb-0 d-flex justify-content-between"><span>Per Jam ({{ $item->hours }} jam):</span> <span>Rp {{ number_format($item->hourly_price * $item->hours, 0, ',', '.') }}</span></p>
                                                @endif
                                                <p class="mb-2 d-flex justify-content-between"><span>Subtotal Item:</span> <span>Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}</span></p>
                                            </div>
                                            <p class="fw-bold mb-2 d-flex justify-content-between" style="color: var(--indiegologi-primary);"><span>Total Item:</span> <span>Rp {{ number_format($item->final_item_price, 0, ',', '.') }}</span></p>
                                            <div class="d-flex justify-content-end align-items-center mt-2">
                                                <a href="{{ route('front.layanan') }}" class="btn btn-sm me-2" style="color: #0C2C5A; border: 1px solid #0C2C5A; background: #fff;">Pesan Lagi</a>
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

                    {{-- Ringkasan Pesanan --}}
                    <div class="col-lg-4">
                        <div class="summary-card sticky-summary">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4">Ringkasan Pesanan</h4>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal Semua Item</span>
                                    <span class="text-muted" id="summary-subtotal">Rp {{ $subtotal }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Total Diskon</span>
                                    <span class="text-danger" id="summary-discount">- Rp {{ $totalDiscount }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                    <span>Grand Total Akhir</span>
                                    <span id="summary-grandtotal">Rp {{ $grandTotal }}</span>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="payment-type-select" class="form-label fw-bold">Pilih Tipe Pembayaran</label>
                                    <select class="form-select" id="payment-type-select" name="global_payment_type">
                                        <option value="full_payment" selected>Pembayaran Penuh</option>
                                        <option value="dp">DP (50%)</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bolder fs-4 mt-3" style="color: var(--indiegologi-primary);">
                                    <span>Total Bayar Sekarang</span>
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
