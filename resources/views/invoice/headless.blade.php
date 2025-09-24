<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
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

    /* === PENGATURAN UTAMA TABEL === */
    .service-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1rem;
        table-layout: fixed; /* Kunci agar lebar kolom dipatuhi */
    }

    .service-table th,
    .service-table td {
        padding: 16px 8px;
        text-align: left;
        word-break: break-word; /* Izinkan pematahan kata jika perlu */
    }
    
    .service-table th {
        white-space: normal; /* Izinkan teks header turun baris */
    }

    /* === PENENTUAN LEBAR SETIAP KOLOM (VERSI FINAL) === */
.service-table th:nth-child(1), .service-table td:nth-child(1) { width: 34%; } /* Deskripsi */
.service-table th:nth-child(2), .service-table td:nth-child(2) { width: 12%; } 
.service-table th:nth-child(3), .service-table td:nth-child(3) { width: 22%; } /* Harga Satuan */
.service-table th:nth-child(4), .service-table td:nth-child(4) { width: 12%; } /* Besaran */
.service-table th:nth-child(5), .service-table td:nth-child(5) { width: 20%; } /* Total Line */
/* ================================================ */


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
        white-space: nowrap; /* Jaga agar ANGKA tidak turun baris */
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

    /* Perbaikan CSS untuk bagian Dear di mobile */

/* Perbaikan CSS untuk bagian Dear di mobile */

@media (max-width: 768px) {
    .invoice-wrapper {
        padding: 15px; /* Lebih besar dari 10px untuk breathing room */
        margin: 20px auto; /* Kurangi margin top/bottom */
    }
    
    .invoice-top-section {
        flex-direction: column; 
        gap: 1rem; /* Kurangi gap antar section */
        margin-bottom: 20px; /* Kurangi margin bottom */
    }

    .invoice-client-info,
    .invoice-details-info {
        padding: 15px; /* Kurangi padding internal */
        grid-template-columns: 1fr; /* Single column layout */
        gap: 8px 0; /* Kurangi gap antar item */
    }

    /* Khusus untuk bagian Dear/Client Info */
    .invoice-client-info p {
        margin: 8px 0; /* Tambah spacing antar paragraph */
        line-height: 1.5; /* Improve readability lebih */
    }

    .invoice-client-info p:first-child {
        font-size: 1rem; /* Sedikit kurangi ukuran title */
        margin-bottom: 12px; /* Tambah spacing setelah title */
    }

    /* Fix untuk list paket konseling */
    .invoice-client-info div {
        padding-left: 0; 
        margin: 8px 0; /* Tambah margin */
    }

    .invoice-client-info div p {
        margin-bottom: 6px; /* Tambah spacing dalam list */
    }

    /* Improve spacing untuk bullet points */
    .invoice-client-info div div {
        padding-left: 15px; /* Indent untuk bullet points */
        line-height: 1.4; /* Sedikit tambah line height */
        margin-top: 4px; /* Tambah space di atas bullet points */
    }

    /* Perbaiki title underline */
    .title-underline {
        padding-bottom: 8px; /* Kurangi padding */
    }

    .title-underline::after {
        width: 45px; /* Kurangi lebar garis */
        height: 2px; /* Sedikit kurangi ketebalan */
    }

    /* Service table improvements */
    .service-table {
        font-size: 0.75rem; /* Sedikit lebih kecil */
        margin-top: 15px; /* Kurangi margin top */
    }

    .service-table th,
    .service-table td {
        padding: 8px 4px; /* Kurangi padding lebih banyak */
    }

    /* Header improvements */
    .invoice-header {
        padding-bottom: 15px; /* Kurangi padding */
        margin-bottom: 20px; /* Kurangi margin */
    }

    .invoice-logo {
        font-size: 20px; /* Kurangi ukuran logo */
    }

    .invoice-contact-info {
        font-size: 12px; /* Kurangi ukuran font */
    }
}

/* Perbaikan tambahan untuk layar sangat kecil */
@media (max-width: 480px) {
    .invoice-wrapper {
        padding: 12px;
        margin: 10px auto;
    }
    
    .invoice-client-info,
    .invoice-details-info {
        padding: 12px;
    }

    .service-table {
        font-size: 0.7rem;
    }

    .service-table th,
    .service-table td {
        padding: 6px 2px;
    }

    .invoice-client-info p:first-child {
        font-size: 0.95rem;
    }

    /* Responsive column widths untuk table di mobile */
    .service-table th:nth-child(1), .service-table td:nth-child(1) { width: 40%; }
    .service-table th:nth-child(2), .service-table td:nth-child(2) { width: 10%; }
    .service-table th:nth-child(3), .service-table td:nth-child(3) { width: 18%; }
    .service-table th:nth-child(4), .service-table td:nth-child(4) { width: 10%; }
    .service-table th:nth-child(5), .service-table td:nth-child(5) { width: 22%; }
}
</style>
</head>
<body>
    <div class="invoice-wrapper">
        @include('invoice.partials.invoice-content')
    </div>
</body>
</html>