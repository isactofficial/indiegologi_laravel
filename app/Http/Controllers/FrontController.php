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
        $articles = Article::where('status', 'published')->latest()->paginate(6);
        return view('front.articles', compact('articles'));
    }

    public function showArticle(Article $article)
    {
        // Tampilkan artikel berdasarkan slug
        // Tambahkan logika untuk menambah views
        $article->increment('views');
        return view('front.articles', compact('article'));
    }

    public function sketch()
    {
        // Ambil semua sketsa yang sudah diterbitkan
        $sketches = sketch::where('status', 'published')->latest()->paginate(6);
        return view('front.sketch', compact('sketches'));
    }

    public function showDetail(sketch $sketch)
    {
        // Tampilkan sketsa berdasarkan slug
        // Tambahkan logika untuk menambah views
        $sketch->increment('views');
        return view('front.sketch_detail', compact('sketch'));
    }

    public function layanan()
    {
        // Ambil semua layanan
        $services = ConsultationService::latest()->paginate(6);
        return view('front.layanan', compact('layanan'));
    }

    public function contact()
    {
        return view('front.contact');
    }

}
