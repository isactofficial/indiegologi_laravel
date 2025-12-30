@extends('layouts.admin')

@section('content')
<style>
    /* Styling dasar invoice - TIDAK DIUBAH */
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

    /* Penyesuaian Responsif tetap dipertahankan */
    @media (max-width: 768px) {
        .container-fluid { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        .invoice-card { padding: 1.5rem 1rem; max-width: none; margin: 0; }
        .invoice-header { flex-direction: column; gap: 1rem; }
        .invoice-header .invoice-contact, .summary-box { text-align: left; }
        .invoice-top-section { flex-direction: row; flex-wrap: nowrap; gap: 0.75rem; }
        .invoice-client-info, .invoice-details-info { flex: 1; padding: 1rem; font-size: 0.85rem; overflow-wrap: break-word; min-width: 0; }
    }

    /* Style untuk baris rincian di summary */
    .calculation-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 5px;
    }
    .calculation-title {
        font-weight: 600;
        color: #001f3f;
        border-bottom: 1px solid #eee;
        margin-bottom: 8px;
        padding-bottom: 4px;
        text-align: left;
    }

    @media print { .no-print { display: none; } }
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
        <div class="invoice-header">
            <div class="invoice-logo">Indiegologi</div>
            <div class="invoice-contact">
                Email: ceriadiego@gmail.com<br>
                Phone: +62 822-2095-5595
            </div>
        </div>

        <div class="invoice-top-section">
            <div class="invoice-client-info">
                <p class="fw-bold fs-5 text-dark">Dear</p>
                <p><strong>Nama:</strong> {{ $consultationBooking->receiver_name ?? 'N/A' }}</p>
                @foreach($consultationBooking->services as $service)
                    @if($loop->first)
                        @if($service->pivot->session_type == 'Offline')
                            <p><strong>Alamat Offline:</strong> {{ $service->pivot->offline_address }}</p>
                        @endif
                        <p><strong>Tanggal Konseling:</strong> {{ \Carbon\Carbon::parse($service->pivot->booked_date)->format('d F Y') }}</p>
                        <p><strong>Waktu Konseling:</strong> {{ \Carbon\Carbon::parse($service->pivot->booked_time)->format('H:i') }} WIB</p>
                    @endif
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

        <table class="service-description-table">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th class="text-center">Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $allAddons = [];
                    $totalExtraHoursPrice = 0;
                    $extraHoursDetails = [];
                @endphp
                @foreach($consultationBooking->services as $service)
                    @php
                        $qty = $service->pivot->participant_count ?? 1;
                        $baseTotal = $service->price * $qty;

                        // Kumpulkan Addons
                        $itemAddons = json_decode($service->pivot->addons_data, true) ?? [];
                        foreach($itemAddons as $addon) {
                            $allAddons[] = $addon;
                        }
                        
                        // Kumpulkan Extra Hours
                        $hoursBooked = $service->pivot->hours_booked;
                        $baseDuration = $service->base_duration ?? 0;
                        $extraHours = max(0, $hoursBooked - $baseDuration);
                        if($extraHours > 0) {
                            $hourlyCost = $service->hourly_price * $extraHours;
                            $totalExtraHoursPrice += $hourlyCost;
                            $extraHoursDetails[] = [
                                'service' => $service->title,
                                'hours' => $extraHours,
                                'price' => $hourlyCost
                            ];
                        }
                    @endphp
                    <tr>
                        <td data-label="Service Description">
                            <div class="fw-bold text-dark">{{ $service->title }}</div>
                            <small class="text-muted">• Durasi: {{ $baseDuration }} Jam</small>
                        </td>
                        <td data-label="Quantity" class="text-center">{{ $qty }}</td>
                        <td data-label="Unit Price">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td data-label="Total">Rp {{ number_format($baseTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-box">
            {{-- BAGIAN RINCIAN PERHITUNGAN TAMBAHAN --}}
            <div style="max-width: 400px; margin-left: auto; text-align: right;">
                
                @if(count($extraHoursDetails) > 0 || !empty($allAddons))
                    <div class="calculation-title">Rincian Tambahan Biaya</div>
                    
                    {{-- Detail Extra Hours --}}
                    @foreach($extraHoursDetails as $extra)
                        <div class="calculation-row">
                            <span>Add-on Durasi ({{ $extra['hours'] }} Jam):</span>
                            <span>Rp {{ number_format($extra['price'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    {{-- Detail Addons --}}
                    @foreach($allAddons as $addon)
                        <div class="calculation-row">
                            <span>{{ $addon['name'] }} (x{{ $addon['quantity'] }}):</span>
                            <span>Rp {{ number_format($addon['price'] * $addon['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr style="margin: 10px 0;">
                @endif
                
                <p><strong>Sub-Total:</strong> Rp {{ number_format($consultationBooking->final_price + $consultationBooking->discount_amount, 0, ',', '.') }}</p>
                <p class="text-success"><strong>Total Diskon:</strong> -Rp {{ number_format($consultationBooking->discount_amount, 0, ',', '.') }}</p>
                
                <div class="final-total">
                    Total Invoice Rp {{ number_format($consultationBooking->final_price, 0, ',', '.') }}
                </div>
            </div>
        </div>

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