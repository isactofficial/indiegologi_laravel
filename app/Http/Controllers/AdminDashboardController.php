<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ConsultationBooking;
use App\Models\ConsultationService;
use App\Models\PageVisit; // Gunakan model baru
use App\Models\Sketch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Data lama yang sudah ada
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalSketches = Sketch::count();
        $publishedSketches = Sketch::where('status', 'published')->count();
        $totalServices = ConsultationService::count();
        $totalBookings = ConsultationBooking::count();
        $totalUsers = User::count();
        $articles = Article::all();
        $sketches = Sketch::all();

        // [LOGIKA BARU] Menghitung data kunjungan dari tabel page_visits
        $homepageVisits = PageVisit::where('page_name', 'homepage')->count();
        $articleVisits  = PageVisit::where('page_name', 'articles')->count();
        $sketchVisits   = PageVisit::where('page_name', 'sketches')->count();
        $layananVisits  = PageVisit::where('page_name', 'layanan')->count();
        $contactVisits  = PageVisit::where('page_name', 'contact')->count();

        // [LOGIKA BARU] Menghitung data untuk kartu Total Visits
        $totalVisits = PageVisit::count();
        $todayVisits = PageVisit::whereDate('created_at', Carbon::today())->count();
        $weekVisits  = PageVisit::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthVisits = PageVisit::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
        $yearVisits  = PageVisit::whereYear('created_at', Carbon::now()->year)->count();

        return view('dashboard.admin', compact(
            'totalArticles',
            'publishedArticles',
            'totalSketches',
            'publishedSketches',
            'totalServices',
            'totalBookings',
            'totalUsers',
            'articles',
            'sketches',
            // Variabel baru untuk data kunjungan
            'homepageVisits',
            'articleVisits',
            'sketchVisits',
            'layananVisits',
            'contactVisits',
            'totalVisits',
            'todayVisits',
            'weekVisits',
            'monthVisits',
            'yearVisits'
        ));
    }
}