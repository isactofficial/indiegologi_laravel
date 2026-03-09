<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_no ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 30px 35px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            font-size: 13px;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        p {
            margin: 4px 0;
            line-height: 1.7;
        }

        /* ── HEADER ── */
        .invoice-header {
            text-align: right;
            border-bottom: 1px solid #ccc;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }

        .invoice-contact {
            font-size: 12px;
            color: #444;
            line-height: 1.9;
            margin-top: 6px;
        }

        /* ── TWO-COLUMN INFO BOXES ── */
        .top-table {
            margin-bottom: 22px;
            border-spacing: 0;
        }

        .info-box {
            background-color: #f3faff;
            padding: 14px 16px;
            border-radius: 6px;
            vertical-align: top;
            width: 48%;
        }

        .spacer-col {
            width: 4%;
        }

        .box-title {
            font-size: 15px;
            font-weight: bold;
            color: #00617A;
            border-bottom: 2.5px solid #CB2786;
            display: inline-block;
            padding-bottom: 5px;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-inner {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .info-inner td {
            padding: 3px 0;
            color: #0F3A77;
            vertical-align: top;
        }

        .lbl {
            font-weight: bold;
            white-space: nowrap;
            padding-right: 8px;
            width: 1%;
        }

        /* ── SERVICE TABLE ── */
        .service-table {
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .service-table thead tr {
            background-color: #0C2C5A;
            color: #fff;
        }

        .service-table thead th {
            padding: 12px 10px;
            font-weight: bold;
            font-size: 13px;
            text-align: right;
        }

        .service-table thead th:first-child {
            text-align: left;
        }

        .service-table tbody td {
            padding: 10px 10px;
            vertical-align: top;
            color: #0C2C5A;
            border-bottom: 1px solid #f0f0f0;
            text-align: right;
        }

        .service-table tbody td:first-child {
            text-align: left;
        }

        .main-title {
            font-weight: bold;
        }

        .sub-desc {
            font-size: 10.5px;
            color: #666;
            font-weight: normal;
        }

        .addon-header td {
            font-weight: bold;
            color: #0C2C5A;
            padding: 12px 10px 6px;
            border-bottom: 1px solid #bbb;
            text-align: left;
        }

        .addon-row td {
            padding-left: 20px;
            font-weight: bold;
        }

        /* ── SUMMARY ROWS ── */
        .row-subtotal td {
            text-align: right;
            padding: 10px 10px 8px;
            border-top: 1.5px solid #ddd;
            font-weight: 600;
            color: #333;
        }

        .row-discount td {
            text-align: right;
            background-color: #FFB700;
            color: #0C2C5A;
            font-weight: bold;
            padding: 12px 10px;
        }

        .row-total td {
            text-align: right;
            background-color: #0C2C5A;
            color: #fff;
            font-weight: bold;
            padding: 12px 10px;
        }

        .row-dp td {
            text-align: right;
            background-color: #0C2C5A;
            color: #fff;
            font-weight: bold;
            padding: 12px 10px;
        }

        /* ── FOOTER ── */
        .footer-signature {
            margin-top: 30px;
            font-size: 13px;
            line-height: 1.8;
            color: #222;
        }

        .payment-info {
            margin-top: 30px;
            background-color: #FFC107;
            padding: 16px 20px;
            border-radius: 6px;
        }

        .payment-title {
            font-size: 18px;
            font-weight: bold;
            color: #001f3f;
            margin-bottom: 8px;
            display: block;
        }

        .payment-details {
            font-size: 12px;
            color: #000;
            line-height: 1.9;
            font-family: Arial, sans-serif;
        }

        .payment-page-num {
            font-size: 11px;
            color: #001f3f;
            text-align: right;
            vertical-align: bottom;
            font-family: Arial, sans-serif;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div style="max-width: 780px; margin: 0 auto;">

    {{-- ── HEADER ── --}}
    <div class="invoice-header avoid-break">
        <img style="width: 280px;" src="{{ public_path('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo">
        <div class="invoice-contact">
            Email: temancerita@indiegologi.com<br>
            Phone Number: +62 822-2095-5595<br>
            Website: indiegologi.com
        </div>
    </div>

    {{-- ── TWO-COLUMN: DEAR + INVOICE DETAILS ── --}}
    @php $allItems = $invoice->getAllItems(); @endphp

    <table class="top-table avoid-break">
        <tr>
            {{-- LEFT: Dear / Client Info --}}
            <td class="info-box">
                <div class="box-title">Dear</div>
                @php
                    $booking     = $invoice->consultationBooking ?? null;
                    $mainService = $allItems->where('type','service')->first();
                @endphp
                <table class="info-inner">
                    <tr>
                        <td class="lbl">Nama</td>
                        <td>: {{ $invoice->user->name ?? 'N/A' }}</td>
                    </tr>
                    @if($booking && $booking->booking_date)
                    <tr>
                        <td class="lbl">Waktu Konseling</td>
                        <td>: {{ $booking->booking_date->format('d F Y') }}</td>
                    </tr>
                    @endif
                    @if($mainService)
                    <tr>
                        <td class="lbl">Paket Konseling</td>
                        <td>: {{ $mainService['item']->title }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="lbl">No Hp</td>
                        <td>: {{ optional($invoice->user->profile)->phone_number ?? 'N/A' }}</td>
                    </tr>
                    @if($booking && $booking->session_type)
                    <tr>
                        <td class="lbl">Session</td>
                        <td>: {{ ucfirst($booking->session_type) }}</td>
                    </tr>
                    @endif
                </table>
            </td>

            <td class="spacer-col"></td>

            {{-- RIGHT: Invoice Details --}}
            <td class="info-box">
                <div class="box-title">Invoice Details</div>
                <table class="info-inner">
                    <tr>
                        <td class="lbl">Invoice No</td>
                        <td>: {{ $invoice->invoice_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Invoice Date</td>
                        <td>: {{ $invoice->invoice_date->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Due Date</td>
                        <td>: {{ $invoice->due_date->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Status</td>
                        <td>: {{ ucfirst($invoice->payment_status ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Payment Type</td>
                        <td>: @if($invoice->payment_type == 'dp') DP (50%) @else Transfer @endif</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── SERVICE TABLE ── --}}
    @php
        $mainService   = $allItems->where('type','service')->first();
        $addonServices = $allItems->where('type','service')->skip(1);
        $eventItems    = $allItems->where('type','event');
        $hasAddons     = $addonServices->isNotEmpty() || $eventItems->isNotEmpty();
    @endphp

    <table class="service-table avoid-break">
        <thead>
            <tr>
                <th style="width:38%; text-align:left;">Service Description</th>
                <th style="width:12%;">Quantity</th>
                <th style="width:18%;">Unit Price</th>
                <th style="width:12%;">Amount</th>
                <th style="width:20%;">Total Line</th>
            </tr>
        </thead>
        <tbody>
            {{-- Main service --}}
            @if($mainService)
            <tr>
                <td>
                    <span class="main-title">{{ $mainService['item']->title }}</span>
                    @if(isset($mainService['item']->duration))
                    <br><span class="sub-desc">{{ $mainService['item']->duration }} Hours Packet</span>
                    @endif
                </td>
                <td>1</td>
                <td>Rp {{ number_format($mainService['pivot']->total_price_at_booking, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp {{ number_format($mainService['pivot']->final_price_at_booking, 0, ',', '.') }}</td>
            </tr>
            @endif

            {{-- Add On header --}}
            @if($hasAddons)
            <tr class="addon-header">
                <td colspan="5">Add On</td>
            </tr>
            @endif

            {{-- Addon services --}}
            @foreach($addonServices as $item)
            <tr class="addon-row">
                <td>
                    <span class="main-title">{{ $item['item']->title }}</span>
                    @if(isset($item['item']->subtitle))
                    <br><span class="sub-desc">{{ $item['item']->subtitle }}</span>
                    @endif
                </td>
                <td>1</td>
                <td>Rp {{ number_format($item['pivot']->total_price_at_booking, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp {{ number_format($item['pivot']->final_price_at_booking, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            {{-- Event items --}}
            @foreach($eventItems as $item)
            <tr class="addon-row">
                <td>
                    <span class="main-title">{{ $item['item']->title }}</span>
                    <br><span class="sub-desc">Event</span>
                </td>
                <td>{{ $item['participant_count'] }} peserta</td>
                <td>Rp {{ number_format($item['booking']->total_price, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp {{ number_format($item['booking']->final_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="row-subtotal">
                <td colspan="4">Sub-Total:</td>
                <td>Rp {{ number_format($invoice->total_amount + $invoice->auto_discount_amount, 0, ',', '.') }}</td>
            </tr>

            @if($invoice->auto_discount_amount > 0)
            <tr class="row-discount">
                <td colspan="4">Total Discount:</td>
                <td>-Rp {{ number_format($invoice->auto_discount_amount, 0, ',', '.') }}</td>
            </tr>
            @endif

            <tr class="row-total">
                <td colspan="4">Total Invoice</td>
                <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>

            @if($invoice->payment_type == 'dp')
            @php $dpAmount = $invoice->total_amount * 0.5; @endphp
            <tr class="row-dp">
                <td colspan="4">Total Bayar (DP 50%)</td>
                <td>Rp {{ number_format($dpAmount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    {{-- ── FOOTER ── --}}
    <div class="footer-signature avoid-break">
        <p><b>Dear Customer,</b></p>
        <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah disepakati akan diberikan charge tambahan.</p>
        <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling.</p>
        <p>Reschedule dapat dilakukan selambat-lambatnya 24 jam sebelum sesi konseling.</p>
        <p style="margin-top: 12px;">Sudah Menjadi bagian sejarah dari hidup {{ $invoice->user->name ?? 'Anda' }}, semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
        <p style="margin-top: 20px;">Regard,</p>
        <br>
        <p>{{ $adminName ?? 'Admin Indiegologi' }}<br>Indiegologi Team</p>
    </div>

    {{-- ── PAYMENT INFO ── --}}
    <div class="payment-info avoid-break">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="vertical-align:top;">
                    <span class="payment-title">Payment Information</span>
                    <div class="payment-details">
                        Bank SMBC Indonesia - 90110023186<br>
                        Name : Artwira Mahatavirya Satyagasty<br>
                        Please transfer payment to the account above before the due date,<br>
                        and please confirm to the following number : 0822 2095 5595
                    </div>
                </td>
                <td class="payment-page-num" style="width: 180px; vertical-align:bottom;">
                    {{ $invoice->user->name ?? 'Customer' }}'s Invoice – Page 1 of 1
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>