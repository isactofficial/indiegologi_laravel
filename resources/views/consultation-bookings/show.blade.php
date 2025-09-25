@extends('layouts.admin')

@section('content')
<style>
    /* Styling khusus untuk tampilan web */
    .invoice-container {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: #f5f7fa;
    }
    .invoice-wrapper {
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .invoice-logo {
        font-size: 24px;
        font-weight: bold;
        color: #001f3f;
    }
    .invoice-contact {
        text-align: right;
        font-size: 14px;
        color: #333;
    }
    .invoice-top-section {
        display: flex;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 30px;
    }
    .invoice-client-info, .invoice-details-info {
        background-color: #f3faff;
        padding: 20px;
        border-radius: 10px;
        width: 100%;
    }
    .invoice-client-info p:first-child {
        font-weight: bold;
        font-size: 1.1rem;
        color: #001f3f;
    }
    .invoice-details-info h5 {
        color: #001f3f;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .service-description-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .service-description-table th, .service-description-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .service-description-table th {
        background-color: #001f3f;
        color: white;
    }
    .discount-row td {
        background-color: #ffc107;
        font-weight: bold;
        color: #000;
    }
    .summary-box {
        margin-top: 30px;
        text-align: right;
    }
    .summary-box p, .summary-box div {
        margin: 4px 0;
    }
    .final-total {
        margin-top: 10px;
        background-color: #ffc107;
        display: inline-block;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 6px;
    }
    .invoice-signature {
        margin-top: 50px;
        font-size: 14px;
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
    }
    /* Hide specific elements for printing */
    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<div class="container-fluid px-4 py-5" style="background-color: #f5f7fa; min-height: 100vh;">
    <div class="d-flex justify-content-start mb-4 no-print">
        <a href="{{ route('admin.consultation-bookings.index') }}" class="btn px-4 py-2"
           style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Booking
        </a>
        <a href="{{ route('admin.consultation-bookings.download-pdf', $consultationBooking->id) }}" class="btn btn-primary px-4 py-2 ms-3">
            <i class="fas fa-download me-2"></i> Unduh PDF
        </a>
    </div>

    <div class="invoice-card">
        {{-- Header --}}
        <div class="invoice-header">
            <div class="invoice-logo">Indiegologi</div>
            <div class="invoice-contact">
                Email: ceriadiego@gmail.com<br>
                Phone Number: +62 822-2095-5595
            </div>
        </div>

        {{-- Info Section --}}
        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <p>Dear</p>
                <p>Nama : {{ $consultationBooking->receiver_name ?? 'N/A' }}</p>
                {{-- Menampilkan info layanan dan tanggal booking --}}
                @foreach($consultationBooking->services as $service)
                    @if($service->pivot->session_type == 'Offline')
                        <p>Alamat Offline: {{ $service->pivot->offline_address }}</p>
                    @endif
                    <p>Waktu Konseling: {{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }}</p>
                    <p>Paket Konseling: {{ $service->title }}</p>
                @endforeach
                <p>No Hp : {{ optional($consultationBooking->user)->phone_number ?? 'N/A' }}</p>
            </div>
            <div class="invoice-details-info">
                <h5>Invoice Details</h5>
                <p>Invoice No : {{ optional($consultationBooking->invoice)->invoice_no ?? 'N/A' }}</p>
                <p>Invoice Date : {{ optional($consultationBooking->invoice)?->invoice_date?->format('d/F/Y') ?? 'N/A' }}</p>
                <p>Due Date : {{ optional($consultationBooking->invoice)?->due_date?->format('d/F/Y') ?? 'N/A' }}</p>
                <p>Status : {{ ucfirst(optional($consultationBooking->invoice)?->payment_status ?? 'N/A') }}</p>
                <p>Session : {{ ucfirst($consultationBooking->session_status ?? 'N/A') }}</p>
                <p>Payment Type : {{ $consultationBooking->payment_type ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Service Table --}}
        <table class="service-description-table">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th>Total </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $totalDiscount = 0;
                @endphp
                @foreach($consultationBooking->services as $service)
                    @php
                        $basePrice = $service->price;
                        $hourlyPrice = $service->hourly_price;
                        $hoursBooked = $service->pivot->hours_booked;
                        $totalPrice = $basePrice + ($hourlyPrice * $hoursBooked);
                        $discountAmount = $service->pivot->discount_amount_at_booking;
                        $subtotal += $totalPrice;
                        $totalDiscount += $discountAmount;
                    @endphp
                    <tr>
                        <td>
                            {{ $service->title }}<br>
                            @if($hoursBooked > 0)
                                <small class="text-muted">{{ $hoursBooked }} Hours - add</small>
                            @endif
                        </td>
                        <td>1</td>
                        <td>
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                            @if($hoursBooked > 0)
                                <br>Rp {{ number_format($hourlyPrice, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>-</td>
                        <td>
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                            @if($hoursBooked > 0)
                                <br>Rp {{ number_format($hourlyPrice * $hoursBooked, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                    @if($discountAmount > 0)
                        <tr class="discount-row">
                            <td>Discount</td>
                            <td>1</td>
                            <td>-</td>
                            <td>Rp {{ number_format($discountAmount, 0, ',', '.') }}</td>
                            <td>-Rp {{ number_format($discountAmount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="summary-box">
            <p>Sub-Total: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            <p class="total-discount">Total Discount Item: -Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
            <div class="final-total">
                Total Invoice Rp {{ number_format($consultationBooking->final_price, 0, ',', '.') }}
            </div>
        </div>

        {{-- Signature --}}
        <div class="invoice-signature">
            <p>Regards,</p>
            <h5>Muhammad Ikhsan Haekal</h5>
            <p>Indiegologi Team</p>
        </div>

        {{-- Payment Info --}}
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
