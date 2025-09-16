@extends('layouts.app')

@section('title', 'Keranjang Belanja Anda')

@push('styles')
{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --brand-primary: #0C2C5A;
        --brand-gold: #FFB700;
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
    .free-consultation-badge {
        background: linear-gradient(45deg, var(--brand-gold), #FFC533);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 15px;
        font-weight: bold;
        margin-left: 0.5rem;
    }
    /* Override warna hijau default Bootstrap menjadi biru Indiegologi */
    .text-success {
        color: var(--brand-primary) !important;
    }
    /* Style untuk checkbox yang di-check */
    .form-check-input:checked {
        background-color: var(--brand-primary);
        border-color: var(--brand-primary);
    }
    .form-check-input:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }
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
    /* Style untuk thumbnail konsultasi gratis */
    .cart-item-card img[alt*="Konsultasi Gratis"] {
        background-color: #0C2C5A !important;
        background-image: url('https://placehold.co/60x60/0C2C5A/FFFFFF?text=GRATIS') !important;
    }
    
    /* Override untuk placeholder image konsultasi gratis di guest cart */
    .cart-item-card img[src*="placehold.co"][src*="D4AF37"],
    .cart-item-card img[src*="placehold.co"][src*="FFB700"] {
        content: url('https://placehold.co/60x60/0C2C5A/FFFFFF?text=GRATIS') !important;
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
                {{-- Empty cart message --}}
                <div class="text-center py-5" data-aos="zoom-in">
                    <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                    <h2 class="fw-bold mt-4" style="color: var(--brand-text);">Keranjang Anda Kosong</h2>
                    <p class="fs-5 text-muted">Mari ciptakan momen berkesan dengan layanan kami.</p>
                    <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">Jelajahi Layanan</a>
                </div>
            @else
                {{-- Cart header --}}
                <div class="text-center mb-5 cart-header" data-aos="fade-down">
                    <h1 class="section-title">Keranjang Belanja Anda</h1>
                    <p class="lead text-muted">Satu langkah lagi untuk menjadi versi terbaik Anda.</p>
                </div>
                
                <form action="{{ route('checkout.process') }}" method="POST" id="cart-form-logged-in">
                    @csrf
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-7">
                            {{-- Select All card --}}
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
                            
                            {{-- Cart Items Loop --}}
                            @foreach ($cartItems as $item)
                                <div class="card mb-3 cart-item-card" data-service-id="{{ $item->service_id }}" data-aos="fade-right" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            {{-- Item Details Column --}}
                                            <div class="col-12 col-md-7 mb-4 mb-md-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check me-3 pt-1">
                                                        <input class="form-check-input item-checkbox" type="checkbox" 
                                                               name="selected_items[]" value="{{ $item->id }}" 
                                                               id="item-{{ $item->id }}" checked>
                                                    </div>
                                                    
                                                    <div class="position-relative me-3">
                                                        <img src="{{ $item->service_thumbnail }}" 
                                                             alt="{{ $item->service_title }}" 
                                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                    </div>
                                                    
                                                    <div class="flex-grow-1">
                                                        <h5 class="fw-bold mb-2 fs-6" style="color: var(--brand-text);">
                                                            {{ $item->service_title }}
                                                            @if($item->isFreeConsultation())
                                                                <span class="free-consultation-badge">GRATIS</span>
                                                            @endif
                                                        </h5>
                                                        
                                                        <ul class="item-details-list">
                                                            <li>
                                                                <i class="bi bi-calendar-check"></i>
                                                                {{ \Carbon\Carbon::parse($item->booked_date)->translatedFormat('d M Y') }}, 
                                                                {{ $item->booked_time }}
                                                                @if($item->isFreeConsultation())
                                                                    (1 jam)
                                                                @else
                                                                    ({{ $item->hours }} jam)
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <i class="bi bi-camera-video"></i>
                                                                {{ $item->session_type }} & 
                                                                {{ $item->contact_preference == 'chat_only' ? 'Chat' : 'Chat & Call' }}
                                                            </li>
                                                            @if($item->offline_address)
                                                                <li><i class="bi bi-geo-alt"></i>{{ $item->offline_address }}</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Pricing Column --}}
                                            <div class="col-12 col-md-5 border-md-start ps-md-4 mt-4 mt-md-0 pt-4 pt-md-0 border-top border-md-0">
                                                @if($item->isFreeConsultation())
                                                    {{-- Free consultation pricing --}}
                                                    <ul class="list-group list-group-flush price-details-list small">
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span class="text-muted">Harga Dasar:</span>
                                                            <span class="fw-bold" style="color: var(--brand-primary);">GRATIS</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span class="text-muted">Durasi:</span>
                                                            <span class="text-muted">1 Jam</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span class="text-muted">Subtotal:</span>
                                                            <span style="color: var(--brand-primary);">Rp 0</span>
                                                        </li>
                                                    </ul>
                                                    <hr class="my-2" style="border-color: var(--brand-border);">
                                                    <p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);">
                                                        <span>Total Item:</span>
                                                        <span>Rp 0</span>
                                                    </p>
                                                @else
                                                    {{-- Regular service pricing --}}
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
                                                @endif
                                                
                                                {{-- Action Buttons --}}
                                                <div class="d-flex justify-content-between align-items-center">
                                                    @if(!$item->isFreeConsultation())
                                                        <a href="{{ route('front.layanan') }}" class="btn btn-brand-outline">Pesan Lagi</a>
                                                    @else
                                                        <span class="text-muted small">Konsultasi Gratis</span>
                                                    @endif
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
                        
                        {{-- Summary Section --}}
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
                
                {{-- Hidden form for removing items --}}
                <form id="remove-item-form" action="{{ route('front.cart.remove') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="id" id="remove-item-id">
                </form>
            @endif
        @else
            {{-- Guest Cart Display --}}
            <div id="temp-cart-display">
                <div class="text-center py-5" data-aos="zoom-in">
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
{{-- AOS Animation --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        once: false,
        offset: 100,
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // ===========================================
    // FUNGSI UNTUK USER YANG SUDAH LOGIN
    // ===========================================
    @auth
        // Hapus temp cart untuk user yang sudah login
        localStorage.removeItem('tempCart');

        // Update ringkasan keranjang
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
                },
                error: function(xhr, status, error) {
                    console.error('Error updating summary:', error);
                    // Tambahkan notifikasi error jika perlu
                    Swal.fire('Error', 'Gagal memperbarui ringkasan pesanan.', 'error');
                }
            });
        }

        // Event handlers untuk user yang login
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
            Swal.fire({
                title: 'Hapus Item?',
                text: "Apakah Anda yakin ingin menghapus item ini dari keranjang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#remove-item-id').val(itemId);
                    $('#remove-item-form').submit();
                }
            });
        });

        // Update awal saat halaman dimuat
        updateSummary();
        
        // Fix thumbnail konsultasi gratis menjadi biru
        function fixFreeConsultationThumbnails() {
            $('.cart-item-card img').each(function() {
                const $img = $(this);
                const alt = $img.attr('alt');
                const src = $img.attr('src');
                
                // Check if this is a free consultation thumbnail
                if (alt && alt.toLowerCase().includes('konsultasi gratis')) {
                    // Replace with blue thumbnail
                    $img.attr('src', 'https://placehold.co/60x60/0C2C5A/FFFFFF?text=GRATIS');
                }
                
                // Also check src for placeholder images with yellow/gold colors
                if (src && src.includes('placehold.co') && (src.includes('D4AF37') || src.includes('FFB700') || src.includes('text=FREE'))) {
                    $img.attr('src', 'https://placehold.co/60x60/0C2C5A/FFFFFF?text=GRATIS');
                }
            });
        }
        
        // Run fix on page load
        fixFreeConsultationThumbnails();
        
        // Run fix again after any dynamic content updates
        setTimeout(fixFreeConsultationThumbnails, 500);

    @else
        // ===========================================
        // FUNGSI UNTUK GUEST USER (TEMP CART)
        // ===========================================
        
        // Helper untuk manajemen localStorage
        function getTempCart() {
            try {
                const cart = localStorage.getItem('tempCart');
                return cart ? JSON.parse(cart) : {};
            } catch (e) {
                console.error("Error parsing temp cart:", e);
                localStorage.removeItem('tempCart'); // Hapus data korup
                return {};
            }
        }

        function saveTempCart(cart) {
            try {
                localStorage.setItem('tempCart', JSON.stringify(cart));
            } catch (e) {
                console.error("Error saving temp cart:", e);
            }
        }
        
        // [BARU] Helper untuk mendeteksi konsultasi gratis (legacy & baru)
        function isFreeConsultation(serviceId, item) {
            return serviceId === 'free-consultation' || (item && item.consultation_type === 'free-consultation-new');
        }

        // Fetch pricing dari server hanya untuk layanan reguler
        async function fetchServicePricing(cartItems) {
            // [DIPERBAIKI] Filter hanya service ID yang bukan konsultasi gratis
            const regularServiceIds = Object.keys(cartItems).filter(id => !isFreeConsultation(id, cartItems[id]));

            if (regularServiceIds.length === 0) {
                return {}; // Tidak perlu panggil API jika hanya ada item gratis
            }

            try {
                const response = await fetch('{{ route("front.service.pricing") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        service_ids: regularServiceIds // Kirim ID yang sudah difilter
                    })
                });

                if (!response.ok) throw new Error('Network response was not ok.');
                const data = await response.json();
                return data.success ? data.pricing : {};
            } catch (error) {
                console.error('Error fetching service pricing:', error);
                return {}; // Kembalikan objek kosong jika gagal
            }
        }

        // Hitung summary dengan data harga yang benar
        function calculateSummary(cartItems, paymentType, pricingData = {}) {
            let subtotal = 0;
            let totalDiscount = 0;
            let grandTotal = 0;

            for (const [id, item] of Object.entries(cartItems)) {
                // [DIPERBAIKI] Gunakan helper untuk skip semua jenis konsultasi gratis
                if (isFreeConsultation(id, item)) continue;
                
                let basePrice = 0;
                let hourlyPrice = 0;
                
                if (pricingData[id]) {
                    basePrice = parseFloat(pricingData[id].price) || 0;
                    hourlyPrice = parseFloat(pricingData[id].hourly_price) || 0;
                }
                
                const hours = parseInt(item.hours) || 1;
                const itemSubtotal = basePrice + (hourlyPrice * hours);
                const discountAmount = 0; // Asumsi diskon dihitung di backend, untuk guest kita tampilkan 0
                const finalItemPrice = itemSubtotal - discountAmount;

                subtotal += itemSubtotal;
                totalDiscount += discountAmount;
                grandTotal += finalItemPrice;
            }

            let totalToPayNow = grandTotal;
            if (paymentType === 'dp' && grandTotal > 0) {
                totalToPayNow = grandTotal * 0.5;
            }

            const formatToIDR = (num) => num.toLocaleString('id-ID');
            return {
                subtotal: formatToIDR(subtotal),
                totalDiscount: formatToIDR(totalDiscount),
                grandTotal: formatToIDR(grandTotal),
                totalToPayNow: formatToIDR(totalToPayNow)
            };
        }

        // Render temp cart dengan logika yang disempurnakan
        async function renderTempCart(cartItems) {
            const container = $('#temp-cart-display');
            container.empty().html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat keranjang...</p></div>');

            if (Object.keys(cartItems).length === 0) {
                container.html(`
                    <div class="text-center py-5" data-aos="zoom-in">
                        <i class="bi bi-bag" style="font-size: 5rem; color: #ccc;"></i>
                        <h2 class="fw-bold mt-4" style="color: var(--brand-text);">Keranjang Anda Kosong</h2>
                        <p class="fs-5 text-muted">Mari ciptakan momen berkesan dengan layanan kami.</p>
                        <a href="{{ route('front.layanan') }}" class="btn btn-checkout mt-3">Jelajahi Layanan</a>
                    </div>
                `);
                AOS.refresh();
                return;
            }

            // [DIPERBAIKI] Fetch pricing data hanya untuk item yang relevan
            const pricingData = await fetchServicePricing(cartItems);
            container.empty();

            const cartHeader = `
                <div class="text-center mb-5 cart-header" data-aos="fade-down">
                    <h1 class="section-title">Keranjang Belanja Anda</h1>
                    <p class="lead text-muted">Satu langkah lagi untuk menjadi versi terbaik Anda.</p>
                </div>
                <div class="alert alert-info" role="alert" data-aos="fade-in" data-aos-delay="100">
                    <h5 class="alert-heading">Keranjang Sementara</h5>
                    <p class="mb-0">Layanan yang Anda pilih disimpan sementara. <a href="{{ route('login') }}" class="alert-link">Masuk atau Daftar</a> untuk melanjutkan ke pembayaran.</p>
                </div>
            `;
            container.append(cartHeader);

            const row = $('<div class="row g-4 g-lg-5"></div>');
            const cartListCol = $('<div class="col-lg-7"></div>');

            Object.entries(cartItems).forEach(([serviceId, item], index) => {
                // [DIPERBAIKI] Logika terpusat untuk mendeteksi item gratis dan menyiapkan datanya
                const isFree = isFreeConsultation(serviceId, item);
                let serviceTitle, serviceThumbnail;
                let basePrice = 0, hourlyPrice = 0;

                if (isFree) {
                    serviceTitle = "Konsultasi Gratis";
                    serviceThumbnail = `https://placehold.co/60x60/0C2C5A/FFFFFF?text=GRATIS`;
                } else if (pricingData[serviceId]) {
                    const details = pricingData[serviceId];
                    serviceTitle = details.title;
                    serviceThumbnail = details.thumbnail || 'https://placehold.co/60x60/cccccc/ffffff?text=No+Image';
                    basePrice = parseFloat(details.price) || 0;
                    hourlyPrice = parseFloat(details.hourly_price) || 0;
                } else {
                    // Fallback jika API gagal atau data tidak ditemukan
                    serviceTitle = "Layanan Tidak Tersedia";
                    serviceThumbnail = 'https://placehold.co/60x60/dc3545/ffffff?text=Error';
                }
                
                const hours = parseInt(item.hours) || 1;
                const itemSubtotal = isFree ? 0 : basePrice + (hourlyPrice * hours);
                const finalItemPrice = itemSubtotal; // Diskon tidak dihitung untuk guest
                
                // [DIPERBAIKI] Format tanggal yang lebih aman
                const displayDate = item.booked_date ? new Date(item.booked_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : 'Jadwal belum dipilih';

                const itemHtml = `
                    <div class="card mb-3 cart-item-card" data-service-id="${serviceId}" data-aos="fade-right" data-aos-delay="${index * 100}">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-12 col-md-7 mb-4 mb-md-0">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative me-3">
                                            <img src="${serviceThumbnail}" alt="${serviceTitle}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fw-bold mb-2 fs-6" style="color: var(--brand-text);">
                                                ${serviceTitle}
                                                ${isFree ? '<span style="background: linear-gradient(45deg, #FFB700, #FFC533); color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 15px; font-weight: bold; margin-left: 0.5rem;">GRATIS</span>' : ''}
                                            </h5>
                                            <ul class="item-details-list">
                                                <li><i class="bi bi-calendar-check"></i>${displayDate}, ${item.booked_time} ${isFree ? '(1 jam)' : '(' + hours + ' jam)'}</li>
                                                <li><i class="bi bi-camera-video"></i>${item.session_type} & ${item.contact_preference === 'chat_only' ? 'Chat' : 'Chat & Call'}</li>
                                                ${item.offline_address ? `<li><i class="bi bi-geo-alt"></i>${item.offline_address}</li>` : ''}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 border-md-start ps-md-4 mt-4 mt-md-0 pt-4 pt-md-0 border-top border-md-0">
                                    ${isFree ? `
                                        <ul class="list-group list-group-flush price-details-list small">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span class="text-muted">Harga:</span>
                                                <span class="fw-bold" style="color: #0C2C5A;">GRATIS</span>
                                            </li>
                                        </ul>
                                        <hr class="my-2" style="border-color: var(--brand-border);">
                                        <p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);">
                                            <span>Total Item:</span>
                                            <span style="color: #0C2C5A;">Rp 0</span>
                                        </p>
                                    ` : `
                                        <ul class="list-group list-group-flush price-details-list small">
                                            <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Harga Dasar:</span><span class="text-muted">Rp ${basePrice.toLocaleString('id-ID')}</span></li>
                                            ${hourlyPrice > 0 && hours > 0 ? `<li class="list-group-item d-flex justify-content-between"><span class="text-muted">Per Jam (${hours} jam):</span><span class="text-muted">Rp ${(hourlyPrice * hours).toLocaleString('id-ID')}</span></li>` : ''}
                                            <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Subtotal:</span><span class="text-muted">Rp ${itemSubtotal.toLocaleString('id-ID')}</span></li>
                                        </ul>
                                        <hr class="my-2" style="border-color: var(--brand-border);"><p class="fw-bold mb-3 d-flex justify-content-between fs-6" style="color: var(--brand-primary);"><span>Total Item:</span><span>Rp ${finalItemPrice.toLocaleString('id-ID')}</span></p>
                                    `}
                                    <div class="d-flex justify-content-between align-items-center">
                                        ${!isFree ? '<a href="{{ route("front.layanan") }}" class="btn btn-brand-outline">Pesan Lagi</a>' : '<span class="text-muted small">Konsultasi Gratis</span>'}
                                        <button type="button" class="btn btn-link p-0 remove-btn-temp" data-id="${serviceId}" title="Hapus item"><i class="bi bi-trash3-fill fs-5"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                cartListCol.append(itemHtml);
            });

            // Build summary
            const summary = calculateSummary(cartItems, 'full_payment', pricingData);
            const summaryCol = `
                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="summary-card sticky-summary">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4" style="color: var(--brand-primary);">Ringkasan Pesanan</h4>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal Semua Item</span><span class="text-muted" id="summary-subtotal-temp">Rp ${summary.subtotal}</span></div>
                            <div class="d-flex justify-content-between mb-3"><span class="text-muted">Total Diskon</span><span class="text-danger" id="summary-discount-temp">- Rp ${summary.totalDiscount}</span></div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3" style="color: var(--brand-text);"><span>Grand Total Akhir</span><span id="summary-grandtotal-temp">Rp ${summary.grandTotal}</span></div>
                            <hr style="border-color: var(--brand-border);"><div class="mb-3"><label for="payment-type-select-temp" class="form-label fw-bold" style="color: var(--brand-text);">Pilih Tipe Pembayaran</label><select class="form-select" id="payment-type-select-temp"><option value="full_payment" selected>Pembayaran Penuh</option><option value="dp">DP (50%)</option></select></div>
                            <hr style="border-color: var(--brand-border);"><div class="d-flex justify-content-between fw-bolder fs-4 mt-3" style="color: var(--brand-primary);"><span style="white-space: nowrap; margin-right: 1rem;">Total Bayar Sekarang</span><span id="summary-totalpay-temp">Rp ${summary.totalToPayNow}</span></div>
                            <div class="d-grid mt-4"><a href="{{ route('login') }}" class="btn btn-checkout">Masuk untuk Checkout</a></div>
                        </div>
                    </div>
                </div>
            `;

            row.append(cartListCol).append(summaryCol);
            container.append(row);
            AOS.refresh();

            // Event handlers untuk temp cart (delegasi event untuk elemen yang baru dibuat)
            container.on('change', '#payment-type-select-temp', function() {
                const tempCart = getTempCart();
                const summary = calculateSummary(tempCart, $(this).val(), pricingData);
                $('#summary-subtotal-temp').text('Rp ' + summary.subtotal);
                $('#summary-discount-temp').text('- Rp ' + summary.totalDiscount);
                $('#summary-grandtotal-temp').text('Rp ' + summary.grandTotal);
                $('#summary-totalpay-temp').text('Rp ' + summary.totalToPayNow);
            });

            container.on('click', '.remove-btn-temp', function() {
                const serviceId = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Item?', text: "Item ini akan dihapus dari keranjang sementara Anda.", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const tempCart = getTempCart();
                        delete tempCart[serviceId];
                        saveTempCart(tempCart);
                        renderTempCart(tempCart); // Re-render the cart
                        Swal.fire('Dihapus!', 'Item telah dihapus dari keranjang.', 'success');
                    }
                });
            });
        }

        // Inisialisasi render temp cart untuk guest user
        const tempCart = getTempCart();
        renderTempCart(tempCart);
    @endauth
});
</script>
@endpush