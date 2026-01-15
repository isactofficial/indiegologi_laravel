<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ConsultationBooking;
use App\Models\FreeConsultationBooking;
use App\Models\FreeConsultationParticipant;
use Barryvdh\DomPDF\Facade\Pdf;

class FreeConsultationBookingController extends Controller
{
    /**
     * Display a listing of the consultation bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = FreeConsultationBooking::with(['user', 'services', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('free-consultation-bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new consultation booking.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created consultation booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Display the specified consultation booking.
     */
    public function show(FreeConsultationBooking $freeConsultationBooking)
    {
        $freeConsultationBooking->load([
            'user',
            'invoice',
            'participants',
            'schedule',
        ]);

        return view('free-consultation-bookings.show', compact('freeConsultationBooking'));
    }

    /**
     * Show the form for editing the specified consultation booking.
     */
    public function edit(FreeConsultationBooking $freeConsultationBooking)
    {
        $users = User::all();
        $services = ConsultationService::whereIn('status', ['published', 'special'])->get();

        // Eager load pivot data
        $freeConsultationBooking->load('services');

        return view('free-consultation-bookings.edit', compact('freeConsultationBooking', 'users', 'services'));
    }

    /**
     * Update the specified consultation booking in storage.
     */
    public function update(Request $request, FreeConsultationBooking $freeConsultationBooking)
    {
        if ($request->has('participants')) {
            foreach ($request->participants as $id => $data) {
                FreeConsultationParticipant::where('id', $id)
                    ->update([
                        'attendance_status' => $data['attendance_status']
                    ]);
            }
        }

        return redirect()->route('admin.free-consultation-bookings.update', $freeConsultationBooking)
            ->with('success', 'Status kehadiran berhasil diperbarui!');
    }


    /**
     * Remove the specified consultation booking from storage.
     */
    public function destroy(FreeConsultationBooking $freeConsultationBooking)
    {
        $referralCodeIds = $freeConsultationBooking->services()->pluck('referral_code_id')->filter();
        if ($referralCodeIds->isNotEmpty()) {
            ReferralCode::whereIn('id', $referralCodeIds)->decrement('current_uses');
        }

        if ($freeConsultationBooking->invoice) {
            $freeConsultationBooking->invoice->delete();
        }

        $freeConsultationBooking->services()->detach();

        $freeConsultationBooking->delete();

        return redirect()->route('admin.free-consultation-bookings.index')->with('success', 'Booking berhasil dihapus!');
    }

    public function showUserProfile(User $user)
    {
        // Load relasi 'profile' untuk mendapatkan data tambahan
        $user->load('profile');

        return view('admin.users.show', compact('user'));
    }
}
