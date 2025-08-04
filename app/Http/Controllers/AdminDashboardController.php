<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sketch;
use App\Models\User;
use App\Models\ConsultationService;
use App\Models\ConsultationBooking;
use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalUsers = User::count();
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'Published')->count();
        $draftArticles = Article::where('status', 'Draft')->count();

        $totalSketches = Sketch::count();
        $publishedSketches = Sketch::where('status', 'Published')->count();
        $draftSketches = Sketch::where('status', 'Draft')->count();

        $totalServices = ConsultationService::count();
        $totalBookings = ConsultationBooking::count();

        try {
            $todayVisit = Visit::whereDate('visited_at', Carbon::today())->count();
            $weekVisit = Visit::whereBetween('visited_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $monthVisit = Visit::whereMonth('visited_at', Carbon::now()->month)->count();
            $yearVisit = Visit::whereYear('visited_at', Carbon::now()->year)->count();

            $homepageUrls = ['http://indiegologi.com', 'http://indiegologi.com/'];
            $homeVisit = Visit::whereIn('url', $homepageUrls)->count();
            $articleVisits = Visit::where('url', 'like', '%/articles%')->count();
            $sketchVisits = Visit::where('url', 'like', '%/sketches%')->count();
            $contactVisits = Visit::where('url', 'like', '%/contact%')->count();

            $homeVisitToday = Visit::whereIn('url', $homepageUrls)
                ->whereDate('visited_at', Carbon::today())
                ->count();
            $homeVisitWeek = Visit::whereIn('url', $homepageUrls)
                ->whereBetween('visited_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count();
            $homeVisitMonth = Visit::whereIn('url', $homepageUrls)
                ->whereMonth('visited_at', Carbon::now()->month)
                ->count();
            $homeVisitYear = Visit::whereIn('url', $homepageUrls)
                ->whereYear('visited_at', Carbon::now()->year)
                ->count();

        } catch (\Exception $e) {
            $todayVisit = $weekVisit = $monthVisit = $yearVisit = 0;
            $homeVisit = $articleVisits = $sketchVisits = $contactVisits = 0;
            $homeVisitToday = $homeVisitWeek = $homeVisitMonth = $homeVisitYear = 0;
        }

        // --- PERBAIKAN: Ambil data articles dan sketches untuk section "Recent" ---
        $articles = Article::orderBy('created_at', 'desc')->take(6)->get();
        $sketches = Sketch::orderBy('created_at', 'desc')->take(6)->get();
        // --- AKHIR PERBAIKAN ---

        return view('dashboard.admin', compact(
            'totalUsers', 'totalArticles', 'publishedArticles', 'draftArticles',
            'totalSketches', 'publishedSketches', 'draftSketches',
            'totalServices', 'totalBookings',
            'todayVisit', 'weekVisit', 'monthVisit', 'yearVisit',
            'homeVisitToday', 'homeVisitWeek', 'homeVisitMonth', 'homeVisitYear',
            'homeVisit',
            'articleVisits', 'sketchVisits', 'contactVisits',
            'articles', 'sketches' 
        ));
    }
}
