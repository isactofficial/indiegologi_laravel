<?php

namespace App\Http\Controllers;

use App\Models\ConsultationBooking;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan ini di-import

class InvoiceController extends Controller
{
    /**
     * Menampilkan halaman invoice di web
     */
    public function show(ConsultationBooking $consultationBooking)
    {
        $consultationBooking->load('services', 'invoice', 'user.profile');
        $admin = User::where('role', 'admin')->whereIn('id', [1, 2])->first();
        $adminName = $admin ? $admin->name : 'Admin Indiegologi';

        return view('invoice.show', [
            'consultationBooking' => $consultationBooking,
            'adminName' => $adminName,
        ]);
    }

    /**
     * Membuat PDF langsung dari Blade View (Metode DomPDF)
     */
    public function downloadPdf(ConsultationBooking $consultationBooking)
    {
        // 1. Load data yang diperlukan
        $consultationBooking->load('services', 'invoice', 'user.profile');
        $admin = User::where('role', 'admin')->whereIn('id', [1, 2])->first();
        $adminName = $admin ? $admin->name : 'Admin Indiegologi';

        $data = [
            'consultationBooking' => $consultationBooking,
            'adminName' => $adminName,
        ];

        // 2. Render view 'invoice.pdf' yang akan kita perbaiki di bawah
        $pdf = PDF::loadView('invoice.pdf', $data)->setPaper('a4', 'portrait');

        // 3. Kirim PDF ke browser untuk diunduh
        $fileName = 'invoice-' . optional($consultationBooking->invoice)->invoice_no . '.pdf';
        return $pdf->download($fileName);
    }
}
