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

class FrontController extends Controller
{
    // ... (method index, articles, showArticle, dll. tidak ada perubahan) ...
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
        return view('front.services.index', compact('services', 'referralCodes'));
    }

    public function addToCart(Request $request)
    {
        // [LANGKAH DEBUGGING] Tampilkan semua data yang masuk dan hentikan kode.
        dd($request->all());

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk menambahkan layanan.'], 401);
        }

        $validatedData = $request->validate([
            'id' => 'required|exists:consultation_services,id',
            'hours' => 'required|integer|min:0',
            'booked_date' => 'required|date',
            'booked_time' => 'required|date_format:H:i',
            'session_type' => 'required|string|in:Online,Offline',
            'offline_address' => 'nullable|string|required_if:session_type,Offline',
            'referral_code' => 'nullable|string|exists:referral_codes,code',
            'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            'payment_type' => 'required|string|in:dp,full_payment',
        ]);

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
                    'payment_type' => $validatedData['payment_type'],
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
            Log::error('Add to cart failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ke keranjang. Silakan periksa kembali data Anda.'
            ], 500);
        }
    }

    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat keranjang.');
        }

        $cartItems = CartItem::with('service')->where('user_id', Auth::id())->get();
        $summary = $this->calculateSummary($cartItems->pluck('id')->toArray());

        return view('front.cart.index', array_merge(
            ['cartItems' => $cartItems],
            $summary
        ));
    }

    public function updateCartSummary(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        $summary = $this->calculateSummary($selectedIds);
        return response()->json($summary);
    }

    private function calculateSummary(array $itemIds)
    {
        $cartItems = CartItem::whereIn('id', $itemIds)->where('user_id', Auth::id())->get();
        $subtotal = 0;
        $totalDiscount = 0;
        $totalToPayNow = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->price + ($item->hourly_price * $item->hours);
            $subtotal += $itemTotal;
            
            $itemDiscount = 0;
            if (!empty($item->referral_code)) {
                $referral = ReferralCode::where('code', $item->referral_code)->first();
                if ($referral) {
                    $itemDiscount = ($itemTotal * $referral->discount_percentage) / 100;
                    $totalDiscount += $itemDiscount;
                }
            }

            $finalItemPrice = $itemTotal - $itemDiscount;
            if ($item->payment_type == 'dp') {
                $totalToPayNow += $finalItemPrice * 0.5;
            } else {
                $totalToPayNow += $finalItemPrice;
            }
        }
        $grandTotal = $subtotal - $totalDiscount;

        return [
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'totalDiscount' => number_format($totalDiscount, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
            'totalToPayNow' => number_format($totalToPayNow, 0, ',', '.'),
        ];
    }

    public function removeFromCart(Request $request)
    {
        $request->validate(['id' => 'required|exists:cart_items,id']);
        
        $cartItem = CartItem::where('id', $request->id)
                            ->where('user_id', Auth::id())
                            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Layanan berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan.');
    }

    public function contact()
    {
        return view('front.contact');
    }
}
