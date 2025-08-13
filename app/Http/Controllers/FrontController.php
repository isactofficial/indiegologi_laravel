<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    // ... (method index, articles, showArticle, dll. tidak ada perubahan) ...
    public function index()
    {
        $latest_articles = Article::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $popular_articles = Article::where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();

        $latest_sketches = Sketch::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $services = ConsultationService::take(3)->get();

        return view('front.index', compact(
            'latest_articles',
            'popular_articles',
            'latest_sketches',
            'services'
        ));
    }

    public function articles()
    {
        $articles = Article::where('status', 'published')
            ->latest()
            ->paginate(6);

        return view('front.articles', compact('articles'));
    }

    public function showArticle(Article $article)
    {
        $article->increment('views');
        return view('front.article_show', compact('article'));
    }

    public function sketches_show()
    {
        $sketches = Sketch::where('status', 'published')
            ->latest()
            ->paginate(6);
        return view('front.sketch', compact('sketches'));
    }

    public function showDetail(Sketch $sketch)
    {
        $sketch->increment('views');
        return view('front.sketch_detail', compact('sketch'));
    }

    public function layanan()
    {
        $services = ConsultationService::whereIn('status', ['published', 'special'])
            ->latest()
            ->paginate(6);
            
        $referralCodes = ReferralCode::all();

        return view('front.services.index', compact('services', 'referralCodes'));
    }

    public function addToCart(Request $request)
    {
        // ... (method ini tidak ada perubahan)
    }

    // [INI PERBAIKAN UTAMANYA]
    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat keranjang.');
        }

        $cartItems = CartItem::with('service')
            ->where('user_id', Auth::id())
            ->get();
        
        $subtotal = 0;
        $totalDiscount = 0;
        $totalToPayNow = 0;

        // Loop ini sekarang menambahkan properti 'item_total' ke setiap item
        foreach ($cartItems as $item) {
            $itemTotal = $item->price + ($item->hourly_price * $item->hours);
            $item->item_total = $itemTotal; // <- KUNCI PERBAIKANNYA ADA DI SINI
            $subtotal += $itemTotal;
            
            $itemDiscount = 0;
            if (!empty($item->referral_code)) {
                $referral = ReferralCode::where('code', $item->referral_code)->first();
                if ($referral) {
                    $itemDiscount = ($itemTotal * $referral->discount_percentage) / 100;
                    $item->discount_amount = $itemDiscount;
                    $totalDiscount += $itemDiscount;
                }
            } else {
                $item->discount_amount = 0;
            }

            $finalItemPrice = $itemTotal - $itemDiscount;
            if ($item->payment_type == 'dp') {
                $totalToPayNow += $finalItemPrice * 0.5;
            } else {
                $totalToPayNow += $finalItemPrice;
            }
        }

        $grandTotal = $subtotal - $totalDiscount;

        return view('front.cart.index', compact('cartItems', 'subtotal', 'totalDiscount', 'grandTotal', 'totalToPayNow'));
    }

    // [BARU] Method untuk kalkulasi dinamis via AJAX
    public function updateCartSummary(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        $summary = $this->calculateSummary($selectedIds);
        return response()->json($summary);
    }

    // [BARU] Fungsi helper untuk kalkulasi agar tidak duplikat kode
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
