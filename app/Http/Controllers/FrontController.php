<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService;
use Illuminate\Http\Request;

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

        return view('front.index', compact(
            'latest_articles',
            'popular_articles',
            'latest_sketches',
            'services'
        ));
    }

    public function articles()
    {
        // Ambil semua artikel yang sudah diterbitkan
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

    public function sketch()
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
        $services = ConsultationService::latest()->paginate(6);

        return view('front.layanan', compact('services'));
    }

    public function contact()
    {
        return view('front.contact');
    }
}
