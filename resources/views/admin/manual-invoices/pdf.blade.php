<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
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
            background-color: #f8cd60;
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
            Email: {{ $invoice->company_email ?? 'temancerita@indiegologi.com' }}<br>
            Phone Number: {{ $invoice->company_phone ?? '+62 822-2095-5595' }}<br>
            Website: {{ $invoice->company_website ?? 'indiegologi.com' }}
        </div>
    </div>

    {{-- ── TWO-COLUMN: DEAR + INVOICE DETAILS ── --}}
    <table class="top-table avoid-break">
        <tr>
            {{-- LEFT: Dear / Client Info --}}
            <td class="info-box">
                <div class="box-title">Dear</div>
                <table class="info-inner">
                    <tr>
                        <td class="lbl">Nama</td>
                        <td>: {{ $invoice->client_name }}</td>
                    </tr>
                    @if($invoice->counseling_date)
                    <tr>
                        <td class="lbl">Waktu Konseling</td>
                        <td>: {{ $invoice->counseling_date }}</td>
                    </tr>
                    @endif
                    @if($invoice->package)
                    <tr>
                        <td class="lbl">Paket Konseling</td>
                        <td>: {{ $invoice->package }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="lbl">No Hp</td>
                        <td>: {{ $invoice->client_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Session</td>
                        <td>: {{ $invoice->session_type ?? 'Offline' }}</td>
                    </tr>
                </table>
            </td>

            <td class="spacer-col"></td>

            {{-- RIGHT: Invoice Details --}}
            <td class="info-box">
                <div class="box-title">Invoice Details</div>
                <table class="info-inner">
                    <tr>
                        <td class="lbl">Invoice No</td>
                        <td>: {{ $invoice->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Invoice Date</td>
                        <td>: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Due Date</td>
                        <td>: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Status</td>
                        <td>: {{ ucfirst($invoice->status ?? 'Unpaid') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Payment Type</td>
                        <td>: @if($invoice->payment_type == 'dp') DP (50%) @else {{ $invoice->payment_type ?? 'Transfer' }} @endif</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── SERVICE TABLE ── --}}
    @php
        $mainItems  = $invoice->items->where('is_addon', false);
        $addonItems = $invoice->items->where('is_addon', true);
        $hasAddons  = $addonItems->isNotEmpty();
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
            @foreach($mainItems as $item)
            <tr>
                <td>
                    <span class="main-title">{{ $item->description }}</span>
                    @if($item->subtitle)
                    <br><span class="sub-desc">{{ $item->subtitle }}</span>
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @if($hasAddons)
            <tr class="addon-header">
                <td colspan="5">Add On</td>
            </tr>
            @endif

            @foreach($addonItems as $item)
            <tr class="addon-row">
                <td>
                    <span class="main-title">{{ $item->description }}</span>
                    @if($item->subtitle)
                    <br><span class="sub-desc">{{ $item->subtitle }}</span>
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="row-subtotal">
                <td colspan="4">Sub-Total:</td>
                <td>Rp {{ number_format($invoice->subtotal_amount, 0, ',', '.') }}</td>
            </tr>

            @if($invoice->discount_amount > 0)
            <tr class="row-discount">
                <td colspan="4">Total Discount:</td>
                <td>-Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
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
        <p style="margin-top: 12px;">Sudah Menjadi bagian sejarah dari hidup {{ $invoice->client_name }}, semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
        <p style="margin-top: 20px;">Regard,</p>
        <br>
        <p>{{ $invoice->signed_by ?? 'Vernandika Stanley Hansen' }}<br>{{ $invoice->signed_title ?? 'Indiegologi Team' }}</p>
    </div>

    {{-- ── PAYMENT INFO ── --}}
    <div class="payment-info avoid-break">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="vertical-align:top;">
                    <span class="payment-title">Payment Information</span>
                    <div class="payment-details">
                        {{ $invoice->bank_name ?? 'Bank SMBC Indonesia' }} - {{ $invoice->bank_account ?? '90110023186' }}<br>
                        Name : {{ $invoice->account_name ?? 'Artwira Mahatavirya Satyagasty' }}<br>
                        Please transfer payment to the account above before the due date,<br>
                        and please confirm to the following number : {{ $invoice->confirm_number ?? '0822 2095 5595' }}
                    </div>
                </td>
                <td class="payment-page-num" style="width: 180px; vertical-align:bottom;">
                    {{ $invoice->client_name }}'s Invoice – Page 1 of 1
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>