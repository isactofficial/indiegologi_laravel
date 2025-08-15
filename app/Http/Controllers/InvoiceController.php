<?php

namespace App\Http\Controllers;

use App\Models\ConsultationBooking;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(ConsultationBooking $consultationBooking)
    {
        // Load necessary relationships for the view
        $consultationBooking->load('services', 'invoice', 'user');

        // Pass the booking data to your invoice view
        return view('invoice.show', compact('consultationBooking'));
    }
}
