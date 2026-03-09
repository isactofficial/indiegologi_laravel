@extends('layouts.admin')

@section('content')
<style>
    .section {
        font-family: 'Times New Roman', serif;
        padding: 20px;
        background-color: #f5f7fa;
    }

    .invoice-wrapper {
        background-color: #fff;
        padding: 40px 50px;
        max-width: 860px;
        margin: 40px auto;
        box-sizing: border-box;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    /* ── HEADER ── */
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

    .invoice-contact-info {
        font-size: 13px;
        color: #444;
        line-height: 1.7;
        margin-top: 8px;
    }

    /* ── TOP TWO COLUMNS ── */
    .invoice-top-section {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .invoice-client-info,
    .invoice-details-info {
        background-color: #f3faff;
        padding: 18px 20px;
        border-radius: 8px;
        width: 48%;
        box-sizing: border-box;
        font-size: 13.5px;
        color: #0F3A77;
        line-height: 1.8;
    }

    .box-title {
        font-size: 16px;
        font-weight: 700;
        color: #00617A;
        display: inline-block;
        padding-bottom: 6px;
        margin-bottom: 12px;
        border-bottom: 2.5px solid #CB2786;
    }

    .info-grid {
        display: grid;
        grid-template-columns: max-content 1fr;
        gap: 2px 10px;
    }

    .info-grid .label { font-weight: 600; white-space: nowrap; }
    .info-grid .value { color: #0F3A77; }

    /* ── SERVICE TABLE ── */
    .service-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
        margin-bottom: 0;
    }

    .service-table thead tr {
        background-color: #0C2C5A;
        color: #fff;
    }

    .service-table thead th {
        padding: 12px 10px;
        font-weight: 600;
    }

    .service-table thead th:first-child { text-align: left; }
    .service-table thead th:not(:first-child) { text-align: right; }

    .service-table tbody td {
        padding: 10px 10px;
        vertical-align: top;
        border-bottom: 1px solid #f0f0f0;
        color: #0C2C5A;
    }

    .service-table tbody td:first-child { text-align: left; }
    .service-table tbody td:not(:first-child) { text-align: right; }

    .row-main td { font-weight: 700; }
    .row-main .sub-desc {
        font-weight: 400;
        font-size: 11.5px;
        color: #555;
        display: block;
        margin-top: 2px;
    }

    .row-addon-header td {
        font-weight: 700;
        color: #0C2C5A;
        padding-top: 14px;
        padding-bottom: 6px;
        border-bottom: 1.5px solid #ccc;
    }

    .row-addon td {
        padding-left: 20px;
        font-weight: 600;
        color: #0C2C5A;
    }

    .row-addon .sub-desc {
        font-weight: 400;
        font-size: 11.5px;
        color: #777;
        display: block;
    }

    /* ── SUMMARY ROWS ── */
    .summary-row td {
        padding: 8px 10px;
        text-align: right;
        font-size: 13.5px;
        color: #333;
        border: none;
    }

    .row-subtotal td {
        border-top: 1px solid #ddd;
        padding-top: 12px;
    }

    .row-discount td {
        background-color: #f8cd60;
        color: #0C2C5A;
        font-weight: 700;
        padding: 12px 10px;
    }

    .row-total td {
        background-color: #0C2C5A;
        color: #fff;
        font-weight: 700;
        padding: 12px 10px;
    }

    .row-dp td {
        background-color: #0C2C5A;
        color: #fff;
        font-weight: 700;
        padding: 12px 10px;
    }

    /* ── FOOTER ── */
    .invoice-footer {
        margin-top: 35px;
    }

    .invoice-signature {
        font-size: 13.5px;
        line-height: 1.8;
        color: #222;
    }

    .invoice-signature p { margin: 4px 0; }

    .payment-info {
        margin-top: 40px;
        background-color: #FFC107;
        padding: 20px 25px;
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .payment-info-left h4 {
        font-weight: 700;
        color: #001f3f;
        font-size: 20px;
        margin: 0 0 10px 0;
    }

    .payment-info-left p {
        font-size: 13px;
        margin: 3px 0;
        color: #000;
    }

    .payment-info-right {
        font-size: 12px;
        color: #001f3f;
        text-align: right;
        white-space: nowrap;
    }

    @media print { .no-print { display: none; } }

    @media (max-width: 768px) {
        .invoice-wrapper { padding: 15px; margin: 20px auto; }
        .invoice-top-section { flex-direction: column; }
        .invoice-client-info, .invoice-details-info { width: 100%; }
        .payment-info { flex-direction: column; gap: 10px; }
    }
</style>

<div class="section">
    <div class="d-flex justify-content-start mb-4 no-print">
        <a href="{{ route('admin.manual-invoices.index') }}" class="btn px-4 py-2" style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.manual-invoices.download-pdf', $invoice->id) }}" class="btn btn-primary px-4 py-2 ms-3">
            <i class="fas fa-download me-2"></i> Unduh PDF
        </a>
    </div>

    <div class="invoice-wrapper">

        {{-- ── HEADER ── --}}
        <div class="invoice-header">
            <div class="invoice-logo">
                <img src="{{ asset('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo">
            </div>
            <div class="invoice-contact-info">
                Email: {{ $invoice->company_email ?? 'temancerita@indiegologi.com' }}<br>
                Phone Number: {{ $invoice->company_phone ?? '+62 822-2095-5595' }}<br>
                Website: {{ $invoice->company_website ?? 'indiegologi.com' }}
            </div>
        </div>

        {{-- ── TWO-COLUMN CLIENT + INVOICE DETAILS ── --}}
        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <div class="box-title">Dear</div>
                <div class="info-grid">
                    <span class="label">Nama</span>
                    <span class="value">: {{ $invoice->client_name }}</span>

                    @if($invoice->counseling_date)
                    <span class="label">Waktu Konseling</span>
                    <span class="value">: {{ $invoice->counseling_date }}</span>
                    @endif

                    @if($invoice->package)
                    <span class="label">Paket Konseling</span>
                    <span class="value">: {{ $invoice->package }}</span>
                    @endif

                    <span class="label">No Hp</span>
                    <span class="value">: {{ $invoice->client_phone ?? '-' }}</span>

                    <span class="label">Session</span>
                    <span class="value">: {{ $invoice->session_type ?? 'Offline' }}</span>
                </div>
            </div>

            <div class="invoice-details-info">
                <div class="box-title">Invoice Details</div>
                <div class="info-grid">
                    <span class="label">Invoice No</span>
                    <span class="value">: {{ $invoice->invoice_no }}</span>

                    <span class="label">Invoice Date</span>
                    <span class="value">: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/F/Y') }}</span>

                    <span class="label">Due Date</span>
                    <span class="value">: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/F/Y') }}</span>

                    <span class="label">Status</span>
                    <span class="value">: {{ ucfirst($invoice->status ?? 'Unpaid') }}</span>

                    <span class="label">Payment Type</span>
                    <span class="value">:
                        @if($invoice->payment_type == 'dp') DP (50%)
                        @else {{ $invoice->payment_type ?? 'Transfer' }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ── SERVICE TABLE ── --}}
        @php
            $mainItems  = $invoice->items->where('is_addon', false);
            $addonItems = $invoice->items->where('is_addon', true);
            $hasAddons  = $addonItems->isNotEmpty();
        @endphp

        <table class="service-table">
            <thead>
                <tr>
                    <th style="width:38%">Service Description</th>
                    <th style="width:12%">Quantity</th>
                    <th style="width:18%">Unit Price</th>
                    <th style="width:12%">Amount</th>
                    <th style="width:20%">Total Line</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mainItems as $item)
                <tr class="row-main">
                    <td>
                        {{ $item->description }}
                        @if($item->subtitle)
                        <span class="sub-desc">{{ $item->subtitle }}</span>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td></td>
                    <td>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                @if($hasAddons)
                <tr class="row-addon-header">
                    <td colspan="5">Add On</td>
                </tr>
                @endif

                @foreach($addonItems as $item)
                <tr class="row-addon">
                    <td>
                        {{ $item->description }}
                        @if($item->subtitle)
                        <span class="sub-desc">{{ $item->subtitle }}</span>
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
                <tr class="summary-row row-subtotal">
                    <td colspan="4" style="text-align:right; font-weight:600; color:#333;">Sub-Total:</td>
                    <td style="text-align:right; font-weight:600; color:#333;">
                        Rp {{ number_format($invoice->subtotal_amount, 0, ',', '.') }}
                    </td>
                </tr>

                @if($invoice->discount_amount > 0)
                <tr class="row-discount">
                    <td colspan="4" style="text-align:right;">Total Discount:</td>
                    <td style="text-align:right;">-Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                </tr>
                @endif

                <tr class="row-total">
                    <td colspan="4" style="text-align:right;">Total Invoice</td>
                    <td style="text-align:right;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                </tr>

                @if($invoice->payment_type == 'dp')
                @php $dpAmount = $invoice->total_amount * 0.5; @endphp
                <tr class="row-dp">
                    <td colspan="4" style="text-align:right;">Total Bayar (DP 50%)</td>
                    <td style="text-align:right;">Rp {{ number_format($dpAmount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tfoot>
        </table>

        {{-- ── FOOTER ── --}}
        <div class="invoice-footer">
            <div class="invoice-signature">
                <p><b>Dear Customer,</b></p>
                <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah disepakati akan diberikan charge tambahan.</p>
                <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling.</p>
                <p>Reschedule dapat dilakukan selambat-lambatnya 24 jam sebelum sesi konseling.</p>
                <p style="margin-top: 12px;">Sudah Menjadi bagian sejarah dari hidup {{ $invoice->client_name }}, semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
                <p style="margin-top: 20px;">Regard,</p>
                <br>
                <p>{{ $invoice->signed_by ?? 'Vernandika Stanley Hansen' }}<br>{{ $invoice->signed_title ?? 'Indiegologi Team' }}</p>
            </div>

            <div class="payment-info">
                <div class="payment-info-left">
                    <h4>Payment Information</h4>
                    <p>{{ $invoice->bank_name ?? 'Bank SMBC Indonesia' }} - {{ $invoice->bank_account ?? '90110023186' }}</p>
                    <p>Name : {{ $invoice->account_name ?? 'Artwira Mahatavirya Satyagasty' }}</p>
                    <p>Please transfer payment to the account above before the due date,</p>
                    <p>and please confirm to the following number : {{ $invoice->confirm_number ?? '0822 2095 5595' }}</p>
                </div>
                <div class="payment-info-right">
                    {{ $invoice->client_name }}'s Invoice – Page 1 of 1
                </div>
            </div>
        </div>

    </div>
</div>
@endsection