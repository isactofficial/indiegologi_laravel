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

// Route for storage link (usually run once after deployment/setup)
Route::get('/storage-link', function () {
    $targetFolder = base_path() . '/storage/app/public';
    // Ensure this points to your web server's public root, e.g., public_path()
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

// Public-Facing Routes (accessible to all, guest or authenticated)
Route::middleware('log.visit')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('front.index');
    Route::get('/articles', [FrontController::class, 'articles'])->name('front.articles');
    Route::get('/articles/{article:slug}', [FrontController::class, 'showArticle'])->name(name: 'front.articles.show');
   Route::get('/layanan', [FrontController::class, 'layanan'])->name('front.layanan');
    Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact');

    // Event Listing (front-facing) - Sekarang ditangani oleh FrontController
Route::get('/sketches', [FrontController::class, 'sketches_show'])->name('front.sketch');

    // Event/Tournament Details (front-facing) - Sekarang ditangani oleh FrontController
Route::get('/sketches/{sketch:slug}', [FrontController::class, 'showDetail'])->name('front.sketches.detail');

});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard redirection based on role after login
Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->middleware('auth')->name('redirect.dashboard');

// Password Reset Routes (Laravel's built-in routes)
Route::get('forgot-password', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// --- Authenticated User Routes ---
// These routes require the user to be logged in.
Route::middleware(['auth'])->group(function () {
    // Comment management on articles
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // User Profile management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Event Registration (User facing) - Uses event slug as per front-facing URL, but sends event ID in AJAX body
    Route::post('/events/{event:slug}/register', [FrontController::class, 'register'])->name('front.events.register');
});


// --- Admin Routes ---
// These routes require authentication and the 'admin' role.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Article management (Admin) - Uses slug for resource binding
    Route::get('/articles/approval', [ArticleController::class, 'approval'])->name('articles.approval');
    Route::put('/articles/{article}/status', [ArticleController::class, 'updateStatus'])->name('articles.updateStatus');
    Route::resource('articles', ArticleController::class)->parameters([
        'articles' => 'article:slug'
    ]);

    // Sketsa management (Admin) - Ditambahkan dengan slug binding
    Route::resource('sketches', SketchController::class)->parameters([
        'sketches' => 'sketch:slug'
    ]);

    // Consultation Service management (Admin) - Ditambahkan
    Route::resource('consultation-services', ConsultationServiceController::class);

    // Referral Codes management (Admin) - Ditambahkan
    Route::resource('referral-codes', ReferralCodeController::class);

    // Consultation Booking management (Admin) - Ditambahkan
    Route::resource('consultation-bookings', ConsultationBookingController::class);
    // Tambahkan route baru untuk PDF
    Route::get('consultation-bookings/{consultationBooking}/download-pdf', [ConsultationBookingController::class, 'downloadPdf'])->name('consultation-bookings.download-pdf');
});


// --- Role-Based Dashboards ---
// Specific dashboards for different roles, accessible only after authentication and role check.
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

// Google OAuth routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);
