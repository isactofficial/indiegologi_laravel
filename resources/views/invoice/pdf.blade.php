{{-- resources/views/invoice/partials/invoice-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{ optional($consultationBooking->invoice)->invoice_no ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        p {
            margin: 5px 0;
            line-height: 1.4;
        }
        .service-table th,
        .service-table td {
            padding: 8px 6px;
            text-align: left;
            vertical-align: top;
        }
        .service-table tfoot td {
            border-top: 2px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper" style="width: 100%; max-width: 800px; margin: 0 auto;">

        {{-- INVOICE HEADER --}}
        <div class="invoice-header" style="text-align: right; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;">
              <div class="invoice-logo" style="font-size: 22px; font-weight: bold; color: #001f3f; margin-bottom: 8px;">
                Indiegologi
              </div>
            <div class="invoice-contact-info" style="font-size: 12px; line-height: 1.5;">
                Email: ceriadiego@gmail.com<br>
                Phone Number: +62 822-2095-5595
            </div>
        </div>

        {{-- CLIENT AND INVOICE DETAILS --}}
        <table style="width: 100%; border-spacing: 0; margin-bottom: 20px;" class="avoid-break">
            <tr>
                <td style="width: 48%; background-color: #f3faff; padding: 15px; vertical-align: top; border-radius: 8px;">
                    <p style="font-weight: bold; font-size: 1.1em; color: #00617A; padding-bottom: 8px; border-bottom: 2.5px solid #CB2786; display: inline-block; margin-top: 0;">Dear</p>
                    <div style="color: #0F3A77;">
                        <p><strong>Nama:</strong> {{ $consultationBooking->receiver_name ?? 'N/A' }}</p>
                        @php
                            $offlineService = $consultationBooking->services->first(function ($service) {
                                return $service->pivot->session_type == 'Offline' && !empty($service->pivot->offline_address);
                            });
                        @endphp
                        @if ($offlineService)
                            <p><strong>Alamat Offline:</strong> {{ $offlineService->pivot->offline_address }}</p>
                        @endif
                        @if ($consultationBooking->services->isNotEmpty())
                            <p style="margin-bottom: 5px;"><strong>Paket Konseling:</strong></p>
                            <div style="padding-left: 10px; line-height: 1.3;">
                                @foreach ($consultationBooking->services as $service)
                                    • {{ $service->title }} ({{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }})<br>
                                @endforeach
                            </div>
                        @endif
                        <p style="margin-top: 10px;"><strong>No Hp:</strong> {{ optional($consultationBooking->user->profile)->phone_number ?? 'N/A' }}</p>
                    </div>
                </td>
                <td style="width: 4%;"></td> {{-- Spacer --}}
                <td style="width: 48%; background-color: #f3faff; padding: 15px; vertical-align: top; border-radius: 8px;">
                    {{-- [START] MODIFIED SECTION --}}
                    <h5 style="font-weight: bold; font-size: 1.1em; color: #00617A; padding-bottom: 8px; border-bottom: 2.5px solid #CB2786; display: inline-block; margin-top: 0; margin-bottom: 8px;">Invoice Details</h5>
                    {{-- [END] MODIFIED SECTION --}}
                    <div style="color: #0F3A77;">
                        <p><strong>Invoice No:</strong> {{ optional($consultationBooking->invoice)->invoice_no ?? 'N/A' }}</p>
                        <p><strong>Invoice Date:</strong> {{ optional($consultationBooking->invoice)?->invoice_date?->format('d/F/Y') ?? 'N/A' }}</p>
                        <p><strong>Due Date:</strong> {{ optional($consultationBooking->invoice)?->due_date?->format('d/F/Y') ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> {{ ucfirst(optional($consultationBooking->invoice)?->payment_status ?? 'N/A') }}</p>
                        <p><strong>Session:</strong>
                            @php
                                $sessionTypes = $consultationBooking->services->pluck('pivot.session_type')->unique()->map(fn($type) => ucfirst($type))->join(', ');
                            @endphp
                            {{ $sessionTypes ?: 'N/A' }}
                        </p>
                        <p><strong>Payment Type:</strong>
                            @if ($consultationBooking->payment_type == 'dp')
                                DP (50%)
                            @else
                                {{ str_replace('_', ' ', Str::title($consultationBooking->payment_type ?? 'N/A')) }}
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        {{-- SERVICE DETAILS TABLE WITH INTEGRATED FOOTER FOR SUMMARY --}}
        <table class="service-table avoid-break">
            <thead style="background-color: #0C2C5A; color: white;">
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
                        <td style="color: #0C2C5A; font-weight: bold; text-align: left;">{{ $service->title }}</td>
                        <td style="text-align: right;">{{ $quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">-</td>
                        <td style="text-align: right;">Rp {{ number_format($service->price * $quantity, 0, ',', '.') }}</td>
                    </tr>
                    @if($service->pivot->hours_booked > 0 && $service->hourly_price > 0)
                    <tr class="service-hours-row">
                        <td style="padding-left: 20px; padding-top: 2px; padding-bottom: 6px; font-size: 0.9em; color: #555; border-bottom: 1px solid #eee; text-align: left;">
                            Sesi Tambahan ({{ $service->pivot->hours_booked }} Jam)
                        </td>
                        <td style="text-align: right; border-bottom: 1px solid #eee;"></td>
                        <td style="text-align: right; border-bottom: 1px solid #eee;">Rp {{ number_format($service->hourly_price, 0, ',', '.') }}</td>
                        <td style="text-align: right; border-bottom: 1px solid #eee;">-</td>
                        <td style="text-align: right; border-bottom: 1px solid #eee;">Rp {{ number_format($service->pivot->hours_booked * $service->hourly_price, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if ($service->pivot->discount_amount_at_booking > 0)
                        <tr class="discount-row">
                            <td style="background-color: #FFB700; color: white; text-align: left;">Diskon</td>
                            <td style="background-color: #FFB700; color: white; text-align: right;">1</td>
                            <td style="background-color: #FFB700; color: white; text-align: right;">-</td>
                            <td style="background-color: #FFB700; color: white; text-align: right;">
                                @if (optional($service->pivot->referralCode)->discount_percentage)
                                    {{ rtrim(rtrim(number_format($service->pivot->referralCode->discount_percentage, 2, ',', '.'), '0'), ',') }}%
                                @else
                                    0%
                                @endif
                            </td>
                            <td style="background-color: #FFB700; color: white; text-align: right;">-Rp {{ number_format($service->pivot->discount_amount_at_booking, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right; padding: 6px 8px; border-top: 2px solid #eee;">
                        Sub-Total : &nbsp;&nbsp;&nbsp; Rp {{ number_format(optional($consultationBooking->invoice)->total_amount + optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @if (optional($consultationBooking->invoice)->discount_amount > 0)
                <tr>
                    <td colspan="5" style="text-align: right; padding: 6px 8px; border-top: none;">
                        <b>Total Diskon Item : &nbsp;&nbsp;&nbsp; -Rp {{ number_format(optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</b>
                    </td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td colspan="5" style="text-align: right; background-color: #FFB700; color: #0C2C5A; font-weight: 700; padding: 12px 8px; border-top: 2px solid #eee;">
                        TOTAL KESELURUHAN : &nbsp;&nbsp;&nbsp; Rp {{ number_format(optional($consultationBooking->invoice)->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @if ($consultationBooking->payment_type == 'dp')
                    @php
                        $dpAmount = optional($consultationBooking->invoice)->total_amount * 0.5;
                    @endphp
                    <tr class="total-payable">
                        <td colspan="5" style="text-align: right; background-color: #0C2C5A; color: #fff; font-weight: 700; padding: 12px 8px; border-top: none;">
                            TOTAL BAYAR (DP 50%) : &nbsp;&nbsp;&nbsp; Rp {{ number_format($dpAmount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tfoot>
        </table>

        {{-- FOOTER & SIGNATURE --}}
        <div class="invoice-footer avoid-break" style="margin-top: 30px;">
            {{-- [START] MODIFIED SECTION --}}
            <div class="invoice-signature" style="font-size: 11px; line-height: 1.6;">
                <p style="margin-bottom: 10px;"><b>Dear Customer,</b></p>
                <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah disepakati akan diberikan charge tambahan.</p>
                <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling.</p>
                <p>Reschedule dapat dilakukan selambat-lambatnya 24 jam sebelum sesi konseling.</p>
                <p style="margin-top: 15px;">Sudah Menjadi bagian sejarah dari hidup {{ $consultationBooking->receiver_name ?? 'Anda' }}, semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
                <p style="margin-top: 25px;">Salam</p>
                <p>{{ $adminName ?? 'Admin Utama' }}<br>Tim Indiegologi</p>
            </div>
            {{-- [END] MODIFIED SECTION --}}

            {{-- PAYMENT INFO --}}
            <div class="payment-info avoid-break" style="margin-top: 25px; background-color: #ffc107; padding: 15px; border-radius: 8px;">
                <h4 style="font-weight: bold; color: #001f3f; margin-top:0; margin-bottom: 10px; font-size: 13px;">Payment Information</h4>
                <p style="font-size: 12px; margin: 4px 0; color: #000;">Bank SMBC Indonesia - 90110023186</p>
                <p style="font-size: 12px; margin: 4px 0; color: #000;">Name: Artwira Mahatavirya Satyagasty</p>
                <p style="font-size: 12px; margin: 4px 0; color: #000;">Please Transfer Payment to the Account above before the due date,</p>
                <p style="font-size: 12px; margin: 4px 0; color: #000;">And Please Confirm to the following number: 0822 2095 5595</p>
            </div>
        </div>

    </div>
</body>
</html>
