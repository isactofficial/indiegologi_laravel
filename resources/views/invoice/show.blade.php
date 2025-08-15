@extends('layouts.app')

@section('content')
    <style>
        /* Styling for the user-facing invoice */
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
        }

        /* === PERUBAHAN CSS HEADER DIMULAI DI SINI === */
        .invoice-header {
            display: flex;
            justify-content: flex-end;
            /* Mendorong semua konten ke kanan */
            align-items: flex-start;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-header-details {
            text-align: right;
            /* Meratakan teks di dalam blok ke kanan */
        }

        .invoice-logo {
            font-size: 24px;
            font-weight: bold;
            color: #001f3f;
            margin-bottom: 10px;
            /* Memberi jarak antara logo dan kontak */
        }

        .invoice-contact-info {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }

        /* === PERUBAHAN CSS HEADER SELESAI === */

        .invoice-top-section {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 30px;
        }

        .invoice-client-info,
        .invoice-details-info {
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

        /* === BAGIAN YANG DIUBAH UNTUK TABEL & RINGKASAN === */
        .service-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        .service-table th,
        .service-table td {
            padding: 16px 8px;
            text-align: left;
        }

        .service-table thead {
            background-color: #F0F8FF;
        }

        .service-table th {
            font-weight: 700;
        }

        .service-table tbody tr:not(.discount-row) {
            border-bottom: 1px solid #eee;
        }

        .service-table .discount-row td {
            background-color: #FFB700;
            color: white;

        }

        .text-right {
            text-align: right;
        }

        .service-table th.text-right,
        .service-table td.text-right {
            text-align: right;
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
            margin-bottom: 100px;
        }

        .invoice-footer {
            clear: both;
            padding-top: 100px;
        }

        .service-table .discount-row {
            /* Menambahkan border bawah transparan untuk memberi efek spasi */
            border-bottom: 20px solid white;
        }

        /* === AKHIR DARI BAGIAN YANG DIUBAH === */

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

        /* Hide elements for printing */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

    <div class="section container-fluid px-4 py-5" style="background-color: #f5f7fa; min-height: 100vh;">
        <div class="d-flex justify-content-start mb-4 no-print" style="max-width: 800px; margin: 0 auto;">
            <a href="{{ route('front.cart.view') }}" class="btn px-4 py-2"
                style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
            </a>
            <a href="{{ route('admin.consultation-bookings.download-pdf', $consultationBooking->id) }}"
                class="btn btn-primary px-4 py-2 ms-3">
                <i class="fas fa-download me-2"></i> Unduh PDF
            </a>
        </div>

        <div class="invoice-wrapper">
            {{-- Header - Telah Diedit --}}
            <div class="invoice-header">
                <div class="invoice-header-details">
                    <div class="invoice-logo">Indiegologi</div>
                    <div class="invoice-contact-info">
                        Email: ceriadiego@gmail.com<br>
                        Phone Number: +62 822-2095-5595
                    </div>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="invoice-top-section">
                <div class="invoice-client-info">
                    <p>Dear</p>
                    <p>Nama : {{ $consultationBooking->receiver_name ?? 'N/A' }}</p>
                    {{-- Service and booking date info --}}
                    @foreach ($consultationBooking->services as $service)
                        @if ($service->pivot->session_type == 'Offline')
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
                    <p>Invoice Date :
                        {{ optional($consultationBooking->invoice)?->invoice_date?->format('d/F/Y') ?? 'N/A' }}</p>
                    <p>Due Date : {{ optional($consultationBooking->invoice)?->due_date?->format('d/F/Y') ?? 'N/A' }}</p>
                    <p>Status : {{ ucfirst(optional($consultationBooking->invoice)?->payment_status ?? 'N/A') }}</p>
                    <p>Session : {{ ucfirst($consultationBooking->session_status ?? 'N/A') }}</p>
                    <p>Payment Type : {{ $consultationBooking->payment_type ?? 'N/A' }}</p>
                </div>
            </div>


            <table class="service-table">
                <thead>
                    <tr>
                        <th>Deskripsi layanan</th>
                        <th class="text-right">Kuantitas</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Besaran</th>
                        <th class="text-right">Total Line</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($consultationBooking->services as $service)
                        <tr>
                            <td>{{ $service->title }}</td>
                            <td class="text-right">1</td>
                            <td class="text-right">Rp
                                {{ number_format($service->pivot->total_price_at_booking, 0, ',', '.') }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp
                                {{ number_format($service->pivot->total_price_at_booking, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="discount-row">
                            <td>Diskon</td>
                            <td class="text-right">
                                @if ($service->pivot->discount_amount_at_booking > 0)
                                    1
                                @else
                                    0
                                @endif
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                @if ($service->pivot->discount_amount_at_booking > 0 && optional($service->pivot->referralCode)->discount_percentage)
                                    {{ $service->pivot->referralCode->discount_percentage }}%
                                @else
                                    0%
                                @endif
                            </td>
                            <td class="text-right">-Rp
                                {{ number_format($service->pivot->discount_amount_at_booking, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-section">
                <div class="summary-line">
                    <span>Sub-Total:</span>
                    <span>Rp
                        {{ number_format(optional($consultationBooking->invoice)->total_amount + optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</span>
                </div>
                <div class="summary-line">
                    <span><b>Total Diskon Item:</b></span>
                    <span><b>-Rp
                            {{ number_format(optional($consultationBooking->invoice)->discount_amount, 0, ',', '.') }}</b></span>
                </div>
                <div class="summary-line grand-total">
                    <span>TOTAL KESELURUHAN :</span>
                    <span>Rp {{ number_format(optional($consultationBooking->invoice)->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Signature --}}
            <div class="invoice-signature">
                <p>Dear Customer,</p>
                <p>Durasi Konseling Sesuai dengan jadwal yang telah disepakati dan apabila melebihi dari jadwal yang telah
                    disepakati akan diberikan charge tambahan</p>
                <p>Keterlambatan yang dilakukan oleh Client tetap terhitung sebagai durasi konseling</p>
                <p>reschedule dapat dilakukan selambat lambatnya 24 jam sebelum sesi konseling</p>
                <p>Sudah Menjadi bagian sejarah dari hidup (XXX)
                    semoga keberuntungan dan kebahagiaan akan mengikuti hidup kita selanjutnya</p>
                <p>Salam</p>
                <p>Melvin<br>Tim Indiegologi</p>
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
