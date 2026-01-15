@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --indiegologi-primary: #0c2c5a;
        }

        /* Tetap mempertahankan styling yang sudah ada */
        @keyframes fadeInUp{from{opacity:0;transform:translate3d(0,20px,0)}to{opacity:1;transform:translate3d(0,0,0)}}.stagger-item{opacity:0}.accordion-collapse.show .stagger-item{animation:fadeInUp .5s ease-out forwards}.accordion-collapse.show .stagger-item:nth-child(1){animation-delay:.05s}.accordion-collapse.show .stagger-item:nth-child(2){animation-delay:.1s}.accordion-collapse.show .stagger-item:nth-child(3){animation-delay:.15s}.accordion-collapse.show .stagger-item:nth-child(4){animation-delay:.2s}.accordion-collapse.show .stagger-item:nth-child(5){animation-delay:.25s}.accordion-collapse.show .stagger-item:nth-child(6){animation-delay:.3s}

        /* Styling khusus untuk konsultasi gratis - highlight effect */
        .free-consultation-highlight {
            background: white !important;
            border: 3px solid var(--indiegologi-primary) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            position: relative;
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

        /* UI Diskon 100% untuk Konsultasi Gratis */
        .price-discount-tag {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        .original-price {
            text-decoration: line-through;
            color: #adb5bd;
            font-size: 1rem;
        }
        .discounted-price {
            color: #28a745;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .percent-badge {
            background: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
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
            white-space: nowrap;
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

        /* MODIFIKASI: Menambahkan kelas untuk item jadwal yang bisa di-klik */
        .schedule-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .schedule-card:hover {
            border-color: var(--indiegologi-primary);
            background: #f8f9fa;
        }

        .schedule-card.selected {
            border-color: var(--indiegologi-primary);
            background: #e3f2fd;
        }

        /* Sembunyikan radio button bawaan */
        .schedule-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
            margin: 0;
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

            .free-consultation-highlight .btn-get-now {
                width: 100%;
                margin-top: 1rem;
                padding: 0.8rem 1.5rem !important;
            }

            .free-consultation-highlight .service-info-wrapper {
                width: 100%;
            }

            .accordion-button .service-header-mobile{display:flex;flex-direction:column;align-items:flex-start;width:100%}.accordion-button .service-header-mobile-top{display:flex;justify-content:space-between;align-items:flex-start;width:100%}.accordion-button .service-header-mobile .service-thumbnail-mobile{width:100%;height:150px;object-fit:cover;border-radius:8px;margin-bottom:1rem}.accordion-button h5{font-size:1.1rem}.accordion-button p{font-size:.85rem}.btn-details-toggle{font-size:0;width:40px;height:40px;padding:0;border-radius:50%;background-color:#f1f3f5;color:var(--indiegologi-primary);display:flex;align-items:center;justify-content:center;border:none;flex-shrink:0}.btn-details-toggle::after{content:'\F285';font-family:'bootstrap-icons';font-size:1rem;transition:transform .3s ease}.accordion-button:not(.collapsed) .btn-details-toggle::after{transform:rotate(90deg)}
        }

        /* CSS untuk Add-On Repeater */
        .selected-addon-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f1f3f5;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
        }
        .selected-addon-item .addon-details {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            margin-right: 0.5rem;
        }
        .selected-addon-item .addon-name {
            font-weight: 500;
        }
        .selected-addon-item .addon-price {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .btn-remove-addon {
            border: none;
            background: none;
            color: #dc3545;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 0 0.25rem;
            line-height: 1;
            margin-left: 0.5rem;
        }
        .btn-remove-addon:hover {
            color: #a71d2a;
        }
    </style>
@endpush

@section('content')
    {{-- Iklan --}}
    <x-floating-ads
        topAdImage="assets/img/PROMOTION_WEBSITE.jpg"
        topAdLink="#"
        bottomAdImage="assets/img/KONSULTASI_GRATIS.jpg"
        bottomAdLink="/layanan" />

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
                                        <div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-free-consultation">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center w-100">
                                                <div class="d-flex align-items-center service-info-wrapper">
                                                    <img src="https://placehold.co/120x120/FFB700/ffffff?text=Gratis" alt="Konsultasi Gratis" class="rounded-3 me-4 d-none d-md-block" style="width:120px;height:120px;object-fit:cover;border: 3px solid #FFB700;">
                                                    <div>
                                                        <h5 class="fw-bold mb-1">Konsultasi Gratis</h5>
                                                        <p class="text-muted mb-0">Pilih jenis konsultasi gratis sesuai kebutuhan Anda.</p>
                                                    </div>
                                                </div>
                                                <div class="ms-md-4 mt-3 mt-md-0">
                                                    <button class="btn-get-now" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-free-consultation">
                                                        Pilih Jadwal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </h2>
                                    <div id="collapse-free-consultation" class="accordion-collapse collapse" data-bs-parent="#freeServiceAccordion">
                                        <div class="accordion-body p-4 rounded-4">
                                            <div class="service-block" data-service-id="new-free-consultation">

                                                <div class="stagger-item">
                                                    <div class="row mb-4">
                                                        <div class="col-12">
                                                            <h6 class="fw-bold mb-3">Pilih Jenis Konsultasi Gratis:</h6>

                                                            @forelse($freeConsultationTypes ?? [] as $type)
                                                                <div class="consultation-type-dropdown" data-type-id="{{ $type->id }}" data-type-name="{{ $type->name }}">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input consultation-type-radio" type="radio" name="free_consultation_type" id="type-{{ $type->id }}" value="{{ $type->id }}">
                                                                            <label class="form-check-label fw-bold" for="type-{{ $type->id }}">
                                                                                {{ $type->name }}
                                                                            </label>
                                                                        </div>
                                                                        
                                                                        {{-- Harga Coret per Tipe --}}
                                                                        <div class="text-end">
                                                                            <div class="price-discount-tag justify-content-end">
                                                                                <span class="original-price" style="font-size: 0.85rem;">
                                                                                    Rp. {{ number_format($type->base_price ?? 250000, 0, ',', '.') }}
                                                                                </span>
                                                                                <span class="percent-badge text-white" style="font-size: 0.7rem;">DISKON 100%</span>
                                                                            </div>
                                                                            <span class="discounted-price" style="font-size: 0.95rem;">FREE</span>
                                                                        </div>
                                                                    </div>

                                                                    <p class="mb-2 mt-2 text-muted small">{{ $type->description }}</p>

                                                                    {{-- Schedule Options --}}
                                                                    <div class="schedule-options" id="schedule-options-{{ $type->id }}">
                                                                        <h6 class="fw-bold mb-2">Pilih Jadwal (Klik untuk Daftar):</h6>
                                                                        <div class="schedule-list" data-type-id="{{ $type->id }}">

                                                                            @foreach($type->availableSchedules ?? [] as $schedule)
                                                                                <div class="schedule-card"
                                                                                     data-schedule-id="{{ $schedule->id }}"
                                                                                     data-date="{{ $schedule->scheduled_date }}"
                                                                                     data-time="{{ $schedule->scheduled_time->format('H:i') }}"
                                                                                     data-type-id="{{ $type->id }}"
                                                                                     data-type-name="{{ $type->name }}"
                                                                                     data-base-price="{{ $type->base_price ?? 250000}}"
                                                                                     data-schedule-name="{{ $schedule->formatted_date }} - {{ $schedule->formatted_time }}">
                                                                                    <input type="radio" name="free_consultation_schedule" value="{{ $schedule->id }}" class="schedule-radio" id="schedule-{{ $schedule->id }}">
                                                                                    <strong>{{ $schedule->formatted_date }}</strong> - {{ $schedule->formatted_time }} (+7 GMT/WIB)
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
                                                    <h5 class="fw-bold mb-1">
                                                        {{ $service->title }}
                                                        <span class="ms-2 fw-normal fs-6">({{ $service->base_duration ?? 1 }} {{ Str::plural('Hour', $service->base_duration ?? 1) }} Packet)</span>
                                                    </h5>
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
                                                    <span class="fw-normal d-block mb-1" style="font-size: 0.9rem;">({{ $service->base_duration ?? 1 }} {{ Str::plural('Hour', $service->base_duration ?? 1) }} Packet)</span>
                                                    <p class="text-muted mb-0">{{ Str::limit($service->short_description, 45) }}</p>
                                                </div>
                                                <button class="btn-details-toggle" type="button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </h2>
                                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                    <div class="accordion-body p-4 rounded-4">

                                        {{-- Data untuk JS --}}
                                        <div class="service-block"
                                             data-service-id="{{ $service->id }}"
                                             data-base-price="{{ $service->price }}"
                                             data-base-duration="{{ $service->base_duration ?? 1 }}"
                                             data-hourly-price="{{ $service->hourly_price ?? $service->price }}">

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

                                                    {{-- Bagian 1: Jadwal --}}
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
                                                                <label class="form-label">Jam Mulai (+7 GMT/WIB):</label>
                                                                <input type="time" class="form-control booked_time-input" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Jam Berakhir (+7 GMT/WIB):</label>
                                                                <input type="time" class="form-control booked_time_end-input" readonly disabled style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Add-On (Durasi Tambahan):</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control hours-input"
                                                                           value="0"
                                                                           min="0"
                                                                           required style="text-align: center;">
                                                                    <span class="input-group-text">jam</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Pilihan Sesi</label>
                                                                <select class="form-select session-type-select">
                                                                    <option value="Online">Online</option>
                                                                    <option value="Offline">Offline</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3 offline-address-container" style="display:none;">
                                                                <label class="form-label">Alamat untuk Sesi Offline:</label>
                                                                <textarea class="form-control" placeholder="Masukkan alamat lengkap untuk sesi offline" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $serviceAddons = [];
                                                        $paintingAddons = [];

                                                        foreach($service->add_ons ?? [] as $i => $addon) {
                                                            $addonName = '';
                                                            $addonPrice = 0;
                                                            $addonId = null;
                                                            $isPainting = false;

                                                            if ($addon['type'] == 'custom') {
                                                                $addonName = $addon['title'];
                                                                $addonPrice = $addon['price'];
                                                                $addonId = 'custom_' . $i;

                                                                if (isset($addon['title']) && strpos($addon['title'], 'Painting:') === 0) {
                                                                    $isPainting = true;
                                                                }

                                                            } elseif ($addon['type'] == 'existing' && !empty($addon['service_id'])) {
                                                                $relatedService = $services->firstWhere('id', $addon['service_id']);
                                                                if ($relatedService) {
                                                                    $addonName = $relatedService->title . ' (Layanan)';
                                                                    $addonPrice = $relatedService->price;
                                                                    $addonId = $relatedService->id;
                                                                }
                                                            }

                                                            if ($addonId) {
                                                                $data = [
                                                                    'id' => $addonId,
                                                                    'name' => $addonName,
                                                                    'price' => $addonPrice,
                                                                    'price_formatted' => 'Rp. ' . number_format($addonPrice, 0, ',', '.')
                                                                ];

                                                                if ($isPainting) {
                                                                    $paintingAddons[] = $data;
                                                                } else {
                                                                    $serviceAddons[] = $data;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <hr class="my-4"> {{-- Bagian 2: Add-on Layanan --}}
                                                    <h6 class="fw-bold mb-3">Add-On (Layanan):</h6>
                                                    @if(!empty($serviceAddons))
                                                        <div class="selected-addons-container mb-2" id="selected-service-addons-{{ $service->id }}">
                                                            {{-- Item yang dipilih akan muncul di sini (via JS) --}}
                                                        </div>
                                                        <div class="input-group addon-controls">
                                                            <select class="form-select addon-dropdown" id="service-addon-dropdown-{{ $service->id }}">
                                                                <option value="">Pilih tambahan layanan...</option>
                                                                @foreach($serviceAddons as $addon)
                                                                    <option value="{{ $addon['id'] }}" data-price="{{ $addon['price'] }}" data-name="{{ $addon['name'] }}">
                                                                        {{ $addon['name'] }} (+{{ $addon['price_formatted'] }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button class="btn btn-primary btn-add-addon" type="button" data-service-id="{{ $service->id }}" data-type="service">
                                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                                            </button>
                                                        </div>
                                                    @else
                                                        <p class="text-muted small">Tidak ada layanan tambahan untuk paket ini.</p>
                                                    @endif

                                                    <hr class="my-4"> {{-- Bagian 3: Add-on Painting --}}
                                                    <h6 class="fw-bold mb-3">Add-On (Painting Character):</h6>
                                                    @if(!empty($paintingAddons))
                                                        <div class="selected-addons-container mb-2" id="selected-painting-addons-{{ $service->id }}">
                                                            {{-- Item yang dipilih akan muncul di sini (via JS) --}}
                                                        </div>
                                                        <div class="input-group addon-controls">
                                                            <select class="form-select addon-dropdown" id="painting-addon-dropdown-{{ $service->id }}">
                                                                <option value="">Pilih tambahan painting...</option>
                                                                @foreach($paintingAddons as $addon)
                                                                    <option value="{{ $addon['id'] }}" data-price="{{ $addon['price'] }}" data-name="{{ $addon['name'] }}">
                                                                        {{ $addon['name'] }} (+{{ $addon['price_formatted'] }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button class="btn btn-primary btn-add-addon" type="button" data-service-id="{{ $service->id }}" data-type="painting">
                                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                                            </button>
                                                        </div>
                                                    @else
                                                        <p class="text-muted small">Tidak ada opsi painting untuk paket ini.</p>
                                                    @endif

                                                </div> {{-- Akhir dari .form-section gabungan --}}
                                            </div>

                                            <div class="stagger-item">
                                                <div class="form-section contact-options mb-4">
                                                    <div class="col-12">
                                                        <h6 class="fw-bold">Saya bersedia dihubungi via:</h6>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_and_call" checked id="contact-chat-call-{{ $service->id }}">
                                                            <label class="form-check-label" for="contact-chat-call-{{ $service->id }}">Telepon & WhatsApp</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_only" id="contact-chat-only-{{ $service->id }}">
                                                            <label class="form-check-label" for="contact-chat-only-{{ $service->id }}">Hanya WhatsApp</label>
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
                                                            <span class="final-price" data-base-price="{{ $service->price }}">
                                                                Rp. {{ number_format($service->price, 0, ',', '.') }}
                                                            </span>
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

                // Helper function to format currency
                function formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value).replace('IDR', 'Rp.');
                }

                function calculateEndTime(startTime, baseDuration, additionalHours) {
                    if (!startTime) {
                        return "";
                    }

                    try {
                        const totalHours = parseInt(baseDuration, 10) + parseInt(additionalHours, 10);
                        const totalMinutes = totalHours * 60;
                        const [hours, minutes] = startTime.split(':').map(Number);
                        const date = new Date();
                        date.setHours(hours, minutes, 0, 0);
                        date.setMinutes(date.getMinutes() + totalMinutes);
                        const endHours = String(date.getHours()).padStart(2, '0');
                        const endMinutes = String(date.getMinutes()).padStart(2, '0');
                        return `${endHours}:${endMinutes}`;
                    } catch (e) {
                        console.error("Error calculating end time:", e);
                        return "";
                    }
                }

                function updateServicePrice(block) {
                    const priceElement = block.find('.final-price');
                    const basePrice = parseFloat(block.data('base-price'));
                    const hourlyPrice = parseFloat(block.data('hourly-price'));

                    if (isNaN(basePrice) || isNaN(hourlyPrice)) {
                        return;
                    }

                    const additionalHours = parseInt(block.find('.hours-input').val(), 10) || 0;
                    let durationCost = 0;

                    if (additionalHours > 0) {
                        durationCost = additionalHours * hourlyPrice;
                    }

                    let totalAddonPrice = 0;
                    block.find('.selected-addon-item').each(function() {
                        const itemPrice = parseFloat($(this).data('price'));
                        const qtyInput = $(this).find('.addon-quantity-input');
                        let quantity = 1;

                        if (qtyInput.length > 0) {
                            quantity = parseInt(qtyInput.val(), 10) || 1;
                        }
                        totalAddonPrice += (itemPrice * quantity);
                    });

                    const finalPrice = basePrice + durationCost + totalAddonPrice;
                    priceElement.text(formatCurrency(finalPrice));
                }

                // ===================================
                // LOGIKA KONSULTASI GRATIS
                // ===================================

                // 1. Pemilihan Jenis Konsultasi (untuk menampilkan jadwal)
                $(document).on('click', '.consultation-type-dropdown', function() {
                    const typeId = $(this).data('type-id');

                    // Set radio button
                    $(this).find('.consultation-type-radio').prop('checked', true).trigger('change');

                    // Reset semua highlight dan schedule options
                    $('.consultation-type-dropdown').removeClass('selected');
                    $('.schedule-options').removeClass('show');

                    // Highlight yang dipilih dan tampilkan jadwal
                    $(this).addClass('selected');
                    $('#schedule-options-' + typeId).addClass('show');

                    // Reset jadwal yang dipilih sebelumnya
                    $('.schedule-radio').prop('checked', false);
                    $('.schedule-card').removeClass('selected');
                });

                // 2. Pemilihan Jadwal (Langsung Redirect ke halaman baru)
                $(document).on('click', '.schedule-card', function() {
                    const scheduleId = $(this).data('schedule-id');
                    const typeId = $(this).data('type-id');
                    const typeName = $(this).data('type-name');
                    const scheduleName = $(this).data('schedule-name');
                    const bookedDate = $(this).data('date');
                    const bookedTime = $(this).data('time');
                    const basePrice = $(this).data('base-price');

                    // Set radio button tersembunyi
                    $(this).find('.schedule-radio').prop('checked', true);

                    // Highlight card yang dipilih
                    $('.schedule-card').removeClass('selected');
                    $(this).addClass('selected');

                    // 1. Kumpulkan data penting
                    const bookingData = {
                        is_free_consultation: true,
                        service_name: `Konsultasi Gratis: ${typeName}`,
                        service_id: 'new-free-consultation',
                        free_consultation_type_id: typeId,
                        free_consultation_schedule_id: scheduleId,
                        booked_date: bookedDate,
                        booked_time: bookedTime,
                        schedule_display: scheduleName,
                        base_price: basePrice,
                        // Data form yang akan diisi di halaman berikutnya:
                        session_type: 'Online',
                        offline_address: null,
                        contact_preference: 'chat_and_call',
                        referral_code: null,
                        total_hours: 1
                    };

                    // 2. Simpan data ke LocalStorage
                    localStorage.setItem('freeBookingData', JSON.stringify(bookingData));

                    Swal.fire({
                        title: translations.success,
                        text: `Anda memilih jadwal ${scheduleName} untuk ${typeName}. Anda akan diarahkan ke halaman pendaftaran.`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Pendaftaran',
                        cancelButtonText: 'Kembali',
                        confirmButtonColor: '#0c2c5a',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('front.free.booking.form') }}';
                        }
                    });
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

                // Event listener validasi dan kalkulasi harga/waktu
                $('.accordion-body').on('input change', '.service-date-picker, .booked_time-input, .hours-input, .session-type-select, .offline-address-container textarea', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');

                    if (serviceId === 'new-free-consultation') {
                        // Skip karena form sudah dihapus
                        return;
                    }

                    const bookedDate = block.find('.service-date-picker').val();
                    const bookedTime = block.find('.booked_time-input').val();
                    const sessionType = block.find('.session-type-select').val();
                    const offlineAddress = block.find('.offline-address-container textarea').val();

                    const hoursInput = block.find('.hours-input');
                    let additionalHours = 0;
                    let minHours = 0;

                    if (hoursInput.length) {
                        additionalHours = parseInt(hoursInput.val(), 10) || 0;
                        minHours = parseInt(hoursInput.attr('min'), 10) || 0;
                    }

                    let isValid = bookedDate && bookedTime && additionalHours >= minHours;

                    if (sessionType === 'Offline' && !offlineAddress.trim()) {
                        isValid = false;
                    }

                    const baseDuration = parseInt(block.data('base-duration'), 10) || 1;
                    const endTime = calculateEndTime(bookedTime, baseDuration, additionalHours);
                    block.find('.booked_time_end-input').val(endTime);

                    block.find('.select-service-btn').prop('disabled', !isValid);
                    updateServicePrice(block);
                });

                // Logika Add-On (tidak berubah)
                $(document).on('click', '.btn-add-addon', function() {
                    const serviceId = $(this).data('service-id');
                    const addonType = $(this).data('type');
                    const block = $(this).closest('.service-block');

                    const dropdown = $('#' + addonType + '-addon-dropdown-' + serviceId);
                    const container = $('#selected-' + addonType + '-addons-' + serviceId);

                    const selectedOption = dropdown.find('option:selected');
                    const addonId = selectedOption.val();
                    const addonName = selectedOption.data('name');
                    const addonPrice = selectedOption.data('price');

                    if (!addonId) {
                        return;
                    }

                    const priceFormatted = formatCurrency(addonPrice);

                    if (addonType === 'painting') {
                        const existingItem = container.find(`.selected-addon-item[data-addon-id="${addonId}"]`);
                        if (existingItem.length > 0) {
                            const qtyInput = existingItem.find('.addon-quantity-input');
                            qtyInput.val((parseInt(qtyInput.val(), 10) || 1) + 1);
                            qtyInput.trigger('input');
                            dropdown.val('');
                            return;
                        }
                    }

                    let selectedItemHtml = `
                        <div class="selected-addon-item" data-addon-id="${addonId}" data-price="${addonPrice}" data-name="${addonName}">
                            <div class="addon-details">
                                <span class="addon-name">${addonName}</span>
                                <span class="addon-price" data-unit-price="${addonPrice}">(+${priceFormatted})</span>
                            </div>
                            <div class="d-flex align-items-center">
                                ${addonType === 'painting' ? `
                                    <span class="me-2 text-muted" style="font-size: 0.85rem;">Jumlah:</span>
                                    <input type="number" class="form-control form-control-sm addon-quantity-input" value="1" min="1" style="width: 60px; text-align: center;">
                                ` : ''}
                                <button class="btn-remove-addon" data-addon-id="${addonId}" data-service-id="${serviceId}" data-type="${addonType}">
                                    &times;
                                </button>
                            </div>
                        </div>`;

                    if (addonType !== 'painting') {
                        selectedOption.hide();
                    }

                    container.append(selectedItemHtml);
                    dropdown.val('');
                    updateServicePrice(block);
                });

                $(document).on('input change', '.addon-quantity-input', function() {
                    const block = $(this).closest('.service-block');
                    const item = $(this).closest('.selected-addon-item');
                    const priceElement = item.find('.addon-price');
                    const unitPrice = parseFloat(priceElement.data('unit-price'));
                    let quantity = parseInt($(this).val(), 10) || 1;

                    if (quantity < 1) {
                        quantity = 1;
                        $(this).val(1);
                    }

                    const itemTotalPrice = unitPrice * quantity;
                    priceElement.text(`(+${formatCurrency(itemTotalPrice)})`);
                    updateServicePrice(block);
                });

                $(document).on('click', '.btn-remove-addon', function() {
                    const serviceId = $(this).data('service-id');
                    const addonId = $(this).data('addon-id');
                    const addonType = $(this).data('type');
                    const block = $(this).closest('.service-block');

                    if (addonType !== 'painting') {
                        $('#' + addonType + '-addon-dropdown-' + serviceId).find('option[value="' + addonId + '"]').show();
                    }

                    $(this).closest('.selected-addon-item').remove();
                    updateServicePrice(block);
                });

                // Handle service selection/booking (regular service)
                $('.select-service-btn').on('click', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');

                    // Skip free consultation
                    if (serviceId === 'new-free-consultation') {
                        return;
                    }

                    // Kumpulkan data (Logika tidak berubah dari sebelumnya)
                    const sessionType = block.find('.session-type-select').val();
                    const offlineAddress = block.find('.offline-address-container textarea').val();

                    const selectedAddons = [];
                    block.find('.selected-addon-item').each(function() {
                        const qtyInput = $(this).find('.addon-quantity-input');
                        let quantity = 1;
                        if (qtyInput.length > 0) {
                            quantity = parseInt(qtyInput.val(), 10) || 1;
                        }
                        selectedAddons.push({
                            id: $(this).data('addon-id'),
                            name: $(this).data('name'),
                            price: $(this).data('price'),
                            quantity: quantity
                        });
                    });

                    const baseDuration = parseInt(block.data('base-duration'), 10) || 1;
                    const additionalHours = parseInt(block.find('.hours-input').val(), 10) || 0;
                    const totalHours = baseDuration + additionalHours;

                    const booked_date = block.find('.service-date-picker').val();
                    const booked_time = block.find('.booked_time-input').val();
                    const contact_preference = block.find(`input[name="contact_preference-${serviceId}"]:checked`).val();
                    const referral_code = block.find('.referral-code-input').val() || null;

                    // Validasi data
                    if (!booked_date || !booked_time) {
                        return Swal.fire(translations.info, 'Harap lengkapi Tanggal dan Jam Mulai.', 'info');
                    }
                    if (sessionType === 'Offline' && !offlineAddress?.trim()) {
                        return Swal.fire(translations.info, 'Harap masukkan alamat untuk sesi Offline.', 'info');
                    }
                    if (!contact_preference) {
                        return Swal.fire(translations.failure, translations.validation_fails, 'error');
                    }

                    // Siapkan data
                    const finalData = {
                        id: serviceId,
                        hours: totalHours.toString(),
                        booked_date: booked_date,
                        booked_time: booked_time,
                        session_type: sessionType,
                        offline_address: sessionType === 'Offline' ? offlineAddress : null,
                        contact_preference: contact_preference,
                        referral_code: referral_code,
                        addons: selectedAddons,
                        _token: '{{ csrf_token() }}'
                    };


                    @auth
                        // Kirim ke AJAX (Authenticated)
                        $.ajax({
                            url: '{{ route("front.cart.add") }}',
                            type: 'POST',
                            data: finalData,
                            success: (response) => {
                                Swal.fire(translations.success, response.message, 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: (response) => {
                                const msg = response.responseJSON?.message || translations.validation_fails;
                                Swal.fire(translations.failure, msg, 'error');
                            }
                        });
                    @else
                        // Simpan ke LocalStorage (Guest)
                        const tempCart = getTempCart();
                        delete finalData._token;
                        tempCart[serviceId] = finalData;

                        saveTempCart(tempCart);
                        updateCartCount();

                        Swal.fire({
                            title: translations.success,
                            text: "Layanan berhasil ditambahkan ke keranjang!",
                            icon: 'success',
                            confirmButtonText: 'Lanjutkan',
                            footer: '<a href="{{ route("login") }}">Login untuk melanjutkan proses booking.</a>'
                        });
                    @endauth
                });

                // Handle referral code application
                $('.apply-referral-btn').on('click', function() {
                    const block = $(this).closest('.service-block');
                    const referralCode = block.find('.referral-code-input').val().trim();

                    if (!referralCode) {
                        return Swal.fire(translations.info, 'Masukkan kode referral terlebih dahulu.', 'info');
                    }

                    @guest
                        Swal.fire(translations.info, 'Kode referral akan divalidasi saat checkout. Silakan login untuk melanjutkan.', 'info');
                        return;
                    @endguest

                    @auth
                        Swal.fire({
                            title: translations.success,
                            text: `Kode referral "${referralCode}" akan diterapkan saat checkout.`,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0c2c5a'
                        });

                        $(this).text('Diterapkan').addClass('btn-success').removeClass('btn-outline-primary');
                        block.find('.referral-code-input').prop('readonly', true);
                    @endauth
                });

                // Logika inisialisasi tombol dan harga (tidak berubah)
                $('.service-block').each(function() {
                    const serviceId = $(this).data('service-id');
                    if(serviceId !== 'new-free-consultation') {
                        $(this).find('.hours-input').trigger('change');
                    }
                });

                updateCartCount();
            });
        </script>
    @endpush
@endsection