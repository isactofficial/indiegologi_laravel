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
