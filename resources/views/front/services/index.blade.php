@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/service-details.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        @keyframes fadeInUp{from{opacity:0;transform:translate3d(0,20px,0)}to{opacity:1;transform:translate3d(0,0,0)}}.stagger-item{opacity:0}.accordion-collapse.show .stagger-item{animation:fadeInUp .5s ease-out forwards}.accordion-collapse.show .stagger-item:nth-child(1){animation-delay:.05s}.accordion-collapse.show .stagger-item:nth-child(2){animation-delay:.1s}.accordion-collapse.show .stagger-item:nth-child(3){animation-delay:.15s}.accordion-collapse.show .stagger-item:nth-child(4){animation-delay:.2s}.accordion-collapse.show .stagger-item:nth-child(5){animation-delay:.25s}@media (max-width:767.98px){.accordion-button .service-header-mobile{display:flex;flex-direction:column;align-items:flex-start;width:100%}.accordion-button .service-header-mobile-top{display:flex;justify-content:space-between;align-items:flex-start;width:100%}.accordion-button .service-header-mobile .service-thumbnail-mobile{width:100%;height:150px;object-fit:cover;border-radius:8px;margin-bottom:1rem}.accordion-button h5{font-size:1.1rem}.accordion-button p{font-size:.85rem}.btn-details-toggle{font-size:0;width:40px;height:40px;padding:0;border-radius:50%;background-color:#f1f3f5;color:var(--indiegologi-primary);display:flex;align-items:center;justify-content:center;border:none;flex-shrink:0}.btn-details-toggle::after{content:'\F285';font-family:'bootstrap-icons';font-size:1rem;transition:transform .3s ease}.accordion-button:not(.collapsed) .btn-details-toggle::after{transform:rotate(90deg)}}
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

                    {{-- Bagian Konsultasi Gratis --}}
                    <div class="row justify-content-center mb-4">
                        <div class="col-lg-12">
                            <div class="accordion" id="freeServiceAccordion">
                                {{-- [PERBAIKAN WARNA] Menghapus class `border-primary` --}}
                                <div class="accordion-item mb-3 rounded-4 shadow-sm" data-aos="fade-up" data-aos-duration="800">
                                    <h2 class="accordion-header"><div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-free-consultation"><div class="d-none d-md-flex justify-content-between align-items-center w-100"><div class="d-flex align-items-center"><img src="https://placehold.co/100x100/D4AF37/ffffff?text=Gratis" alt="Konsultasi Gratis" class="rounded-3 me-3" style="width:100px;height:100px;object-fit:cover"><div><h5 class="fw-bold mb-1">Konsultasi Gratis</h5><p class="text-muted mb-0">Coba sesi 1 jam pertama Anda tanpa biaya.</p></div></div><button class="btn-details-toggle" type="button">Baca Selengkapnya</button></div><div class="d-flex d-md-none service-header-mobile"><img src="https://placehold.co/400x200/D4AF37/ffffff?text=Gratis" alt="Konsultasi Gratis" class="service-thumbnail-mobile"><div class="service-header-mobile-top"><div><h5 class="fw-bold mb-1">Konsultasi Gratis</h5><p class="text-muted mb-0">Coba sesi 1 jam pertama.</p></div><button class="btn-details-toggle" type="button"></button></div></div></div></h2>
                                    <div id="collapse-free-consultation" class="accordion-collapse collapse" data-bs-parent="#freeServiceAccordion">
                                        <div class="accordion-body p-4">
                                            <div class="service-block" data-service-id="free-consultation">
                                                <div class="stagger-item"><div class="row mb-4"><div class="col-12"><h6 class="fw-judul">Deskripsi Produk:</h6><p>Sesi konsultasi gratis ini dirancang untuk memberikan Anda gambaran tentang bagaimana kami dapat membantu. Selama 1 jam, Anda akan berbicara dengan konselor kami untuk membahas tujuan Anda dan menentukan langkah selanjutnya.</p></div></div></div>
                                                <div class="stagger-item"><div class="form-section mb-4"><div class="row"><div class="col-12 mb-3"><h6 class="fw-bold">Pilih Jadwal Konsultasi:</h6><small class="text-muted">(Pemesanan minimal H-1)</small></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Tanggal:</label><input type="date" class="form-control service-date-picker" required></div></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Jam Mulai:</label><input type="time" class="form-control booked_time-input" required></div></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Durasi (Jam)</label><input type="number" class="form-control hours-input" value="1" readonly><small class="text-muted">Sesi gratis berdurasi 1 jam.</small></div></div><div class="col-12"><div class="mb-3"><label class="form-label">Pilihan Sesi</label><select class="form-select session-type-select"><option value="Online">Online</option><option value="Offline">Offline</option></select></div><div class="mb-3 offline-address-container" style="display:none;"><textarea class="form-control" placeholder="Sleman Utara DIY"></textarea></div></div></div></div></div>
                                                <div class="stagger-item"><div class="form-section contact-options mb-4"><div class="col-12"><h6 class="fw-bold">Saya bersedia dihubungi via:</h6><div class="form-check mb-2"><input class="form-check-input" type="radio" name="contact_preference-free-consultation" value="chat_and_call" checked><label class="form-check-label">Telepon & WhatsApp</label></div><div class="form-check"><input class="form-check-input" type="radio" name="contact_preference-free-consultation" value="chat_only"><label class="form-check-label">Hanya WhatsApp</label></div></div></div></div>
                                                <div class="stagger-item"><hr class="my-5"></div>
                                                <div class="stagger-item"><div class="row justify-content-between align-items-start mb-3"><div class="col-auto"><div class="final-price-display"><span class="final-price">Rp. 0</span></div></div><div class="col-auto"><button type="button" class="btn btn-primary px-4 py-2 select-service-btn" data-service-id="free-consultation">Booking Sesi Gratis</button></div></div></div>
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
                                <h2 class="accordion-header" id="heading-{{ $service->id }}"><div class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $service->id }}"><div class="d-none d-md-flex justify-content-between align-items-center w-100"><div class="d-flex align-items-center"><img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="rounded-3 me-3" style="width:100px;height:100px;object-fit:cover"><div><h5 class="fw-bold mb-1">{{ $service->title }}</h5><p class="text-muted mb-0">{{ Str::limit($service->short_description, 70) }}</p></div></div><button class="btn-details-toggle" type="button">Baca Selengkapnya</button></div><div class="d-flex d-md-none service-header-mobile"><img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="service-thumbnail-mobile"><div class="service-header-mobile-top"><div><h5 class="fw-bold mb-1">{{ $service->title }}</h5><p class="text-muted mb-0">{{ Str::limit($service->short_description, 45) }}</p></div><button class="btn-details-toggle" type="button"></button></div></div></div></h2>
                                <div id="collapse-{{ $service->id }}" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                    <div class="accordion-body p-4">
                                        <div class="service-block" data-service-id="{{ $service->id }}">
                                            <div class="stagger-item"><div class="row mb-4"><div class="col-12"><h6 class="fw-judul">Deskripsi Produk:</h6><p>{{ $service->product_description }}</p></div></div></div>
                                            <div class="stagger-item"><div class="form-section mb-4"><div class="row"><div class="col-12 mb-3"><h6 class="fw-bold">Pilih Jadwal:</h6><small class="text-muted">(Pemesanan minimal H-1)</small></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Tanggal:</label><input type="date" class="form-control service-date-picker" required></div></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Jam Mulai:</label><input type="time" class="form-control booked_time-input" required></div></div><div class="col-lg-4 col-md-12"><div class="mb-3"><label class="form-label">Jumlah Jam</label><input type="number" class="form-control hours-input" value="0" min="0" required></div></div><div class="col-12"><div class="mb-3"><label class="form-label">Pilihan Sesi</label><select class="form-select session-type-select"><option value="Online">Online</option><option value="Offline">Offline</option></select></div><div class="mb-3 offline-address-container" style="display:none;"><textarea class="form-control" placeholder="Sleman Utara DIY"></textarea></div></div></div></div></div>
                                            <div class="stagger-item"><div class="form-section contact-options mb-4"><div class="col-12"><h6 class="fw-bold">Saya bersedia dihubungi via:</h6><div class="form-check mb-2"><input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_and_call" checked><label class="form-check-label">Telepon & WhatsApp</label></div><div class="form-check"><input class="form-check-input" type="radio" name="contact_preference-{{ $service->id }}" value="chat_only"><label class="form-check-label">Hanya WhatsApp</label></div></div></div></div>
                                            <div class="stagger-item"><hr class="my-5"></div>
                                            <div class="stagger-item"><div class="row justify-content-between align-items-start mb-3"><div class="col-auto"><div class="final-price-display"><span class="final-price">Rp. {{ number_format($service->price, 0, ',', '.') }}</span></div></div><div class="col-auto"><button type="button" class="btn btn-primary px-4 py-2 select-service-btn" data-service-id="{{ $service->id }}">Pilih Layanan</button></div></div><div class="referral-section text-center"><label class="form-label d-block mb-2">Punya Kode Referral?</label><div class="input-group"><input type="text" class="form-control referral-code-input"><button class="btn apply-referral-btn" type="button" data-service-id="{{ $service->id }}">Apply</button></div></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5"><p class="text-muted">Layanan kami akan segera tersedia!</p></div>
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
                const minDate = new Date();
                minDate.setDate(minDate.getDate() + 1);
                document.querySelectorAll('.service-date-picker').forEach(input => input.min = minDate.toISOString().split("T")[0]);
                const translations = {
                    success: "Berhasil!", failure: "Gagal!", info: "Perhatian!",
                    validation_fails: "Validasi gagal. Pastikan semua kolom terisi dengan benar.",
                };
                function getTempCart() { return JSON.parse(localStorage.getItem('tempCart')) || {}; }
                function saveTempCart(cart) { localStorage.setItem('tempCart', JSON.stringify(cart)); }
                function updateCartCount() { /* Logika Anda */ }
                $('.accordion-body').on('change', '.session-type-select', function() {
                    $(this).closest('.service-block').find('.offline-address-container').slideToggle($(this).val() === 'Offline');
                });
                $('.accordion-body').on('input', '.service-date-picker, .booked_time-input, .hours-input', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');
                    const bookedDate = block.find('.service-date-picker').val();
                    const bookedTime = block.find('.booked_time-input').val();
                    const hoursInput = block.find('.hours-input');
                    const hoursBooked = hoursInput.length ? parseInt(hoursInput.val(), 10) : 1;
                    const isValid = (serviceId === 'free-consultation') ? (bookedDate && bookedTime) : (bookedDate && bookedTime && hoursBooked >= 0);
                    block.find('.select-service-btn').prop('disabled', !isValid);
                });
                $('.select-service-btn').on('click', function() {
                    const block = $(this).closest('.service-block');
                    const serviceId = block.data('service-id');
                    const formData = {
                        id: serviceId,
                        hours: block.find('.hours-input').val(),
                        booked_date: block.find('.service-date-picker').val(),
                        booked_time: block.find('.booked_time-input').val(),
                        session_type: block.find('.session-type-select').val(),
                        offline_address: block.find('.offline-address-container textarea').val(),
                        contact_preference: block.find(`input[name="contact_preference-${serviceId}"]:checked`).val(),
                        _token: '{{ csrf_token() }}'
                    };
                    if (!formData.booked_date || !formData.booked_time) {
                        return Swal.fire(translations.info, 'Harap lengkapi Tanggal dan Jam Mulai.', 'info');
                    }
                    if (formData.session_type === 'Offline' && !formData.offline_address) {
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
                            success: (response) => Swal.fire(translations.success, response.message, 'success').then(updateCartCount),
                            error: (response) => {
                                const msg = response.responseJSON?.message || translations.validation_fails;
                                Swal.fire(translations.failure, msg, 'error');
                            }
                        });
                    @else
                        const tempCart = getTempCart();
                        tempCart[serviceId] = formData;
                        saveTempCart(tempCart);
                        updateCartCount();
                        Swal.fire({
                            title: translations.success, text: "Layanan berhasil ditambahkan!",
                            icon: 'success', confirmButtonText: 'Lanjutkan', footer: '<a href="{{ route("login") }}">Login untuk melanjutkan.</a>'
                        });
                    @endauth
                });
                $('.service-block').each(function() {
                    $(this).find('.select-service-btn').prop('disabled', true);
                });
            });
        </script>
    @endpush
@endsection