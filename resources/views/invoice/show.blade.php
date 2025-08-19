@extends('layouts.app')

@section('content')
    <style>
        /* Styling for the user-facing invoice */
        .section {
            font-family: 'Playfair Display', serif;
            padding: 20px;
            background-color: #f5f7fa;
        }

        .invoice-wrapper {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
        }


        .invoice-header {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-header-details {
            text-align: right;
        }

        .invoice-logo {
            font-size: 24px;
            font-weight: bold;
            color: #001f3f;
            margin-bottom: 10px;
        }

        .invoice-contact-info {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }

        .invoice-top-section {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 30px;
        }

        .invoice-client-info,
        .invoice-details-info {
            background-color: #f3faff;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 8px 10px;
            align-items: center;
        }

        .grid-full-span {
            grid-column: 1 / -1;
        }

        .grid-align-top-left {
            align-self: start;
        }

        .title-underline {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .title-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 57px;
            height: 2.5px;
            background-color: #CB2786;
        }

        .invoice-client-info p:first-child {
            font-weight: bold;
            font-size: 1.1rem;
            color: #001f3f;
        }

        .invoice-client-info  {
            color: #0F3A77;
        }

        .invoice-details-info h5 {
            color: #001f3f;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .invoice-details-info span {
            color: #0F3A77;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        .service-table th,
        .service-table td {
            padding: 16px 8px;
            text-align: left;
        }

        .service-table thead {
            background-color: #0C2C5A;
            color: white;
        }

        .service-table th {
            font-weight: 540;
        }

        .service-table .service td {
            color: #0C2C5A;
            font-weight: bold;
        }

        .service-hours-row td {
            padding-top: 2px;
            padding-bottom: 8px;
            font-size: 0.9em;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .service-table tbody tr.service {
             border-bottom: none;
        }

        .service-table .discount-row td {
            background-color: #FFB700;
            color: white;
        }

        .text-right {
            text-align: right;
        }

        .service-table th.text-right,
        .service-table td.text-right {
            text-align: right;
        }

        .summary-section {
            margin-top: 0;
            float: right;
            width: 100%;
        }

        .summary-line {
            display: flex;
            justify-content: flex-end;
            padding: 8px;
            border-bottom: 3px solid #eee;
        }

        .summary-line span:first-child {
            margin-right: 40px;
        }

        .summary-line.grand-total {
            background-color: #FFB700;
            font-weight: 700;
            color: #0C2C5A;
            padding: 16px 8px;
            border-bottom: none;
        }

        .summary-line.total-payable {
            background-color: #0C2C5A;
            color: #fff;
            font-weight: 700;
            padding: 16px 8px;
            border-bottom: none;
            margin-bottom: 100px;
        }

        .invoice-footer {
            clear: both;
            padding-top: 100px;
        }

        .payment-info {
            margin-top: 50px;
            background-color: #ffc107;
            padding: 20px;
            border-radius: 8px;
        }

        .payment-info h4 {
            font-weight: bold;
            color: #001f3f;
            margin-bottom: 10px;
        }

        .payment-info p {
            font-size: 14px;
            margin: 5px 0;
            color: #000;
            font-family: Arial, sans-serif;
        }

        .invoice-signature {
            margin-top: 50px;
            font-size: 14px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

    <div class="section container-fluid px-4 py-5" style="background-color: #f5f7fa; min-height: 100vh;">
        <div class="d-flex justify-content-start mb-4 no-print" style="max-width: 800px; margin: 0 auto;">
            <a href="{{ route('front.cart.view') }}" class="btn px-4 py-2"
                style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
            </a>
            <a href="{{ route('admin.consultation-bookings.download-pdf', $consultationBooking->id) }}"
                class="btn btn-primary px-4 py-2 ms-3">
                <i class="fas fa-download me-2"></i> Unduh PDF
            </a>
        </div>

        <div class="invoice-wrapper">
            <div class="invoice-header">
                <div class="invoice-header-details">
                    <div class="invoice-logo">Indiegologi</div>
                    <div class="invoice-contact-info">
                        Email: ceriadiego@gmail.com<br>
                        Phone Number: +62 822-2095-5595
                    </div>
                </div>
            </div>

            <div class="invoice-top-section">
                <div class="invoice-client-info">
                    <p class="grid-full-span title-underline" style="font-weight: bold; font-size: 1.1rem; color: #00617A;">Dear</p>

                    <span>Nama</span>
                    <span>: {{ $consultationBooking->receiver_name ?? 'N/A' }}</span>

                    @php
                        $offlineService = $consultationBooking->services->first(function ($service) {
                            return $service->pivot->session_type == 'Offline' && !empty($service->pivot->offline_address);
                        });
                    @endphp

                    @if ($offlineService)
                        <span>Alamat Offline</span>
                        <span>: {{ $offlineService->pivot->offline_address }}</span>
                    @endif

                    @if ($consultationBooking->services->isNotEmpty())

                        <span class="grid-full-span grid-align-top-left" style="margin-bottom: -25px;">Paket Konseling:</span>
                        <div class="grid-full-span" style="padding-left: 10px; line-height: 1.3;">

                            @foreach ($consultationBooking->services as $service)
                                • {{ $service->title }} ({{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }})<br>
                            @endforeach
                        </div>
                    @endif

                    <span>No Hp</span>
                    <span>: {{ optional($consultationBooking->user)->phone_number ?? 'N/A' }}</span>
                </div>

                <div class="invoice-details-info">
                    <h5 class="grid-full-span title-underline" style=" color: #00617A;">Invoice Details</h5>
                    <span>Invoice No</span>
                    <span>: {{ optional($consultationBooking->invoice)->invoice_no ?? 'N/A' }}</span>
                    <span>Invoice Date</span>
                    <span>: {{ optional($consultationBooking->invoice)?->invoice_date?->format('d/F/Y') ?? 'N/A' }}</span>
                    <span>Due Date</span>
                    <span>: {{ optional($consultationBooking->invoice)?->due_date?->format('d/F/Y') ?? 'N/A' }}</span>
                    <span>Status</span>
                    <span>: {{ ucfirst(optional($consultationBooking->invoice)?->payment_status ?? 'N/A') }}</span>

                    <span>Session</span>
                    <span>:
                        @php
                            $sessionTypes = $consultationBooking->services
                                            ->pluck('pivot.session_type')
                                            ->unique()
                                            ->map(function ($type) {
                                                return ucfirst($type);
                                            })
                                            ->join(', ');
                        @endphp
                        {{ $sessionTypes ?: 'N/A' }}
                    </span>

                    <span>Payment Type</span>
                    <span>:
                        @if ($consultationBooking->payment_type == 'dp')
                            DP (50%)
                        @else
                            {{ str_replace('_', ' ', Str::title($consultationBooking->payment_type ?? 'N/A')) }}
                        @endif
                    </span>
                    </div>
            </div>

            <table class="service-table">
                <thead>
                    <tr>
                        <th>Deskripsi layanan</th>
                        <th class="text-right">Kuantitas</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Besaran</th>
                        <th class="text-right">Total Line</th>
                    </tr>
                </thead>
                <tbody>
                    @php $quantity = 1; @endphp
                    @foreach ($consultationBooking->services as $service)
                        <tr class="service">
                            <td>{{ $service->title }}</td>
                            <td class="text-right">{{ $quantity }}</td>
                            <td class="text-right">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp {{ number_format($service->price * $quantity, 0, ',', '.') }}</td>
                        </tr>

                        @if($service->pivot->hours_booked > 0 && $service->hourly_price > 0)
                        <tr class="service-hours-row">
                            <td style="padding-left: 25px;">
                                 Sesi Tambahan ({{ $service->pivot->hours_booked }} Jam)
                            </td>
                            <td class="text-right"></td>
                            <td class="text-right">Rp {{ number_format($service->hourly_price, 0, ',', '.') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp {{ number_format($service->pivot->hours_booked * $service->hourly_price, 0, ',', '.') }}</td>
                        </tr>
                        @endif

                        {{-- ==================== PERUBAHAN DIMULAI DI SINI ==================== --}}
                        @if ($service->pivot->discount_amount_at_booking > 0)
                            <tr class="discount-row">
                                <td>Diskon</td>
                                <td class="text-right">1</td>
                                <td class="text-right">-</td>
                                <td class="text-right">
                                    @if (optional($service->pivot->referralCode)->discount_percentage)
                                        {{ rtrim(rtrim(number_format($service->pivot->referralCode->discount_percentage, 2, ',', '.'), '0'), ',') }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td class="text-right">-Rp {{ number_format($service->pivot->discount_amount_at_booking, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        {{-- ==================== PERUBAHAN SELESAI DI SINI ==================== --}}
                    @endforeach
                </tbody>
            </table>

            <div class="summary-section">
                <div class="summary-line">
                    <span>Sub-Total:</span>
                    <span>Rp
                        {{ number_format(optional($consultationBooking->invoice)->total_amount + optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</span>
                </div>

                {{-- ==================== PERUBAHAN DIMULAI DI SINI ==================== --}}
                @if (optional($consultationBooking->invoice)->discount_amount > 0)
                <div class="summary-line">
                    <span><b>Total Diskon Item:</b></span>
                    <span><b>-Rp
                            {{ number_format(optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</b></span>
                </div>
                @endif
                {{-- ==================== PERUBAHAN SELESAI DI SINI ==================== --}}

                <div class="summary-line grand-total">
                    <span>TOTAL KESELURUHAN :</span>
                    <span>Rp {{ number_format(optional($consultationBooking->invoice)->total_amount, 0, ',', '.') }}</span>
                </div>

                @if ($consultationBooking->payment_type == 'dp')
                    @php
                        $dpAmount = optional($consultationBooking->invoice)->total_amount * 0.5;
                    @endphp
                    <div class="summary-line total-payable">
                        <span>TOTAL BAYAR (DP 50%) :</span>
                        <span>Rp {{ number_format($dpAmount, 0, ',', '.') }}</span>
                    </div>
                @else
                    <style>
                        .summary-line.grand-total {
                            margin-bottom: 100px;
                        }
                    </style>
                @endif
            </div>

            <div class="invoice-signature">
                <p>Dear Customer,</p>
                <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah
                    disepakati akan diberikan charge tambahan</p>
                <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling</p>
                <p>reschedule dapat dilakukan selambat lambatnya 24 jam sebelum sesi konseling</p>
                <p>Sudah Menjadi bagian sejarah dari hidup {{ $consultationBooking->receiver_name ?? 'Anda' }},
                    semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
                <p>Salam</p>
                <p>{{ $adminName ?? 'Melvin' }}<br>Tim Indiegologi</p>
            </div>

            <div class="payment-info">
                <h4>Payment Information</h4>
                <p>Bank SMBC Indonesia - 90110023186</p>
                <p>Name: Artwira Mahatavirya Satyagasty</p>
                <p>Please Transfer Payment to the Account above before the due date,</p>
                <p>And Please Confirm to the following number: 0822 2095 5595</p>
            </div>
        </div>
    </div>
@endsection
