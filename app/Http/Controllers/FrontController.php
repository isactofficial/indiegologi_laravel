<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\CartItem;
use App\Models\BookingService;
use App\Models\Invoice;
use App\Models\Testimonial;
use App\Models\FreeConsultationType;
use App\Models\FreeConsultationSchedule;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FrontController extends Controller
{
    // --- Metode Halaman Depan Umum ---
    public function index()
    {
        $latest_articles = Article::where('status', 'published')->latest()->take(6)->get();
        $popular_articles = Article::where('status', 'published')->orderBy('views', 'desc')->take(6)->get();
        $latest_sketches = Sketch::where('status', 'published')->latest()->get();
        $services = ConsultationService::take(3)->get();

        // Tambahkan data testimoni dari database
        $testimonials = Testimonial::active()
            ->ordered()
            ->get();



        // --- TAMBAHKAN KODE INI ---
        // Mengambil semua jenis konsultasi gratis yang statusnya 'active'
        // dan memuat relasi 'availableSchedules' secara efisien (Eager Loading).
        $freeConsultationTypes = FreeConsultationType::with('availableSchedules')
            ->active() // Menggunakan scopeActive() dari model
            ->get();
        // -------------------------

        $featured_event = Event::where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->first();

        // --- UBAH BARIS RETURN ---
        return view('front.index', compact(
            'latest_articles',
            'popular_articles',
            'latest_sketches',
            'services',
            'testimonials',
            'freeConsultationTypes',
            'featured_event'
        ));
    }

    public function sketchShow(Sketch $sketch)
    {
        // Ubah dari 'front.sketch_show' menjadi 'front.sketch_detail'
        return view('front.sketch_detail', [
            'sketch' => $sketch
        ]);
    }

    /**
     * Display articles for frontend with popular article highlight
     */
    public function articles(Request $request)
    {
        $filter = $request->query('filter', '');

        $articlesQuery = Article::whereIn('status', ['Published', 'published']);

        // Apply filtering
        switch ($filter) {
            case 'latest':
                $articlesQuery->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $articlesQuery->orderBy('views', 'desc');
                break;
            case 'author':
                $articlesQuery->orderBy('author', 'asc');
                break;
            default:
                $articlesQuery->orderBy('created_at', 'desc');
                break;
        }

        // Get the most popular article first
        $popularArticle = Article::whereIn('status', ['Published', 'published'])
            ->orderBy('views', 'desc')
            ->first();

        // Get paginated articles (exclude popular article if it exists to avoid duplication)
        if ($popularArticle) {
            $articlesQuery->where('id', '!=', $popularArticle->id);
        }

        $articles = $articlesQuery->paginate(6)->withQueryString();

        return view('front.articles', compact('articles', 'popularArticle'));
    }

    /**
     * Display a single article and increment view count
     */
    public function showArticle(Article $article)
    {
        // Only show published articles (case-sensitive check)
        if (strtolower($article->status) !== 'published') {
            abort(404);
        }

        // Increment views count
        $article->increment('views');

        // Eager load relationships for better performance
        $article->load([
            'subheadings.paragraphs' => function ($query) {
                $query->orderBy('order_number');
            },
            'comments.user' => function ($query) {
                $query->latest();
            }
        ]);

        return view('front.article_show', compact('article'));
    }

    public function sketches_show()
    {
        $sketches = Sketch::where('status', 'published')->latest()->paginate(6);
        return view('front.sketch', compact('sketches'));
    }

    public function showDetail(Sketch $sketch)
    {
        $sketch->increment('views');
        return view('front.sketch_detail', compact('sketch'));
    }

    public function layanan()
    {
        $services = ConsultationService::whereIn('status', ['published', 'special'])->latest()->paginate(6);
        $referralCodes = ReferralCode::all();

        // Get free consultation types with their available schedules
        $freeConsultationTypes = FreeConsultationType::active()
            ->with(['availableSchedules'])
            ->get();

        $cartCount = 0;
        if (Auth::check()) {
            try {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
            } catch (\Exception $e) {
                Log::warning('Could not count cart items for user ' . Auth::id() . ': ' . $e->getMessage());
                $cartCount = 0;
            }
        }

        return view('front.services.index', compact('services', 'referralCodes', 'cartCount', 'freeConsultationTypes'));
    }

    public function contact()
    {
        return view('front.contact');
    }

    /**
     * Menampilkan halaman detail untuk satu layanan.
     */
    public function showLayanan(ConsultationService $service)
    {
        return view('front.layanan_show', compact('service'));
    }

    /**
     * NEW: Get service pricing for guest cart calculations
     */
    public function getServicePricing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_ids' => 'required|array',
            'service_ids.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid service IDs',
                'errors' => $validator->errors()
            ], 422);
        }

        $serviceIds = $request->input('service_ids');
        $pricing = [];

        foreach ($serviceIds as $serviceId) {
            if ($serviceId === 'free-consultation') {
                $pricing['free-consultation'] = [
                    'title' => 'Konsultasi Gratis',
                    'price' => 0,
                    'hourly_price' => 0,
                    'thumbnail' => 'https://placehold.co/60x60/D4AF37/ffffff?text=Gratis'
                ];
            } else {
                $service = ConsultationService::find($serviceId);
                if ($service) {
                    $pricing[$serviceId] = [
                        'title' => $service->title,
                        'price' => (float) $service->price,
                        'hourly_price' => (float) ($service->hourly_price ?? 0),
                        'thumbnail' => $service->thumbnail ? asset('storage/' . $service->thumbnail) : 'https://placehold.co/60x60/cccccc/ffffff?text=No+Image'
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'pricing' => $pricing
        ]);
    }

    /**
     * Get available schedules for a specific free consultation type
     */
    public function getFreeConsultationSchedules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:free_consultation_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid consultation type',
                'errors' => $validator->errors()
            ], 422);
        }

        $typeId = $request->input('type_id');

        $schedules = FreeConsultationSchedule::forType($typeId)
            ->available()
            ->future()
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'date' => $schedule->scheduled_date->format('Y-m-d'),
                    'time' => $schedule->scheduled_time->format('H:i'),
                    'formatted_datetime' => $schedule->formatted_date_time,
                    'available_slots' => $schedule->max_participants - $schedule->current_bookings
                ];
            });

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    /**
     * Check availability for new free consultation booking
     */
    public function checkFreeConsultationAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:free_consultation_schedules,id',
            'type_id' => 'required|exists:free_consultation_types,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'Invalid schedule or consultation type.',
                'errors' => $validator->errors()
            ], 422);
        }

        $scheduleId = $request->input('schedule_id');
        $typeId = $request->input('type_id');

        $schedule = FreeConsultationSchedule::where('id', $scheduleId)
            ->where('type_id', $typeId)
            ->first();

        if (!$schedule) {
            return response()->json([
                'available' => false,
                'message' => 'Jadwal tidak ditemukan.'
            ], 404);
        }

        if (!$schedule->isAvailable()) {
            return response()->json([
                'available' => false,
                'message' => 'Jadwal sudah tidak tersedia. Silakan pilih jadwal lain.'
            ], 409);
        }

        // Check if user already has this type of consultation in cart
        if (Auth::check()) {
            $existingCartItem = CartItem::where('user_id', Auth::id())
                ->where('free_consultation_type_id', $typeId)
                ->first();

            if ($existingCartItem) {
                return response()->json([
                    'available' => false,
                    'message' => 'Anda sudah memiliki konsultasi gratis jenis ini di keranjang.'
                ], 409);
            }
        }

        return response()->json([
            'available' => true,
            'message' => 'Jadwal tersedia.',
            'schedule' => [
                'id' => $schedule->id,
                'formatted_datetime' => $schedule->formatted_date_time,
                'available_slots' => $schedule->max_participants - $schedule->current_bookings
            ]
        ]);
    }

    /**
     * Memeriksa ketersediaan jadwal layanan.
     */
    public function checkBookingAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booked_date' => 'required|date_format:Y-m-d',
            'booked_time' => 'required|date_format:H:i',
            'hours_booked' => 'required|integer|min:0',
            'service_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'Validasi input jadwal gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $bookedDate = $request->input('booked_date');
        $bookedTime = $request->input('booked_time');
        $hoursBooked = (int) $request->input('hours_booked');
        $serviceId = $request->input('service_id');

        try {
            $requestedStart = Carbon::parse("{$bookedDate} {$bookedTime}");
        } catch (\Exception $e) {
            Log::error("Failed to parse requested start time: {$bookedDate} {$bookedTime} - " . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Format tanggal atau jam tidak valid.'
            ], 422);
        }

        $requestedEnd = $requestedStart->copy()->addHours($hoursBooked);

        // Handle free consultation separately
        if ($serviceId === 'free-consultation') {
            // Check for existing free consultation bookings in cart_items first
            $existingCartBooking = CartItem::where('service_id', 'free-consultation')
                ->where('booked_date', $bookedDate)
                ->where('booked_time', $bookedTime)
                ->exists();

            if ($existingCartBooking) {
                return response()->json([
                    'available' => false,
                    'message' => 'Jadwal konsultasi gratis yang Anda pilih sudah terisi. Silakan pilih waktu lain.'
                ], 409);
            }

            return response()->json([
                'available' => true,
                'message' => 'Jadwal konsultasi gratis tersedia.'
            ]);
        }

        // Handle regular services
        $service = ConsultationService::find($serviceId);
        if (!$service) {
            return response()->json([
                'available' => false,
                'message' => 'Layanan tidak ditemukan.'
            ], 404);
        }

        // Check existing bookings in cart_items and booking_service tables
        $existingCartBookings = CartItem::where('service_id', $serviceId)
            ->where('booked_date', $bookedDate)
            ->get();

        foreach ($existingCartBookings as $booking) {
            try {
                $existingStart = Carbon::parse("{$booking->booked_date} {$booking->booked_time}");
                $existingEnd = $existingStart->copy()->addHours($booking->hours);

                if ($requestedStart->lt($existingEnd) && $existingStart->lt($requestedEnd)) {
                    return response()->json([
                        'available' => false,
                        'message' => 'Jadwal yang Anda pilih sudah terisi. Silakan pilih waktu lain.'
                    ], 409);
                }
            } catch (\Exception $e) {
                Log::warning("Failed to parse cart booking time: " . $e->getMessage());
                continue;
            }
        }

        return response()->json([
            'available' => true,
            'message' => 'Jadwal tersedia.'
        ]);
    }

    // --- Metode Keranjang Belanja ---

    /**
     * Updated addToCart method to support new free consultation system
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk menambahkan layanan.'], 401);
        }

        // Handle new free consultation system
        if ($request->input('consultation_type') === 'free-consultation-new') {
            $validator = Validator::make($request->all(), [
                'free_consultation_type_id' => 'required|exists:free_consultation_types,id',
                'free_consultation_schedule_id' => 'required|exists:free_consultation_schedules,id',
                'session_type' => 'required|string|in:Online,Offline',
                'offline_address' => 'required_if:session_type,Offline|nullable|string',
                'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Pastikan semua kolom terisi dengan benar.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            try {
                $user = Auth::user();
                $typeId = $validatedData['free_consultation_type_id'];
                $scheduleId = $validatedData['free_consultation_schedule_id'];

                // Verify schedule availability
                $schedule = FreeConsultationSchedule::where('id', $scheduleId)
                    ->where('type_id', $typeId)
                    ->first();

                if (!$schedule || !$schedule->isAvailable()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jadwal yang dipilih sudah tidak tersedia.'
                    ], 422);
                }

                // Check if user already has this type of consultation
                $existingCartItem = CartItem::where('user_id', $user->id)
                    ->where('free_consultation_type_id', $typeId)
                    ->first();

                if ($existingCartItem) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah memiliki konsultasi gratis jenis ini di keranjang.'
                    ], 422);
                }

                // Create new free consultation cart item
                CartItem::create([
                    'user_id' => $user->id,
                    'service_id' => null, // No service_id for new system
                    'free_consultation_type_id' => $typeId,
                    'free_consultation_schedule_id' => $scheduleId,
                    'price' => 0,
                    'hourly_price' => 0,
                    'quantity' => 1,
                    'hours' => 1,
                    'booked_date' => $schedule->scheduled_date,
                    'booked_time' => $schedule->scheduled_time,
                    'session_type' => $validatedData['session_type'],
                    'offline_address' => $validatedData['offline_address'] ?? null,
                    'contact_preference' => $validatedData['contact_preference'],
                    'referral_code' => null,
                    'payment_type' => 'full_payment'
                ]);

                // Reserve the schedule slot
                $schedule->incrementBooking();

                $cartCount = CartItem::where('user_id', $user->id)->count();

                Log::info('New free consultation added to cart for user: ' . $user->id, [
                    'type_id' => $typeId,
                    'schedule_id' => $scheduleId,
                    'session_type' => $validatedData['session_type']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Konsultasi gratis berhasil ditambahkan ke keranjang!',
                    'cart_count' => $cartCount
                ]);
            } catch (\Exception $e) {
                Log::error('Add new free consultation to cart failed: ' . $e->getMessage(), [
                    'user_id' => Auth::id(),
                    'data' => $validatedData
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan konsultasi gratis ke keranjang. Detail: ' . $e->getMessage()
                ], 500);
            }
        }

        // Handle legacy free consultation
        if ($request->input('id') === 'free-consultation') {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string',
                'hours' => 'required|integer|min:1|max:1',
                'booked_date' => 'required|date|after:today',
                'booked_time' => 'required|date_format:H:i',
                'session_type' => 'required|string|in:Online,Offline',
                'offline_address' => 'required_if:session_type,Offline|nullable|string',
                'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal. Pastikan semua kolom terisi dengan benar.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            try {
                $user = Auth::user();

                // Check if user already has a free consultation in cart
                $existingFreeConsultation = CartItem::where('user_id', $user->id)
                    ->where('service_id', 'free-consultation')
                    ->first();

                if ($existingFreeConsultation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah memiliki konsultasi gratis di keranjang. Setiap pengguna hanya bisa mendapatkan satu sesi konsultasi gratis.'
                    ], 422);
                }

                // Create free consultation cart item
                CartItem::create([
                    'user_id' => $user->id,
                    'service_id' => 'free-consultation',
                    'price' => 0,
                    'hourly_price' => 0,
                    'quantity' => 1,
                    'hours' => 1,
                    'booked_date' => $validatedData['booked_date'],
                    'booked_time' => $validatedData['booked_time'],
                    'session_type' => $validatedData['session_type'],
                    'offline_address' => $validatedData['offline_address'] ?? null,
                    'contact_preference' => $validatedData['contact_preference'],
                    'referral_code' => null,
                    'payment_type' => 'full_payment'
                ]);

                $cartCount = CartItem::where('user_id', $user->id)->count();

                Log::info('Free consultation added to cart for user: ' . $user->id, [
                    'booked_date' => $validatedData['booked_date'],
                    'booked_time' => $validatedData['booked_time'],
                    'session_type' => $validatedData['session_type']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Konsultasi gratis berhasil ditambahkan ke keranjang!',
                    'cart_count' => $cartCount
                ]);
            } catch (\Exception $e) {
                Log::error('Add free consultation to cart failed: ' . $e->getMessage(), [
                    'user_id' => Auth::id(),
                    'data' => $validatedData
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan konsultasi gratis ke keranjang. Detail: ' . $e->getMessage()
                ], 500);
            }
        }

        // Handle regular services
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:consultation_services,id',
            'hours' => 'required|integer|min:1',
            'booked_date' => 'required|date|after:today',
            'booked_time' => 'required|date_format:H:i',
            'session_type' => 'required|string|in:Online,Offline',
            'offline_address' => 'required_if:session_type,Offline|nullable|string',
            'referral_code' => 'nullable|string|exists:referral_codes,code',
            'contact_preference' => 'required|string|in:chat_only,chat_and_call',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Pastikan semua kolom terisi dengan benar.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            $service = ConsultationService::find($validatedData['id']);
            $user = Auth::user();

            CartItem::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'service_id' => $service->id,
                ],
                [
                    'price' => $service->price,
                    'hourly_price' => $service->hourly_price ?? 0,
                    'quantity' => 1,
                    'hours' => (int)$validatedData['hours'],
                    'booked_date' => $validatedData['booked_date'],
                    'booked_time' => $validatedData['booked_time'],
                    'session_type' => $validatedData['session_type'],
                    'offline_address' => $validatedData['offline_address'] ?? null,
                    'contact_preference' => $validatedData['contact_preference'],
                    'referral_code' => $validatedData['referral_code'] ?? null,
                    'payment_type' => 'full_payment'
                ]
            );

            $cartCount = CartItem::where('user_id', $user->id)->count();

            Log::info('Service added to cart for user: ' . $user->id, [
                'service_id' => $service->id,
                'service_title' => $service->title,
                'hours' => $validatedData['hours']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            Log::error('Add to cart failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $validatedData
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan layanan ke keranjang. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transfer temp cart to database when user logs in
     */
    public function transferTempCart()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        try {
            $user = Auth::user();
            $tempCartData = session('temp_cart', []);

            if (empty($tempCartData)) {
                return response()->json(['success' => true, 'message' => 'No temp cart to transfer']);
            }

            $transferredCount = 0;

            foreach ($tempCartData as $serviceId => $itemData) {
                try {
                    // Handle free consultation
                    if ($serviceId === 'free-consultation') {
                        // Check if user already has free consultation
                        $existing = CartItem::where('user_id', $user->id)
                            ->where('service_id', 'free-consultation')
                            ->first();

                        if (!$existing) {
                            CartItem::create([
                                'user_id' => $user->id,
                                'service_id' => 'free-consultation',
                                'price' => 0,
                                'hourly_price' => 0,
                                'quantity' => 1,
                                'hours' => 1,
                                'booked_date' => $itemData['booked_date'],
                                'booked_time' => $itemData['booked_time'],
                                'session_type' => $itemData['session_type'],
                                'offline_address' => $itemData['offline_address'] ?? null,
                                'contact_preference' => $itemData['contact_preference'],
                                'referral_code' => null,
                                'payment_type' => 'full_payment'
                            ]);
                            $transferredCount++;
                        }
                    } else {
                        // Handle regular services
                        $service = ConsultationService::find($serviceId);
                        if ($service) {
                            CartItem::updateOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'service_id' => $service->id,
                                ],
                                [
                                    'price' => $service->price,
                                    'hourly_price' => $service->hourly_price ?? 0,
                                    'quantity' => 1,
                                    'hours' => (int)($itemData['hours'] ?? 1),
                                    'booked_date' => $itemData['booked_date'],
                                    'booked_time' => $itemData['booked_time'],
                                    'session_type' => $itemData['session_type'],
                                    'offline_address' => $itemData['offline_address'] ?? null,
                                    'contact_preference' => $itemData['contact_preference'],
                                    'referral_code' => $itemData['referral_code'] ?? null,
                                    'payment_type' => 'full_payment'
                                ]
                            );
                            $transferredCount++;
                        }
                    }
                } catch (\Exception $itemException) {
                    Log::error('Failed to transfer individual cart item: ' . $itemException->getMessage(), [
                        'service_id' => $serviceId,
                        'user_id' => $user->id,
                        'item_data' => $itemData
                    ]);
                }
            }

            // Clear temp cart from session
            session()->forget('temp_cart');

            $cartCount = CartItem::where('user_id', $user->id)->count();

            Log::info('Temp cart transfer completed', [
                'user_id' => $user->id,
                'transferred_items' => $transferredCount,
                'total_cart_count' => $cartCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Temp cart transferred successfully',
                'cart_count' => $cartCount,
                'transferred_items' => $transferredCount
            ]);
        } catch (\Exception $e) {
            Log::error('Transfer temp cart failed: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to transfer temp cart'
            ], 500);
        }
    }

    /**
     * Menampilkan halaman keranjang belanja pengguna.
     */
    public function viewCart()
    {
        $user = Auth::user();

        if (!$user) {
            // Guest users see empty cart with temp cart handling in JS
            return view('front.cart.index', [
                'cartItems' => collect([]),
                'subtotal' => '0',
                'totalDiscount' => '0',
                'grandTotal' => '0',
                'totalToPayNow' => '0'
            ]);
        }

        $cartItems = CartItem::with(['service', 'referralCode', 'event', 'participantData'])
            ->where('user_id', $user->id)
            ->get();

        // ✅ Calculate summary correctly
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($cartItems as $item) {
            // Calculate subtotal BEFORE discount
            $itemSubtotal = $item->calculateOriginalSubtotal();
            $itemDiscount = $item->getDiscountAmount();

            $subtotal += $itemSubtotal;
            $totalDiscount += $itemDiscount;

            Log::info('Cart item loaded', [
                'item_id' => $item->id,
                'item_type' => $item->item_type,
                'is_event' => $item->isEvent(),
                'original_price' => $item->original_price,
                'participant_count' => $item->participant_count,
                'hours' => $item->hours,
                'hourly_price' => $item->hourly_price,
                'discount_amount' => $item->discount_amount,
                'item_subtotal' => $itemSubtotal,
                'item_discount' => $itemDiscount
            ]);
        }

        $grandTotal = $subtotal - $totalDiscount;
        $totalToPayNow = $grandTotal; // Default to full payment

        Log::info('Cart view loaded', [
            'user_id' => $user->id,
            'item_count' => $cartItems->count(),
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal
        ]);

        return view('front.cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'totalDiscount' => number_format($totalDiscount, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
            'totalToPayNow' => number_format($totalToPayNow, 0, ',', '.')
        ]);
    }

    /**
     * Memperbarui ringkasan keranjang belanja melalui permintaan AJAX.
     */
    public function updateCartSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'nullable|array',
            'ids.*' => 'exists:cart_items,id',
            'payment_type' => 'nullable|string|in:full_payment,dp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item IDs or payment type provided.',
                'errors' => $validator->errors()
            ], 422);
        }

        $selectedIds = $request->input('ids', []);
        $paymentType = $request->input('payment_type', 'full_payment');

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini.'
            ], 401);
        }

        $summary = $this->calculateSummary($selectedIds, $paymentType);
        return response()->json($summary);
    }

    /**
     * Updated removeFromCart to handle new free consultation system
     */
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Gagal menghapus item: ' . $validator->errors()->first());
        }

        $cartItem = CartItem::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cartItem) {
            $itemTitle = $cartItem->service_title;

            // If removing new free consultation, release the schedule slot
            if ($cartItem->isNewFreeConsultation() && $cartItem->freeConsultationSchedule) {
                $cartItem->freeConsultationSchedule->decrementBooking();
            }

            $cartItem->delete();

            Log::info('Item removed from cart', [
                'user_id' => Auth::id(),
                'item_title' => $itemTitle,
                'service_id' => $cartItem->service_id,
                'free_consultation_type_id' => $cartItem->free_consultation_type_id
            ]);

            return redirect()->back()->with('success', 'Layanan berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.');
    }

    /**
     * Helper: Menghitung ringkasan total untuk item keranjang yang dipilih.
     */
    private function calculateSummary(array $itemIds, string $globalPaymentType = 'full_payment'): array
    {
        $userId = Auth::id();
        if (!$userId) {
            return $this->formatSummary(0, 0, 0, 0);
        }

        $cartItems = CartItem::with(['referralCode', 'service', 'event'])
            ->whereIn('id', $itemIds)
            ->where('user_id', $userId)
            ->get();

        $subtotal = 0.0;
        $totalDiscount = 0.0;

        foreach ($cartItems as $item) {
            // ✅ FIX: Use methods instead of non-existent accessors
            if ($item->isNewFreeConsultation() || $item->isLegacyFreeConsultation()) {
                // Free consultation has no cost
                $subtotal += 0;
                $totalDiscount += 0;
            } else {
                // ✅ CRITICAL FIX: Use calculateOriginalSubtotal() method
                $itemSubtotal = $item->calculateOriginalSubtotal();
                $itemDiscount = $item->getDiscountAmount();

                $subtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;

                Log::info('Cart summary item calculation', [
                    'item_id' => $item->id,
                    'item_type' => $item->item_type,
                    'is_event' => $item->isEvent(),
                    'original_price' => $item->original_price,
                    'participant_count' => $item->participant_count,
                    'hours' => $item->hours,
                    'item_subtotal' => $itemSubtotal,
                    'item_discount' => $itemDiscount
                ]);
            }
        }

        $grandTotal = $subtotal - $totalDiscount;
        $totalToPayNow = $grandTotal;

        if ($globalPaymentType == 'dp' && $grandTotal > 0) {
            $totalToPayNow = $grandTotal * 0.5;
        }

        Log::info('Cart summary totals', [
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            'payment_type' => $globalPaymentType,
            'total_to_pay_now' => $totalToPayNow
        ]);

        return $this->formatSummary($subtotal, $totalDiscount, $grandTotal, $totalToPayNow);
    }

    /**
     * Helper: Memformat nilai mata uang menjadi format Rupiah.
     */
    private function formatSummary(float $subtotal, float $totalDiscount, float $grandTotal, float $totalToPayNow): array
    {
        return [
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'totalDiscount' => number_format($totalDiscount, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
            'totalToPayNow' => number_format($totalToPayNow, 0, ',', '.'),
            'success' => true,
        ];
    }

    // --- [FUNGSI PENCARIAN BARU YANG TELAH DI-IMPROVE] ---

    /**
     * Menyorot kata kunci dalam teks.
     */
    private function highlightText($text, $query)
    {
        if (empty($text) || empty($query)) {
            return $text;
        }
        return preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark class="bg-warning">$1</mark>', $text);
    }

    /**
     * Menampilkan hasil pencarian berdasarkan query dari pengguna dengan fungsionalitas yang lebih luas.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');

        // 1. Cari di model Article (Judul, Deskripsi, dan Penulis)
        $articles = Article::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        // 2. Cari di model Sketch (Judul, Konten, dan Penulis)
        $sketches = Sketch::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        // 3. Cari di model ConsultationService (Judul Layanan dan Deskripsi)
        $services = ConsultationService::whereIn('status', ['published', 'special'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('product_description', 'like', "%{$query}%");
            })
            ->latest()
            ->get();

        // 4. Proses highlight untuk setiap hasil
        $articles->each(function ($item) use ($query) {
            $item->title = $this->highlightText($item->title, $query);
            $item->description = $this->highlightText(Str::limit(strip_tags($item->description), 150), $query);
        });

        $sketches->each(function ($item) use ($query) {
            $item->title = $this->highlightText($item->title, $query);
            $item->content = $this->highlightText(Str::limit(strip_tags($item->content), 150), $query);
        });

        $services->each(function ($item) use ($query) {
            $item->title = $this->highlightText($item->title, $query);
            $item->short_description = $this->highlightText($item->short_description, $query);
        });

        // 5. Kirim semua hasil ke view
        return view('front.search.results', compact('query', 'articles', 'sketches', 'services'));
    }
}
