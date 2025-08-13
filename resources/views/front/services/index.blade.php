@extends('layouts.app')

@push('styles')
    {{-- 1. Impor Font dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    {{-- 2. Import file CSS yang sudah dimodifikasi --}}
    <link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
@endpush

@section('content')
    <div class="service-details-page">
        <section class="container-title mb-5">
            <div class="row">
                <h1 class="section-title">Penawaran Spesial <span class="ampersand-style">&</span> Paket Kesejahteraan</h1>
                <p class="section-desc">Temukan berbagai promo eksklusif dan paket layanan yang dirancang untuk mendukung perjalanan Anda menuju kesejahteraan mental optimal dengan nilai terbaik.
                </p>
            </div>
        </section>

        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-11">
                    <div class="accordion" id="servicesAccordion">
                        @forelse($services as $service)
                            <div class="accordion-item mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header" id="heading-{{ $service->id }}">
                                    <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse"
                                         data-bs-target="#collapse-{{ $service->id }}" aria-expanded="false"
                                         aria-controls="collapse-{{ $service->id }}">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $service->thumbnail) }}"
                                                     alt="{{ $service->title }}" class="d-none d-md-block rounded-3 me-3"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <div>
                                                    <h5 class="fw-bold mb-1">
                                                        {{ $service->title }}</h5>
                                                    <p class="text-muted mb-0">
                                                        {{ Str::limit($service->short_description, 70) }}</p>
                                                </div>
                                            </div>
                                            <button class="btn-details-toggle" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $service->id }}"
                                                    aria-expanded="false" aria-controls="collapse-{{ $service->id }}">
                                                Baca Selengkapnya
                                            </button>
                                        </div>
                                    </div>
                                </h2>
                                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading-{{ $service->id }}" data-bs-parent="#servicesAccordion">
                                    <div class="accordion-body p-4">
                                        <div class="service-block" data-service-id="{{ $service->id }}"
                                             data-price="{{ $service->price }}"
                                             data-hourly-price="{{ $service->hourly_price }}">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h6 class="fw-judul">Deskripsi Produk:</h6>
                                                    <p>{{ $service->product_description }}</p>
                                                </div>
                                            </div>

                                            <div class="form-section mb-4">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h6 class="fw-bold">Pilih Jadwal Meditasi:</h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3"><label for="booked_date-{{ $service->id }}"
                                                                                  class="form-label">Tanggal:</label><input type="date"
                                                                                                                            id="booked_date-{{ $service->id }}" class="form-control"
                                                                                                                            required></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3"><label for="booked_time-{{ $service->id }}"
                                                                                  class="form-label">Jam Mulai:</label><input type="time"
                                                                                                                              id="booked_time-{{ $service->id }}" class="form-control"
                                                                                                                              required></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3"><label for="hours-{{ $service->id }}"
                                                                                  class="form-label">Jumlah Jam</label><input type="number"
                                                                                                                              id="hours-{{ $service->id }}"
                                                                                                                              class="form-control hours-input" value="0"
                                                                                                                              min="0" required></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-3"><label for="session_type-{{ $service->id }}"
                                                                                  class="form-label">Pilihan Sesi</label><select
                                                                    id="session_type-{{ $service->id }}"
                                                                    class="form-select session-type-select">
                                                                <option value="Online">Online</option>
                                                                <option value="Offline">Offline</option>
                                                            </select></div>
                                                        <div class="mb-3 offline-address-container" style="display:none;">
                                                            <label class="form-label">Alamat Sesi Offline:</label>
                                                            <div class="offline-address-text">Sleman Utara, DIY, Indonesia</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section payment-options mb-4">
                                                <div class="col-12">
                                                    <h6 class="fw-bold">Pilihan Pembayaran</h6>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_type-{{ $service->id }}"
                                                               id="payment-full-{{ $service->id }}" value="full_payment"
                                                               checked>
                                                        <label class="form-check-label"
                                                               for="payment-full-{{ $service->id }}">Bayar Penuh (Rp
                                                                {{ number_format($service->price, 0, ',', '.') }})</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_type-{{ $service->id }}"
                                                               id="payment-dp-{{ $service->id }}" value="dp">
                                                        <label class="form-check-label"
                                                               for="payment-dp-{{ $service->id }}">Bayar DP 50% (Rp
                                                                {{ number_format($service->price / 2, 0, ',', '.') }})</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section contact-options mb-4">
                                                <div class="col-12">
                                                    <h6 class="fw-bold">Saya bersedia dihubungi via:</h6>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                               name="contact_preference-{{ $service->id }}"
                                                               id="contact-chatcall-{{ $service->id }}"
                                                               value="chat_and_call" checked>
                                                        <label class="form-check-label"
                                                               for="contact-chatcall-{{ $service->id }}">Telepon &
                                                                WhatsApp</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="contact_preference-{{ $service->id }}"
                                                               id="contact-chatonly-{{ $service->id }}" value="chat_only">
                                                        <label class="form-check-label"
                                                               for="contact-chatonly-{{ $service->id }}">Hanya
                                                                WhatsApp</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-5">

                                            <div>
                                                <div class="row justify-content-between align-items-start mb-3">
                                                    <div class="col-auto">
                                                        <div class="final-price-display">
                                                            <span class="final-price">{{ number_format($service->price, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="d-none"><span class="initial-price"></span><span
                                                                    class="discount-amount"></span></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        @auth
                                                            <button type="button"
                                                                    class="btn btn-primary px-4 py-2 select-service-btn"
                                                                    data-service-id="{{ $service->id }}">Pilih Layanan</button>
                                                        @else
                                                            <p class="text-danger mb-0">Silakan <a href="{{ route('login') }}">login</a> untuk memilih layanan.</p>
                                                        @endauth
                                                    </div>
                                                </div>

                                                <div class="referral-section text-center">
                                                    <label for="referral_code-{{ $service->id }}"
                                                           class="form-label d-block mb-2">Punya Kode Referral untuk Layanan
                                                        Ini?</label>
                                                    <div class="input-group">
                                                        <input type="text" id="referral_code-{{ $service->id }}"
                                                               class="form-control referral-code-input">
                                                        <button class="btn apply-referral-btn" type="button"
                                                                data-service-id="{{ $service->id }}">Apply</button>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const servicesData = @json($services->keyBy('id'));
            const referralCodesData = @json($referralCodes->keyBy('code'));
            let appliedReferrals = {};

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

                block.find('.initial-price').text('Rp ' + initialPrice.toLocaleString('id-ID'));
                block.find('.discount-amount').text('Rp ' + discountAmount.toLocaleString('id-ID'));
                block.find('.final-price').text('Rp. ' + finalPrice.toLocaleString('id-ID'));
            }

            // --- Event Listeners ---
            $('.accordion-body').on('change', '.hours-input, .session-type-select, .referral-code-input',
                function() {
                    const block = $(this).closest('.service-block');
                    calculatePrices(block);
                });

            $('.accordion-body').on('input', '.hours-input', function() {
                const block = $(this).closest('.service-block');
                calculatePrices(block);
            });

            $('.accordion-body').on('change', '.session-type-select', function() {
                const block = $(this).closest('.service-block');
                const container = block.find('.offline-address-container');
                if ($(this).val() === 'Offline') {
                    container.show();
                } else {
                    container.hide();
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
                        Swal.fire('Berhasil!', 'Kode referral berhasil diterapkan.', 'success');
                    } else {
                        Swal.fire('Gagal!', 'Kode referral tidak valid atau sudah habis.', 'error');
                    }
                } else {
                    if (referralCodeInput) {
                        Swal.fire('Gagal!', 'Kode referral tidak ditemukan.', 'error');
                    } else {
                        Swal.fire('Perhatian!', 'Masukkan kode referral terlebih dahulu.', 'info');
                    }
                }
                calculatePrices(block);
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
                    // Alamat kini diambil dari teks yang ditampilkan, bukan dari input
                    offline_address: block.find('.offline-address-text').text(),
                    referral_code: referralCode,
                    contact_preference: block.find('input[name="contact_preference-' + serviceId +
                        '"]:checked').val(),
                    payment_type: block.find('input[name="payment_type-' + serviceId + '"]:checked')
                        .val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route("front.cart.add") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        const cartCountElement = $('#cart-count');
                        cartCountElement.text(response.cart_count);
                        cartCountElement.show();
                    },
                    error: function(response) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (response.responseJSON && response.responseJSON.message) {
                            errorMessage = response.responseJSON.message;
                        } else if (response.responseJSON && response.responseJSON.errors) {
                            errorMessage = 'Validasi gagal: ' + Object.values(response
                                .responseJSON.errors).flat().join(' ');
                        }
                        Swal.fire('Gagal!', errorMessage, 'error');
                    }
                });
            });

            $('.service-block').each(function() {
                calculatePrices($(this));
            });
        });
    </script>
@endsection
