<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FrontController extends Controller
{
    public function index()
    {
        // Ambil 6 artikel terbaru
        $latest_articles = Article::where('status', 'published')
                                ->latest()
                                ->take(6)
                                ->get();

        // Ambil 6 artikel terpopuler (berdasarkan views)
        // Pastikan kolom 'views' ada di tabel 'articles'
        $popular_articles = Article::where('status', 'published')
                                ->orderBy('views', 'desc')
                                ->take(6)
                                ->get();

        // Ambil 6 sketsa terbaru
        $latest_sketches = Sketch::where('status', 'published')
                                ->latest()
                                ->take(6)
                                ->get();

        // Ambil 3 layanan
        $services = ConsultationService::take(3)->get();

        // Kirim semua data ke view
        return view('front.index', compact('latest_articles', 'popular_articles', 'latest_sketches', 'services'));
    }

    public function articles()
    {
        // Ambil semua artikel yang sudah diterbitkan
        $articles = Article::where('status', 'published')->latest()->get();
        return view('front.articles', compact('articles'));
    }

    public function showArticle(Article $article)
    {
        // Tampilkan artikel berdasarkan slug
        // Tambahkan logika untuk menambah views
        $article->increment('views');
        return view('front.articles.show', compact('article'));
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function register(Request $request, Event $event)
    {
        // Logika pendaftaran event
        // ...
    }
}
