@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #00617a;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }
    .btn-primary {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
    }
    .btn-primary:hover {
        background-color: #004a5f;
        border-color: #004a5f;
    }

    /* Penyesuaian Responsif */
    @media (max-width: 768px) {
        .page-header .d-flex {
            flex-direction: column;
            align-items: center !important;
            text-align: center;
        }
        .page-header .rounded-circle {
            margin-right: 0 !important;
            margin-bottom: 1rem;
        }
        .form-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        .form-actions .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }
</style>

<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(0, 97, 122, 0.1);">
                        <i class="fas fa-edit fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Edit Booking Konsultasi</h2>
                        <p class="text-muted mb-0">Ubah detail booking konsultasi #{{ $consultationBooking->id }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form id="editBookingForm" action="{{ route('admin.consultation-bookings.update', $consultationBooking->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <div class="row">
                    {{-- Form fields... --}}
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label text-secondary fw-medium">Pemesan</label>
                        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $consultationBooking->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="receiver_name" class="form-label text-secondary fw-medium">Nama Penerima</label>
                        <input type="text" id="receiver_name" name="receiver_name" class="form-control @error('receiver_name') is-invalid @enderror" value="{{ old('receiver_name', $consultationBooking->receiver_name) }}">
                        @error('receiver_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_type" class="form-label text-secondary fw-medium">Tipe Pembayaran</label>
                        <select id="payment_type" name="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                            <option value="full_payment" {{ old('payment_type', $consultationBooking->payment_type) == 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                            <option value="dp" {{ old('payment_type', $consultationBooking->payment_type) == 'dp' ? 'selected' : '' }}>DP (50%)</option>
                        </select>
                        @error('payment_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_preference" class="form-label text-secondary fw-medium">Preferensi Kontak</label>
                        <select id="contact_preference" name="contact_preference" class="form-select @error('contact_preference') is-invalid @enderror" required>
                            <option value="chat_only" {{ old('contact_preference', $consultationBooking->contact_preference) == 'chat_only' ? 'selected' : '' }}>Chat Only</option>
                            <option value="chat_and_call" {{ old('contact_preference', $consultationBooking->contact_preference) == 'chat_and_call' ? 'selected' : '' }}>Chat & Call</option>
                        </select>
                        @error('contact_preference') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="session_status" class="form-label text-secondary fw-medium">Status Sesi</label>
                        <select id="session_status" name="session_status" class="form-select @error('session_status') is-invalid @enderror" required>
                            <option value="menunggu pembayaran" {{ old('session_status', $consultationBooking->session_status) == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="terdaftar" {{ old('session_status', $consultationBooking->session_status) == 'terdaftar' ? 'selected' : '' }}>Terdaftar</option>
                            <option value="ongoing" {{ old('session_status', $consultationBooking->session_status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="selesai" {{ old('session_status', $consultationBooking->session_status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('session_status', $consultationBooking->session_status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('session_status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_status" class="form-label text-secondary fw-medium">Status Pembayaran</label>
                        <select id="payment_status" name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                            <option value="Pending" {{ old('payment_status', optional($consultationBooking->invoice)->payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ old('payment_status', optional($consultationBooking->invoice)->payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="UnPaid" {{ old('payment_status', optional($consultationBooking->invoice)->payment_status) == 'UnPaid' ? 'selected' : '' }}>UnPaid</option>
                        </select>
                        @error('payment_status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3">Detail Layanan yang Dipesan</h5>
                <div id="service-container">
                    @foreach ($consultationBooking->services as $index => $service)
                    <div class="service-block border rounded-3 p-3 mb-3" data-discount-percentage="{{ $service->pivot->referralCode->discount_percentage ?? 0 }}">
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-danger btn-sm remove-service" style="{{ $loop->first && $consultationBooking->services->count() === 1 ? 'display: none;' : '' }}">Hapus</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-id" class="form-label text-secondary fw-medium">Layanan</label>
                                <select id="services-{{ $index }}-id" name="services[{{ $index }}][id]" class="form-select service-select" required>
                                    <option value="">Pilih Layanan</option>
                                    @foreach($services as $s)
                                        <option value="{{ $s->id }}"
                                                data-price="{{ $s->price }}"
                                                data-hourly-price="{{ $s->hourly_price }}"
                                                {{ old("services.{$index}.id", $service->id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->title }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-hours" class="form-label text-secondary fw-medium">Jumlah Jam</label>
                                <input type="number" id="services-{{ $index }}-hours" name="services[{{ $index }}][hours]" class="form-control hours-input" value="{{ old("services.{$index}.hours", $service->pivot->hours_booked) }}" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-booked_date" class="form-label text-secondary fw-medium">Tanggal Booking</label>
                                <input type="date" id="services-{{ $index }}-booked_date" name="services[{{ $index }}][booked_date]" class="form-control" value="{{ old("services.{$index}.booked_date", optional($service->pivot->booked_date)->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-booked_time" class="form-label text-secondary fw-medium">Waktu Booking</label>
                                <input type="time" id="services-{{ $index }}-booked_time" name="services[{{ $index }}][booked_time]" class="form-control" value="{{ old("services.{$index}.booked_time", date('H:i', strtotime($service->pivot->booked_time))) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-session_type" class="form-label text-secondary fw-medium">Tipe Sesi</label>
                                <select id="services-{{ $index }}-session_type" name="services[{{ $index }}][session_type]" class="form-select session-type-select" required>
                                    <option value="Online" {{ old("services.{$index}.session_type", $service->pivot->session_type) == 'Online' ? 'selected' : '' }}>Online</option>
                                    <option value="Offline" {{ old("services.{$index}.session_type", $service->pivot->session_type) == 'Offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 offline-address-container" style="{{ old("services.{$index}.session_type", $service->pivot->session_type) == 'Offline' ? '' : 'display:none;' }}">
                                <label for="services-{{ $index }}-offline_address" class="form-label text-secondary fw-medium">Alamat Offline</label>
                                <textarea id="services-{{ $index }}-offline_address" name="services[{{ $index }}][offline_address]" class="form-control" {{ old("services.{$index}.session_type", $service->pivot->session_type) == 'Offline' ? 'required' : '' }}>{{ old("services.{$index}.offline_address", $service->pivot->offline_address) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services-{{ $index }}-referral_code" class="form-label text-secondary fw-medium">Kode Referral (Opsional)</label>
                                <div class="input-group">
                                    <input type="text" id="services-{{ $index }}-referral_code" name="services[{{ $index }}][referral_code]" class="form-control referral-code-input" value="{{ old("services.{$index}.referral_code", $service->pivot->referralCode->code ?? '') }}">
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
                    @endforeach
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

                <div class="d-flex justify-content-start mt-4 form-actions">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Booking</button>
                    <a href="{{ route('admin.consultation-bookings.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Script JavaScript tetap sama
    document.addEventListener('DOMContentLoaded', function () {
        let serviceIndex = {{ $consultationBooking->services->count() - 1 }};
        const servicesData = @json($services->keyBy('id'));
        const referralCodesData = @json($referralCodes->keyBy('code'));

        function calculatePrices() {
            let totalFinalPrice = 0;
            $('.service-block').each(function() {
                const block = $(this);
                const serviceId = block.find('.service-select').val();
                const hours = parseInt(block.find('.hours-input').val()) || 0;
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
            let paidAmount = (paymentType === 'dp') ? totalFinalPrice * 0.5 : totalFinalPrice;
            $('#paid-amount').text('Rp ' + paidAmount.toLocaleString('id-ID'));
        }

        $('#service-container').on('click', '.apply-referral-btn', function() {
            const block = $(this).closest('.service-block');
            const serviceId = block.find('.service-select').val();
            const referralCodeInput = block.find('.referral-code-input').val().toUpperCase();
            block.data('discount-percentage', 0);

            if (serviceId && referralCodeInput && referralCodesData[referralCodeInput]) {
                const code = referralCodesData[referralCodeInput];
                const isValid = !code.valid_until || new Date(code.valid_until) > new Date();

                if (isValid) {
                    block.data('discount-percentage', parseFloat(code.discount_percentage));
                    Swal.fire('Berhasil!', 'Kode referral berhasil diterapkan.', 'success');
                } else {
                    Swal.fire('Gagal!', 'Kode referral tidak valid atau sudah kadaluarsa.', 'error');
                }
            } else {
                Swal.fire('Gagal!', referralCodeInput ? 'Kode referral tidak ditemukan.' : 'Masukkan kode referral terlebih dahulu.', 'error');
            }
            calculatePrices();
        });

        function addServiceBlock() {
            serviceIndex++;
            const newBlock = `
                <div class="service-block border rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-end mb-2"><button type="button" class="btn btn-danger btn-sm remove-service">Hapus</button></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-id" class="form-label text-secondary fw-medium">Layanan</label>
                            <select id="services-${serviceIndex}-id" name="services[${serviceIndex}][id]" class="form-select service-select" required>
                                <option value="">Pilih Layanan</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-hourly-price="{{ $s->hourly_price }}">
                                        {{ $s->title }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label for="services-${serviceIndex}-hours" class="form-label text-secondary fw-medium">Jumlah Jam</label><input type="number" id="services-${serviceIndex}-hours" name="services[${serviceIndex}][hours]" class="form-control hours-input" value="0" min="0" required></div>
                        <div class="col-md-6 mb-3"><label for="services-${serviceIndex}-booked_date" class="form-label text-secondary fw-medium">Tanggal Booking</label><input type="date" id="services-${serviceIndex}-booked_date" name="services[${serviceIndex}][booked_date]" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label for="services-${serviceIndex}-booked_time" class="form-label text-secondary fw-medium">Waktu Booking</label><input type="time" id="services-${serviceIndex}-booked_time" name="services[${serviceIndex}][booked_time]" class="form-control" required></div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-session_type" class="form-label text-secondary fw-medium">Tipe Sesi</label>
                            <select id="services-${serviceIndex}-session_type" name="services[${serviceIndex}][session_type]" class="form-select session-type-select" required><option value="Online">Online</option><option value="Offline">Offline</option></select>
                        </div>
                        <div class="col-md-6 mb-3 offline-address-container" style="display:none;"><label for="services-${serviceIndex}-offline_address" class="form-label text-secondary fw-medium">Alamat Offline</label><textarea id="services-${serviceIndex}-offline_address" name="services[${serviceIndex}][offline_address]" class="form-control"></textarea></div>
                        <div class="col-md-6 mb-3">
                            <label for="services-${serviceIndex}-referral_code" class="form-label text-secondary fw-medium">Kode Referral</label>
                            <div class="input-group"><input type="text" id="services-${serviceIndex}-referral_code" name="services[${serviceIndex}][referral_code]" class="form-control referral-code-input"><button class="btn btn-secondary apply-referral-btn" type="button">Apply</button></div>
                        </div>
                    </div>
                    <div class="row mt-2"><div class="col-12 text-end"><span class="fw-semibold">Harga Awal:</span> <span class="initial-price">Rp 0</span><br><span class="text-success fw-semibold">Diskon:</span> <span class="discount-amount text-success">Rp 0</span><br><span class="fw-bold">Harga Setelah Diskon:</span> <span class="final-price">Rp 0</span></div></div>
                </div>`;
            $('#service-container').append(newBlock);
            $('.remove-service').show();
            calculatePrices();
        }

        $('#add-service-btn').on('click', addServiceBlock);

        $('#service-container').on('click', '.remove-service', function() {
            $(this).closest('.service-block').remove();
            if ($('.service-block').length <= 1) {
                $('.remove-service').hide();
            }
            calculatePrices();
        });

        $('#service-container, #payment_type').on('change input', '.service-select, .hours-input, .referral-code-input', calculatePrices);

        $('#service-container').on('change', '.session-type-select', function() {
            const container = $(this).closest('.service-block').find('.offline-address-container');
            if ($(this).val() === 'Offline') {
                container.show().find('textarea').attr('required', true);
            } else {
                container.hide().find('textarea').attr('required', false);
            }
        });z

        calculatePrices();
    });
</script>
@endsection
