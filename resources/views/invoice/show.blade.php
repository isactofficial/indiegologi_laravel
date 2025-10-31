@extends('layouts.app')

@section('content')
<style>
    /* Your existing styles remain the same */
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
        box-sizing: border-box;
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
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 30px;
    }

    .invoice-client-info,
    .invoice-details-info {
        background-color: #f3faff;
        padding: 20px;
        border-radius: 10px;
        width: 48%;
        display: grid;
        grid-template-columns: max-content 1fr;
        gap: 8px 10px;
        align-items: center;
    }

    .grid-full-span {
        grid-column: 1 / -1;
        margin-bottom: 10px;
    }

    .grid-align-top-left {
        align-self: start;
        margin-bottom: 8px;
        margin-top: 15px;
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

    .service-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1rem;
        table-layout: fixed;
    }

    .service-table th,
    .service-table td {
        padding: 16px 8px;
        text-align: left;
        word-break: break-word;
    }

    .service-table th {
        white-space: normal;
    }

    .service-table th:nth-child(1),
    .service-table td:nth-child(1) {
        width: 34%;
    }

    .service-table th:nth-child(2),
    .service-table td:nth-child(2) {
        width: 12%;
    }

    .service-table th:nth-child(3),
    .service-table td:nth-child(3) {
        width: 22%;
    }

    .service-table th:nth-child(4),
    .service-table td:nth-child(4) {
        width: 12%;
    }

    .service-table th:nth-child(5),
    .service-table td:nth-child(5) {
        width: 20%;
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

    .text-right {
        text-align: right;
    }

    .service-table th.text-right,
    .service-table td.text-right {
        text-align: right;
        white-space: nowrap;
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

    /* Mobile responsive styles */
    @media (max-width: 768px) {
        .invoice-wrapper {
            padding: 15px;
            margin: 20px auto;
        }

        .invoice-top-section {
            flex-direction: column;
            gap: 1rem;
        }

        .invoice-client-info,
        .invoice-details-info {
            width: 100%;
        }
    }
</style>

<div class="section container-fluid px-4 py-5">
    <div class="d-flex justify-content-start mb-4 no-print">
        <a href="{{ route('front.cart.view') }}" class="btn px-4 py-2" style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
        </a>
        <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-primary px-4 py-2 ms-3">
            <i class="fas fa-download me-2"></i> Unduh PDF
        </a>
    </div>

    <div class="invoice-wrapper">
        {{-- Invoice Header --}}
        <div class="invoice-header">
            <div class="invoice-header-details">
                <div class="invoice-logo"><img style="width: 300px;" src="{{ asset('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo"></div>
                <div class="invoice-contact-info">
                    Email: temancerita@indiegologi.com<br>
                    Phone Number: +62 822-2095-5595
                </div>
            </div>
        </div>

        {{-- Client and Invoice Details --}}
        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <p class="grid-full-span title-underline">Dear</p>
                <span>Nama</span>
                <span>: {{ $invoice->user->name ?? 'N/A' }}</span>

                <span>No Hp</span>
                <span>: {{ optional($invoice->user->profile)->phone_number ?? 'N/A' }}</span>

                @php
                $allItems = $invoice->getAllItems();
                @endphp

                @if($allItems->isNotEmpty())
                <span class="grid-full-span grid-align-top-left">Pesanan:</span>
                <div class="grid-full-span">
                    @foreach($allItems as $item)
                    @if($item['type'] === 'service')
                    • {{ $item['item']->title }} (Service)<br>
                    @elseif($item['type'] === 'event')
                    • {{ $item['item']->title }} (Event - {{ $item['participant_count'] }} peserta)<br>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>

            <div class="invoice-details-info">
                <h5 class="grid-full-span title-underline">Invoice Details</h5>
                <span>Invoice No</span>
                <span>: {{ $invoice->invoice_no }}</span>
                <span>Invoice Date</span>
                <span>: {{ $invoice->invoice_date->format('d/F/Y') }}</span>
                <span>Due Date</span>
                <span>: {{ $invoice->due_date->format('d/F/Y') }}</span>
                <span>Status</span>
                <span>: {{ ucfirst($invoice->payment_status) }}</span>
                <span>Payment Type</span>
                <span>: {{ $invoice->payment_type == 'dp' ? 'DP (50%)' : 'Pembayaran Penuh' }}</span>
            </div>
        </div>

        {{-- Service Table --}}
        <table class="service-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="text-right">Kuantitas</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Diskon</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                $allItems = $invoice->getAllItems();
                @endphp

                @foreach($allItems as $item)
                @if($item['type'] === 'service')
                {{-- Service Item --}}
                <tr class="service">
                    <td>{{ $item['item']->title }} (Service)</td>
                    <td class="text-right">1</td>
                    <td class="text-right">Rp {{ number_format($item['pivot']->total_price_at_booking, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($item['pivot']->discount_amount_at_booking > 0)
                        -Rp {{ number_format($item['pivot']->discount_amount_at_booking, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item['pivot']->final_price_at_booking, 0, ',', '.') }}</td>
                </tr>
                @elseif($item['type'] === 'event')
                {{-- Event Item --}}
                <tr class="service">
                    <td>{{ $item['item']->title }} (Event)</td>
                    <td class="text-right">{{ $item['participant_count'] }} peserta</td>
                    <td class="text-right">Rp {{ number_format($item['booking']->total_price, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($item['booking']->discount_amount > 0)
                        -Rp {{ number_format($item['booking']->discount_amount, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item['booking']->final_price, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        {{-- Summary Section --}}
        <div class="summary-section">
            <div class="summary-line">
                <span>Sub-Total:</span>
                <span>Rp {{ number_format($invoice->total_amount + $invoice->auto_discount_amount, 0, ',', '.') }}</span>
            </div>

            @if ($invoice->auto_discount_amount > 0)
            <div class="summary-line">
                <span><b>Total Diskon Item:</b></span>
                <span><b>-Rp {{ number_format($invoice->auto_discount_amount, 0, ',', '.') }}</b></span>
            </div>
            @endif

            <div class="summary-line grand-total">
                <span>TOTAL KESELURUHAN :</span>
                <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
            </div>

            @if ($invoice->payment_type == 'dp')
            @php
            $dpAmount = $invoice->total_amount * 0.5;
            @endphp
            <div class="summary-line total-payable">
                <span>TOTAL BAYAR (DP 50%) :</span>
                <span>Rp {{ number_format($dpAmount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="invoice-footer">
            <div class="invoice-signature">
                <p>Dear Customer,</p>
                <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah disepakati akan diberikan charge tambahan</p>
                <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling</p>
                <p>reschedule dapat dilakukan selambat lambatnya 24 jam sebelum sesi konseling</p>
                <p>Sudah Menjadi bagian sejarah dari hidup {{ $invoice->user->name ?? 'Anda' }}, semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya.</p>
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
</div>
@endsection