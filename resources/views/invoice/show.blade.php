@extends('layouts.app')

@section('content')
<style>
    /* STYLE DESAIN AWAL - TIDAK DIUBAH */
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
        overflow: hidden;
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

    .title-underline {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        font-weight: bold;
        font-size: 1.1rem;
        color: #00617A;
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
        border-bottom: 1px solid #eee;
    }

    .service-table thead {
        background-color: #0C2C5A;
        color: white;
    }

    .service-table th:nth-child(1), .service-table td:nth-child(1) { width: 34%; }
    .service-table th:nth-child(2), .service-table td:nth-child(2) { width: 12%; }
    .service-table th:nth-child(3), .service-table td:nth-child(3) { width: 22%; }
    .service-table th:nth-child(4), .service-table td:nth-child(4) { width: 12%; }
    .service-table th:nth-child(5), .service-table td:nth-child(5) { width: 20%; }

    .service-table .service td {
        color: #0C2C5A;
        font-weight: bold;
    }

    .text-right { text-align: right; }

    .summary-section {
        margin-top: 20px;
        float: right;
        width: 100%;
        margin-bottom: 20px;
    }

    .additional-details-box {
        margin-left: auto;
        max-width: 450px;
        margin-bottom: 15px;
        padding-right: 8px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 5px;
    }

    .detail-item .label {
        flex-shrink: 0;
    }

    .detail-item .line {
        flex-grow: 1;
        border-bottom: 1px dotted #ccc;
        margin: 0 10px;
        position: relative;
        top: -4px;
    }

    .detail-item .value {
        flex-shrink: 0;
        font-weight: 500;
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
    }

    .invoice-footer {
        clear: both;
        padding-top: 40px;
    }

    .invoice-signature {
        font-size: 14px;
        line-height: 1.6;
    }

    .payment-info {
        margin-top: 40px;
        background-color: #ffc107;
        padding: 20px;
        border-radius: 8px;
    }

    @media print { .no-print { display: none; } }
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
        {{-- Header --}}
        <div class="invoice-header">
            <div class="invoice-header-details">
                <div class="invoice-logo"><img style="width: 300px;" src="{{ asset('assets/img/logo_revisi_2.png') }}" alt="Indiegologi Logo"></div>
                <div class="invoice-contact-info">
                    Email: temancerita@indiegologi.com<br>
                    Phone Number: +62 822-2095-5595
                </div>
            </div>
        </div>

        {{-- Top Section --}}
        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <p class="grid-full-span title-underline">Dear</p>
                <span>Nama</span>
                <span>: {{ $invoice->user->name ?? 'N/A' }}</span>
                <span>No Hp</span>
                <span>: {{ optional($invoice->user->profile)->phone_number ?? 'N/A' }}</span>

                @php
                    $allItems = $invoice->getAllItems();
                    $allAddons = [];
                    $extraHoursDetails = [];
                @endphp

                @if($allItems->isNotEmpty())
                <span class="grid-full-span grid-align-top-left" style="margin-top:15px; font-weight:bold;">Pesanan:</span>
                <div class="grid-full-span" style="padding-left: 10px; line-height: 1;">
                    @foreach($allItems as $item)
                        @if($item['type'] === 'service')
                            • {{ $item['item']->title }}<br>
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

        {{-- Table --}}
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
                @foreach($allItems as $item)
                    @if($item['type'] === 'service')
                        @php
                            $hoursBooked = $item['pivot']->hours_booked;
                            $baseDuration = $item['item']->base_duration ?? 0;
                            $extraHours = max(0, $hoursBooked - $baseDuration);
                            if($extraHours > 0) {
                                $extraHoursDetails[] = ['name' => 'Sesi Tambahan ('.$extraHours.' Jam)', 'price' => $item['item']->hourly_price * $extraHours];
                            }
                            $itemAddons = json_decode($item['pivot']->addons_data, true) ?? [];
                            foreach($itemAddons as $addon) { $allAddons[] = $addon; }
                        @endphp
                        <tr class="service">
                            <td>
                                {{ $item['item']->title }}<br>
                                <small style="font-weight: normal; color: #666;">
                                    Jadwal: {{ \Carbon\Carbon::parse($item['pivot']->booked_date)->format('d F Y') }}
                                </small>
                            </td>
                            <td class="text-right">1</td>
                            <td class="text-right">Rp {{ number_format($item['item']->price, 0, ',', '.') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp {{ number_format($item['item']->price, 0, ',', '.') }}</td>
                        </tr>
                    @elseif($item['type'] === 'event')
                        <tr class="service">
                            <td>{{ $item['item']->title }} (Event)</td>
                            <td class="text-right">{{ $item['participant_count'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['item']->price, 0, ',', '.') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp {{ number_format($item['booking']->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="summary-section">
            @if(count($extraHoursDetails) > 0 || !empty($allAddons))
                <div class="additional-details-box">
                    <p style="font-weight: bold; color: #00617A; font-size: 0.9rem; margin-bottom: 10px; text-align: right;">Rincian Tambahan:</p>
                    @foreach($extraHoursDetails as $extra)
                        <div class="detail-item">
                            <span class="label">{{ $extra['name'] }}</span>
                            <div class="line"></div>
                            <span class="value">Rp {{ number_format($extra['price'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    @foreach($allAddons as $addon)
                        <div class="detail-item">
                            <span class="label">{{ $addon['name'] }} (x{{ $addon['quantity'] }})</span>
                            <div class="line"></div>
                            <span class="value">Rp {{ number_format($addon['price'] * $addon['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

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
            <div class="summary-line total-payable">
                <span>TOTAL BAYAR (DP 50%) :</span>
                <span>Rp {{ number_format($invoice->total_amount * 0.5, 0, ',', '.') }}</span>
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