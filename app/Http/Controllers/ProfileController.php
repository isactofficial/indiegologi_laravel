<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ADD THIS IMPORT
use App\Models\UserProfile;
use App\Models\Invoice;
use App\Models\ConsultationBooking;
use App\Models\EventBooking;
use App\Models\BookingService;
use App\Models\ConsultationService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();

        // FIXED: Use proper relationship loading
        $user->load([
            'profile',
            'invoices',
            'eventBookings.event', // This should work if you have the relationship defined in User model
            'eventBookings.participants',
            'eventBookings.invoice',
            'consultationBookings.bookingServices.event',
            'consultationBookings.bookingServices.freeConsultationType',
            'consultationBookings.invoice'
        ]);

        // If eventBookings relationship doesn't exist, load manually
        if (!$user->relationLoaded('eventBookings')) {
            $user->setRelation('eventBookings', EventBooking::where('user_id', $user->id)
                ->with(['event', 'participants', 'invoice'])
                ->get());
        }

        // If consultationBookings relationship doesn't exist, load manually  
        if (!$user->relationLoaded('consultationBookings')) {
            $user->setRelation('consultationBookings', ConsultationBooking::where('user_id', $user->id)
                ->with(['bookingServices.event', 'bookingServices.freeConsultationType', 'invoice'])
                ->get());
        }

        // Manually load consultation services for booking services
        foreach ($user->consultationBookings as $consultation) {
            $consultation->load(['bookingServices' => function ($query) {
                $query->with(['event', 'freeConsultationType']);
            }]);

            // Load services separately since there's no direct relationship
            $serviceIds = $consultation->bookingServices->pluck('service_id')->filter()->unique();
            if ($serviceIds->isNotEmpty()) {
                $services = ConsultationService::whereIn('id', $serviceIds)->get()->keyBy('id');
                foreach ($consultation->bookingServices as $bookingService) {
                    if ($bookingService->service_id && isset($services[$bookingService->service_id])) {
                        $bookingService->consultation_service = $services[$bookingService->service_id];
                    }
                }
            }
        }

        // Get all appointment data for display
        $appointmentData = $this->getAppointmentSummary($user);

        // Calculate total stats for display
        $stats = $this->getAppointmentStats($user);

        return view('front.profile.profile', compact('user', 'appointmentData', 'stats'));
    }

    /**
     * Get formatted appointment summary for profile - UPDATED TO HANDLE GUEST BOOKINGS
     */
    private function getAppointmentSummary($user)
    {
        $invoiceGroups = [];

        // Process Consultation Bookings
        foreach ($user->consultationBookings as $consultation) {
            $invoiceId = $consultation->invoice_id;
            
            // Skip if no invoice (can happen with guest bookings)
            if (!$invoiceId) continue;

            if (!isset($invoiceGroups[$invoiceId])) {
                $invoiceGroups[$invoiceId] = [
                    'invoice' => $consultation->invoice,
                    'items' => [],
                    'created_at' => $consultation->created_at
                ];
            }

            foreach ($consultation->bookingServices as $bookingService) {
                if ($bookingService->event_id && $bookingService->event) {
                    // Event from consultation
                    $invoiceGroups[$invoiceId]['items'][] = [
                        'type' => 'event',
                        'title' => $bookingService->event->title,
                        'participant_count' => $bookingService->participant_count ?? 1,
                        'booking' => $consultation,
                        'source' => 'consultation_booking'
                    ];
                } elseif ($bookingService->service_id) {
                    // Service
                    $serviceTitle = 'Service';
                    if (isset($bookingService->consultation_service)) {
                        $serviceTitle = $bookingService->consultation_service->title;
                    } else {
                        $service = ConsultationService::find($bookingService->service_id);
                        $serviceTitle = $service ? $service->title : 'Service';
                    }

                    $invoiceGroups[$invoiceId]['items'][] = [
                        'type' => 'service',
                        'title' => $serviceTitle,
                        'participant_count' => 1,
                        'booking' => $consultation,
                        'source' => 'consultation_booking'
                    ];
                } elseif ($bookingService->free_consultation_type_id && $bookingService->freeConsultationType) {
                    // Free consultation
                    $invoiceGroups[$invoiceId]['items'][] = [
                        'type' => 'free_consultation',
                        'title' => 'Konsultasi Gratis - ' . $bookingService->freeConsultationType->name,
                        'participant_count' => 1,
                        'booking' => $consultation,
                        'source' => 'consultation_booking'
                    ];
                }
            }
        }

        // Process Standalone Event Bookings
        foreach ($user->eventBookings as $eventBooking) {
            $invoiceId = $eventBooking->invoice_id;
            
            // Skip if no invoice (guest bookings don't have invoices)
            if (!$invoiceId) continue;

            // Skip if this invoice already has items (meaning it was processed above)
            if (isset($invoiceGroups[$invoiceId])) {
                continue;
            }

            $invoiceGroups[$invoiceId] = [
                'invoice' => $eventBooking->invoice,
                'items' => [
                    [
                        'type' => 'event',
                        'title' => $eventBooking->event->title ?? 'Event',
                        'participant_count' => $eventBooking->participant_count,
                        'booking' => $eventBooking,
                        'source' => 'event_booking'
                    ]
                ],
                'created_at' => $eventBooking->created_at
            ];
        }

        // Convert to array and sort by creation date (newest first)
        $groupedAppointments = array_values($invoiceGroups);
        usort($groupedAppointments, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $groupedAppointments;
    }

    /**
     * Get appointment statistics for display - UPDATED FOR GUEST BOOKINGS
     */
    private function getAppointmentStats($user)
    {
        $appointments = $this->getAppointmentSummary($user);

        // Count all items across all invoices
        $totalItems = 0;
        $eventCount = 0;
        $serviceCount = 0;
        $freeConsultationCount = 0;
        $totalAmount = 0;
        $allItems = [];

        foreach ($appointments as $invoiceGroup) {
            // Calculate total amount only if invoice exists and has final_amount
            if ($invoiceGroup['invoice'] && $invoiceGroup['invoice']->final_amount) {
                $totalAmount += $invoiceGroup['invoice']->final_amount;
            }

            foreach ($invoiceGroup['items'] as $item) {
                $totalItems++;

                switch ($item['type']) {
                    case 'event':
                        $eventCount++;
                        break;
                    case 'service':
                        $serviceCount++;
                        break;
                    case 'free_consultation':
                        $freeConsultationCount++;
                        break;
                }

                $displayType = match ($item['type']) {
                    'event' => 'Event',
                    'service' => 'Service',
                    'free_consultation' => 'Konsultasi Gratis',
                    default => $item['type']
                };

                $allItems[] = [
                    'type' => $displayType,
                    'name' => $item['title'],
                    'participants' => $item['participant_count']
                ];
            }
        }

        return [
            'total_items' => $totalItems,
            'event_count' => $eventCount,
            'service_count' => $serviceCount,
            'free_consultation_count' => $freeConsultationCount,
            'total_amount' => $totalAmount,
            'items' => $allItems
        ];
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');

        return view('front.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'phone_number' => 'nullable|string|max:20',
            'social_media' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update user basic info
        $user->name = $request->name;
        $user->save();

        // Update profile info
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        $profile->birthdate = $request->birthdate;
        $profile->gender = $request->gender;
        $profile->phone_number = $request->phone_number;
        $profile->social_media = $request->social_media;
        $profile->description = $request->description;

        // Handle photo upload
        if ($request->has('clear_profile_photo') && $request->clear_profile_photo) {
            if ($profile->profile_photo && Storage::exists('public/' . $profile->profile_photo)) {
                Storage::delete('public/' . $profile->profile_photo);
            }
            $profile->profile_photo = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo && Storage::exists('public/' . $profile->profile_photo)) {
                Storage::delete('public/' . $profile->profile_photo);
            }
            $profile->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    public function bookings()
    {
        $user = auth()->user();

        $bookings = ConsultationBooking::with([
            'services',
            'bookingServices.event',
            'bookingServices.freeConsultationType',
            'invoice'
        ])->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.bookings', compact('bookings'));
    }

    public function eventBookings()
    {
        $user = Auth::user();

        // FIXED: Use direct query if relationship doesn't exist
        $upcomingBookings = EventBooking::where('user_id', $user->id)
            ->with(['event', 'participants', 'invoice'])
            ->whereHas('event', function ($query) {
                $query->where('event_date', '>=', now()->toDateString());
            })
            ->where('booking_status', 'terdaftar')
            ->orderBy('created_at', 'desc')
            ->get();

        $pastBookings = EventBooking::where('user_id', $user->id)
            ->with(['event', 'participants', 'invoice'])
            ->whereHas('event', function ($query) {
                $query->where('event_date', '<', now()->toDateString());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('front.profile.event-bookings', compact('upcomingBookings', 'pastBookings'));
    }
}