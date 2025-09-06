<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\CartItem;
use App\Models\BookingService;
use App\Models\Invoice;
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
        $latest_sketches = Sketch::where('status', 'published')->latest()->take(6)->get();
        $services = ConsultationService::take(3)->get();
        return view('front.index', compact('latest_articles', 'popular_articles', 'latest_sketches', 'services'));
    }

    public function articles()
    {
        $articles = Article::where('status', 'published')->latest()->paginate(6);
        return view('front.articles', compact('articles'));
    }

    public function showArticle(Article $article)
    {
        $article->increment('views');
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

        $cartCount = 0;
        if (Auth::check()) {
            try {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
            } catch (\Exception $e) {
                Log::warning('Could not count cart items for user ' . Auth::id() . ': ' . $e->getMessage());
                $cartCount = 0;
            }
        }

        return view('front.services.index', compact('services', 'referralCodes', 'cartCount'));
    }

    public function contact()
    {
        return view('front.contact');
    }

        /**
     * Menampilkan halaman detail untuk satu layanan.
     *
     * @param \App\Models\ConsultationService $service
     * @return \Illuminate\View\View
     */
    public function showLayanan(ConsultationService $service)
    {
        // Dari struktur foldermu, view-nya sepertinya ada di 'front.layanan_show'
        // Jika nama file-nya beda, sesuaikan di sini.
        return view('front.layanan_show', compact('service'));
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
            'service_id' => 'required|exists:consultation_services,id',
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

        $service = ConsultationService::find($serviceId);
        if (!$service) {
            return response()->json([
                'available' => false,
                'message' => 'Layanan tidak ditemukan.'
            ], 404);
        }

        $existingBookings = BookingService::where('service_id', $serviceId)
            ->whereHas('invoice', function ($query) {
                $query->where('payment_status', 'paid')
                    ->orWhere(function ($query) {
                        $query->whereIn('payment_status', ['unpaid', 'pending'])
                            ->where('due_date', '>=', now());
                    });
            })
            ->get();

        foreach ($existingBookings as $booking) {
            try {
                $existingStart = Carbon::parse("{$booking->booked_date} {$booking->booked_time}");
            } catch (\Exception $e) {
                Log::warning("Failed to parse existing booking start time for booking ID {$booking->id}: {$booking->booked_date} {$booking->booked_time} - " . $e->getMessage());
                continue;
            }

            $existingEnd = $existingStart->copy()->addHours($booking->hours_booked);

            if ($requestedStart->lt($existingEnd) && $existingStart->lt($requestedEnd)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Jadwal yang Anda pilih sudah terisi. Silakan pilih waktu lain.'
                ], 409);
            }
        }

        return response()->json([
            'available' => true,
            'message' => 'Jadwal tersedia.'
        ]);
    }

    // --- Metode Keranjang Belanja ---

    /**
     * Menambahkan layanan ke keranjang belanja.
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk menambahkan layanan.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:consultation_services,id',
            'hours' => 'required|integer|min:0',
            'booked_date' => 'required|date',
            'booked_time' => 'required|date_format:H:i',
            'session_type' => 'required|string|in:Online,Offline',
            'offline_address' => 'nullable|string',
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
                    'hourly_price' => $service->hourly_price,
                    'quantity' => 1,
                    'hours' => (int)$validatedData['hours'],
                    'booked_date' => $validatedData['booked_date'],
                    'booked_time' => $validatedData['booked_time'],
                    'session_type' => $validatedData['session_type'],
                    'offline_address' => $validatedData['offline_address'] ?? null,
                    'contact_preference' => $validatedData['contact_preference'],
                    'referral_code' => $validatedData['referral_code'] ?? null,
                ]
            );

            $cartCount = CartItem::where('user_id', $user->id)->count();

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            Log::error('Add to cart failed: ' . $e->getMessage() . ' - Data: ' . json_encode($validatedData));
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan layanan ke keranjang. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman keranjang belanja pengguna.
     */
    public function viewCart()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = CartItem::with(['service', 'referralCode'])
                                      ->where('user_id', $userId)
                                      ->get();
            $summary = $this->calculateSummary($cartItems->pluck('id')->toArray(), 'full_payment');

            return view('front.cart.index', array_merge([
                'cartItems' => $cartItems,
            ], $summary));
        }

        $cartItems = collect();
        $summary = $this->formatSummary(0, 0, 0, 0);

        return view('front.cart.index', compact('cartItems', 'summary'));
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
     * Menghapus item dari keranjang belanja.
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
            $cartItem->delete();
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

        $cartItems = CartItem::with('referralCode')
                            ->whereIn('id', $itemIds)
                            ->where('user_id', $userId)
                            ->get();

        $subtotal = 0.0;
        $totalDiscount = 0.0;

        foreach ($cartItems as $item) {
            $subtotal += $item->item_subtotal;
            $totalDiscount += $item->discount_amount;
        }

        $grandTotal = $subtotal - $totalDiscount;
        $totalToPayNow = $grandTotal;

        if ($globalPaymentType == 'dp') {
            $totalToPayNow = $grandTotal * 0.5;
        }

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