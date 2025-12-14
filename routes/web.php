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

// Import Controllers untuk Manajemen Kinerja
use App\Http\Controllers\Admin\Committee\CommitteeStructureController;
use App\Http\Controllers\Admin\Committee\JobDescController;
use App\Http\Controllers\Admin\Committee\PerformanceEvaluationController;
use App\Http\Controllers\Admin\Timeline\ProjectTimelineController;
use App\Http\Controllers\Admin\Timeline\MilestoneController;
use App\Http\Controllers\Admin\Timeline\ProgressReportController;

// Import Controllers untuk Manajemen Keuangan
use App\Http\Controllers\Admin\Finance\BudgetController;
use App\Http\Controllers\Admin\Finance\BudgetAllocationController;
use App\Http\Controllers\Admin\Finance\SponsorshipController;
use App\Http\Controllers\Admin\Finance\IncomeController;
use App\Http\Controllers\Admin\Finance\ExpenseController;
use App\Http\Controllers\Admin\Finance\CashFlowController;
use App\Http\Controllers\Admin\Finance\FinancialReportController;

// Import Controllers untuk Aset & Pengetahuan
use App\Http\Controllers\Admin\Knowledge\SOPController;
use App\Http\Controllers\Admin\Knowledge\WorkInstructionController;
use App\Http\Controllers\Admin\Knowledge\TemplateController;
use App\Http\Controllers\Admin\Knowledge\DocumentController;
use App\Http\Controllers\Admin\Administration\ProposalController;
use App\Http\Controllers\Admin\Administration\MeetingMinuteController;
use App\Http\Controllers\Admin\Administration\ContractController;
use App\Http\Controllers\Admin\Administration\OfficialLetterController;

// Import Controllers untuk Analisis & Laporan
use App\Http\Controllers\Admin\Analytics\EventAnalyticsController;
use App\Http\Controllers\Admin\Analytics\RegistrationAnalyticsController;
use App\Http\Controllers\Admin\Analytics\FinancialAnalyticsController;
use App\Http\Controllers\Admin\Analytics\PerformanceAnalyticsController;
use App\Http\Controllers\Admin\Reports\CustomReportController;
use App\Http\Controllers\Admin\Reports\ExecutiveSummaryController;
use App\Http\Controllers\Admin\Reports\FinalEventReportController;
use App\Http\Controllers\Admin\Reports\ComparativeAnalysisController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // =============================================================================
    // Dashboard
    // =============================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // =============================================================================
    // 1. PELAYANAN PUBLIK
    // =============================================================================

    // Events Management
    Route::resource('events', AdminEventController::class);
    Route::post('/events/{event}/duplicate', [AdminEventController::class, 'duplicate'])->name('events.duplicate');
    Route::get('/events/{event}/export-registrations', [AdminEventController::class, 'exportRegistrations'])->name('events.export-registrations');

    // Event Registrations Management
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

    // Posts/Blog Management
    Route::resource('posts', AdminPostController::class);
    Route::post('/posts/bulk-action', [AdminPostController::class, 'bulkAction'])->name('posts.bulk-action');

    // Categories Management
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Tags Management
    Route::controller(TagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{tag}', 'update')->name('update');
        Route::delete('/{tag}', 'destroy')->name('destroy');
        Route::post('/merge', 'merge')->name('merge');
    });

    // Feedbacks Management
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

    // Contact Messages Management
    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
        Route::post('/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('reply');
        Route::post('/{contactMessage}/mark-resolved', [ContactMessageController::class, 'markAsResolved'])->name('mark-resolved');
        Route::post('/{contactMessage}/archive', [ContactMessageController::class, 'archive'])->name('archive');
        Route::delete('/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [ContactMessageController::class, 'bulkAction'])->name('bulk-action');
    });

    // Pages Management
    Route::resource('pages', AdminPageController::class);

    // FAQs Management
    Route::resource('faqs', FAQController::class);
    Route::post('/faqs/{faq}/toggle-status', [FAQController::class, 'toggleStatus'])->name('faqs.toggle-status');
    Route::post('/faqs/reorder', [FAQController::class, 'reorder'])->name('faqs.reorder');

    // Sliders Management
    Route::resource('sliders', SliderController::class);
    Route::post('/sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::post('/sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');

    // Media Library Management
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::get('/{media}', [MediaController::class, 'show'])->name('show');
        Route::get('/{media}/edit', [MediaController::class, 'edit'])->name('edit');
        Route::patch('/{media}', [MediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    });


    // =============================================================================
    // 2. MANAJEMEN KINERJA 📊
    // =============================================================================

    // -------------------------------------------------------------------------
    // Kepanitiaan
    // -------------------------------------------------------------------------

    // Committee Structure - MENGGUNAKAN RESOURCE ROUTE
    Route::prefix('committee')->name('committee.')->group(function () {
        // CRUD routes
        Route::get('/structure', [CommitteeStructureController::class, 'index'])->name('structure.index');
        Route::get('/structure/create', [CommitteeStructureController::class, 'create'])->name('structure.create');
        Route::post('/structure', [CommitteeStructureController::class, 'store'])->name('structure.store');
        Route::get('/structure/{structure}/edit', [CommitteeStructureController::class, 'edit'])->name('structure.edit');
        Route::put('/structure/{structure}', [CommitteeStructureController::class, 'update'])->name('structure.update');
        Route::delete('/structure/{structure}', [CommitteeStructureController::class, 'destroy'])->name('structure.destroy');

        // Custom actions
        Route::post('/structure/reorder', [CommitteeStructureController::class, 'reorder'])->name('structure.reorder');
        Route::get('/structure/export', [CommitteeStructureController::class, 'export'])->name('structure.export');
    });

    // Job Descriptions & Assignments
    Route::resource('jobdescs', JobDescController::class);
    Route::post('/jobdescs/{jobdesc}/assign', [JobDescController::class, 'assign'])->name('jobdescs.assign');
    Route::delete('/jobdescs/{jobdesc}/unassign/{user}', [JobDescController::class, 'unassign'])->name('jobdescs.unassign');
    Route::post('/jobdescs/bulk-assign', [JobDescController::class, 'bulkAssign'])->name('jobdescs.bulk-assign');

    // Performance Evaluations
    Route::resource('evaluations', PerformanceEvaluationController::class);
    Route::post('/evaluations/{evaluation}/submit', [PerformanceEvaluationController::class, 'submit'])->name('evaluations.submit');
    Route::post('/evaluations/{evaluation}/approve', [PerformanceEvaluationController::class, 'approve'])->name('evaluations.approve');
    Route::get('/evaluations/user/{user}', [PerformanceEvaluationController::class, 'userEvaluations'])->name('evaluations.user');
    Route::get('/evaluations/export/{evaluation}', [PerformanceEvaluationController::class, 'export'])->name('evaluations.export');

    // -------------------------------------------------------------------------
    // Timeline & Milestone
    // -------------------------------------------------------------------------

    // Project Timeline
    Route::prefix('timeline')->name('timeline.')->group(function () {
        Route::get('/', [ProjectTimelineController::class, 'index'])->name('index');
        Route::post('/', [ProjectTimelineController::class, 'store'])->name('store');
        Route::put('/{timeline}', [ProjectTimelineController::class, 'update'])->name('update');
        Route::delete('/{timeline}', [ProjectTimelineController::class, 'destroy'])->name('destroy');
        Route::post('/{timeline}/duplicate', [ProjectTimelineController::class, 'duplicate'])->name('duplicate');
        Route::get('/gantt-chart', [ProjectTimelineController::class, 'ganttChart'])->name('gantt-chart');
        Route::get('/export', [ProjectTimelineController::class, 'export'])->name('export');
    });

    // Milestones
    Route::resource('milestones', MilestoneController::class);
    Route::post('/milestones/{milestone}/complete', [MilestoneController::class, 'complete'])->name('milestones.complete');
    Route::post('/milestones/{milestone}/reopen', [MilestoneController::class, 'reopen'])->name('milestones.reopen');
    Route::post('/milestones/bulk-update-status', [MilestoneController::class, 'bulkUpdateStatus'])->name('milestones.bulk-update-status');

    // Progress Reports
    Route::resource('progress-reports', ProgressReportController::class);
    Route::post('/progress-reports/{report}/submit', [ProgressReportController::class, 'submit'])->name('progress-reports.submit');
    Route::post('/progress-reports/{report}/approve', [ProgressReportController::class, 'approve'])->name('progress-reports.approve');
    Route::get('/progress-reports/{report}/export', [ProgressReportController::class, 'export'])->name('progress-reports.export');


    // =============================================================================
    // 3. MANAJEMEN KEUANGAN 💰
    // =============================================================================

    // -------------------------------------------------------------------------
    // Perencanaan
    // -------------------------------------------------------------------------

    // Budget Planning (RAB)
    Route::resource('budgets', BudgetController::class);
    Route::post('/budgets/{budget}/approve', [BudgetController::class, 'approve'])->name('budgets.approve');
    Route::post('/budgets/{budget}/reject', [BudgetController::class, 'reject'])->name('budgets.reject');
    Route::post('/budgets/{budget}/revise', [BudgetController::class, 'revise'])->name('budgets.revise');
    Route::post('/budgets/{budget}/duplicate', [BudgetController::class, 'duplicate'])->name('budgets.duplicate');
    Route::get('/budgets/{budget}/export', [BudgetController::class, 'export'])->name('budgets.export');
    Route::get('/budgets/{budget}/print', [BudgetController::class, 'print'])->name('budgets.print');

    // Budget Allocations
    Route::resource('budget-allocations', BudgetAllocationController::class);
    Route::post('/budget-allocations/{allocation}/transfer', [BudgetAllocationController::class, 'transfer'])->name('budget-allocations.transfer');
    Route::post('/budget-allocations/{allocation}/adjust', [BudgetAllocationController::class, 'adjust'])->name('budget-allocations.adjust');
    Route::get('/budget-allocations/division/{division}', [BudgetAllocationController::class, 'byDivision'])->name('budget-allocations.by-division');
    Route::get('/budget-allocations/event/{event}', [BudgetAllocationController::class, 'byEvent'])->name('budget-allocations.by-event');

    // Sponsorships
    Route::resource('sponsorships', SponsorshipController::class);
    Route::post('/sponsorships/{sponsorship}/confirm', [SponsorshipController::class, 'confirm'])->name('sponsorships.confirm');
    Route::post('/sponsorships/{sponsorship}/cancel', [SponsorshipController::class, 'cancel'])->name('sponsorships.cancel');
    Route::post('/sponsorships/{sponsorship}/invoice', [SponsorshipController::class, 'generateInvoice'])->name('sponsorships.invoice');
    Route::post('/sponsorships/{sponsorship}/receipt', [SponsorshipController::class, 'generateReceipt'])->name('sponsorships.receipt');
    Route::get('/sponsorships/export', [SponsorshipController::class, 'export'])->name('sponsorships.export');

    // -------------------------------------------------------------------------
    // Transaksi
    // -------------------------------------------------------------------------

    // Income (Pemasukan)
    Route::resource('incomes', IncomeController::class);
    Route::post('/incomes/{income}/verify', [IncomeController::class, 'verify'])->name('incomes.verify');
    Route::post('/incomes/{income}/receipt', [IncomeController::class, 'generateReceipt'])->name('incomes.receipt');
    Route::post('/incomes/bulk-verify', [IncomeController::class, 'bulkVerify'])->name('incomes.bulk-verify');
    Route::get('/incomes/export', [IncomeController::class, 'export'])->name('incomes.export');

    // Expenses (Pengeluaran)
    Route::resource('expenses', ExpenseController::class);
    Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::post('/expenses/{expense}/pay', [ExpenseController::class, 'markAsPaid'])->name('expenses.pay');
    Route::post('/expenses/{expense}/receipt', [ExpenseController::class, 'uploadReceipt'])->name('expenses.receipt');
    Route::post('/expenses/bulk-approve', [ExpenseController::class, 'bulkApprove'])->name('expenses.bulk-approve');
    Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');

    // Cash Flow
    Route::prefix('cash-flow')->name('cash-flow.')->group(function () {
        Route::get('/', [CashFlowController::class, 'index'])->name('index');
        Route::get('/daily', [CashFlowController::class, 'daily'])->name('daily');
        Route::get('/monthly', [CashFlowController::class, 'monthly'])->name('monthly');
        Route::get('/by-category', [CashFlowController::class, 'byCategory'])->name('by-category');
        Route::get('/projection', [CashFlowController::class, 'projection'])->name('projection');
        Route::get('/export', [CashFlowController::class, 'export'])->name('export');
    });

    // -------------------------------------------------------------------------
    // Laporan Keuangan
    // -------------------------------------------------------------------------

    Route::prefix('financial-reports')->name('financial-reports.')->group(function () {
        // Budget vs Actual Report
        Route::get('/budget-vs-actual', [FinancialReportController::class, 'budgetVsActual'])->name('budget-vs-actual');
        Route::get('/budget-vs-actual/export', [FinancialReportController::class, 'budgetVsActualExport'])->name('budget-vs-actual.export');

        // Income Statement
        Route::get('/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('/income-statement/export', [FinancialReportController::class, 'incomeStatementExport'])->name('income-statement.export');

        // Variance Analysis
        Route::get('/variance-analysis', [FinancialReportController::class, 'varianceAnalysis'])->name('variance-analysis');
        Route::get('/variance-analysis/export', [FinancialReportController::class, 'varianceAnalysisExport'])->name('variance-analysis.export');

        // Financial Summary
        Route::get('/summary', [FinancialReportController::class, 'summary'])->name('summary');
        Route::get('/summary/by-event/{event}', [FinancialReportController::class, 'summaryByEvent'])->name('summary.by-event');
        Route::get('/summary/by-division/{division}', [FinancialReportController::class, 'summaryByDivision'])->name('summary.by-division');
        Route::get('/summary/export', [FinancialReportController::class, 'summaryExport'])->name('summary.export');
    });


    // =============================================================================
    // 4. ASET & PENGETAHUAN 📚
    // =============================================================================

    // -------------------------------------------------------------------------
    // Dokumentasi
    // -------------------------------------------------------------------------

    // SOP Library
    Route::resource('sops', SOPController::class);
    Route::post('/sops/{sop}/publish', [SOPController::class, 'publish'])->name('sops.publish');
    Route::post('/sops/{sop}/archive', [SOPController::class, 'archive'])->name('sops.archive');
    Route::post('/sops/{sop}/duplicate', [SOPController::class, 'duplicate'])->name('sops.duplicate');
    Route::get('/sops/{sop}/download', [SOPController::class, 'download'])->name('sops.download');
    Route::post('/sops/{sop}/version', [SOPController::class, 'createVersion'])->name('sops.version');

    // Work Instructions
    Route::resource('work-instructions', WorkInstructionController::class);
    Route::post('/work-instructions/{instruction}/publish', [WorkInstructionController::class, 'publish'])->name('work-instructions.publish');
    Route::post('/work-instructions/{instruction}/archive', [WorkInstructionController::class, 'archive'])->name('work-instructions.archive');
    Route::get('/work-instructions/{instruction}/download', [WorkInstructionController::class, 'download'])->name('work-instructions.download');

    // Templates Library
    Route::resource('templates', TemplateController::class);
    Route::get('/templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');
    Route::post('/templates/{template}/duplicate', [TemplateController::class, 'duplicate'])->name('templates.duplicate');
    Route::get('/templates/category/{category}', [TemplateController::class, 'byCategory'])->name('templates.by-category');

    // Documentation Repository
    Route::resource('documents', DocumentController::class);
    Route::post('/documents/{document}/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/share', [DocumentController::class, 'share'])->name('documents.share');
    Route::get('/documents/folder/{folder}', [DocumentController::class, 'byFolder'])->name('documents.by-folder');
    Route::post('/documents/create-folder', [DocumentController::class, 'createFolder'])->name('documents.create-folder');

    // -------------------------------------------------------------------------
    // Administrasi
    // -------------------------------------------------------------------------

    // Proposals & Reports
    Route::resource('proposals', ProposalController::class);
    Route::post('/proposals/{proposal}/submit', [ProposalController::class, 'submit'])->name('proposals.submit');
    Route::post('/proposals/{proposal}/approve', [ProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{proposal}/reject', [ProposalController::class, 'reject'])->name('proposals.reject');
    Route::post('/proposals/{proposal}/revise', [ProposalController::class, 'requestRevision'])->name('proposals.revise');
    Route::get('/proposals/{proposal}/download', [ProposalController::class, 'download'])->name('proposals.download');
    Route::get('/proposals/{proposal}/print', [ProposalController::class, 'print'])->name('proposals.print');

    // Meeting Minutes (Notulensi)
    Route::resource('meeting-minutes', MeetingMinuteController::class);
    Route::post('/meeting-minutes/{minute}/finalize', [MeetingMinuteController::class, 'finalize'])->name('meeting-minutes.finalize');
    Route::post('/meeting-minutes/{minute}/send', [MeetingMinuteController::class, 'send'])->name('meeting-minutes.send');
    Route::get('/meeting-minutes/{minute}/download', [MeetingMinuteController::class, 'download'])->name('meeting-minutes.download');
    Route::get('/meeting-minutes/{minute}/print', [MeetingMinuteController::class, 'print'])->name('meeting-minutes.print');

    // Contracts & Agreements
    Route::resource('contracts', ContractController::class);
    Route::post('/contracts/{contract}/sign', [ContractController::class, 'sign'])->name('contracts.sign');
    Route::post('/contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
    Route::post('/contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');
    Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])->name('contracts.download');
    Route::get('/contracts/expiring-soon', [ContractController::class, 'expiringSoon'])->name('contracts.expiring-soon');

    // Official Letters (Surat Menyurat)
    Route::resource('official-letters', OfficialLetterController::class);
    Route::post('/official-letters/{letter}/send', [OfficialLetterController::class, 'send'])->name('official-letters.send');
    Route::post('/official-letters/{letter}/archive', [OfficialLetterController::class, 'archive'])->name('official-letters.archive');
    Route::get('/official-letters/{letter}/download', [OfficialLetterController::class, 'download'])->name('official-letters.download');
    Route::get('/official-letters/{letter}/print', [OfficialLetterController::class, 'print'])->name('official-letters.print');
    Route::post('/official-letters/generate-number', [OfficialLetterController::class, 'generateNumber'])->name('official-letters.generate-number');


    // =============================================================================
    // 5. ANALISIS & LAPORAN 📈
    // =============================================================================

    // -------------------------------------------------------------------------
    // Analytics Dashboard
    // -------------------------------------------------------------------------

    Route::prefix('analytics')->name('analytics.')->group(function () {
        // Event Analytics
        Route::get('/event', [EventAnalyticsController::class, 'index'])->name('event');
        Route::get('/event/{event}', [EventAnalyticsController::class, 'show'])->name('event.show');
        Route::get('/event/{event}/export', [EventAnalyticsController::class, 'export'])->name('event.export');

        // Registration Trends
        Route::get('/registration', [RegistrationAnalyticsController::class, 'index'])->name('registration');
        Route::get('/registration/trends', [RegistrationAnalyticsController::class, 'trends'])->name('registration.trends');
        Route::get('/registration/demographics', [RegistrationAnalyticsController::class, 'demographics'])->name('registration.demographics');
        Route::get('/registration/conversion', [RegistrationAnalyticsController::class, 'conversionRate'])->name('registration.conversion');
        Route::get('/registration/export', [RegistrationAnalyticsController::class, 'export'])->name('registration.export');

        // Financial Trends
        Route::get('/financial', [FinancialAnalyticsController::class, 'index'])->name('financial');
        Route::get('/financial/trends', [FinancialAnalyticsController::class, 'trends'])->name('financial.trends');
        Route::get('/financial/breakdown', [FinancialAnalyticsController::class, 'breakdown'])->name('financial.breakdown');
        Route::get('/financial/forecast', [FinancialAnalyticsController::class, 'forecast'])->name('financial.forecast');
        Route::get('/financial/export', [FinancialAnalyticsController::class, 'export'])->name('financial.export');

        // Performance Metrics
        Route::get('/performance', [PerformanceAnalyticsController::class, 'index'])->name('performance');
        Route::get('/performance/team', [PerformanceAnalyticsController::class, 'teamPerformance'])->name('performance.team');
        Route::get('/performance/individual/{user}', [PerformanceAnalyticsController::class, 'individualPerformance'])->name('performance.individual');
        Route::get('/performance/kpi', [PerformanceAnalyticsController::class, 'kpiDashboard'])->name('performance.kpi');
        Route::get('/performance/export', [PerformanceAnalyticsController::class, 'export'])->name('performance.export');
    });

    // -------------------------------------------------------------------------
    // Report Builder
    // -------------------------------------------------------------------------

    Route::prefix('reports')->name('reports.')->group(function () {
        // Custom Reports
        Route::get('/custom', [CustomReportController::class, 'index'])->name('custom');
        Route::post('/custom/generate', [CustomReportController::class, 'generate'])->name('custom.generate');
        Route::get('/custom/{report}', [CustomReportController::class, 'show'])->name('custom.show');
        Route::post('/custom/{report}/save', [CustomReportController::class, 'save'])->name('custom.save');
        Route::delete('/custom/{report}', [CustomReportController::class, 'destroy'])->name('custom.destroy');
        Route::get('/custom/{report}/export', [CustomReportController::class, 'export'])->name('custom.export');

        // Executive Summary
        Route::get('/executive-summary', [ExecutiveSummaryController::class, 'index'])->name('executive-summary');
        Route::post('/executive-summary/generate', [ExecutiveSummaryController::class, 'generate'])->name('executive-summary.generate');
        Route::get('/executive-summary/{summary}', [ExecutiveSummaryController::class, 'show'])->name('executive-summary.show');
        Route::get('/executive-summary/{summary}/download', [ExecutiveSummaryController::class, 'download'])->name('executive-summary.download');
        Route::get('/executive-summary/{summary}/print', [ExecutiveSummaryController::class, 'print'])->name('executive-summary.print');

        // Final Event Report
        Route::get('/final-event', [FinalEventReportController::class, 'index'])->name('final-event');
        Route::get('/final-event/{event}', [FinalEventReportController::class, 'show'])->name('final-event.show');
        Route::post('/final-event/{event}/generate', [FinalEventReportController::class, 'generate'])->name('final-event.generate');
        Route::post('/final-event/{event}/publish', [FinalEventReportController::class, 'publish'])->name('final-event.publish');
        Route::get('/final-event/{event}/download', [FinalEventReportController::class, 'download'])->name('final-event.download');
        Route::get('/final-event/{event}/print', [FinalEventReportController::class, 'print'])->name('final-event.print');

        // Comparative Analysis (Year over Year)
        Route::get('/comparative', [ComparativeAnalysisController::class, 'index'])->name('comparative');
        Route::post('/comparative/generate', [ComparativeAnalysisController::class, 'generate'])->name('comparative.generate');
        Route::get('/comparative/year-over-year', [ComparativeAnalysisController::class, 'yearOverYear'])->name('comparative.yoy');
        Route::get('/comparative/event-comparison', [ComparativeAnalysisController::class, 'eventComparison'])->name('comparative.events');
        Route::get('/comparative/export', [ComparativeAnalysisController::class, 'export'])->name('comparative.export');
    });


    // =============================================================================
    // SYSTEM ADMINISTRATION
    // =============================================================================

    // Users Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
        Route::get('/{log}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('show');
        Route::delete('/clear-old', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clearOld'])->name('clear-old');
        Route::get('/export', [\App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('export');
    });

    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::post('/backup', [SettingController::class, 'backup'])->name('backup');
        Route::get('/backup/download/{file}', [SettingController::class, 'downloadBackup'])->name('backup.download');
    });
});


// =============================================================================
// FALLBACK ROUTE - 404 Handler
// =============================================================================

// Custom 404 Page (optional)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
