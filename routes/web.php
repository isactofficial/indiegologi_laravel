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
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ChatbotController;

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

// ======================================================================
// Rute Publik (Akses Tanpa Login)
// ======================================================================

Route::get('/', [FrontController::class, 'index'])->name('front.index')->middleware('track.views:homepage');
Route::get('/articles', [FrontController::class, 'articles'])->name('front.articles')->middleware('track.views:articles');
Route::get('/articles/{article:slug}', [FrontController::class, 'showArticle'])->name('front.articles.show')->middleware('track.views:articles');
Route::get('/layanan', [FrontController::class, 'layanan'])->name('front.layanan')->middleware('track.views:layanan');

Route::get('/layanan/{service}', [FrontController::class, 'showLayanan'])->name('layanan.show');

Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact')->middleware('track.views:contact');
Route::get('/sketches', [FrontController::class, 'sketches_show'])->name('front.sketch')->middleware('track.views:sketches');
Route::get('/sketches/{sketch:slug}', [FrontController::class, 'showDetail'])->name('front.sketches.detail')->middleware('track.views:sketches');

// Rute searchbar
Route::get('/search', [FrontController::class, 'search'])->name('search.results');

// PENTING: Pindahkan rute keranjang belanja ke sini, di luar middleware auth.
Route::get('/cart', [FrontController::class, 'viewCart'])->name('front.cart.view');

// ======================================================================
// Rute Chatbot BOTMAN
// ======================================================================

// Rute ini yang akan menangani semua pesan dari BotMan dan mengarahkannya ke ChatbotController
Route::match(['get', 'post'], 'botman', [ChatbotController::class, 'handle']);

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
    // Comment management
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // User Profile management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // [BARU] Cart Routes
    Route::prefix('cart')->name('front.cart.')->group(function () {
        // Hapus rute 'view' dari sini, sudah dipindahkan ke atas
        Route::post('/add', [FrontController::class, 'addToCart'])->name('add');
        Route::post('/remove', [FrontController::class, 'removeFromCart'])->name('remove');
        Route::post('/update-summary', [FrontController::class, 'updateCartSummary'])->name('updateSummary');
    });

    // Checkout Route
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    // [PENTING: TAMBAHKAN ROUTE INI UNTUK CEK KETERSEDIAAN JADWAL]
    Route::post('/check-availability', [FrontController::class, 'checkBookingAvailability'])->name('front.check.availability');

    // [BARU] Grup Rute untuk Onboarding
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'show'])->name('show');
        Route::post('/', [OnboardingController::class, 'store'])->name('store');
    });
});


// ======================================================================
// Rute Admin
// ======================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Article management
    Route::get('/articles/approval', [ArticleController::class, 'approval'])->name('articles.approval');
    Route::put('/articles/{article}/status', [ArticleController::class, 'updateStatus'])->name('articles.updateStatus');
    Route::resource('articles', ArticleController::class)->parameters(['articles' => 'article:slug']);

    // Sketsa management
    Route::resource('sketches', SketchController::class)->parameters(['sketches' => 'sketch:slug']);

    // Consultation Service management
    Route::resource('consultation-services', ConsultationServiceController::class);

    // Referral Codes management
    Route::resource('referral-codes', ReferralCodeController::class);

    // Consultation Booking management
    Route::resource('consultation-bookings', ConsultationBookingController::class);
    Route::get('consultation-bookings/{consultationBooking}/download-pdf', [ConsultationBookingController::class, 'downloadPdf'])->name('consultation-bookings.download-pdf');


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

// This route will display the invoice to the user
Route::get('/invoice/{consultationBooking}', [InvoiceController::class, 'show'])->name('invoice.show');

// Google OAuth routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);
Route::get('/users/profile/{user}', [ConsultationBookingController::class, 'showUserProfile'])->name('admin.users.show');

Route::get('/tes-403', function () {
    abort(403, 'Akses Ditolak.');
});

Route::get('/tes-500', function () {
    abort(500, 'Terjadi Kesalahan Server.');
});
