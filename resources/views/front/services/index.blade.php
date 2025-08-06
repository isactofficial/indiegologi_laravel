@extends('layouts.app')

@section('content')

<section class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold section-title"><span class="main-text">LAYANAN</span> <span class="highlight-text">KAMI</span></h1>
            <p class="lead text-muted">Jelajahi berbagai layanan konsultasi yang kami tawarkan.</p>
        </div>
    </div>
</section>

<div class="container pb-5">
    <div class="accordion" id="servicesAccordion">
        @forelse($services as $service)
            <div class="accordion-item mb-3 rounded-4 shadow-sm">
                <h2 class="accordion-header" id="heading-{{ $service->id }}">
                    <button class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $service->id }}" aria-expanded="false" aria-controls="collapse-{{ $service->id }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="rounded-3 me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $service->title }}</h5>
                                <p class="text-muted mb-0">{{ Str::limit($service->short_description, 100) }}</p>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $service->id }}" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body">
                        <div class="service-block" data-service-id="{{ $service->id }}" data-price="{{ $service->price }}" data-hourly-price="{{ $service->hourly_price }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold">Deskripsi Produk</h6>
                                    <p>{{ $service->product_description }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold">Pilih Jadwal</h6>
                                    <div class="mb-3">
                                        <label for="booked_date-{{ $service->id }}" class="form-label">Tanggal</label>
                                        <input type="date" id="booked_date-{{ $service->id }}" name="booked_date-{{ $service->id }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="booked_time-{{ $service->id }}" class="form-label">Jam Mulai</label>
                                        <input type="time" id="booked_time-{{ $service->id }}" name="booked_time-{{ $service->id }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hours-{{ $service->id }}" class="form-label">Jumlah Jam</label>
                                        <input type="number" id="hours-{{ $service->id }}" name="hours-{{ $service->id }}" class="form-control hours-input" value="0" min="0" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <h6 class="fw-bold">Pilih Tipe Sesi & Kontak</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="session_type-{{ $service->id }}" class="form-label">Tipe Sesi</label>
                                            <select id="session_type-{{ $service->id }}" name="session_type-{{ $service->id }}" class="form-select session-type-select">
                                                <option value="Online">Online</option>
                                                <option value="Offline">Offline</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3 offline-address-container" style="display:none;">
                                            <label for="offline_address-{{ $service->id }}" class="form-label">Alamat Offline</label>
                                            <textarea id="offline_address-{{ $service->id }}" name="offline_address-{{ $service->id }}" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_preference-{{ $service->id }}" class="form-label">Preferensi Kontak</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" id="contact-chatonly-{{ $service->id }}" value="chat_only" checked>
                                            <label class="form-check-label" for="contact-chatonly-{{ $service->id }}">Chat Only</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" id="contact-chatcall-{{ $service->id }}" value="chat_and_call">
                                            <label class="form-check-label" for="contact-chatcall-{{ $service->id }}">Chat & Call</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <h6 class="fw-bold">Pilih Pembayaran</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type-{{ $service->id }}" id="payment-full-{{ $service->id }}" value="full_payment" checked>
                                        <label class="form-check-label" for="payment-full-{{ $service->id }}">Full Payment</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type-{{ $service->id }}" id="payment-dp-{{ $service->id }}" value="dp">
                                        <label class="form-check-label" for="payment-dp-{{ $service->id }}">DP (50%)</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <h6 class="fw-bold">Kode Referral</h6>
                                    <div class="input-group">
                                        <input type="text" id="referral_code-{{ $service->id }}" class="form-control referral-code-input">
                                        <button class="btn btn-secondary apply-referral-btn" type="button" data-service-id="{{ $service->id }}">Apply</button>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <span class="fw-semibold">Harga Awal:</span> <span class="initial-price">Rp {{ number_format($service->price, 0, ',', '.') }}</span><br>
                                    <span class="text-success fw-semibold">Diskon:</span> <span class="discount-amount text-success">Rp 0</span><br>
                                    <span class="fw-bold">Total Harga:</span> <span class="final-price">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    @auth
                                        <button type="button" class="btn btn-lg btn-primary select-service-btn" data-service-id="{{ $service->id }}">Pilih Layanan</button>
                                    @else
                                        <p class="text-danger mb-0">Silakan <a href="{{ route('login') }}">login</a> untuk memilih layanan.</p>
                                    @endauth
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            block.find('.final-price').text('Rp ' + finalPrice.toLocaleString('id-ID'));
        }

        // --- Event Listeners ---
        $('.accordion-body').on('change', '.hours-input, .session-type-select, .referral-code-input', function() {
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
                offline_address: block.find('#offline_address-' + serviceId).val(),
                referral_code: referralCode,
                contact_preference: block.find('input[name="contact_preference-' + serviceId + '"]:checked').val(),
                payment_type: block.find('input[name="payment_type-' + serviceId + '"]:checked').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '#',
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
                         errorMessage = 'Validasi gagal: ' + Object.values(response.responseJSON.errors).flat().join(' ');
                     }
                     Swal.fire('Gagal!', errorMessage, 'error');
                }
            });
        });

        // Inisialisasi awal untuk semua blok accordion
        $('.service-block').each(function() {
            calculatePrices($(this));
        });
    });
</script>

@endsection

