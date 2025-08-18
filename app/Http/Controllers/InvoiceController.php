<?php

namespace App\Http\Controllers;

use App\Models\ConsultationBooking;
use App\Models\User; // 1. IMPORT MODEL USER
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(ConsultationBooking $consultationBooking)
    {
        // Load necessary relationships for the view
        $consultationBooking->load('services', 'invoice', 'user');

        // 2. AMBIL DATA ADMIN DARI DATABASE
        $admin = User::where('role', 'admin')
                       ->whereIn('id', [1, 2])
                       ->first();

        // 3. SIAPKAN NAMA ADMIN DENGAN FALLBACK
        $adminName = $admin ? $admin->name : 'Admin Indiegologi';

        // 4. KIRIM SEMUA DATA KE VIEW
        return view('invoice.show', [
            'consultationBooking' => $consultationBooking,
            'adminName' => $adminName,
        ]);
    }
}
