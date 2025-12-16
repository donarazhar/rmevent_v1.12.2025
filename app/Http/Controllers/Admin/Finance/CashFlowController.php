<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    /**
     * Display cash flow overview/dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : now()->endOfMonth();

        // Calculate cash flow summary
        $summary = $this->calculateCashFlowSummary($startDate, $endDate);

        // Get daily cash flow data for chart
        $dailyCashFlow = $this->getDailyCashFlow($startDate, $endDate);

        // Get category breakdown
        $categoryBreakdown = $this->getCategoryBreakdown($startDate, $endDate);

        // Get recent transactions
        $recentIncomes = Income::with(['event', 'verifier'])
            ->whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->orderBy('received_date', 'desc')
            ->limit(5)
            ->get();

        $recentExpenses = Expense::with(['event', 'payer'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Get events for filter
        $events = Event::orderBy('title')->get();

        return view('admin.finance.cash-flow.index', compact(
            'summary',
            'dailyCashFlow',
            'categoryBreakdown',
            'recentIncomes',
            'recentExpenses',
            'events',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display daily cash flow report
     */
    public function daily(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : now();

        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        // Get daily incomes
        $incomes = Income::with(['event', 'verifier'])
            ->whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->orderBy('received_date', 'desc')
            ->get();

        // Get daily expenses
        $expenses = Expense::with(['event', 'payer'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('paid_amount');
        $netCashFlow = $totalIncome - $totalExpense;

        // Get previous day for comparison
        $previousDate = $date->copy()->subDay();
        $previousDayData = $this->calculateCashFlowSummary(
            $previousDate->startOfDay(),
            $previousDate->endOfDay()
        );

        $stats = [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_cash_flow' => $netCashFlow,
            'income_count' => $incomes->count(),
            'expense_count' => $expenses->count(),
            'previous_day' => $previousDayData,
        ];

        return view('admin.finance.cash-flow.daily', compact(
            'incomes',
            'expenses',
            'stats',
            'date'
        ));
    }

    /**
     * Display monthly cash flow report
     */
    public function monthly(Request $request)
    {
        $year = $request->filled('year') ? $request->year : now()->year;
        $month = $request->filled('month') ? $request->month : now()->month;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get monthly summary
        $summary = $this->calculateCashFlowSummary($startDate, $endDate);

        // Get daily breakdown for the month
        $dailyBreakdown = $this->getDailyBreakdown($startDate, $endDate);

        // Get weekly breakdown
        $weeklyBreakdown = $this->getWeeklyBreakdown($startDate, $endDate);

        // Get category breakdown
        $categoryBreakdown = $this->getCategoryBreakdown($startDate, $endDate);

        // Compare with previous month
        $previousMonth = $startDate->copy()->subMonth();
        $previousMonthData = $this->calculateCashFlowSummary(
            $previousMonth->startOfMonth(),
            $previousMonth->endOfMonth()
        );

        // Get year-to-date comparison
        $ytdStart = Carbon::createFromDate($year, 1, 1);
        $ytdData = $this->calculateCashFlowSummary($ytdStart, $endDate);

        return view('admin.finance.cash-flow.monthly', compact(
            'summary',
            'dailyBreakdown',
            'weeklyBreakdown',
            'categoryBreakdown',
            'previousMonthData',
            'ytdData',
            'year',
            'month',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display cash flow by category
     */
    public function byCategory(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : now()->endOfMonth();

        // Get income by category
        $incomeByCategory = Income::select('category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Get expense by category
        $expenseByCategory = Expense::select('category', DB::raw('SUM(paid_amount) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $incomeByCategory->sum('total');
        $totalExpense = $expenseByCategory->sum('total');

        // Get detailed transactions for selected category
        $selectedCategory = $request->get('category');
        $selectedType = $request->get('type', 'income');

        $categoryTransactions = collect();
        if ($selectedCategory) {
            if ($selectedType === 'income') {
                $categoryTransactions = Income::with(['event', 'verifier'])
                    ->where('category', $selectedCategory)
                    ->whereBetween('received_date', [$startDate, $endDate])
                    ->where('status', 'verified')
                    ->orderBy('received_date', 'desc')
                    ->get();
            } else {
                $categoryTransactions = Expense::with(['event', 'payer'])
                    ->where('category', $selectedCategory)
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->where('status', 'paid')
                    ->orderBy('payment_date', 'desc')
                    ->get();
            }
        }

        return view('admin.finance.cash-flow.by-category', compact(
            'incomeByCategory',
            'expenseByCategory',
            'totalIncome',
            'totalExpense',
            'categoryTransactions',
            'selectedCategory',
            'selectedType',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display cash flow projection
     */
    public function projection(Request $request)
    {
        $months = $request->filled('months') ? (int)$request->months : 3;

        // Get historical data (last 6 months)
        $historicalStart = now()->subMonths(6)->startOfMonth();
        $historicalEnd = now()->endOfMonth();
        $historicalData = $this->getMonthlyData($historicalStart, $historicalEnd);

        // Calculate averages for projection
        $avgIncome = collect($historicalData)->avg('income');
        $avgExpense = collect($historicalData)->avg('expense');

        // Generate projections
        $projections = [];
        $currentBalance = $this->getCurrentBalance();

        for ($i = 1; $i <= $months; $i++) {
            $projectionDate = now()->addMonths($i);

            // Simple projection based on averages
            // You can implement more sophisticated algorithms
            $projectedIncome = $avgIncome;
            $projectedExpense = $avgExpense;
            $projectedNet = $projectedIncome - $projectedExpense;
            $currentBalance += $projectedNet;

            $projections[] = [
                'month' => $projectionDate->format('F Y'),
                'date' => $projectionDate,
                'projected_income' => $projectedIncome,
                'projected_expense' => $projectedExpense,
                'projected_net' => $projectedNet,
                'projected_balance' => $currentBalance,
            ];
        }

        // Get pending transactions that affect projection
        $pendingIncomes = Income::where('status', 'pending')
            ->where('received_date', '>=', now())
            ->orderBy('received_date')
            ->get();

        $pendingExpenses = Expense::whereIn('status', ['submitted', 'under_review', 'approved'])
            ->where('needed_by_date', '>=', now())
            ->orderBy('needed_by_date')
            ->get();

        return view('admin.finance.cash-flow.projection', compact(
            'historicalData',
            'projections',
            'pendingIncomes',
            'pendingExpenses',
            'months',
            'avgIncome',
            'avgExpense'
        ));
    }

    /**
     * Export cash flow data
     */
    public function export(Request $request)
    {
        // This is a placeholder - implement export logic (Excel/CSV)
        // You can use packages like maatwebsite/excel or similar

        return back()->with('info', 'Export feature coming soon!');
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Calculate cash flow summary for a date range
     */
    private function calculateCashFlowSummary($startDate, $endDate)
    {
        $totalIncome = Income::whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->sum('amount');

        $totalExpense = Expense::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('paid_amount');

        $netCashFlow = $totalIncome - $totalExpense;

        $incomeCount = Income::whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->count();

        $expenseCount = Expense::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->count();

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_cash_flow' => $netCashFlow,
            'income_count' => $incomeCount,
            'expense_count' => $expenseCount,
        ];
    }

    /**
     * Get daily cash flow data
     */
    private function getDailyCashFlow($startDate, $endDate)
    {
        $days = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            $income = Income::whereBetween('received_date', [$dayStart, $dayEnd])
                ->where('status', 'verified')
                ->sum('amount');

            $expense = Expense::whereBetween('payment_date', [$dayStart, $dayEnd])
                ->where('status', 'paid')
                ->sum('paid_amount');

            $days[] = [
                'date' => $currentDate->format('Y-m-d'),
                'display_date' => $currentDate->format('d M'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
            ];

            $currentDate->addDay();
        }

        return $days;
    }

    /**
     * Get category breakdown
     */
    private function getCategoryBreakdown($startDate, $endDate)
    {
        $incomeCategories = Income::select('category', DB::raw('SUM(amount) as total'))
            ->whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $expenseCategories = Expense::select('category', DB::raw('SUM(paid_amount) as total'))
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return [
            'income' => $incomeCategories,
            'expense' => $expenseCategories,
        ];
    }

    /**
     * Get daily breakdown for monthly view
     */
    private function getDailyBreakdown($startDate, $endDate)
    {
        return $this->getDailyCashFlow($startDate, $endDate);
    }

    /**
     * Get weekly breakdown
     */
    private function getWeeklyBreakdown($startDate, $endDate)
    {
        $weeks = [];
        $currentDate = $startDate->copy()->startOfWeek();

        while ($currentDate->lte($endDate)) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->endOfWeek();

            // Ensure we don't go beyond the month
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }

            $income = Income::whereBetween('received_date', [$weekStart, $weekEnd])
                ->where('status', 'verified')
                ->sum('amount');

            $expense = Expense::whereBetween('payment_date', [$weekStart, $weekEnd])
                ->where('status', 'paid')
                ->sum('paid_amount');

            $weeks[] = [
                'week' => 'Week ' . $weekStart->weekOfMonth,
                'start_date' => $weekStart->format('d M'),
                'end_date' => $weekEnd->format('d M'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
            ];

            $currentDate->addWeek();
        }

        return $weeks;
    }

    /**
     * Get monthly data for historical analysis
     */
    private function getMonthlyData($startDate, $endDate)
    {
        $months = [];
        $currentDate = $startDate->copy()->startOfMonth();

        while ($currentDate->lte($endDate)) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            $income = Income::whereBetween('received_date', [$monthStart, $monthEnd])
                ->where('status', 'verified')
                ->sum('amount');

            $expense = Expense::whereBetween('payment_date', [$monthStart, $monthEnd])
                ->where('status', 'paid')
                ->sum('paid_amount');

            $months[] = [
                'month' => $currentDate->format('M Y'),
                'date' => $currentDate->format('Y-m'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
            ];

            $currentDate->addMonth();
        }

        return $months;
    }

    /**
     * Get current balance
     */
    private function getCurrentBalance()
    {
        $totalIncome = Income::where('status', 'verified')->sum('amount');
        $totalExpense = Expense::where('status', 'paid')->sum('paid_amount');

        return $totalIncome - $totalExpense;
    }
}
