<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\RegistrationController;
use App\Http\Controllers\Frontend\FeedbackController;
use App\Http\Controllers\Frontend\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes - Ecosystem Digital Terpadu Ramadhan 1447 H 🕌✨
|--------------------------------------------------------------------------
|
| Routes untuk Frontend Website Ramadhan
| Menghubungkan semua Controllers untuk memberikan pengalaman digital terbaik
|
*/

// =============================================================================
// AUTHENTICATION ROUTES 🔐
// =============================================================================

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

// Logout Route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');


// =============================================================================
// HOME & SEARCH ROUTES
// =============================================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Global Search
Route::get('/search', [HomeController::class, 'search'])->name('search');


// =============================================================================
// EVENT ROUTES 🎯
// =============================================================================

Route::prefix('events')->name('events.')->group(function () {
    // Event Listing
    Route::get('/', [EventController::class, 'index'])->name('index');

    // Event by Category
    Route::get('/category/{slug}', [EventController::class, 'category'])->name('category');

    // Event by Tag
    Route::get('/tag/{slug}', [EventController::class, 'tag'])->name('tag');

    // Event Detail
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
});


// =============================================================================
// POST/BLOG ROUTES 📝
// =============================================================================

Route::prefix('blog')->name('posts.')->group(function () {
    // Blog Listing
    Route::get('/', [PostController::class, 'index'])->name('index');

    // Post by Category
    Route::get('/category/{slug}', [PostController::class, 'category'])->name('category');

    // Post by Tag
    Route::get('/tag/{slug}', [PostController::class, 'tag'])->name('tag');

    // Post Detail
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
});


// =============================================================================
// EVENT REGISTRATION ROUTES 📋
// =============================================================================

Route::prefix('registrations')->name('registrations.')->group(function () {
    // Registration Form
    Route::get('/events/{eventSlug}/register', [RegistrationController::class, 'create'])
        ->name('create');

    // Submit Registration
    Route::post('/events/{eventSlug}/register', [RegistrationController::class, 'store'])
        ->name('store');

    // View Registration Detail
    Route::get('/{registrationCode}', [RegistrationController::class, 'show'])
        ->name('show');

    // Cancel Registration
    Route::post('/{registrationCode}/cancel', [RegistrationController::class, 'cancel'])
        ->name('cancel');

    // Download Ticket/Certificate
    Route::get('/{registrationCode}/ticket', [RegistrationController::class, 'downloadTicket'])
        ->name('ticket');
});


// =============================================================================
// FEEDBACK & TESTIMONIAL ROUTES 💬
// =============================================================================

// Public Testimonials Page (harus di luar prefix agar route name-nya 'testimonials')
Route::get('/testimonials', [FeedbackController::class, 'testimonials'])->name('testimonials');

Route::prefix('feedback')->name('feedback.')->group(function () {
    // Feedback Form - bisa untuk general atau event-specific
    // URL: /feedback/create (general) atau /feedback/create/{event-slug} (event-specific)
    Route::get('/create/{eventSlug?}', [FeedbackController::class, 'create'])->name('create');

    // Submit Feedback
    Route::post('/store', [FeedbackController::class, 'store'])->name('store');

    // Success Page after submission
    Route::get('/success', [FeedbackController::class, 'success'])->name('success');
});

// =============================================================================
// CONTACT ROUTES 📧
// =============================================================================

Route::prefix('contact')->name('contact.')->group(function () {
    // Submit Contact Message
    Route::post('/', [ContactController::class, 'store'])->name('store');
});


// =============================================================================
// STATIC PAGES ROUTES 📄
// =============================================================================

Route::name('pages.')->group(function () {
    // Dynamic page route
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('show');
});

// Shortcut routes (redirect atau direct)
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('about');
Route::get('/faq', [PageController::class, 'show'])->defaults('slug', 'faq')->name('faq');
Route::get('/gallery', [PageController::class, 'show'])->defaults('slug', 'gallery')->name('gallery');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact')->name('contact');
Route::get('/privacy-policy', [PageController::class, 'show'])->defaults('slug', 'privacy-policy')->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'show'])->defaults('slug', 'terms-of-service')->name('terms');

// =============================================================================
// ADMIN ROUTES 👨‍💼
// =============================================================================

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\EventRegistrationController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // =============================================================================
    // Dashboard
    // =============================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // =============================================================================
    // Events Management
    // =============================================================================
    Route::resource('events', AdminEventController::class);
    Route::post('/events/{event}/duplicate', [AdminEventController::class, 'duplicate'])->name('events.duplicate');
    Route::get('/events/{event}/export-registrations', [AdminEventController::class, 'exportRegistrations'])->name('events.export-registrations');


    // =============================================================================
    // Event Registrations Management
    // =============================================================================
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [EventRegistrationController::class, 'index'])->name('index');
        Route::get('/{registration}', [EventRegistrationController::class, 'show'])->name('show');
        Route::post('/{registration}/confirm', [EventRegistrationController::class, 'confirm'])->name('confirm');
        Route::post('/{registration}/check-in', [EventRegistrationController::class, 'checkIn'])->name('check-in');
        Route::post('/{registration}/cancel', [EventRegistrationController::class, 'cancel'])->name('cancel');
        Route::patch('/{registration}/notes', [EventRegistrationController::class, 'updateNotes'])->name('update-notes');
        Route::post('/bulk-action', [EventRegistrationController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [EventRegistrationController::class, 'export'])->name('export');
    });


    // =============================================================================
    // Posts/Blog Management
    // =============================================================================
    Route::resource('posts', AdminPostController::class);
    Route::post('/posts/bulk-action', [AdminPostController::class, 'bulkAction'])->name('posts.bulk-action');


    // =============================================================================
    // Categories Management
    // =============================================================================
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');


    // =============================================================================
    // Tags Management
    // =============================================================================
    Route::resource('tags', TagController::class)->except(['show']);
    Route::post('/tags/merge', [TagController::class, 'merge'])->name('tags.merge');


    // =============================================================================
    // Feedbacks Management
    // =============================================================================
    Route::prefix('feedbacks')->name('feedbacks.')->group(function () {
        Route::get('/', [AdminFeedbackController::class, 'index'])->name('index');
        Route::get('/{feedback}', [AdminFeedbackController::class, 'show'])->name('show');
        Route::post('/{feedback}/respond', [AdminFeedbackController::class, 'respond'])->name('respond');
        Route::patch('/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('update-status');
        Route::post('/{feedback}/publish', [AdminFeedbackController::class, 'publish'])->name('publish');
        Route::post('/{feedback}/unpublish', [AdminFeedbackController::class, 'unpublish'])->name('unpublish');
        Route::post('/{feedback}/toggle-featured', [AdminFeedbackController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::delete('/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('destroy');
    });


    // =============================================================================
    // Contact Messages Management
    // =============================================================================
    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
        Route::post('/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('reply');
        Route::post('/{contactMessage}/mark-resolved', [ContactMessageController::class, 'markAsResolved'])->name('mark-resolved');
        Route::post('/{contactMessage}/archive', [ContactMessageController::class, 'archive'])->name('archive');
        Route::delete('/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [ContactMessageController::class, 'bulkAction'])->name('bulk-action');
    });


    // =============================================================================
    // Users Management
    // =============================================================================
    Route::resource('users', UserController::class);


    // =============================================================================
    // Pages Management
    // =============================================================================
    Route::resource('pages', AdminPageController::class);


    // =============================================================================
    // FAQs Management
    // =============================================================================
    Route::resource('faqs', FAQController::class);
    Route::post('/faqs/{faq}/toggle-status', [FAQController::class, 'toggleStatus'])->name('faqs.toggle-status');
    Route::post('/faqs/reorder', [FAQController::class, 'reorder'])->name('faqs.reorder');


    // =============================================================================
    // Sliders Management
    // =============================================================================
    Route::resource('sliders', SliderController::class);
    Route::post('/sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::post('/sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');


    // =============================================================================
    // Media Library Management
    // =============================================================================
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::patch('/{media}', [MediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    });


    // =============================================================================
    // Settings Management
    // =============================================================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
    });
});


// =============================================================================
// FALLBACK ROUTE - 404 Handler
// =============================================================================

// Custom 404 Page (optional)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
