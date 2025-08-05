<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil 3 artikel terbaru yang statusnya 'published'
        $latest_articles = Article::where('status', 'published')
                                ->latest() // Shortcut untuk orderBy('created_at', 'desc')
                                ->take(3)
                                ->get();

        // Kirim hanya data artikel yang dibutuhkan ke view
        return view('front.index', compact('latest_articles'));
    }
}