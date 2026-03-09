<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        @page { margin: 30px 35px; }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            font-size: 13px;
            background: #fff;
        }

        table { width: 100%; border-collapse: collapse; }
        p { margin: 4px 0; line-height: 1.6; }

        .invoice-header {
            text-align: right;
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-logo img {
            width: 300px;
            height: auto;
        }

        .invoice-contact {
            font-size: 13px;
            color: #444;
            line-height: 1.7;
            margin-top: 8px;
        }

        .top-table { margin-bottom: 30px; }

        .info-box {
            background-color: #f3faff;
            padding: 18px 20px;
            border-radius: 8px;
            vertical-align: top;
            width: 48%;
            font-size: 13.5px;
            color: #0F3A77;
        }

        .box-title {
            font-size: 16px;
            font-weight: 700;
            color: #00617A;
            border-bottom: 2.5px solid #CB2786;
            display: inline-block;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .info-row-label { font-weight: 600; color: #0F3A77; white-space: nowrap; padding-right: 6px; }
        .info-row-value { color: #0F3A77; }

        .service-table { margin-top: 0; }

        .service-table thead tr {
            background-color: #0C2C5A;
            color: #fff;
        }

        .service-table thead th {
            padding: 12px 10px;
            font-weight: 600;
            font-size: 13px;
        }

        .service-table th:first-child,
        .service-table td:first-child { text-align: left; }

        .service-table th:not(:first-child),
        .service-table td:not(:first-child) { text-align: right; }

        .service-table tbody td {
            padding: 10px 10px;
            vertical-align: top;
            color: #0C2C5A;
            border-bottom: 1px solid #f0f0f0;
        }

        .main-title { font-weight: 700; }
        .sub-desc { font-size: 11.5px; color: #555; font-weight: normal; }

        .addon-header td {
            font-weight: 700;
            color: #0C2C5A;
            padding-top: 14px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #ccc;
        }

        .addon-row td { padding-left: 20px; font-weight: 600; }

        .row-subtotal td {
            text-align: right;
            padding: 12px 10px 8px;
            border-top: 1.5px solid #ddd;
            font-weight: 600;
            color: #333;
        }

        .row-discount td {
            text-align: right;
            background-color: #FFB700;
            color: #0C2C5A;
            font-weight: 700;
            padding: 12px 10px;
        }

        .row-total td {
            text-align: right;
            background-color: #0C2C5A;
            color: #fff;
            font-weight: 700;
            padding: 12px 10px;
        }

        .row-dp td {
            text-align: right;
            background-color: #0C2C5A;
            color: #fff;
            font-weight: 700;
            padding: 12px 10px;
        }

        .footer-signature {
            margin-top: 35px;
            font-size: 13.5px;
            line-height: 1.8;
            color: #222;
            text-align: left;
        }

        .payment-info {
            margin-top: 40px;
            background-color: #FFC107;
            padding: 20px 25px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .payment-info-inner {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .payment-title {
            font-size: 20px;
            font-weight: 700;
            color: #001f3f;
            margin-bottom: 10px;
        }

        .payment-details { font-size: 13px; color: #000; line-height: 1.8; }

        .payment-page-num {
            font-size: 12px;
            color: #001f3f;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .avoid-break { page-break-inside: avoid; }
    </style>
</head>
<body>
<div style="max-width: 800px; margin: 0 auto;">

    {{-- HEADER --}}
    <div class="invoice-header avoid-break">
        <div class="invoice-logo">
            <img src="{{ asset('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo">
        </div>
        <div class="invoice-contact">
            Email: {{ $invoice->company_email ?? 'temancerita@indiegologi.com' }}<br>
            Phone Number: {{ $invoice->company_phone ?? '+62 822-2095-5595' }}<br>
            Website: {{ $invoice->company_website ?? 'indiegologi.com' }}
        </div>

    {{-- TWO-COLUMN: CLIENT + INVOICE DETAILS --}}
    <table class="top-table avoid-break">
        <tr>
            <td class="info-box">
                <div class="box-title">Dear</div>
                <table style="width:100%; border-spacing: 0;">
                    <tr>
                        <td class="info-row-label">Nama</td>
                        <td class="info-row-value">: {{ $invoice->client_name }}</td>
                    </tr>
                    @if($invoice->counseling_date)
                    <tr>
                        <td class="info-row-label">Waktu Konseling</td>
                        <td class="info-row-value">: {{ $invoice->counseling_date }}</td>
                    </tr>
                    @endif
                    @if($invoice->package)
                    <tr>
                        <td class="info-row-label">Paket Konseling</td>
                        <td class="info-row-value">: {{ $invoice->package }}</td>
                    </tr>
                    @endif
                    @if($invoice->client_phone)
                    <tr>
                        <td class="info-row-label">No Hp</td>
                        <td class="info-row-value">: {{ $invoice->client_phone }}</td>
                    </tr>
                    @endif
                    @if($invoice->client_email)
                    <tr>
                        <td class="info-row-label">Email</td>
                        <td class="info-row-value">: {{ $invoice->client_email }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-row-label">Session</td>
                        <td class="info-row-value">: {{ $invoice->session_type ?? 'Offline' }}</td>
                    </tr>
                </table>
            </td>

            <td style="width:4%;"></td>

            <td class="info-box">
                <div class="box-title">Invoice Details</div>
                <table style="width:100%; border-spacing: 0;">
                    <tr>
                        <td class="info-row-label">Invoice No</td>
                        <td class="info-row-value">: {{ $invoice->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td class="info-row-label">Invoice Date</td>
                        <td class="info-row-value">: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-row-label">Due Date</td>
                        <td class="info-row-value">: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/F/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-row-label">Status</td>
                        <td class="info-row-value">: {{ ucfirst($invoice->status ?? 'Unpaid') }}</td>
                    </tr>
                    <tr>
                        <td class="info-row-label">Payment Type</td>
                        <td class="info-row-value">:
                            @if($invoice->payment_type == 'dp') DP (50%)
                            @else {{ $invoice->payment_type ?? 'Transfer' }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- SERVICE TABLE --}}
    @php
        $mainItems = $invoice->items->where('is_addon', false);
        $addonItems = $invoice->items->where('is_addon', true);
        $hasAddons = $addonItems->isNotEmpty();
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

    {{-- FOOTER --}}
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

    <div class="payment-info avoid-break">
        <div class="payment-info-inner">
            <div style="vertical-align:top;">
                <div class="payment-title">Payment Information</div>
                <div class="payment-details">
                    {{ $invoice->bank_name ?? 'Bank SMBC Indonesia' }} - {{ $invoice->bank_account ?? '90110023186' }}<br>
                    Name : {{ $invoice->account_name ?? 'Artwira Mahatavirya Satyagasty' }}<br>
                    Please transfer payment to the account above before the due date,<br>
                    and please confirm to the following number : {{ $invoice->confirm_number ?? '0822 2095 5595' }}
                </div>
            <div class="payment-page-num" style="width:200px; vertical-align:bottom;">
                {{ $invoice->client_name }}'s Invoice - Page 1 of 1
            </div>
    </div>
</body>
</html>
