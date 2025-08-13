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
            display: grid; /* Gunakan grid untuk layout detail */
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
            flex-direction: column; /* Ubah ke kolom untuk mobile */
            align-items: flex-end; /* Sesuaikan alignment */
        }
        .item-price {
            font-size: 1.1rem;
            margin-bottom: 0;
            width: 100%; /* Agar total harga bisa di sisi kanan */
            text-align: end;
        }
        .remove-btn {
            order: 2; /* Agar tombol hapus di bawah */
            margin-top: 0.5rem;
        }
        .cart-item-actions .d-none.d-md-flex {
            display: flex !important; /* Tampilkan ini di mobile juga */
            flex-direction: row; /* Biar tetap sejajar */
            width: 100%;
            justify-content: flex-end;
            margin-top: 0.5rem;
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

        {{-- Form untuk proses checkout --}}
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
                                        {{-- Pastikan item->service tidak null sebelum mengakses propertinya --}}
                                        @if ($item->service)
                                            <img src="{{ asset('storage/' . $item->service->thumbnail) }}" alt="{{ $item->service->title }}" class="rounded me-md-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            {{-- Placeholder jika layanan tidak ditemukan --}}
                                            <img src="{{ 'https://placehold.co/80x80/cccccc/333333?text=No+Image' }}" alt="Layanan tidak ditemukan" class="rounded me-md-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        @endif

                                        <div class="mt-2 mt-md-0">
                                            <h5 class="fw-bold mb-1 fs-6">{{ $item->service ? $item->service->title : 'Layanan Tidak Ditemukan' }}</h5>
                                            <div class="item-details-grid small text-muted">
                                                <span><i class="bi bi-calendar-check me-2"></i>{{ \Carbon\Carbon::parse($item->booked_date)->translatedFormat('d M Y') }}, {{ $item->booked_time }}</span>
                                                <span><i class="bi bi-person-check me-2"></i>{{ $item->session_type }}</span>
                                                {{-- payment_type dihapus dari tampilan per item --}}
                                                <span><i class="bi bi-telephone me-2"></i>{{ $item->contact_preference == 'chat_only' ? 'Chat Only' : 'Chat & Call' }}</span>
                                                @if ($item->offline_address)
                                                    <span><i class="bi bi-geo-alt me-2"></i>{{ $item->offline_address }}</span>
                                                @endif
                                                @if ($item->referral_code)
                                                    <span><i class="bi bi-tag me-2"></i>Kode Referral: {{ $item->referral_code }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ms-md-auto text-end cart-item-actions">
                                            {{-- Menampilkan detail harga menggunakan accessors --}}
                                            <p class="mb-0 text-muted small">Harga Dasar: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            @if ($item->hours > 0)
                                                <p class="mb-0 text-muted small">Per Jam ({{ $item->hours }} jam): Rp {{ number_format($item->hourly_price * $item->hours, 0, ',', '.') }}</p>
                                            @endif
                                            <p class="mb-0 text-muted small">Subtotal Item: Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}</p>
                                            @if ($item->discount_amount > 0)
                                                <p class="mb-0 text-danger small">Diskon: -Rp {{ number_format($item->discount_amount, 0, ',', '.') }}</p>
                                            @endif
                                            {{-- Harga total akhir item setelah diskon --}}
                                            <p class="fw-bold mb-2 item-price" style="color: #0C2C5A;">Total Item: Rp {{ number_format($item->final_item_price, 0, ',', '.') }}</p>
                                            {{-- total_to_pay per item dihapus dari tampilan karena sudah global --}}

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

                            {{-- Dropdown untuk memilih Payment Type --}}
                            <div class="mb-3">
                                <label for="payment-type-select" class="form-label fw-bold">Pilih Tipe Pembayaran</label>
                                <select class="form-select" id="payment-type-select" name="global_payment_type">
                                    <option value="full_payment" selected>Pembayaran Penuh</option>
                                    <option value="dp">DP (50%)</option>
                                </select>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between fw-bolder fs-4 text-primary mt-3">
                                <span>Total Bayar Sekarang</span>
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

        {{-- Form tersembunyi untuk menghapus item --}}
        <form id="remove-item-form" action="{{ route('front.cart.remove') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="id" id="remove-item-id">
        </form>
    @endif
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

            let selectedPaymentType = $('#payment-type-select').val(); // Ambil nilai payment type dari dropdown

            let postData = {
                _token: '{{ csrf_token() }}',
                ids: selectedIds,
                payment_type: selectedPaymentType // Kirim payment type ke controller
            };

            $.ajax({
                url: '{{ route("front.cart.updateSummary") }}',
                type: 'POST',
                data: postData,
                success: function(response) {
                    $('#summary-subtotal').text('Rp ' + response.subtotal);
                    $('#summary-discount').text('- Rp ' + response.totalDiscount);
                    $('#summary-grandtotal').text('Rp ' + response.grandTotal);
                    $('#summary-totalpay').text('Rp ' + response.totalToPayNow);

                    // Jika tidak ada item yang dipilih, disable tombol checkout
                    if (selectedIds.length === 0) {
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        $('button[type="submit"]').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error updating summary:", xhr.responseText);
                    // Opsional: tampilkan pesan error kepada pengguna
                }
            });
        }

        // Event listener untuk setiap checkbox item
        $('.item-checkbox').on('change', function() {
            let allChecked = $('.item-checkbox:checked').length === $('.item-checkbox').length;
            $('#select-all').prop('checked', allChecked); // Update status "Pilih Semua"
            updateSummary(); // Perbarui ringkasan
        });

        // Event listener untuk checkbox "Pilih Semua"
        $('#select-all').on('change', function() {
            $('.item-checkbox').prop('checked', this.checked); // Setel semua checkbox item sesuai "Pilih Semua"
            updateSummary(); // Perbarui ringkasan
        });

        // Event listener untuk dropdown payment type
        $('#payment-type-select').on('change', function() {
            updateSummary(); // Perbarui ringkasan saat payment type berubah
        });


        // Event listener untuk tombol hapus item
        $('.remove-btn').on('click', function() {
            let itemId = $(this).data('id');
            $('#remove-item-id').val(itemId);
            $('#remove-item-form').submit(); // Kirim form hapus
        });

        // Panggil updateSummary saat halaman pertama kali dimuat
        // Ini memastikan ringkasan ditampilkan dengan benar berdasarkan item yang tercentang secara default
        updateSummary();
    });
</script>
@endpush
