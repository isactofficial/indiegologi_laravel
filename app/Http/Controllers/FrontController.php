<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\ConsultationService; // Pastikan Model Layanan di-import

class FrontController extends Controller
{
    public function index()
    {
        // Ambil 6 artikel terbaru
        $latest_articles = Article::where('status', 'published')
                                ->latest()
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
        return view('front.index', compact('latest_articles', 'latest_sketches', 'services'));
    }
}