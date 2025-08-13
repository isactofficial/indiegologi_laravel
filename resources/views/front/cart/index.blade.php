@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    /* Desktop-specific styles */
    @media (min-width: 992px) {
        .sticky-summary {
            position: sticky;
            top: 120px;
        }
    }
    .cart-item-card {
        transition: all 0.2s ease-in-out;
    }
    .cart-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(12, 44, 90, 0.1) !important;
    }
    .remove-btn {
        color: #6c757d;
        font-size: 1.2rem;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .remove-btn:hover {
        color: var(--bs-danger);
    }
    .form-check-input:checked {
        background-color: var(--indiegologi-primary);
        border-color: var(--indiegologi-primary);
    }

    /* Mobile Responsive Styles */
    @media (max-width: 767.98px) {
        .cart-item-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .item-details-grid {
            grid-template-columns: 1fr; 
            gap: 0.8rem 1rem;
            width: 100%;
            margin-top: 1rem;
            font-size: 0.95rem;
        }
        .cart-item-actions {
            width: 100%;
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-price {
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .remove-btn {
            order: 2;
        }
    }
</style>
@endpush

@section('content')
<div class="container" style="margin-top: 120px; margin-bottom: 80px;">
    
    @if ($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
            <h2 class="fw-bold mt-4">Keranjang Anda Kosong</h2>
            <p class="fs-5 text-muted">Sepertinya Anda belum menambahkan layanan apapun.</p>
            <a href="{{ route('front.layanan') }}" class="btn btn-primary btn-lg mt-3">Jelajahi Layanan</a>
        </div>
    @else
        <div class="text-center mb-5">
            <h1 class="fw-bold" style="color: #0C2C5A;">Keranjang Anda</h1>
            <p class="text-muted">Pilih layanan yang ingin Anda bayar.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="cart-form">
            @csrf
            <div class="row g-4">
                {{-- Daftar Item Keranjang --}}
                <div class="col-lg-8">
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select-all" checked>
                                <label class="form-check-label fw-bold" for="select-all">
                                    Pilih Semua
                                </label>
                            </div>
                        </div>
                    </div>

                    @foreach ($cartItems as $item)
                        <div class="card mb-3 shadow-sm border-0 cart-item-card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start">
                                    <div class="form-check me-3 d-flex align-items-center pt-1">
                                        <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="item-{{ $item->id }}" checked>
                                    </div>
                                    
                                    <div class="flex-grow-1 d-flex flex-column flex-md-row cart-item-details">
                                        <img src="{{ asset('storage/' . $item->service->thumbnail) }}" alt="{{ $item->service->title }}" class="rounded me-md-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        
                                        <div class="mt-2 mt-md-0">
                                            <h5 class="fw-bold mb-1 fs-6">{{ $item->service->title }}</h5>
                                            <div class="item-details-grid small text-muted">
                                                <span><i class="bi bi-calendar-check me-2"></i>{{ \Carbon\Carbon::parse($item->booked_date)->translatedFormat('d M Y') }}, {{ $item->booked_time }}</span>
                                                <span><i class="bi bi-person-check me-2"></i>{{ $item->session_type }}</span>
                                                <span><i class="bi bi-credit-card me-2"></i>{{ $item->payment_type == 'dp' ? 'DP (50%)' : 'Full Payment' }}</span>
                                                <span><i class="bi bi-telephone me-2"></i>{{ $item->contact_preference == 'chat_only' ? 'Chat Only' : 'Chat & Call' }}</span>
                                            </div>
                                        </div>

                                        <div class="ms-md-auto text-end cart-item-actions">
                                            {{-- [INI PERBAIKANNYA] Menggunakan variabel $item->item_total --}}
                                            <p class="fw-bold mb-2 item-price" style="color: #0C2C5A;">Rp {{ number_format($item->item_total, 0, ',', '.') }}</p>
                                            <a href="{{ route('front.layanan') }}" class="btn btn-sm btn-outline-primary d-md-none">Pesan Lagi</a>
                                            <div class="d-none d-md-flex align-items-center gap-2">
                                                <a href="{{ route('front.layanan') }}" class="btn btn-sm btn-outline-primary">Pesan Lagi</a>
                                                <button type="button" class="btn btn-link p-0 remove-btn" data-id="{{ $item->id }}" title="Hapus item">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan Pesanan --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-summary">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Ringkasan Pesanan</h4>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="text-muted" id="summary-subtotal">Rp {{ $subtotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Diskon</span>
                                <span class="text-danger" id="summary-discount">- Rp {{ $totalDiscount }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>Grand Total</span>
                                <span id="summary-grandtotal">Rp {{ $grandTotal }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bolder fs-4 text-primary mt-3">
                                <span>Total Bayar</span>
                                <span id="summary-totalpay">Rp {{ $totalToPayNow }}</span>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Lanjutkan ke Pembayaran</button>
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
@endsection

@push('scripts')
{{-- JavaScript tidak perlu diubah --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateSummary() {
        let selectedIds = [];
        $('.item-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        $.ajax({
            url: '{{ route("front.cart.updateSummary") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
            },
            success: function(response) {
                $('#summary-subtotal').text('Rp ' + response.subtotal);
                $('#summary-discount').text('- Rp ' + response.totalDiscount);
                $('#summary-grandtotal').text('Rp ' + response.grandTotal);
                $('#summary-totalpay').text('Rp ' + response.totalToPayNow);
            }
        });
    }

    $('.item-checkbox').on('change', function() {
        let allChecked = $('.item-checkbox:checked').length === $('.item-checkbox').length;
        $('#select-all').prop('checked', allChecked);
        updateSummary();
    });

    $('#select-all').on('change', function() {
        $('.item-checkbox').prop('checked', this.checked);
        updateSummary();
    });

    $('.remove-btn').on('click', function() {
        let itemId = $(this).data('id');
        $('#remove-item-id').val(itemId);
        $('#remove-item-form').submit();
    });
});
</script>
@endpush
