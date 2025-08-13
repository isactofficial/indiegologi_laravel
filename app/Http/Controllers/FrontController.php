<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Pastikan Carbon diimpor untuk validasi tanggal

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

        // Hitung cart_count di sini sebelum view dirender, untuk menghindari error di layout
        $cartCount = 0;
        if (Auth::check()) {
            try {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
            } catch (\Exception $e) {
                // Log error jika tabel belum siap, tapi jangan sampai crash halaman
                Log::warning('Could not count cart items for user ' . Auth::id() . ': ' . $e->getMessage());
                $cartCount = 0; // Set default ke 0 jika ada error
            }
        }

        return view('front.services.index', compact('services', 'referralCodes', 'cartCount'));
    }

    public function contact()
    {
        return view('front.contact');
    }

    // --- Metode Keranjang Belanja ---

    /**
     * Menambahkan layanan ke keranjang belanja.
     * Menghapus validasi dan pengisian payment_type karena akan dipilih di halaman keranjang.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            // 'payment_type' dihapus dari validasi karena akan dipilih di halaman keranjang
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
                    // 'payment_type' tidak lagi diatur di sini
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
     * Memuat item keranjang dan menghitung ringkasan awal.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat keranjang.');
        }

        $userId = Auth::id();
        $cartItems = CartItem::with(['service', 'referralCode'])
                             ->where('user_id', $userId)
                             ->get();

        // Siapkan summary awal dengan asumsi 'full_payment' sebagai default
        $summary = $this->calculateSummary($cartItems->pluck('id')->toArray(), 'full_payment');

        return view('front.cart.index', array_merge([
            'cartItems' => $cartItems,
        ], $summary));
    }

    /**
     * Memperbarui ringkasan keranjang belanja melalui permintaan AJAX.
     * Menerima juga payment_type dari request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCartSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'nullable|array',
            'ids.*' => 'exists:cart_items,id',
            'payment_type' => 'nullable|string|in:full_payment,dp', // Validasi untuk payment_type
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item IDs or payment type provided.',
                'errors' => $validator->errors()
            ], 422);
        }

        $selectedIds = $request->input('ids', []);
        $paymentType = $request->input('payment_type', 'full_payment'); // Ambil payment_type dari request, default full_payment

        $summary = $this->calculateSummary($selectedIds, $paymentType);
        return response()->json($summary);
    }

    /**
     * Menghapus item dari keranjang belanja.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Menerima payment_type global untuk menghitung total yang harus dibayar sekarang.
     *
     * @param array $itemIds Array of CartItem IDs
     * @param string $globalPaymentType Tipe pembayaran global ('full_payment' atau 'dp')
     * @return array Formatted summary data (subtotal, totalDiscount, grandTotal, totalToPayNow)
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
        $grandTotalBeforePaymentType = 0.0; // Total sebelum diterapkan payment_type (DP/Full)

        foreach ($cartItems as $item) {
            $subtotal += $item->item_subtotal;
            $totalDiscount += $item->discount_amount;
            $grandTotalBeforePaymentType += $item->final_item_price;
        }

        $grandTotal = $subtotal - $totalDiscount; // Ini adalah Grand Total akhir
        $totalToPayNow = $grandTotal; // Default ke pembayaran penuh

        if ($globalPaymentType == 'dp') {
            $totalToPayNow = $grandTotal * 0.5;
        }

        return $this->formatSummary($subtotal, $totalDiscount, $grandTotal, $totalToPayNow);
    }

    /**
     * Helper: Memformat nilai mata uang menjadi format Rupiah.
     *
     * @param float $subtotal
     * @param float $totalDiscount
     * @param float $grandTotal
     * @param float $totalToPayNow
     * @return array
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
}
