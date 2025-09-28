@extends('layouts.admin')

@section('content')
<style>
    /* Styling dasar invoice */
    .invoice-card {
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
    .summary-box {
        margin-top: 30px;
        text-align: right;
    }
    .final-total {
        margin-top: 10px;
        background-color: #ffc107;
        display: inline-block;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 6px;
    }
    .payment-info {
        margin-top: 50px;
        background-color: #ffc107;
        padding: 20px;
        border-radius: 8px;
    }

    /* Penyesuaian Responsif */
    @media (max-width: 768px) {
        /* Atur padding container & hilangkan batasan lebar card */
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        .invoice-card {
            padding: 1.5rem 1rem;
            max-width: none;
            margin: 0;
        }

        .invoice-header {
            flex-direction: column;
            gap: 1rem;
        }
        .invoice-header .invoice-contact, .summary-box {
            text-align: left;
        }

        /* Atur agar info client & invoice tetap berdampingan */
        .invoice-top-section {
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 0.75rem;
        }

        /* PERBAIKAN: Gunakan flex: 1 agar lebar terbagi otomatis */
        .invoice-client-info, .invoice-details-info {
            flex: 1; /* <-- KUNCI PERBAIKAN: Biarkan flexbox yang mengatur lebar */
            padding: 1rem;
            font-size: 0.85rem;
            overflow-wrap: break-word; /* Memastikan teks panjang tidak overflow */
            min-width: 0; /* Mencegah konten mendorong container */
        }

        .invoice-details-info h5 {
            font-size: 1rem;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch !important;
        }
        .action-buttons .btn {
            width: 100%;
            margin-left: 0 !important;
            margin-bottom: 0.5rem;
        }

        /* Atur tabel agar responsif */
        .service-description-table thead {
            display: none;
        }
        .service-description-table, .service-description-table tbody, .service-description-table tr, .service-description-table td {
            display: block;
            width: 100%;
        }
        .service-description-table tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }
        .service-description-table td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            border: none;
            border-bottom: 1px solid #eee;
        }
        .service-description-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<div class="container-fluid py-5" style="background-color: #f5f7fa; min-height: 100vh;">
    <div class="d-flex justify-content-start mb-4 no-print action-buttons">
        <a href="{{ route('admin.consultation-bookings.index') }}" class="btn px-4 py-2"
           style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        <a href="{{ route('admin.consultation-bookings.download-pdf', $consultationBooking->id) }}" class="btn btn-primary px-4 py-2 ms-md-3">
            <i class="fas fa-download me-2"></i> Unduh PDF
        </a>
    </div>

    <div class="invoice-card">
        {{-- Header --}}
        <div class="invoice-header">
            <div class="invoice-logo">Indiegologi</div>
            <div class="invoice-contact">
                Email: ceriadiego@gmail.com<br>
                Phone: +62 822-2095-5595
            </div>
        </div>

        {{-- Info Section --}}
        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <p class="fw-bold fs-5 text-dark">Dear</p>
                <p><strong>Nama:</strong> {{ $consultationBooking->receiver_name ?? 'N/A' }}</p>
                @foreach($consultationBooking->services as $service)
                    @if($service->pivot->session_type == 'Offline')
                        <p><strong>Alamat Offline:</strong> {{ $service->pivot->offline_address }}</p>
                    @endif
                    <p><strong>Waktu Konseling:</strong> {{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }}</p>
                    <p><strong>Paket Konseling:</strong> {{ $service->title }}</p>
                @endforeach
                <p><strong>No Hp:</strong> {{ optional($consultationBooking->user)->phone_number ?? 'N/A' }}</p>
            </div>
            <div class="invoice-details-info">
                <h5>Invoice Details</h5>
                <p><strong>Invoice No:</strong> {{ optional($consultationBooking->invoice)->invoice_no ?? 'N/A' }}</p>
                <p><strong>Invoice Date:</strong> {{ optional($consultationBooking->invoice)?->invoice_date?->format('d/F/Y') ?? 'N/A' }}</p>
                <p><strong>Due Date:</strong> {{ optional($consultationBooking->invoice)?->due_date?->format('d/F/Y') ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ ucfirst(optional($consultationBooking->invoice)?->payment_status ?? 'N/A') }}</p>
                <p><strong>Session:</strong> {{ ucfirst($consultationBooking->session_status ?? 'N/A') }}</p>
                <p><strong>Payment Type:</strong> {{ $consultationBooking->payment_type ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Service Table --}}
        <table class="service-description-table">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
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
                        $finalServicePrice = $totalPrice - $discountAmount;
                        $subtotal += $totalPrice;
                        $totalDiscount += $discountAmount;
                    @endphp
                    <tr>
                        <td data-label="Service">
                            {{ $service->title }}
                            @if($hoursBooked > 0)
                                <small class="d-block text-muted">{{ $hoursBooked }} jam tambahan</small>
                            @endif
                        </td>
                        <td data-label="Quantity">1</td>
                        <td data-label="Unit Price">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                        <td data-label="Total">Rp {{ number_format($finalServicePrice, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="summary-box">
            <p>Sub-Total: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            <p class="text-success">Total Diskon: -Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
            <div class="final-total">
                Total Invoice Rp {{ number_format($consultationBooking->final_price, 0, ',', '.') }}
            </div>
        </div>

        {{-- Signature & Payment Info --}}
        <div class="mt-5">
            <div class="invoice-signature mb-4">
                <p class="mb-0">Regards,</p>
                <h5 class="mb-0">Muhammad Ikhsan Haekal</h5>
                <p>Indiegologi Team</p>
            </div>
            <div class="payment-info">
                <h4 class="fw-bold text-dark mb-3">Payment Information</h4>
                <p><strong>Bank SMBC Indonesia:</strong> 90110023186</p>
                <p><strong>Atas Nama:</strong> Artwira Mahatavirya Satyagasty</p>
                <p class="mt-3">Mohon transfer sebelum jatuh tempo dan konfirmasi ke nomor: 0822 2095 5595</p>
            </div>
        </div>
    </div>
</div>
@endsection
