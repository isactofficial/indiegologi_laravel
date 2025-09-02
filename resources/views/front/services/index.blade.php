@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
    
    {{-- Library Animasi Scroll --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
        /* [IMPROVEMENT] Penyesuaian Tampilan Mobile & Animasi */

        /* Mendefinisikan animasi keyframe */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        /* Styling untuk setiap item yang akan dianimasikan */
        .stagger-item {
            opacity: 0; /* Sembunyikan item secara default */
        }

        /* Saat akordion TERBUKA (.show ditambahkan oleh Bootstrap) */
        .accordion-collapse.show .stagger-item {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        /* Memberi jeda (delay) pada setiap item */
        .accordion-collapse.show .stagger-item:nth-child(1) { animation-delay: 0.05s; }
        .accordion-collapse.show .stagger-item:nth-child(2) { animation-delay: 0.1s; }
        .accordion-collapse.show .stagger-item:nth-child(3) { animation-delay: 0.15s; }
        .accordion-collapse.show .stagger-item:nth-child(4) { animation-delay: 0.2s; }
        .accordion-collapse.show .stagger-item:nth-child(5) { animation-delay: 0.25s; }

        /* Styling untuk header akordion di mobile */
        @media (max-width: 767.98px) {
            .accordion-button .service-header-mobile {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .accordion-button .service-header-mobile-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
            }

            .accordion-button .service-header-mobile .service-thumbnail-mobile {
                width: 100%;
                height: 150px;
                object-fit: cover;
                border-radius: 8px;
                margin-bottom: 1rem;
            }
            
            .accordion-button h5 {
                font-size: 1.1rem; /* Ukuran font judul disesuaikan */
            }

            .accordion-button p {
                font-size: 0.85rem; /* Ukuran font deskripsi disesuaikan */
            }

            /* Mengubah tombol "Baca Selengkapnya" menjadi ikon */
            .btn-details-toggle {
                font-size: 0; /* Sembunyikan teks */
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
                flex-shrink: 0;
            }

            .btn-details-toggle::after {
                content: '\F285'; /* Ikon Bootstrap Chevron Right */
                font-family: 'bootstrap-icons';
                font-size: 1rem;
                transition: transform 0.3s ease;
            }

            .accordion-button:not(.collapsed) .btn-details-toggle::after {
                transform: rotate(90deg); /* Putar ikon saat akordion terbuka */
            }
        }
    </style>
@endpush

@section('content')
    <div class="service-details-page">
        <section class="container container-title mb-5" data-aos="fade-down">
            <div class="row">
                <h1 class="section-title">Penawaran Spesial <span class="ampersand-style">&</span> Paket Kesejahteraan</h1>
                <p class="section-desc">Temukan berbagai promo eksklusif dan paket layanan yang dirancang untuk mendukung perjalanan Anda menuju kesejahteraan mental optimal dengan nilai terbaik.</p>
            </div>
        </section>

        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-11">
                    <div class="accordion" id="servicesAccordion">
                        @forelse($services as $service)
                            <div class="accordion-item mb-3 rounded-4 shadow-sm" data-aos="fade-up" data-aos-duration="800">
                                <h2 class="accordion-header" id="heading-{{ $service->id }}">
                                    <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $service->id }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $service->id }}">
                                        
                                        {{-- [IMPROVEMENT] Header akordion dengan layout berbeda untuk desktop dan mobile --}}
                                        
                                        <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $service->thumbnail) }}"
                                                     alt="{{ $service->title }}" class="rounded-3 me-3"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <div>
                                                    <h5 class="fw-bold mb-1">{{ $service->title }}</h5>
                                                    <p class="text-muted mb-0">{{ Str::limit($service->short_description, 70) }}</p>
                                                </div>
                                            </div>
                                            <button class="btn-details-toggle" type="button">
                                                Baca Selengkapnya
                                            </button>
                                        </div>

                                        <div class="d-flex d-md-none service-header-mobile">
                                            <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="service-thumbnail-mobile">
                                            <div class="service-header-mobile-top">
                                                 <div>
                                                    <h5 class="fw-bold mb-1">{{ $service->title }}</h5>
                                                    <p class="text-muted mb-0">{{ Str::limit($service->short_description, 45) }}</p>
                                                </div>
                                                <button class="btn-details-toggle" type="button"></button>
                                            </div>
                                        </div>

                                    </div>
                                </h2>
                                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading-{{ $service->id }}" data-bs-parent="#servicesAccordion">
                                    <div class="accordion-body p-4">
                                        <div class="service-block" data-service-id="{{ $service->id }}"
                                             data-price="{{ $service->price }}"
                                             data-hourly-price="{{ $service->hourly_price }}">

                                            <div class="stagger-item">
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h6 class="fw-judul">Deskripsi Produk:</h6>
                                                        <p>{{ $service->product_description }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="stagger-item">
                                                <div class="form-section mb-4">
                                                    {{-- [IMPROVEMENT] Form lebih rapi di mobile (col-md-4 menjadi col-lg-4) --}}
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <h6 class="fw-bold">Pilih Jadwal Meditasi:</h6>
                                                            <small class="text-muted">(Pemesanan minimal H-1 sebelum jadwal yang diinginkan)</small>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label for="booked_date-{{ $service->id }}" class="form-label">Tanggal:</label>
                                                                <input type="date" id="booked_date-{{ $service->id }}" class="form-control service-date-picker" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label for="booked_time-{{ $service->id }}" class="form-label">Jam Mulai:</label>
                                                                <input type="time" id="booked_time-{{ $service->id }}" class="form-control booked_time-input" required>
                                                                <div class="invalid-feedback d-block time-error-message" style="display: none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label for="hours-{{ $service->id }}" class="form-label">Jumlah Jam</label>
                                                                <input type="number" id="hours-{{ $service->id }}" class="form-control hours-input" value="0" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label for="session_type-{{ $service->id }}" class="form-label">Pilihan Sesi</label>
                                                                <select id="session_type-{{ $service->id }}" class="form-select session-type-select">
                                                                    <option value="Online">Online</option>
                                                                    <option value="Offline">Offline</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 offline-address-container" style="display:none;">
                                                                <textarea id="offline_address-{{ $service->id }}" class="form-control" placeholder="Sleman Utara DIY"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="stagger-item">
                                                <div class="form-section contact-options mb-4">
                                                    <div class="col-12">
                                                        <h6 class="fw-bold">Saya bersedia dihubungi via:</h6>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" id="contact-chatcall-{{ $service->id }}" value="chat_and_call" checked>
                                                            <label class="form-check-label" for="contact-chatcall-{{ $service->id }}">Telepon & WhatsApp</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" id="contact-chatonly-{{ $service->id }}" value="chat_only">
                                                            <label class="form-check-label" for="contact-chatonly-{{ $service->id }}">Hanya WhatsApp</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="stagger-item">
                                                <hr class="my-5">
                                            </div>

                                            <div class="stagger-item">
                                                <div>
                                                    <div class="row justify-content-between align-items-start mb-3">
                                                        <div class="col-auto">
                                                            <div class="final-price-display">
                                                                <span class="final-price">Rp. {{ number_format($service->price, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="d-none"><span class="initial-price"></span><span class="discount-amount"></span></div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-primary px-4 py-2 select-service-btn" data-service-id="{{ $service->id }}">Pilih Layanan</button>
                                                        </div>
                                                    </div>
                                    
                                                    <div class="referral-section text-center">
                                                        <label for="referral_code-{{ $service->id }}" class="form-label d-block mb-2">
                                                            Punya Kode Referral untuk Layanan Ini?</label>
                                                        <div class="input-group">
                                                            <input type="text" id="referral_code-{{ $service->id }}" class="form-control referral-code-input">
                                                            <button class="btn apply-referral-btn" type="button" data-service-id="{{ $service->id }}">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">Layanan kami akan segera tersedia!</p>
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
        
        {{-- Script Animasi Scroll --}}
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init();

                // Sisa kode JavaScript Anda tetap sama persis, tidak perlu diubah.
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const year = tomorrow.getFullYear();
                const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
                const day = String(tomorrow.getDate()).padStart(2, '0');
                const minDate = `${year}-${month}-${day}`;

                document.querySelectorAll('.service-date-picker').forEach(function(input) {
                    input.setAttribute('min', minDate);
                });

                const servicesData = @json($services->keyBy('id'));
                const referralCodesData = @json($referralCodes->keyBy('code'));
                let appliedReferrals = {};

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

                function updateCartCount() {
                    // Logika update cart count Anda di sini...
                    // Pastikan id badge sesuai dengan yang ada di layout app.blade.php Anda
                }

                updateCartCount();
                
                function calculatePrices(block) {
                    const serviceId = block.data('service-id');
                    const hours = parseInt(block.find('.hours-input').val()) || 0;
                    const service = servicesData[serviceId];
                    if (!service) return;

                    const basePrice = parseFloat(service.price);
                    const hourlyPrice = parseFloat(service.hourly_price);
                    let initialPrice = basePrice + (hourlyPrice * hours);
                    let discountAmount = 0;
                    let finalPrice = initialPrice;
                    const referralCode = appliedReferrals[serviceId];

                    if (referralCode) {
                        const discountPercentage = referralCode.discount_percentage;
                        discountAmount = (initialPrice * discountPercentage) / 100;
                        finalPrice = initialPrice - discountAmount;
                    }

                    block.find('.final-price').text('Rp. ' + finalPrice.toLocaleString('id-ID'));
                }

                $('.accordion-body').on('change input', '.hours-input, .session-type-select', function() {
                    const block = $(this).closest('.service-block');
                    calculatePrices(block);
                });

                $('.accordion-body').on('change', '.session-type-select', function() {
                    const block = $(this).closest('.service-block');
                    const container = block.find('.offline-address-container');
                    if ($(this).val() === 'Offline') {
                        container.show().find('textarea').attr('required', true);
                    } else {
                        container.hide().find('textarea').attr('required', false);
                    }
                });

                $('.accordion-body').on('click', '.apply-referral-btn', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');
                    const referralCodeInput = block.find('.referral-code-input').val().toUpperCase();

                    if (appliedReferrals[serviceId]) {
                        delete appliedReferrals[serviceId];
                    }

                    if (serviceId && referralCodeInput && referralCodesData[referralCodeInput]) {
                        const code = referralCodesData[referralCodeInput];
                        const isValid = !code.valid_until || new Date(code.valid_until) > new Date();
                        const hasUses = !code.max_uses || code.current_uses < code.max_uses;

                        if (isValid && hasUses) {
                            appliedReferrals[serviceId] = {
                                code: referralCodeInput,
                                discount_percentage: parseFloat(code.discount_percentage)
                            };
                            Swal.fire(translations.success, translations.referral_applied, 'success');
                        } else {
                            Swal.fire(translations.failure, translations.referral_invalid, 'error');
                        }
                    } else {
                        if (referralCodeInput) {
                            Swal.fire(translations.failure, translations.referral_not_found, 'error');
                        } else {
                            Swal.fire(translations.info, translations.referral_enter_first, 'info');
                        }
                    }
                    calculatePrices(block);
                });

                $('.accordion-body').on('change input', '.service-date-picker, .booked_time-input, .hours-input', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');
                    const bookedDate = block.find('#booked_date-' + serviceId).val();
                    const bookedTime = block.find('#booked_time-' + serviceId).val();
                    const hoursBooked = parseInt(block.find('#hours-' + serviceId).val()) || 0;
                    const selectBtn = block.find('.select-service-btn');
                    const timeErrorDisplay = block.find('.time-error-message');

                    if (bookedDate && bookedTime && hoursBooked >= 0) {
                        selectBtn.prop('disabled', false).text('Pilih Layanan');
                        timeErrorDisplay.text('').hide();
                    } else {
                        selectBtn.prop('disabled', true).text('Pilih Jadwal Dulu');
                        timeErrorDisplay.text('').hide();
                    }
                });

                $('.select-service-btn').on('click', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');
                    const referralCode = appliedReferrals[serviceId] ? appliedReferrals[serviceId].code : null;

                    const formData = {
                        id: serviceId,
                        hours: block.find('#hours-' + serviceId).val(),
                        booked_date: block.find('#booked_date-' + serviceId).val(),
                        booked_time: block.find('#booked_time-' + serviceId).val(),
                        session_type: block.find('#session_type-' + serviceId).val(),
                        offline_address: block.find('#offline_address-' + serviceId).val(),
                        referral_code: referralCode,
                        contact_preference: block.find('input[name="contact_preference-' + serviceId + '"]:checked').val(),
                        price: parseFloat(servicesData[serviceId].price),
                        hourly_price: parseFloat(servicesData[serviceId].hourly_price),
                        service_title: servicesData[serviceId].title,
                        service_thumbnail: servicesData[serviceId].thumbnail,
                        discount_percentage: appliedReferrals[serviceId] ? appliedReferrals[serviceId].discount_percentage : 0
                    };

                    if (!formData.booked_date || !formData.booked_time || formData.hours < 0) {
                        Swal.fire(translations.info, 'Harap lengkapi semua informasi jadwal (Tanggal, Jam Mulai, Jumlah Jam).', 'info');
                        return;
                    }

                    if (formData.session_type === 'Offline' && !formData.offline_address) {
                        Swal.fire(translations.info, 'Harap masukkan alamat offline untuk sesi Offline.', 'info');
                        return;
                    }

                    @auth
                        $.ajax({
                            url: '{{ route("front.cart.add") }}',
                            type: 'POST',
                            data: { ...formData, _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire(translations.success, response.message, 'success');
                                updateCartCount(); // Panggil fungsi update global
                            },
                            error: function(response) {
                                let errorMessage = translations.generic_error;
                                if (response.responseJSON && response.responseJSON.message) {
                                    errorMessage = response.responseJSON.message;
                                } else if (response.responseJSON && response.responseJSON.errors) {
                                    errorMessage = translations.validation_fails + ': ' + Object.values(response.responseJSON.errors).flat().join(' ');
                                }
                                Swal.fire(translations.failure, errorMessage, 'error');
                            }
                        });
                    @else
                        const tempCart = getTempCart();
                        tempCart[serviceId] = formData;
                        saveTempCart(tempCart);
                        updateCartCount(); // Panggil fungsi update global

                        Swal.fire({
                            title: translations.success,
                            text: "Layanan berhasil ditambahkan ke keranjang sementara! Data akan tersimpan di sini sampai Anda login.",
                            icon: 'success',
                            confirmButtonText: 'Lanjutkan',
                            footer: '<a href="{{ route("login") }}">Ingin login sekarang?</a>'
                        });
                    @endauth
                });

                $('.service-block').each(function() {
                    calculatePrices($(this));
                    const block = $(this);
                    const selectBtn = block.find('.select-service-btn');
                    selectBtn.prop('disabled', true).text('Pilih Jadwal Dulu');
                });
            });
        </script>
    @endpush
@endsection