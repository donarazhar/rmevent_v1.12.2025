<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Post;
use App\Models\User;
use App\Models\Feedback;
use App\Models\ContactMessage;
use App\Models\ActivityLog;
use App\Models\Budget;
use App\Models\BudgetAllocation;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Sponsorship;
use App\Models\CommitteeStructure;
use App\Models\CommitteeMember;
use App\Models\JobDescription;
use App\Models\ProjectTimeline;
use App\Models\Milestone;
use App\Models\ProgressReport;
use App\Models\PerformanceEvaluation;
use App\Models\Document;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========================================
        // 1. OVERVIEW STATISTICS
        // ========================================
        $stats = $this->getOverviewStats();

        // ========================================
        // 2. FINANCIAL SUMMARY
        // ========================================
        $financialSummary = $this->getFinancialSummary();

        // ========================================
        // 3. EVENT & REGISTRATION DATA
        // ========================================
        $recentRegistrations = EventRegistration::with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $upcomingEvents = Event::published()
            ->upcoming()
            ->with('category')
            ->orderBy('start_datetime')
            ->take(5)
            ->get();

        $topEvents = Event::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take(5)
            ->get();

        // ========================================
        // 4. COMMITTEE & PERFORMANCE
        // ========================================
        $committeeStats = $this->getCommitteeStats();
        $performanceStats = $this->getPerformanceStats();

        // ========================================
        // 5. PROJECT TIMELINE & MILESTONES
        // ========================================
        $timelineStats = $this->getTimelineStats();
        $upcomingMilestones = Milestone::with(['responsiblePerson', 'structure'])
            ->where('status', '!=', 'completed')
            ->orderBy('target_date')
            ->take(5)
            ->get();

        $overdueMilestones = Milestone::with(['responsiblePerson', 'structure'])
            ->where('target_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('target_date')
            ->take(5)
            ->get();

        // ========================================
        // 6. RECENT ACTIVITIES & MESSAGES
        // ========================================
        $recentActivities = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $newMessages = ContactMessage::new()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingFeedbacks = Feedback::where('status', Feedback::STATUS_NEW)
            ->with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========================================
        // 7. PROPOSALS & DOCUMENTS
        // ========================================
        $proposalStats = $this->getProposalStats();
        $pendingProposals = Proposal::with(['createdBy', 'structure'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========================================
        // 8. SPONSORSHIP SUMMARY
        // ========================================
        $sponsorshipStats = $this->getSponsorshipStats();

        // ========================================
        // 9. CHART DATA
        // ========================================
        $chartData = $this->getChartData();

        // ========================================
        // 10. ALERTS & NOTIFICATIONS
        // ========================================
        $alerts = $this->getSystemAlerts();

        return view('admin.dashboard', compact(
            'stats',
            'financialSummary',
            'recentRegistrations',
            'upcomingEvents',
            'topEvents',
            'committeeStats',
            'performanceStats',
            'timelineStats',
            'upcomingMilestones',
            'overdueMilestones',
            'recentActivities',
            'newMessages',
            'pendingFeedbacks',
            'proposalStats',
            'pendingProposals',
            'sponsorshipStats',
            'chartData',
            'alerts'
        ));
    }

    /**
     * Get Overview Statistics
     */
    private function getOverviewStats(): array
    {
        return [
            // Events
            'total_events' => Event::count(),
            'active_events' => Event::published()->where('start_datetime', '<=', now())->where('end_datetime', '>=', now())->count(),
            'upcoming_events' => Event::published()->upcoming()->count(),
            'completed_events' => Event::where('status', 'completed')->count(),

            // Registrations
            'total_registrations' => EventRegistration::count(),
            'confirmed_registrations' => EventRegistration::confirmed()->count(),
            'pending_registrations' => EventRegistration::pending()->count(),
            'attended_registrations' => EventRegistration::attended()->count(),
            'today_registrations' => EventRegistration::whereDate('created_at', today())->count(),
            'this_week_registrations' => EventRegistration::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),

            // Content
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            // 'total_documents' => Document::count(),

            // Users
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'jamaah_count' => User::jamaah()->count(),
            'panitia_count' => User::panitia()->count(),
            'admin_count' => User::admins()->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),

            // Engagement
            'new_feedbacks' => Feedback::where('status', Feedback::STATUS_NEW)->count(),
            'total_feedbacks' => Feedback::count(),
            'avg_rating' => round(Feedback::whereNotNull('overall_rating')->avg('overall_rating'), 1) ?? 0,
            'new_messages' => ContactMessage::new()->count(),
        ];
    }

    /**
     * Get Financial Summary
     */
    private function getFinancialSummary(): array
    {
        $activeBudget = Budget::where('status', 'active')->first();

        $totalBudgetPlanned = Budget::approved()->sum('total_planned');
        $totalBudgetApproved = Budget::approved()->sum('total_approved');
        $totalSpent = Expense::paid()->sum('paid_amount');
        $totalIncome = Income::where('status', 'received')->sum('amount');

        $pendingExpenses = Expense::pending()->sum('requested_amount');
        $approvedExpenses = Expense::approved()->sum('approved_amount');

        return [
            'total_budget_planned' => $totalBudgetPlanned,
            'total_budget_approved' => $totalBudgetApproved,
            'total_spent' => $totalSpent,
            'total_income' => $totalIncome,
            'budget_remaining' => $totalBudgetApproved - $totalSpent,
            'budget_utilization' => $totalBudgetApproved > 0 ? round(($totalSpent / $totalBudgetApproved) * 100, 1) : 0,
            'pending_expenses' => $pendingExpenses,
            'pending_expenses_count' => Expense::pending()->count(),
            'approved_expenses' => $approvedExpenses,
            'this_month_expenses' => Expense::paid()->whereMonth('payment_date', now()->month)->sum('paid_amount'),
            'this_month_income' => Income::where('status', 'received')->whereMonth('received_date', now()->month)->sum('amount'),
            'cash_flow' => $totalIncome - $totalSpent,
            'active_budget' => $activeBudget,
        ];
    }

    /**
     * Get Committee Statistics
     */
    private function getCommitteeStats(): array
    {
        return [
            'total_structures' => CommitteeStructure::count(),
            'active_structures' => CommitteeStructure::active()->count(),
            'total_members' => CommitteeMember::count(),
            'active_members' => CommitteeMember::active()->count(),
            'total_jobdescs' => JobDescription::count(),
            'active_jobdescs' => JobDescription::active()->count(),
            'unfilled_positions' => JobDescription::active()->whereColumn('assigned_members', '<', 'required_members')->count(),
            'structures_by_level' => CommitteeStructure::select('level', DB::raw('COUNT(*) as count'))
                ->groupBy('level')
                ->orderBy('level')
                ->pluck('count', 'level')
                ->toArray(),
        ];
    }

    /**
     * Get Performance Statistics
     */
    private function getPerformanceStats(): array
    {
        $evaluations = PerformanceEvaluation::approved();

        return [
            'total_evaluations' => PerformanceEvaluation::count(),
            'pending_evaluations' => PerformanceEvaluation::pending()->count(),
            'approved_evaluations' => $evaluations->count(),
            'avg_overall_score' => round($evaluations->avg('overall_score'), 2) ?? 0,
            'avg_task_completion' => round($evaluations->avg('task_completion_score'), 2) ?? 0,
            'avg_quality' => round($evaluations->avg('quality_score'), 2) ?? 0,
            'avg_teamwork' => round($evaluations->avg('teamwork_score'), 2) ?? 0,
            'top_performers' => PerformanceEvaluation::with('user')
                ->approved()
                ->orderByDesc('overall_score')
                ->take(5)
                ->get(),
            'needs_improvement' => PerformanceEvaluation::with('user')
                ->approved()
                ->where('overall_score', '<', 3)
                ->count(),
        ];
    }

    /**
     * Get Timeline Statistics
     */
    private function getTimelineStats(): array
    {
        return [
            'total_timelines' => ProjectTimeline::count(),
            'in_progress' => ProjectTimeline::inProgress()->count(),
            'completed' => ProjectTimeline::where('status', 'completed')->count(),
            'delayed' => ProjectTimeline::delayed()->count(),
            'not_started' => ProjectTimeline::where('status', 'not_started')->count(),
            'overall_progress' => round(ProjectTimeline::avg('progress_percentage'), 1) ?? 0,
            'total_milestones' => Milestone::count(),
            'completed_milestones' => Milestone::completed()->count(),
            'overdue_milestones' => Milestone::overdue()->count(),
            'upcoming_milestones' => Milestone::upcoming()->count(),
            'milestone_completion_rate' => Milestone::count() > 0
                ? round((Milestone::completed()->count() / Milestone::count()) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get Proposal Statistics
     */
    private function getProposalStats(): array
    {
        return [
            'total_proposals' => Proposal::count(),
            'draft' => Proposal::draft()->count(),
            'submitted' => Proposal::submitted()->count(),
            'under_review' => Proposal::underReview()->count(),
            'approved' => Proposal::approved()->count(),
            'rejected' => Proposal::rejected()->count(),
            'total_requested_amount' => Proposal::sum('requested_amount'),
            'total_approved_amount' => Proposal::approved()->sum('approved_amount'),
            'approval_rate' => Proposal::whereIn('status', ['approved', 'rejected'])->count() > 0
                ? round((Proposal::approved()->count() / Proposal::whereIn('status', ['approved', 'rejected'])->count()) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get Sponsorship Statistics
     */
    private function getSponsorshipStats(): array
    {
        return [
            'total_sponsors' => Sponsorship::count(),
            'confirmed_sponsors' => Sponsorship::confirmed()->count(),
            'pending_sponsors' => Sponsorship::pending()->count(),
            'total_committed' => Sponsorship::confirmed()->sum('committed_amount'),
            'total_received' => Sponsorship::sum('received_amount'),
            'total_outstanding' => Sponsorship::sum('outstanding_amount'),
            'collection_rate' => Sponsorship::confirmed()->sum('committed_amount') > 0
                ? round((Sponsorship::sum('received_amount') / Sponsorship::confirmed()->sum('committed_amount')) * 100, 1)
                : 0,
            'by_tier' => Sponsorship::confirmed()
                ->select('tier', DB::raw('COUNT(*) as count'), DB::raw('SUM(committed_amount) as total'))
                ->groupBy('tier')
                ->get()
                ->keyBy('tier')
                ->toArray(),
        ];
    }

    /**
     * Get Chart Data
     */
    private function getChartData(): array
    {
        // Registration trend (last 30 days)
        $registrationTrend = EventRegistration::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates
        $registrationLabels = [];
        $registrationData = [];
        $startDate = now()->subDays(29);
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $registrationLabels[] = $startDate->copy()->addDays($i)->format('d M');
            $found = $registrationTrend->firstWhere('date', $date);
            $registrationData[] = $found ? $found->count : 0;
        }

        // Events by Category
        $eventsByCategory = Event::select('categories.name', DB::raw('COUNT(*) as count'))
            ->leftJoin('categories', 'events.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Expense by Category (last 6 months)
        $expenseByCategory = Expense::select('category', DB::raw('SUM(paid_amount) as total'))
            ->paid()
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        // Monthly Financial Trend (last 6 months)
        $monthlyFinancial = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyFinancial[] = [
                'month' => $month->format('M Y'),
                'income' => Income::where('status', 'received')
                    ->whereMonth('received_date', $month->month)
                    ->whereYear('received_date', $month->year)
                    ->sum('amount'),
                'expense' => Expense::paid()
                    ->whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->sum('paid_amount'),
            ];
        }

        // Registration by Status
        $registrationByStatus = EventRegistration::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Timeline Progress Distribution
        $timelineProgress = ProjectTimeline::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Milestone Status
        $milestoneStatus = Milestone::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'registrations' => [
                'labels' => $registrationLabels,
                'data' => $registrationData,
            ],
            'events_by_category' => [
                'labels' => $eventsByCategory->pluck('name')->toArray(),
                'data' => $eventsByCategory->pluck('count')->toArray(),
            ],
            'expense_by_category' => [
                'labels' => $expenseByCategory->pluck('category')->toArray(),
                'data' => $expenseByCategory->pluck('total')->toArray(),
            ],
            'monthly_financial' => [
                'labels' => collect($monthlyFinancial)->pluck('month')->toArray(),
                'income' => collect($monthlyFinancial)->pluck('income')->toArray(),
                'expense' => collect($monthlyFinancial)->pluck('expense')->toArray(),
            ],
            'registration_by_status' => [
                'labels' => $registrationByStatus->pluck('status')->map(fn($s) => ucfirst($s))->toArray(),
                'data' => $registrationByStatus->pluck('count')->toArray(),
            ],
            'timeline_progress' => [
                'labels' => $timelineProgress->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
                'data' => $timelineProgress->pluck('count')->toArray(),
            ],
            'milestone_status' => [
                'labels' => $milestoneStatus->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))->toArray(),
                'data' => $milestoneStatus->pluck('count')->toArray(),
            ],
        ];
    }

    /**
     * Get System Alerts
     */
    private function getSystemAlerts(): array
    {
        $alerts = [];

        // Overdue milestones
        $overdueMilestones = Milestone::overdue()->count();
        if ($overdueMilestones > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'title' => 'Milestone Terlambat',
                'message' => "{$overdueMilestones} milestone melewati target tanggal",
                'link' => route('admin.milestone.index', ['filter' => 'overdue']),
            ];
        }

        // Pending expenses
        $pendingExpenses = Expense::pending()->count();
        if ($pendingExpenses > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'clock',
                'title' => 'Pengeluaran Menunggu Approval',
                'message' => "{$pendingExpenses} pengajuan pengeluaran menunggu persetujuan",
                'link' => route('admin.expenses.index', ['status' => 'pending']),
            ];
        }

        // Low budget utilization or over budget
        $budgetUtilization = Budget::approved()->first();
        if ($budgetUtilization) {
            $utilization = $budgetUtilization->utilization_rate;
            if ($utilization > 90) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'exclamation-circle',
                    'title' => 'Anggaran Hampir Habis',
                    'message' => "Penggunaan anggaran sudah mencapai {$utilization}%",
                    'link' => route('admin.budgets.index'),
                ];
            }
        }

        // Unfilled positions
        $unfilledPositions = JobDescription::active()->whereColumn('assigned_members', '<', 'required_members')->count();
        if ($unfilledPositions > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'users',
                'title' => 'Posisi Belum Terisi',
                'message' => "{$unfilledPositions} posisi masih membutuhkan anggota",
                'link' => route('admin.jobdescs.index'),
            ];
        }

        // Delayed timelines
        $delayedTimelines = ProjectTimeline::delayed()->count();
        if ($delayedTimelines > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'calendar-times',
                'title' => 'Timeline Terlambat',
                'message' => "{$delayedTimelines} timeline mengalami keterlambatan",
                'link' => route('admin.timeline.index', ['status' => 'delayed']),
            ];
        }

        // Pending proposals
        $pendingProposals = Proposal::pending()->count();
        if ($pendingProposals > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'file-alt',
                'title' => 'Proposal Menunggu Review',
                'message' => "{$pendingProposals} proposal menunggu ditinjau",
                'link' => route('admin.proposals.index', ['status' => 'pending']),
            ];
        }

        // Outstanding sponsorship payments
        $outstandingSponsorship = Sponsorship::withOutstanding()->count();
        if ($outstandingSponsorship > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'money-bill',
                'title' => 'Pembayaran Sponsor Outstanding',
                'message' => "{$outstandingSponsorship} sponsor dengan pembayaran tertunda",
                'link' => route('admin.sponsorships.index', ['filter' => 'outstanding']),
            ];
        }

        // New feedbacks needing attention
        $newFeedbacks = Feedback::where('status', Feedback::STATUS_NEW)->count();
        if ($newFeedbacks > 5) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'comment',
                'title' => 'Feedback Baru',
                'message' => "{$newFeedbacks} feedback baru menunggu respon",
                'link' => route('admin.feedbacks.index', ['status' => 'new']),
            ];
        }

        return $alerts;
    }
}
