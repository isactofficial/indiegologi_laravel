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
            gap: 1.5rem;
            margin-bottom: 30px;
        }

        .invoice-client-info,
        .invoice-details-info {
            background-color: #f3faff;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 8px 10px;
            align-items: center;
        }

        .grid-full-span {
            grid-column: 1 / -1;
        }

        .grid-align-top-left {
            align-self: start;
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

        .invoice-client-info p:first-child {
            font-weight: bold;
            font-size: 1.1rem;
            color: #001f3f;
        }

        .invoice-client-info  {
            color: #0F3A77;
        }

        .invoice-details-info h5 {
            color: #001f3f;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .invoice-details-info span {
            color: #0F3A77;
        }

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

        .service-hours-row td {
            padding-top: 2px;
            padding-bottom: 8px;
            font-size: 0.9em;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .service-table tbody tr.service {
             border-bottom: none;
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
    </style>

    <div class="section container-fluid px-4 py-5">
        <div class="d-flex justify-content-start mb-4 no-print" style="max-width: 800px; margin: 0 auto;">
            <a href="{{ route('front.cart.view') }}" class="btn px-4 py-2" style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
            </a>
            <a href="{{ route('invoice.download', $consultationBooking->id) }}" class="btn btn-primary px-4 py-2 ms-3">
                <i class="fas fa-download me-2"></i> Unduh PDF
            </a>
        </div>

        <div class="invoice-wrapper">
            @include('invoice.partials.invoice-content')
        </div>
    </div>
@endsection
