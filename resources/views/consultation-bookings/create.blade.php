@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #00617a;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-calendar-check fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Buat Booking Baru</h2>
                        <p class="text-muted mb-0">Isi detail booking konsultasi di bawah ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.consultation-bookings.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- User and Receiver --}}
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label text-secondary fw-medium">Pemesan</label>
                        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="receiver_name" class="form-label text-secondary fw-medium">Nama Penerima</label>
                        <input type="text" id="receiver_name" name="receiver_name" class="form-control @error('receiver_name') is-invalid @enderror" value="{{ old('receiver_name') }}">
                        @error('receiver_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Payment Type and Contact Preference --}}
                    <div class="col-md-6 mb-3">
                        <label for="payment_type" class="form-label text-secondary fw-medium">Tipe Pembayaran</label>
                        <select id="payment_type" name="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                            <option value="full_payment" {{ old('payment_type') == 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                            <option value="dp" {{ old('payment_type') == 'dp' ? 'selected' : '' }}>DP (50%)</option>
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_preference" class="form-label text-secondary fw-medium">Preferensi Kontak</label>
                        <select id="contact_preference" name="contact_preference" class="form-select @error('contact_preference') is-invalid @enderror" required>
                            <option value="chat_only" {{ old('contact_preference') == 'chat_only' ? 'selected' : '' }}>Chat Only</option>
                            <option value="chat_and_call" {{ old('contact_preference') == 'chat_and_call' ? 'selected' : '' }}>Chat & Call</option>
                        </select>
                        @error('contact_preference')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                {{-- Dynamic Service Blocks --}}
                <h5 class="fw-bold mb-3">Detail Layanan yang Dipesan</h5>
                <div id="service-container">
                    <div class="service-block border rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-danger btn-sm remove-service" style="display: none;">Hapus</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="services-0-id" class="form-label text-secondary fw-medium">Layanan</label>
                                <select id="services-0-id" name="services[0][id]" class="form-select service-select" required>
                                    <option value="">Pilih Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}"
                                                data-price="{{ $service->price }}"
                                                data-hourly-price="{{ $service->hourly_price }}">
                                            {{ $service->title }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-0-hours" class="form-label text-secondary fw-medium">Jumlah Jam</label>
                                <input type="number" id="services-0-hours" name="services[0][hours]" class="form-control hours-input" value="0" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-0-booked_date" class="form-label text-secondary fw-medium">Tanggal Booking</label>
                                <input type="date" id="services-0-booked_date" name="services[0][booked_date]" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-0-booked_time" class="form-label text-secondary fw-medium">Waktu Booking</label>
                                <input type="time" id="services-0-booked_time" name="services[0][booked_time]" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-0-session_type" class="form-label text-secondary fw-medium">Tipe Sesi</label>
                                <select id="services-0-session_type" name="services[0][session_type]" class="form-select session-type-select" required>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 offline-address-container" style="display:none;">
                                <label for="services-0-offline_address" class="form-label text-secondary fw-medium">Alamat Offline</label>
                                <textarea id="services-0-offline_address" name="services[0][offline_address]" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-0-referral_code" class="form-label text-secondary fw-medium">Kode Referral (Opsional)</label>
                                <div class="input-group">
                                    <input type="text" id="services-0-referral_code" name="services[0][referral_code]" class="form-control referral-code-input">
                                    <button class="btn btn-secondary apply-referral-btn" type="button">Apply</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 text-end">
                                <span class="fw-semibold">Harga Awal:</span> <span class="initial-price">Rp 0</span><br>
                                <span class="text-success fw-semibold">Diskon:</span> <span class="discount-amount text-success">Rp 0</span><br>
                                <span class="fw-bold">Harga Setelah Diskon:</span> <span class="final-price">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="button" id="add-service-btn" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Layanan
                    </button>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <h5 class="fw-bold">Total Tagihan: <span id="final-total-amount">Rp 0</span></h5>
                        <h5 class="fw-bold">Jumlah yang Harus Dibayar: <span id="paid-amount">Rp 0</span></h5>
                    </div>
                </div>

                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2">Buat Booking</button>
                    <a href="{{ route('admin.consultation-bookings.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let serviceIndex = 0;
        const servicesData = @json($services->keyBy('id'));
        const referralCodesData = @json($referralCodes->keyBy('code'));

        function calculatePrices() {
            let totalFinalPrice = 0;
            $('.service-block').each(function() {
                const block = $(this);
                const serviceId = block.find('.service-select').val();
                const hours = parseInt(block.find('.hours-input').val()) || 0;

                // Ambil data diskon dari data attribute, bukan dari input
                const discountPercentage = parseFloat(block.data('discount-percentage')) || 0;

                if (serviceId) {
                    const service = servicesData[serviceId];
                    const basePrice = parseFloat(service.price);
                    const hourlyPrice = parseFloat(service.hourly_price);

                    let initialPrice = basePrice + (hourlyPrice * hours);

                    let discountAmount = (initialPrice * discountPercentage) / 100;
                    let finalPrice = initialPrice - discountAmount;

                    block.find('.initial-price').text('Rp ' + initialPrice.toLocaleString('id-ID'));
                    block.find('.discount-amount').text('Rp ' + discountAmount.toLocaleString('id-ID'));
                    block.find('.final-price').text('Rp ' + finalPrice.toLocaleString('id-ID'));
                    totalFinalPrice += finalPrice;
                } else {
                    block.find('.initial-price').text('Rp 0');
                    block.find('.discount-amount').text('Rp 0');
                    block.find('.final-price').text('Rp 0');
                }
            });

            $('#final-total-amount').text('Rp ' + totalFinalPrice.toLocaleString('id-ID'));

            const paymentType = $('#payment_type').val();
            let paidAmount = 0;
            if (paymentType === 'dp') {
                paidAmount = totalFinalPrice * 0.5;
            } else {
                paidAmount = totalFinalPrice;
            }
            $('#paid-amount').text('Rp ' + paidAmount.toLocaleString('id-ID'));
        }

        // Fungsi untuk meng-apply kode referral
        $('#service-container').on('click', '.apply-referral-btn', function() {
            const block = $(this).closest('.service-block');
            const serviceId = block.find('.service-select').val();
            const referralCodeInput = block.find('.referral-code-input').val().toUpperCase();

            block.data('referral-code-applied', null);
            block.data('discount-percentage', 0);

            if (serviceId && referralCodeInput && referralCodesData[referralCodeInput]) {
                const code = referralCodesData[referralCodeInput];
                const isValid = !code.valid_until || new Date(code.valid_until) > new Date();
                const hasUses = !code.max_uses || code.current_uses < code.max_uses;

                if (isValid && hasUses) {
                    block.data('referral-code-applied', referralCodeInput);
                    block.data('discount-percentage', parseFloat(code.discount_percentage));
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
            calculatePrices();
        });


        // Fungsi untuk menambah block layanan
        function addServiceBlock() {
            serviceIndex++;
            const newBlock = `
                <div class="service-block border rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-danger btn-sm remove-service">Hapus</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-id" class="form-label text-secondary fw-medium">Layanan</label>
                            <select id="services-${serviceIndex}-id" name="services[${serviceIndex}][id]" class="form-select service-select" required>
                                <option value="">Pilih Layanan</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                            data-price="{{ $service->price }}"
                                            data-hourly-price="{{ $service->hourly_price }}">
                                        {{ $service->title }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-hours" class="form-label text-secondary fw-medium">Jumlah Jam</label>
                            <input type="number" id="services-${serviceIndex}-hours" name="services[${serviceIndex}][hours]" class="form-control hours-input" value="0" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-booked_date" class="form-label text-secondary fw-medium">Tanggal Booking</label>
                            <input type="date" id="services-${serviceIndex}-booked_date" name="services[${serviceIndex}][booked_date]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-booked_time" class="form-label text-secondary fw-medium">Waktu Booking</label>
                            <input type="time" id="services-${serviceIndex}-booked_time" name="services[${serviceIndex}][booked_time]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-session_type" class="form-label text-secondary fw-medium">Tipe Sesi</label>
                            <select id="services-${serviceIndex}-session_type" name="services[${serviceIndex}][session_type]" class="form-select session-type-select" required>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 offline-address-container" style="display:none;">
                            <label for="services-${serviceIndex}-offline_address" class="form-label text-secondary fw-medium">Alamat Offline</label>
                            <textarea id="services-${serviceIndex}-offline_address" name="services[${serviceIndex}][offline_address]" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-referral_code" class="form-label text-secondary fw-medium">Kode Referral (Opsional)</label>
                            <div class="input-group">
                                <input type="text" id="services-${serviceIndex}-referral_code" name="services[${serviceIndex}][referral_code]" class="form-control referral-code-input">
                                <button class="btn btn-secondary apply-referral-btn" type="button">Apply</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 text-end">
                            <span class="fw-semibold">Harga Awal:</span> <span class="initial-price">Rp 0</span><br>
                            <span class="text-success fw-semibold">Diskon:</span> <span class="discount-amount text-success">Rp 0</span><br>
                            <span class="fw-bold">Harga Setelah Diskon:</span> <span class="final-price">Rp 0</span>
                        </div>
                    </div>
                </div>
            `;
            $('#service-container').append(newBlock);
            if ($('.service-block').length > 1) {
                $('.remove-service').show();
            }
            calculatePrices();
        }

        // Event listener untuk menambah block layanan
        $('#add-service-btn').on('click', addServiceBlock);

        // Event listener untuk menghapus block layanan
        $('#service-container').on('click', '.remove-service', function() {
            $(this).closest('.service-block').remove();
            if ($('.service-block').length <= 1) {
                $('.remove-service').hide();
            }
            calculatePrices();
        });

        // Event listener untuk perubahan input
        $('#service-container').on('change', '.service-select, .hours-input, .referral-code-input', function() {
            calculatePrices();
        });
        $('#service-container').on('input', '.hours-input, .referral-code-input', function() {
            calculatePrices();
        });

        // Event listener untuk perubahan tipe pembayaran
        $('#payment_type').on('change', function() {
            calculatePrices();
        });

        // Event listener untuk perubahan tipe sesi (online/offline)
        $('#service-container').on('change', '.session-type-select', function() {
            const container = $(this).closest('.service-block').find('.offline-address-container');
            if ($(this).val() === 'Offline') {
                container.show().find('textarea').attr('required', true);
            } else {
                container.hide().find('textarea').attr('required', false);
            }
        });

        // Inisialisasi
        calculatePrices();
        if ($('.service-block').length <= 1) {
            $('.remove-service').hide();
        }
    });
</script>
@endsection
