<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetAllocation;
use App\Models\BudgetItem;
use App\Models\CommitteeStructure;
use App\Models\Event;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['event', 'budget', 'budgetItem', 'requester', 'approver', 'payer']);

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by requester
        if ($request->filled('requested_by')) {
            $query->where('requested_by', $request->requested_by);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        // Filter overdue
        if ($request->filled('overdue') && $request->overdue == '1') {
            $query->overdue();
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('expense_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('vendor_name', 'like', "%{$search}%")
                    ->orWhere('payment_reference', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'request_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $expenses = $query->paginate(15)->withQueryString();

        // Summary statistics
        $stats = [
            'total_count' => Expense::count(),
            'total_requested' => Expense::sum('requested_amount'),
            'total_approved' => Expense::whereNotNull('approved_amount')->sum('approved_amount'),
            'total_paid' => Expense::paid()->sum('paid_amount'),
            'pending_count' => Expense::pending()->count(),
            'approved_count' => Expense::approved()->count(),
            'paid_count' => Expense::paid()->count(),
            'overdue_count' => Expense::overdue()->count(),
        ];

        // Category breakdown
        $categoryStats = Expense::paid()
            ->select('category', DB::raw('SUM(paid_amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $events = Event::orderBy('title')->get();
        $requesters = User::orderBy('name')->get();

        return view('admin.finance.expenses.index', compact('expenses', 'stats', 'categoryStats', 'events', 'requesters'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $events = Event::orderBy('title')->get();
        $budgets = Budget::where('status', 'active')->orderBy('budget_code')->get();
        $budgetItems = BudgetItem::orderBy('code')->get();
        $budgetAllocations = BudgetAllocation::orderBy('allocation_code')->get();
        $structures = CommitteeStructure::orderBy('name')->get();

        // Generate expense code
        $expenseCode = Expense::generateExpenseCode();

        return view('admin.finance.expenses.create', compact(
            'events',
            'budgets',
            'budgetItems',
            'budgetAllocations',
            'structures',
            'expenseCode'
        ));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'budget_id' => 'nullable|exists:budgets,id',
            'budget_item_id' => 'nullable|exists:budget_items,id',
            'budget_allocation_id' => 'nullable|exists:budget_allocations,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'expense_code' => 'required|unique:expenses,expense_code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:operational,event_execution,equipment,logistics,marketing,transportation,accommodation,meals,honorarium,utilities,other',
            'vendor_name' => 'required|string|max:255',
            'vendor_contact' => 'nullable|string|max:50',
            'vendor_address' => 'nullable|string',
            'vendor_tax_id' => 'nullable|string|max:50',
            'requested_amount' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'needed_by_date' => 'nullable|date|after_or_equal:request_date',
            'payment_method' => 'required|in:cash,bank_transfer,check,petty_cash,e_wallet,other',
            'bank_account' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|string|max:50',
            'has_tax_invoice' => 'nullable|boolean',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        // Handle file upload
        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('expenses/invoices', 'public');
        }

        $validated['requested_by'] = Auth::id();
        $validated['has_tax_invoice'] = $request->has('has_tax_invoice');

        $expense = Expense::create($validated);

        $message = $expense->status === 'submitted'
            ? 'Expense berhasil dibuat dan disubmit untuk review!'
            : 'Expense berhasil disimpan sebagai draft!';

        return redirect()->route('admin.expenses.index')
            ->with('success', $message);
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        $expense->load([
            'event',
            'budget',
            'budgetItem',
            'budgetAllocation',
            'structure',
            'requester',
            'reviewer',
            'approver',
            'payer'
        ]);

        return view('admin.finance.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        // Only allow edit if not yet paid
        if (in_array($expense->status, ['paid', 'cancelled'])) {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Expense yang sudah paid atau cancelled tidak bisa diedit!');
        }

        $events = Event::orderBy('title')->get();
        $budgets = Budget::where('status', 'active')->orderBy('budget_code')->get();
        $budgetItems = BudgetItem::orderBy('code')->get();
        $budgetAllocations = BudgetAllocation::orderBy('allocation_code')->get();
        $structures = CommitteeStructure::orderBy('name')->get();

        return view('admin.finance.expenses.edit', compact(
            'expense',
            'events',
            'budgets',
            'budgetItems',
            'budgetAllocations',
            'structures'
        ));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Prevent update if paid or cancelled
        if (in_array($expense->status, ['paid', 'cancelled'])) {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Expense yang sudah paid atau cancelled tidak bisa diupdate!');
        }

        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'budget_id' => 'nullable|exists:budgets,id',
            'budget_item_id' => 'nullable|exists:budget_items,id',
            'budget_allocation_id' => 'nullable|exists:budget_allocations,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:operational,event_execution,equipment,logistics,marketing,transportation,accommodation,meals,honorarium,utilities,other',
            'vendor_name' => 'required|string|max:255',
            'vendor_contact' => 'nullable|string|max:50',
            'vendor_address' => 'nullable|string',
            'vendor_tax_id' => 'nullable|string|max:50',
            'requested_amount' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'needed_by_date' => 'nullable|date|after_or_equal:request_date',
            'payment_method' => 'required|in:cash,bank_transfer,check,petty_cash,e_wallet,other',
            'bank_account' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|string|max:50',
            'has_tax_invoice' => 'nullable|boolean',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        // Handle file upload
        if ($request->hasFile('invoice_file')) {
            if ($expense->invoice_file) {
                Storage::disk('public')->delete($expense->invoice_file);
            }
            $validated['invoice_file'] = $request->file('invoice_file')->store('expenses/invoices', 'public');
        }

        $validated['has_tax_invoice'] = $request->has('has_tax_invoice');

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense berhasil diupdate!');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        // Only allow delete if draft or rejected
        if (!in_array($expense->status, ['draft', 'rejected'])) {
            return redirect()->route('admin.expenses.index')
                ->with('error', 'Hanya expense dengan status draft atau rejected yang bisa dihapus!');
        }

        // Delete associated files
        if ($expense->invoice_file) {
            Storage::disk('public')->delete($expense->invoice_file);
        }
        if ($expense->receipt_file) {
            Storage::disk('public')->delete($expense->receipt_file);
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense berhasil dihapus!');
    }

    /**
     * Approve expense.
     */
    public function approve(Request $request, Expense $expense)
    {
        if (!in_array($expense->status, ['submitted', 'under_review'])) {
            return back()->with('error', 'Expense tidak bisa diapprove!');
        }

        $request->validate([
            'approved_amount' => 'required|numeric|min:0',
            'approval_notes' => 'nullable|string',
        ]);

        $expense->approve(
            Auth::id(),
            $request->approved_amount,
            $request->approval_notes
        );

        return back()->with('success', 'Expense berhasil diapprove!');
    }

    /**
     * Reject expense.
     */
    public function reject(Request $request, Expense $expense)
    {
        if ($expense->status === 'paid') {
            return back()->with('error', 'Expense yang sudah paid tidak bisa direject!');
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $expense->reject(Auth::id(), $request->rejection_reason);

        return back()->with('success', 'Expense berhasil direject!');
    }

    /**
     * Mark expense as paid.
     */
    public function markAsPaid(Request $request, Expense $expense)
    {
        if ($expense->status !== 'approved') {
            return back()->with('error', 'Hanya expense yang sudah approved yang bisa dibayar!');
        }

        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $expense->update([
            'payment_reference' => $request->payment_reference,
        ]);

        $expense->markAsPaid(
            Auth::id(),
            $request->paid_amount,
            $request->payment_date
        );

        return back()->with('success', 'Expense berhasil ditandai sebagai paid!');
    }

    /**
     * Upload receipt for expense.
     */
    public function uploadReceipt(Request $request, Expense $expense)
    {
        $request->validate([
            'receipt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($expense->receipt_file) {
            Storage::disk('public')->delete($expense->receipt_file);
        }

        $receiptPath = $request->file('receipt_file')->store('expenses/receipts', 'public');

        $expense->update(['receipt_file' => $receiptPath]);

        return back()->with('success', 'Receipt berhasil diupload!');
    }

    /**
     * Bulk approve expenses.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'expense_ids' => 'required|array',
            'expense_ids.*' => 'exists:expenses,id',
            'approval_notes' => 'nullable|string',
        ]);

        $approved = 0;
        foreach ($request->expense_ids as $id) {
            $expense = Expense::find($id);
            if ($expense && in_array($expense->status, ['submitted', 'under_review'])) {
                $expense->approve(
                    Auth::id(),
                    $expense->requested_amount,
                    $request->approval_notes
                );
                $approved++;
            }
        }

        return back()->with('success', "{$approved} expense berhasil diapprove!");
    }

    /**
     * Export expenses data.
     */
    public function export(Request $request)
    {
        // This is a placeholder - implement export logic (Excel/CSV)
        // You can use packages like maatwebsite/excel or similar

        return back()->with('info', 'Export feature coming soon!');
    }
}
