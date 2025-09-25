{{-- Mulai dari <div class="invoice-header"> hingga <div class="payment-info"> --}}
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
            {{-- PERBAIKAN: Hilangkan margin negatif dan tambahkan spacing yang cukup --}}
            <span class="grid-full-span grid-align-top-left">Paket Konseling:</span>
            <div class="grid-full-span" style="padding-left: 10px; line-height: 1.6; margin-top: 8px;">
                @foreach ($consultationBooking->services as $service)
                    • {{ $service->title }} ({{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }})<br>
                @endforeach
            </div>
        @endif

        <span>No Hp</span>
        <span>: {{ optional($consultationBooking->user->profile)->phone_number ?? 'N/A' }}</span>
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
            <th>Deskripsi</th>
            <th class="text-right">kuantitas</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Diskon</th>
            <th class="text-right">Total</th>
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
        @endforeach
    </tbody>
</table>

<div class="summary-section">
    <div class="summary-line">
        <span>Sub-Total:</span>
        <span>Rp
            {{ number_format(optional($consultationBooking->invoice)->total_amount + optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</span>
    </div>

    @if (optional($consultationBooking->invoice)->discount_amount > 0)
    <div class="summary-line">
        <span><b>Total Diskon Item:</b></span>
        <span><b>-Rp
                {{ number_format(optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</b></span>
    </div>
    @endif

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