@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        @keyframes fadeInUp{from{opacity:0;transform:translate3d(0,20px,0)}to{opacity:1;transform:translate3d(0,0,0)}}.stagger-item{opacity:0}.accordion-collapse.show .stagger-item{animation:fadeInUp .5s ease-out forwards}.accordion-collapse.show .stagger-item:nth-child(1){animation-delay:.05s}.accordion-collapse.show .stagger-item:nth-child(2){animation-delay:.1s}.accordion-collapse.show .stagger-item:nth-child(3){animation-delay:.15s}.accordion-collapse.show .stagger-item:nth-child(4){animation-delay:.2s}.accordion-collapse.show .stagger-item:nth-child(5){animation-delay:.25s}

        /* Styling khusus untuk konsultasi gratis - highlight effect */
        .free-consultation-highlight {
            background: white !important;
            border: 3px solid var(--indiegologi-primary) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            position: relative;
            /* overflow: hidden; -- DIHAPUS UNTUK MEMPERBAIKI BADGE GRATIS YANG TERPOTONG */
        }

        .free-consultation-highlight .accordion-button {
            background: white !important;
            padding: 2rem !important;
        }

        .free-consultation-highlight .accordion-button h5 {
            font-size: 1.5rem !important;
            color: var(--indiegologi-primary) !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .free-consultation-highlight .accordion-button p {
            font-size: 1.1rem !important;
            color: #6c757d !important;
        }

        /* Tombol "Dapatkan Sekarang" yang telah diperbaiki */
        .free-consultation-highlight .btn-get-now {
            background: var(--indiegologi-primary) !important;
            color: white !important;
            font-weight: 600;
            padding: 0.75rem 1.5rem !important;
            border-radius: 25px !important;
            font-size: 0.9rem !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            white-space: nowrap; /* Mencegah teks patah */
            border: none;
        }

        .free-consultation-highlight .btn-get-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Badge gratis */
        .free-badge {
            position: absolute;
            top: -10px;
            right: 15px;
            background: linear-gradient(135deg, #FFB700, #FFC533);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(255, 183, 0, 0.4);
            z-index: 10;
        }

        /* Dropdown styling */
        .consultation-type-dropdown {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .consultation-type-dropdown.selected {
            background: #e3f2fd;
            border-color: var(--indiegologi-primary);
        }

        .schedule-options {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .schedule-options.show {
            display: block;
        }

        .schedule-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .schedule-card:hover {
            border-color: var(--indiegologi-primary);
            background: #f8f9fa;
        }

        .schedule-card.selected {
            border-color: var(--indiegologi-primary);
            background: #e3f2fd;
        }

        .schedule-card input[type="radio"] {
            margin-right: 0.5rem;
        }

        /* Responsive untuk mobile */
        @media (max-width:767.98px){
            .free-consultation-highlight .accordion-button {
                padding: 1.5rem !important;
                flex-direction: column;
                align-items: flex-start !important;
            }

            .free-consultation-highlight .accordion-button h5 {
                font-size: 1.3rem !important;
            }

            /* Tombol "Dapatkan Sekarang" versi mobile */
            .free-consultation-highlight .btn-get-now {
                width: 100%; /* Tombol memenuhi lebar container */
                margin-top: 1rem;
                padding: 0.8rem 1.5rem !important;
            }

            /* Penyesuaian gambar dan teks di mobile */
            .free-consultation-highlight .service-info-wrapper {
                width: 100%;
            }

            .accordion-button .service-header-mobile{display:flex;flex-direction:column;align-items:flex-start;width:100%}.accordion-button .service-header-mobile-top{display:flex;justify-content:space-between;align-items:flex-start;width:100%}.accordion-button .service-header-mobile .service-thumbnail-mobile{width:100%;height:150px;object-fit:cover;border-radius:8px;margin-bottom:1rem}.accordion-button h5{font-size:1.1rem}.accordion-button p{font-size:.85rem}.btn-details-toggle{font-size:0;width:40px;height:40px;padding:0;border-radius:50%;background-color:#f1f3f5;color:var(--indiegologi-primary);display:flex;align-items:center;justify-content:center;border:none;flex-shrink:0}.btn-details-toggle::after{content:'\F285';font-family:'bootstrap-icons';font-size:1rem;transition:transform .3s ease}.accordion-button:not(.collapsed) .btn-details-toggle::after{transform:rotate(90deg)}
        }
    </style>
@endpush

@section('content')
    <div class="service-details-page">
        <section class="container container-title mb-5" data-aos="fade-down">
            <div class="row">
                <h1 class="section-title">Penawaran Spesial <span class="ampersand-style">&</span> Paket Kesejahteraan</h1>
                <p class="section-desc">Temukan berbagai promo eksklusif dan paket layanan yang dirancang untuk mendukung perjalanan Anda.</p>
            </div>
        </section>

        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-11">

                    {{-- Bagian Konsultasi Gratis - Highlighted dengan Dropdown --}}
                    <div class="row justify-content-center mb-5">
                        <div class="col-lg-12">
                            <div class="accordion" id="freeServiceAccordion">
                                <div class="accordion-item mb-3 rounded-4 shadow-lg free-consultation-highlight" data-aos="fade-up" data-aos-duration="800">
                                    <div class="free-badge">GRATIS</div>
                                    <h2 class="accordion-header">
                                        {{-- REFACTORED: Single responsive header for the button --}}
                                        <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-free-consultation">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center w-100">
                                                {{-- Wrapper for Image and Text --}}
                                                <div class="d-flex align-items-center service-info-wrapper">
                                                    <img src="https://placehold.co/120x120/FFB700/ffffff?text=Gratis" alt="Konsultasi Gratis" class="rounded-3 me-4 d-none d-md-block" style="width:120px;height:120px;object-fit:cover;border: 3px solid #FFB700;">
                                                    <div>
                                                        <h5 class="fw-bold mb-2">Konsultasi Gratis</h5>
                                                        <p class="text-muted mb-0">Pilih jenis konsultasi gratis sesuai kebutuhan Anda.</p>
                                                    </div>
                                                </div>
                                                {{-- Button --}}
                                                <div class="ms-md-4 mt-3 mt-md-0">
                                                    <button class="btn-get-now" type="button">
                                                        Dapatkan Sekarang
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </h2>
                                    <div id="collapse-free-consultation" class="accordion-collapse collapse" data-bs-parent="#freeServiceAccordion">
                                        <div class="accordion-body p-4">
                                            <div class="service-block" data-service-id="new-free-consultation">

                                                <div class="stagger-item">
                                                    <div class="row mb-4">
                                                        <div class="col-12">
                                                            <h6 class="fw-bold mb-3">Pilih Jenis Konsultasi Gratis:</h6>
                                                            @forelse($freeConsultationTypes as $type)
                                                                <div class="consultation-type-dropdown" data-type-id="{{ $type->id }}">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input consultation-type-radio" type="radio" name="free_consultation_type" id="type-{{ $type->id }}" value="{{ $type->id }}">
                                                                        <label class="form-check-label fw-bold" for="type-{{ $type->id }}">
                                                                            {{ $type->name }}
                                                                        </label>
                                                                    </div>
                                                                    <p class="mb-2 mt-2 text-muted">{{ $type->description }}</p>

                                                                    {{-- Schedule Options --}}
                                                                    <div class="schedule-options" id="schedule-options-{{ $type->id }}">
                                                                        <h6 class="fw-bold mb-2">Pilih Jadwal:</h6>
                                                                        <div class="schedule-list" data-type-id="{{ $type->id }}">
                                                                            @foreach($type->availableSchedules as $schedule)
                                                                                <div class="schedule-card" data-schedule-id="{{ $schedule->id }}">
                                                                                    <input type="radio" name="free_consultation_schedule" value="{{ $schedule->id }}" class="schedule-radio" data-type-id="{{ $type->id }}" data-date="{{ $schedule->scheduled_date }}" data-time="{{ $schedule->scheduled_time->format('H:i') }}">
                                                                                    <strong>{{ $schedule->formatted_date }}</strong> - {{ $schedule->formatted_time }}
                                                                                    <small class="text-muted d-block">Sisa slot: {{ $schedule->max_participants - $schedule->current_bookings }}</small>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <p class="text-muted">Belum ada jenis konsultasi gratis yang tersedia.</p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="stagger-item">
                                                    <div class="form-section mb-4" id="additional-form-section" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pilihan Sesi</label>
                                                                    <select class="form-select session-type-select">
                                                                        <option value="Online">Online</option>
                                                                        <option value="Offline">Offline</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3 offline-address-container" style="display:none;">
                                                                    <label class="form-label">Alamat untuk Sesi Offline:</label>
                                                                    <textarea class="form-control" placeholder="Masukkan alamat lengkap untuk sesi offline" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="stagger-item">
                                                    <div class="form-section contact-options mb-4" id="contact-form-section" style="display: none;">
                                                        <div class="col-12">
                                                            <h6 class="fw-bold">Saya bersedia dihubungi via:</h6>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="radio" name="contact_preference-new-free-consultation" value="chat_and_call" checked>
                                                                <label class="form-check-label">Telepon & WhatsApp</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="contact_preference-new-free-consultation" value="chat_only">
                                                                <label class="form-check-label">Hanya WhatsApp</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="stagger-item">
                                                    <hr class="my-5" id="divider-section" style="display: none;">
                                                </div>

                                                <div class="stagger-item">
                                                    <div class="row justify-content-between align-items-start mb-3" id="booking-section" style="display: none;">
                                                        <div class="col-auto">
                                                            <div class="final-price-display">
                                                                <span class="final-price">Rp. 0</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-primary px-4 py-2 select-service-btn" data-service-id="new-free-consultation" disabled>
                                                                Booking Sesi Gratis
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Layanan dari Database --}}
                    <div class="accordion" id="servicesAccordion">
                        @forelse($services as $service)
                            <div class="accordion-item mb-3 rounded-4 shadow-sm" data-aos="fade-up" data-aos-duration="800">
                                <h2 class="accordion-header" id="heading-{{ $service->id }}">
                                    <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $service->id }}">
                                        <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="rounded-3 me-3" style="width:100px;height:100px;object-fit:cover">
                                                <div>
                                                    <h5 class="fw-bold mb-1">{{ $service->title }}</h5>
                                                    <p class="text-muted mb-0">{{ Str::limit($service->short_description, 70) }}</p>
                                                </div>
                                            </div>
                                            <button class="btn-details-toggle" type="button">Baca Selengkapnya</button>
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
                                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                    <div class="accordion-body p-4">
                                        <div class="service-block" data-service-id="{{ $service->id }}">
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
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <h6 class="fw-bold">Pilih Jadwal:</h6>
                                                            <small class="text-muted">(Pemesanan minimal H-1)</small>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Tanggal:</label>
                                                                <input type="date" class="form-control service-date-picker" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Jam Mulai:</label>
                                                                <input type="time" class="form-control booked_time-input" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Jumlah Jam</label>
                                                                <input type="number" class="form-control hours-input" value="1" min="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Pilihan Sesi</label>
                                                                <select class="form-select session-type-select">
                                                                    <option value="Online">Online</option>
                                                                    <option value="Offline">Offline</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 offline-address-container" style="display:none;">
                                                                <label class="form-label">Alamat untuk Sesi Offline:</label>
                                                                <textarea class="form-control" placeholder="Masukkan alamat lengkap untuk sesi offline" rows="3"></textarea>
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
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_and_call" checked>
                                                            <label class="form-check-label">Telepon & WhatsApp</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_only">
                                                            <label class="form-check-label">Hanya WhatsApp</label>
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
                                                            <span class="final-price">Rp. {{ number_format($service->price, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-primary px-4 py-2 select-service-btn" data-service-id="{{ $service->id }}" disabled>
                                                            Pilih Layanan
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="referral-section text-center">
                                                    <label class="form-label d-block mb-2">Punya Kode Referral?</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control referral-code-input" placeholder="Masukkan kode referral">
                                                        <button class="btn apply-referral-btn" type="button" data-service-id="{{ $service->id }}">Apply</button>
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
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init();

                // Set minimum date (tomorrow) for all date pickers
                const minDate = new Date();
                minDate.setDate(minDate.getDate() + 1);
                document.querySelectorAll('.service-date-picker').forEach(input => {
                    input.min = minDate.toISOString().split("T")[0];
                });

                const translations = {
                    success: "Berhasil!",
                    failure: "Gagal!",
                    info: "Perhatian!",
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

                // Generate unique cart key for new free consultation
                function generateFreeConsultationKey(typeId, scheduleId) {
                    // Use consistent format with backend: new-free-consultation-{timestamp}
                    return 'new-free-consultation-' + Date.now() + '-' + typeId + '-' + scheduleId;
                }

                // Generate unique cart key for legacy free consultation
                function generateLegacyFreeConsultationKey() {
                    // Use consistent format with backend: legacy-free-consultation-{timestamp}
                    return 'legacy-free-consultation-' + Date.now();
                }

                // Free consultation type selection
                $(document).on('change', '.consultation-type-radio', function() {
                    const typeId = $(this).val();
                    const isChecked = $(this).is(':checked');

                    // Reset all dropdowns
                    $('.consultation-type-dropdown').removeClass('selected');
                    $('.schedule-options').removeClass('show');
                    $('.schedule-radio').prop('checked', false);
                    $('#additional-form-section, #contact-form-section, #divider-section, #booking-section').hide();

                    if (isChecked) {
                        // Show selected type
                        $(this).closest('.consultation-type-dropdown').addClass('selected');
                        $('#schedule-options-' + typeId).addClass('show');
                    }
                });

                // Schedule selection
                $(document).on('change', '.schedule-radio', function() {
                    const scheduleId = $(this).val();
                    const typeId = $(this).data('type-id');
                    const bookedDate = $(this).data('date');
                    const bookedTime = $(this).data('time');

                    // Reset all schedule cards
                    $('.schedule-card').removeClass('selected');

                    if ($(this).is(':checked')) {
                        $(this).closest('.schedule-card').addClass('selected');

                        // Store schedule data for later use
                        $(this).closest('.service-block').data('scheduled-date', bookedDate);
                        $(this).closest('.service-block').data('scheduled-time', bookedTime);

                        // Show additional form sections
                        $('#additional-form-section, #contact-form-section, #divider-section, #booking-section').show();
                        validateFreeConsultationForm();
                    }
                });

                // Validate free consultation form
                function validateFreeConsultationForm() {
                    const hasType = $('input[name="free_consultation_type"]:checked').length > 0;
                    const hasSchedule = $('input[name="free_consultation_schedule"]:checked').length > 0;
                    const sessionType = $('#additional-form-section .session-type-select').val();
                    const offlineAddress = $('#additional-form-section .offline-address-container textarea').val();
                    const hasContactPreference = $('input[name="contact_preference-new-free-consultation"]:checked').length > 0;

                    let isValid = hasType && hasSchedule && hasContactPreference;

                    // Additional validation for offline sessions
                    if (sessionType === 'Offline' && !offlineAddress.trim()) {
                        isValid = false;
                    }

                    $('.select-service-btn[data-service-id="new-free-consultation"]').prop('disabled', !isValid);
                }

                // Toggle offline address field for free consultation
                $(document).on('change', '#additional-form-section .session-type-select', function() {
                    const container = $('#additional-form-section .offline-address-container');
                    if ($(this).val() === 'Offline') {
                        container.slideDown();
                        container.find('textarea').attr('required', true);
                    } else {
                        container.slideUp();
                        container.find('textarea').removeAttr('required');
                    }
                    validateFreeConsultationForm();
                });

                // Validate on contact preference change
                $(document).on('change', 'input[name="contact_preference-new-free-consultation"]', function() {
                    validateFreeConsultationForm();
                });

                // Toggle offline address field based on session type for regular services
                $('.accordion-body').on('change', '.session-type-select', function() {
                    const container = $(this).closest('.service-block').find('.offline-address-container');
                    if ($(this).val() === 'Offline') {
                        container.slideDown();
                        container.find('textarea').attr('required', true);
                    } else {
                        container.slideUp();
                        container.find('textarea').removeAttr('required');
                    }
                });

                // Validate form inputs and enable/disable button for regular services
                $('.accordion-body').on('input change', '.service-date-picker, .booked_time-input, .hours-input, .session-type-select', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');

                    if (serviceId === 'new-free-consultation') {
                        return; // Skip for new free consultation system
                    }

                    const bookedDate = block.find('.service-date-picker').val();
                    const bookedTime = block.find('.booked_time-input').val();
                    const sessionType = block.find('.session-type-select').val();
                    const offlineAddress = block.find('.offline-address-container textarea').val();

                    let hoursBooked = 1;
                    const hoursInput = block.find('.hours-input');
                    if (hoursInput.length && !hoursInput.prop('readonly')) {
                        hoursBooked = parseInt(hoursInput.val(), 10) || 1;
                    }

                    let isValid = bookedDate && bookedTime && hoursBooked >= 1;

                    if (sessionType === 'Offline' && !offlineAddress.trim()) {
                        isValid = false;
                    }

                    block.find('.select-service-btn').prop('disabled', !isValid);
                });

                // Handle service selection/booking
                $('.select-service-btn').on('click', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');

                    // Handle new free consultation system
                    if (serviceId === 'new-free-consultation') {
                        const selectedType = $('input[name="free_consultation_type"]:checked').val();
                        const selectedSchedule = $('input[name="free_consultation_schedule"]:checked').val();
                        const sessionType = $('#additional-form-section .session-type-select').val();
                        const offlineAddress = $('#additional-form-section .offline-address-container textarea').val();
                        const contactPreference = $('input[name="contact_preference-new-free-consultation"]:checked').val();

                        // Get scheduled date and time from selected schedule
                        const selectedScheduleElement = $('input[name="free_consultation_schedule"]:checked');
                        const bookedDate = selectedScheduleElement.data('date');
                        const bookedTime = selectedScheduleElement.data('time');

                        if (!selectedType || !selectedSchedule) {
                            return Swal.fire(translations.info, 'Harap pilih jenis konsultasi dan jadwal.', 'info');
                        }

                        if (sessionType === 'Offline' && !offlineAddress?.trim()) {
                            return Swal.fire(translations.info, 'Harap masukkan alamat untuk sesi Offline.', 'info');
                        }

                        if (!contactPreference) {
                            return Swal.fire(translations.failure, translations.validation_fails, 'error');
                        }

                        const formData = {
                            consultation_type: 'free-consultation-new',
                            free_consultation_type_id: selectedType,
                            free_consultation_schedule_id: selectedSchedule,
                            session_type: sessionType,
                            offline_address: sessionType === 'Offline' ? offlineAddress : null,
                            contact_preference: contactPreference,
                            _token: '{{ csrf_token() }}'
                        };

                        @auth
                            $.ajax({
                                url: '{{ route("front.cart.add") }}',
                                type: 'POST',
                                data: formData,
                                success: (response) => {
                                    Swal.fire(translations.success, response.message, 'success').then(() => {
                                        // Reset form
                                        $('.consultation-type-radio, .schedule-radio').prop('checked', false);
                                        $('.consultation-type-dropdown').removeClass('selected');
                                        $('.schedule-options').removeClass('show');
                                        $('#additional-form-section, #contact-form-section, #divider-section, #booking-section').hide();
                                        $('#additional-form-section .offline-address-container textarea').val('');
                                        $('#additional-form-section .session-type-select').val('Online');
                                        $('#additional-form-section .offline-address-container').hide();
                                        updateCartCount();
                                    });
                                },
                                error: (response) => {
                                    const msg = response.responseJSON?.message || translations.validation_fails;
                                    Swal.fire(translations.failure, msg, 'error');
                                }
                            });
                        @else
                            // For guests, save to localStorage with consistent key format
                            const tempCart = getTempCart();
                            const cartKey = generateFreeConsultationKey(selectedType, selectedSchedule);

                            tempCart[cartKey] = {
                                consultation_type: 'free-consultation-new',
                                free_consultation_type_id: selectedType,
                                free_consultation_schedule_id: selectedSchedule,
                                session_type: sessionType,
                                offline_address: sessionType === 'Offline' ? offlineAddress : null,
                                contact_preference: contactPreference,
                                booked_date: bookedDate,
                                booked_time: bookedTime,
                                hours: 1 // Free consultation is always 1 hour
                            };

                            saveTempCart(tempCart);
                            updateCartCount();

                            // Reset form
                            $('.consultation-type-radio, .schedule-radio').prop('checked', false);
                            $('.consultation-type-dropdown').removeClass('selected');
                            $('.schedule-options').removeClass('show');
                            $('#additional-form-section, #contact-form-section, #divider-section, #booking-section').hide();
                            $('#additional-form-section .offline-address-container textarea').val('');
                            $('#additional-form-section .session-type-select').val('Online');
                            $('#additional-form-section .offline-address-container').hide();

                            Swal.fire({
                                title: translations.success,
                                text: "Konsultasi gratis berhasil ditambahkan ke keranjang!",
                                icon: 'success',
                                confirmButtonText: 'Lanjutkan',
                                footer: '<a href="{{ route("login") }}">Login untuk melanjutkan proses booking.</a>'
                            });
                        @endauth

                        return;
                    }

                    // Handle regular services
                    const sessionType = block.find('.session-type-select').val();
                    const offlineAddress = block.find('.offline-address-container textarea').val();

                    const formData = {
                        id: serviceId,
                        hours: block.find('.hours-input').val() || '1',
                        booked_date: block.find('.service-date-picker').val(),
                        booked_time: block.find('.booked_time-input').val(),
                        session_type: sessionType,
                        offline_address: sessionType === 'Offline' ? offlineAddress : null,
                        contact_preference: block.find(`input[name="contact_preference-${serviceId}"]:checked`).val(),
                        referral_code: block.find('.referral-code-input').val() || null,
                        _token: '{{ csrf_token() }}'
                    };

                    if (!formData.booked_date || !formData.booked_time) {
                        return Swal.fire(translations.info, 'Harap lengkapi Tanggal dan Jam Mulai.', 'info');
                    }

                    if (formData.session_type === 'Offline' && !formData.offline_address?.trim()) {
                        return Swal.fire(translations.info, 'Harap masukkan alamat untuk sesi Offline.', 'info');
                    }

                    if (!formData.contact_preference) {
                        return Swal.fire(translations.failure, translations.validation_fails, 'error');
                    }

                    @auth
                        $.ajax({
                            url: '{{ route("front.cart.add") }}',
                            type: 'POST',
                            data: formData,
                            success: (response) => {
                                Swal.fire(translations.success, response.message, 'success').then(() => {
                                    // Reset form
                                    block.find('.service-date-picker, .booked_time-input').val('');
                                    block.find('.hours-input').val('1');
                                    block.find('.session-type-select').val('Online');
                                    block.find('.offline-address-container').hide();
                                    block.find('.offline-address-container textarea').val('');
                                    block.find('.referral-code-input').val('');
                                    block.find('.select-service-btn').prop('disabled', true);
                                    updateCartCount();
                                });
                            },
                            error: (response) => {
                                const msg = response.responseJSON?.message || translations.validation_fails;
                                Swal.fire(translations.failure, msg, 'error');
                            }
                        });
                    @else
                        // For guests, save regular services to localStorage
                        const tempCart = getTempCart();
                        tempCart[serviceId] = {
                            id: serviceId,
                            hours: formData.hours,
                            booked_date: formData.booked_date,
                            booked_time: formData.booked_time,
                            session_type: formData.session_type,
                            offline_address: formData.offline_address,
                            contact_preference: formData.contact_preference,
                            referral_code: formData.referral_code
                        };
                        saveTempCart(tempCart);
                        updateCartCount();

                        // Reset form
                        block.find('.service-date-picker, .booked_time-input').val('');
                        block.find('.hours-input').val('1');
                        block.find('.session-type-select').val('Online');
                        block.find('.offline-address-container').hide();
                        block.find('.offline-address-container textarea').val('');
                        block.find('.referral-code-input').val('');
                        block.find('.select-service-btn').prop('disabled', true);

                        Swal.fire({
                            title: translations.success,
                            text: "Layanan berhasil ditambahkan ke keranjang!",
                            icon: 'success',
                            confirmButtonText: 'Lanjutkan',
                            footer: '<a href="{{ route("login") }}">Login untuk melanjutkan proses booking.</a>'
                        });
                    @endauth
                });

                // Handle referral code application - Simple validation without AJAX
                $('.apply-referral-btn').on('click', function() {
                    const serviceId = $(this).data('service-id');
                    const block = $(this).closest('.service-block');
                    const referralCode = block.find('.referral-code-input').val().trim();

                    if (!referralCode) {
                        return Swal.fire(translations.info, 'Masukkan kode referral terlebih dahulu.', 'info');
                    }

                    // For guest users, just show info message
                    @guest
                        Swal.fire(translations.info, 'Kode referral akan divalidasi saat checkout. Silakan login untuk melanjutkan.', 'info');
                        return;
                    @endguest

                    // For authenticated users, just show confirmation
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

                // Initially disable all service buttons
                $('.service-block').each(function() {
                    $(this).find('.select-service-btn').prop('disabled', true);
                });

                // Initialize cart count on page load
                updateCartCount();
            });
        </script>
    @endpush
@endsection
