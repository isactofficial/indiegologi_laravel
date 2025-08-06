<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FrontController extends Controller
{
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

    // Menampilkan semua sketsa per halaman
    public function sketches_show()
    {
        $sketches = Sketch::where('status', 'published')
            ->latest()
            ->paginate(6);

        return view('front.sketch', compact('sketches'));
    }

    // Menampilkan detail sketsa berdasarkan slug
    public function showDetail(Sketch $sketch)
    {
        $sketch->increment('views');

        return view('front.sketch_detail', compact('sketch'));
    }

    // Metode untuk halaman daftar layanan
    public function layanan() // <-- Diperbaiki
    {
        $services = ConsultationService::whereIn('status', ['published', 'special'])
            ->latest()
            ->paginate(6);

        $referralCodes = ReferralCode::all();

        return view('front.services.index', compact('services', 'referralCodes'));
    }

    public function showService(ConsultationService $service)
    {
        $referralCodes = ReferralCode::all();
        return view('front.services.show', compact('service', 'referralCodes'));
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk menambahkan layanan ke keranjang.'], 401);
        }

        $validatedData = $request->validate([
            'id' => 'required|exists:consultation_services,id',
            'hours' => 'required|integer|min:0',
            'booked_date' => 'required|date',
            'booked_time' => 'required|date_format:H:i',
            'session_type' => 'required|string|in:Online,Offline',
            'offline_address' => 'nullable|string|required_if:session_type,Offline',
            'referral_code' => 'nullable|string',
            'contact_preference' => 'required|string|in:chat_only,chat_and_call',
            'payment_type' => 'required|string|in:dp,full_payment',
        ]);

        $service = ConsultationService::find($validatedData['id']);
        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan.'], 404);
        }

        $referralCode = null;
        if (!empty($validatedData['referral_code'])) {
            $referralCode = ReferralCode::where('code', Str::upper($validatedData['referral_code']))->first();
            if (!$referralCode) {
                 return response()->json(['success' => false, 'message' => 'Kode referral tidak ditemukan.'], 404);
            }
        }

        $cart = Session::get('cart', []);

        $cartItemId = Str::uuid();

        $itemToAdd = [
            'id' => $service->id,
            'title' => $service->title,
            'price' => $service->price,
            'hourly_price' => $service->hourly_price,
            'hours' => $validatedData['hours'],
            'booked_date' => $validatedData['booked_date'],
            'booked_time' => $validatedData['booked_time'],
            'session_type' => $validatedData['session_type'],
            'offline_address' => $validatedData['offline_address'] ?? null,
            'referral_code' => $validatedData['referral_code'] ?? null,
            'contact_preference' => $validatedData['contact_preference'],
            'payment_type' => $validatedData['payment_type'],
            'referral_code_id' => $referralCode ? $referralCode->id : null,
            'discount_percentage' => $referralCode ? $referralCode->discount_percentage : 0,
        ];

        $cart[$cartItemId] = $itemToAdd;
        Session::put('cart', $cart);

        $cartCount = count($cart);

        return response()->json(['success' => true, 'message' => 'Layanan berhasil ditambahkan ke keranjang!', 'cart_count' => $cartCount]);
    }

    public function viewCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat keranjang.');
        }

        $cart = Session::get('cart', []);
        return view('front.cart.index', compact('cart'));
    }

    public function contact()
    {
        return view('front.contact');
    }
}
