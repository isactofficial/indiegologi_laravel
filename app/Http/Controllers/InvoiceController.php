<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ConsultationBooking;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Menampilkan halaman invoice di web - UPDATED TO SUPPORT BOTH SERVICES AND EVENTS
     */
    public function show($invoiceId)
    {
        // Find invoice with all relationships
        $invoice = Invoice::with([
            'consultationBooking.services', 
            'eventBookings.event',
            'eventBookings.participants',
            'user.profile'
        ])->findOrFail($invoiceId);

        $admin = User::where('role', 'admin')->whereIn('id', [1, 2])->first();
        $adminName = $admin ? $admin->name : 'Admin Indiegologi';

        return view('invoice.show', [
            'invoice' => $invoice,
            'adminName' => $adminName,
        ]);
    }

    /**
     * Membuat PDF - UPDATED TO SUPPORT BOTH SERVICES AND EVENTS
     */
    public function downloadPdf($invoiceId)
    {
        $invoice = Invoice::with([
            'consultationBooking.services', 
            'eventBookings.event',
            'eventBookings.participants',
            'user.profile'
        ])->findOrFail($invoiceId);

        $admin = User::where('role', 'admin')->whereIn('id', [1, 2])->first();
        $adminName = $admin ? $admin->name : 'Admin Indiegologi';

        $data = [
            'invoice' => $invoice,
            'adminName' => $adminName,
        ];

        $pdf = PDF::loadView('invoice.pdf', $data)->setPaper('a4', 'portrait');
        $fileName = 'invoice-' . $invoice->invoice_no . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * KEEP FOR BACKWARD COMPATIBILITY: Redirect from old route
     */
    public function showFromConsultationBooking(ConsultationBooking $consultationBooking)
    {
        return redirect()->route('invoice.show', $consultationBooking->invoice_id);
    }
}