<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\Admin\SketchController;
use App\Http\Controllers\Admin\ReferralCodeController;
use App\Http\Controllers\Admin\ConsultationServiceController;
use App\Http\Controllers\Admin\ConsultationBookingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Http\Request;

Route::post('/test-simple', function() {
    return response()->json(['status' => 'OK', 'message' => 'Simple test works']);
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::match(['get', 'post'], '/botman-test', [ChatbotController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
// Route for storage link
Route::get('/storage-link', function () {
    $targetFolder = base_path() . '/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    if (!file_exists($linkFolder)) {
        try {
            symlink($targetFolder, $linkFolder);
            return "Symlink created successfully!";
        } catch (\Exception $e) {
            return "Failed to create symlink: " . $e->getMessage();
        }
    }
    return "Symlink already exists.";
});

Route::match(['get', 'post'], '/botman', [ChatbotController::class, 'handle']);

// ======================================================================
// Rute Publik (Akses Tanpa Login)
// ======================================================================

Route::get('/', [FrontController::class, 'index'])->name('front.index')->middleware('track.views:homepage');

// Articles routes with popular article support
Route::get('/articles', [FrontController::class, 'articles'])->name('front.articles')->middleware('track.views:articles');
Route::get('/articles/{article:slug}', [FrontController::class, 'showArticle'])->name('front.articles.show')->middleware('track.views:articles');

// Layanan routes - FIXED: Keep original route name for backward compatibility
Route::get('/layanan', [FrontController::class, 'layanan'])->name('front.layanan')->middleware('track.views:layanan');
Route::get('/layanan/{service}', [FrontController::class, 'showLayanan'])->name('layanan.show');

// Contact route
Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact')->middleware('track.views:contact');

// Sketches routes
Route::get('/sketches', [FrontController::class, 'sketches_show'])->name('front.sketch')->middleware('track.views:sketches');
Route::get('/sketches/{sketch:slug}', [FrontController::class, 'showDetail'])->name('front.sketches.detail')->middleware('track.views:sketches');

// Search route
Route::get('/search', [FrontController::class, 'search'])->name('search.results');

// Cart route (public access)
Route::get('/cart', [FrontController::class, 'viewCart'])->name('front.cart.view');

// Service pricing route for guest cart pricing
Route::post('/get-service-pricing', [FrontController::class, 'getServicePricing'])->name('front.service.pricing');

// NEW: Free Consultation API Routes (Public Access for AJAX calls)
Route::prefix('api/free-consultation')->name('api.free-consultation.')->group(function () {
    Route::get('/schedules', [FrontController::class, 'getFreeConsultationSchedules'])->name('schedules');
    Route::post('/check-availability', [FrontController::class, 'checkFreeConsultationAvailability'])->name('check-availability');
});


// ======================================================================
// Rute Autentikasi
// ======================================================================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard redirection
Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->middleware('auth')->name('redirect.dashboard');

// Password Reset Routes
Route::get('forgot-password', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// ======================================================================
// Rute Pengguna yang Diautentikasi
// ======================================================================

Route::middleware(['auth'])->group(function () {
    // Comment management - Fixed to use slug consistently
    Route::post('/articles/{article:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // User Profile management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Cart Routes (authenticated users)
    Route::prefix('cart')->name('front.cart.')->group(function () {
        Route::post('/add', [FrontController::class, 'addToCart'])->name('add');
        Route::post('/remove', [FrontController::class, 'removeFromCart'])->name('remove');
        Route::post('/update-summary', [FrontController::class, 'updateCartSummary'])->name('updateSummary');
        // Transfer temp cart route for free consultation support
        Route::post('/transfer-temp-cart', [FrontController::class, 'transferTempCart'])->name('transfer-temp');
    });

    // Checkout Route
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    // Check availability routes
    Route::post('/check-availability', [FrontController::class, 'checkBookingAvailability'])->name('front.check.availability');

    // Onboarding Routes - FIXED: Added both routes with correct names
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'show'])->name('show');
        Route::get('/start', [OnboardingController::class, 'show'])->name('start'); // Added this route
        Route::post('/', [OnboardingController::class, 'store'])->name('store');
    });
});

// ======================================================================
// Invoice Routes (Public for PDF downloads)
// ======================================================================

Route::get('/invoice/{consultationBooking}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{consultationBooking}/download', [InvoiceController::class, 'downloadPdf'])->name('invoice.download');

// ======================================================================
// Rute Admin
// ======================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Article management (Admin)
    Route::get('/articles/approval', [ArticleController::class, 'approval'])->name('articles.approval');
    Route::put('/articles/{article:slug}/status', [ArticleController::class, 'updateStatus'])->name('articles.updateStatus');
    Route::resource('articles', ArticleController::class)->parameters(['articles' => 'article:slug']);

    // Sketches management
    Route::resource('sketches', SketchController::class)->parameters(['sketches' => 'sketch:slug']);

    // Consultation Service management
    Route::resource('consultation-services', ConsultationServiceController::class);

    // Referral Codes management
    Route::resource('referral-codes', ReferralCodeController::class);

    // Consultation Booking management
    Route::resource('consultation-bookings', ConsultationBookingController::class);
    Route::get('consultation-bookings/{consultationBooking}/download-pdf', [ConsultationBookingController::class, 'downloadPdf'])->name('consultation-bookings.download-pdf');

    // Testimonials management
    Route::resource('testimonials', TestimonialController::class);
    Route::patch('/testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status'); // ✅ UBAH PUT JADI PATCH

    // NEW: Free Consultation Management
    Route::prefix('free-consultation')->name('free-consultation.')->group(function () {
        // Free consultation types management
        Route::resource('types', App\Http\Controllers\Admin\FreeConsultationTypeController::class);
        
        // Free consultation schedules management
        Route::resource('schedules', App\Http\Controllers\Admin\FreeConsultationScheduleController::class);
        
        // Bulk operations for schedules
        Route::post('schedules/bulk-create', [App\Http\Controllers\Admin\FreeConsultationScheduleController::class, 'bulkCreate'])->name('schedules.bulk-create');
        Route::put('schedules/{schedule}/toggle-availability', [App\Http\Controllers\Admin\FreeConsultationScheduleController::class, 'toggleAvailability'])->name('schedules.toggle-availability');
    });

    // Admin can also manage comments
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('admin.admin.comments.destroy');
});

// ======================================================================
// Role-Based Dashboards
// ======================================================================

Route::middleware(['auth', 'role:author'])->group(function () {
    Route::get('/author/dashboard', function () {
        return view('dashboard.author');
    })->name('author.dashboard');
});

Route::middleware(['auth', 'role:reader'])->group(function () {
    Route::get('/reader/dashboard', function () {
        return view('dashboard.reader');
    })->name('reader.dashboard');
});

// ======================================================================
// Additional Routes
// ======================================================================

Route::post('/test-chatbot', function(Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'Test endpoint works',
        'received' => $request->all()
    ]);
});

// Letakkan ini di dalam grup route yang sesuai, misalnya di bawah route untuk homepage.

Route::get('/sketch/{sketch:slug}', [FrontController::class, 'sketchShow'])->name('front.sketch.show');

// Google OAuth routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// User profile route for admin
Route::get('/users/profile/{user}', [ConsultationBookingController::class, 'showUserProfile'])->name('admin.users.show');

// ======================================================================
// Test Routes (Development Only - Remove in Production)
// ======================================================================

Route::get('/debug-article/{slug}', function($slug) {
    $article = \App\Models\Article::where('slug', $slug)->first();
    if (!$article) {
        return response()->json(['error' => 'Article not found', 'slug' => $slug]);
    }
    return response()->json([
        'found' => true,
        'title' => $article->title,
        'status' => $article->status,
        'views' => $article->views,
        'slug' => $article->slug
    ]);
});

// Test error routes
Route::get('/tes-403', function () { abort(403, 'Akses Ditolak.'); });
Route::get('/tes-500', function () { abort(500, 'Terjadi Kesalahan Server.'); });
Route::get('/test-401', function () { abort(401); });
Route::get('/test-419', function () { abort(419); });
Route::get('/test-429', function () { abort(429); });
Route::get('/test-503', function () { abort(503); });
Route::get('/test-400', function () { abort(400); });
Route::get('/test-413', function () { abort(413); });
Route::get('/test-502', function () { abort(502); });

// NEW: Test routes for free consultation system (Development only)
Route::prefix('test-free-consultation')->group(function () {
    Route::get('/types', function () {
        return response()->json(\App\Models\FreeConsultationType::with('availableSchedules')->get());
    });
    
    Route::get('/schedules/{typeId}', function ($typeId) {
        return response()->json(\App\Models\FreeConsultationSchedule::where('type_id', $typeId)->available()->future()->get());
    });
    
    Route::get('/cart-items', function () {
        if (!auth()->check()) return response()->json(['error' => 'Not authenticated']);
        return response()->json(\App\Models\CartItem::where('user_id', auth()->id())->with(['freeConsultationType', 'freeConsultationSchedule'])->get());
    });
});