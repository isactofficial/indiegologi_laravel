<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Booking Baru Indiegology</title>
    <link rel="stylesheet" href="/css/new-booking-mail.css">
</head>

<body style="margin: 0; padding: 0; background-color: #f7f7f7; font-family: Arial, sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%"
        style="table-layout: fixed; background-color: #f7f7f7;">
        <tr>
            <td align="center" style="padding: 30px 0;">

                <table border="0" cellpadding="0" cellspacing="0" width="600" class="full-width-table"
                    style="border-radius: 8px; overflow: hidden; background-color: #ffffff; border: 1px solid #e0e0e0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">

                    <!-- Header Section - Branding -->
                    <tr>
                        <td align="center" style="padding: 25px; background-color: #1f3a5f; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
                                INDIEGOLOGY
                            </h1>
                            @if ($invoice->source_type == 'service')
                                <h2 style="margin: 5px 0 0 0; font-size: 18px; font-weight: 400; color: #a9c5ec;">
                                    Notifikasi Booking Layanan Baru
                                </h2>
                            @elseif ($invoice->source_type == 'event')
                                <h2 style="margin: 5px 0 0 0; font-size: 18px; font-weight: 400; color: #a9c5ec;">
                                    Notifikasi Booking Event Baru
                                </h2>
                            @endif
                        </td>
                    </tr>

                    <!-- Greeting and User Highlight -->
                    <tr>
                        <td style="padding: 35px 40px 15px 40px;" class="content-padding">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #333333;">Hai Admin,</p>

                            @if ($invoice->source_type == 'service')
                                <p style="margin: 0; font-size: 17px; color: #555555;">
                                    Telah diterima pemesanan layanan konsultasi baru dari:
                                </p>
                            @elseif ($invoice->source_type == 'event')
                                <p style="margin: 0; font-size: 17px; color: #555555;">
                                    Telah diterima pemesanan event baru dari:
                                </p>
                            @endif
                            <p style="margin: 10px 0 0 0; font-size: 22px; color: #1f3a5f; font-weight: bold;">
                                {{ $booking->user->name }}
                            </p>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="border-top: 2px solid #e0e0e0; margin-top: 15px; padding-top: 15px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Contact Details & WA Button -->
                    <tr>
                        <td style="padding: 15px 40px 30px 40px;" class="content-padding">
                            <h3 style="margin: 0 0 15px; font-size: 18px; color: #1f3a5f; font-weight: bold;">
                                Kontak Klien
                            </h3>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="font-size: 16px; color: #333333;">
                                <tr>
                                    <td class="stack-cell" style="padding-bottom: 10px; color: #555555;">
                                        Nomor HP:</td>
                                    <td class="stack-cell" style="padding-bottom: 10px;">
                                        <strong>{{ $booking->user->profile->phone_number ?? '-' }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- WA.ME Button-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                <tr>
                                    <td align="left">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center"
                                                    style="border-radius: 4px; background-color: #10b981; padding: 12px 20px;">
                                                    @php
                                                        // Ambil nomor dari relasi user->profile, jika ada
                                                        $phone = $booking->user?->profile?->phone_number ?? '';
                                                        $phone = preg_replace('/[^0-9]/', '', $phone);

                                                        // Jika nomor dimulai dengan 0, ubah ke 62
                                                        if (Str::startsWith($phone, '0')) {
                                                            $phone = '62' . substr($phone, 1);
                                                        }
                                                    @endphp

                                                    <a href="https://wa.me/{{ $phone }}" target="_blank"
                                                        style="font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; font-weight: bold; display: inline-block;">
                                                        Hubungi via WhatsApp
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Booking Details Section -->
                    <tr>
                        <td style="padding: 0 40px 20px 40px;" class="content-padding">
                            <h3
                                style="margin: 0 0 15px; font-size: 18px; color: #1f3a5f; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px;">
                                Detail Pemesanan
                            </h3>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="font-size: 15px; color: #555555; line-height: 1.6;">
                                <tr>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Nomor Invoice:</strong> <span
                                            style="font-weight: bold; color: #e57300; word-wrap: break-word;">{{ $invoice->invoice_no ?? 'N/A' }}</span>
                                    </td>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Tanggal Pesan:</strong>
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Penerima:</strong>
                                        {{ $booking->receiver_name ?? '-' }}
                                    </td>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Metode Kontak:</strong>
                                        {{ ucfirst(str_replace('_', ' ', $booking->contact_preference)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Tipe Pembayaran:</strong>
                                        {{ ucfirst($booking->payment_type) }}
                                    </td>
                                    <td width="50%" class="stack-cell" style="padding-bottom: 8px;">
                                        <strong style="color: #333333;">Status Sesi:</strong>
                                        <span
                                            style="font-weight: bold; color: #e57300;">{{ ucfirst($booking->session_status) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Total Price Section  -->
                    <tr>
                        <td align="center" style="padding: 0 0 0 0;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="background-color: #e6eef5; border-top: 1px solid #c0c0c0;">
                                <tr>
                                    <td style="padding: 20px 40px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="font-size: 18px; font-weight: bold; color: #1f3a5f;">
                                                    NILAI TRANSAKSI
                                                </td>
                                                <td align="right"
                                                    style="font-size: 26px; font-weight: bold; color: #1f3a5f;">
                                                    Rp {{ number_format($booking->final_price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Action Button: Download PDF Invoice -->
                    <tr>
                        <td align="center" style="padding: 30px 40px 15px 40px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center"
                                        style="border-radius: 4px; background-color: #1f3a5f; padding: 14px 25px;">
                                        <a href="{{ route('invoice.download', $invoice->id) }}" target="_blank"
                                            style="font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; font-weight: bold; display: inline-block;">
                                            Unduh Invoice PDF
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Action Button: Redirect to Admin Dashboard -->
                    <tr>
                        <td align="center" style="padding: 15px 40px 30px 40px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center"
                                        style="border-radius: 4px; background-color: #0d47a1; padding: 14px 25px;">
                                        @if ($invoice->source_type == 'service')
                                            <a href="http://indiegologi_laravel.io/admin/consultation-bookings" target="_blank"
                                                style="font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; font-weight: bold; display: inline-block;">
                                                Lihat Semua Booking Layanan (Admin Panel)
                                            </a>
                                        @elseif ($invoice->source_type == 'event')
                                            <a href="http://indiegologi_laravel.io/admin/event-bookings" target="_blank"
                                                style="font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; font-weight: bold; display: inline-block;">
                                                Lihat Semua Booking Event (Admin Panel)
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td align="center"
                            style="padding: 20px 40px; background-color: #f7f7f7; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #6b7280;">
                                Mohon segera proses pemesanan ini.
                            </p>
                            <p style="margin: 0; font-size: 13px; color: #999999;">
                                Hormat kami, Indiegology System.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>