<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Income;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    /**
     * Display a listing of incomes.
     */
    public function index(Request $request)
    {
        $query = Income::with(['event', 'budget', 'registration', 'sponsorship', 'verifier', 'recorder']);

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

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('received_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('received_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('income_code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('source_name', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'received_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $incomes = $query->paginate(15)->withQueryString();

        // Summary statistics
        $stats = [
            'total_count' => Income::count(),
            'total_amount' => Income::verified()->sum('amount'),
            'pending_amount' => Income::pending()->sum('amount'),
            'verified_count' => Income::verified()->count(),
            'pending_count' => Income::pending()->count(),
            'rejected_count' => Income::where('status', 'rejected')->count(),
        ];

        // Category breakdown
        $categoryStats = Income::verified()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $events = Event::orderBy('title')->get();

        return view('admin.finance.incomes.index', compact('incomes', 'stats', 'categoryStats', 'events'));
    }

    /**
     * Show the form for creating a new income.
     */
    public function create()
    {
        $events = Event::orderBy('title')->get();
        $budgets = Budget::where('status', 'active')->orderBy('budget_code')->get();
        $registrations = EventRegistration::where('status', 'confirmed')->orderBy('registration_code')->get();
        $sponsorships = Sponsorship::whereIn('status', ['confirmed', 'delivered'])->orderBy('sponsor_code')->get();
        
        // Generate income code
        $lastIncome = Income::latest('id')->first();
        $nextNumber = $lastIncome ? (intval(substr($lastIncome->income_code, -3)) + 1) : 1;
        $incomeCode = 'IN-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.finance.incomes.create', compact('events', 'budgets', 'registrations', 'sponsorships', 'incomeCode'));
    }

    /**
     * Store a newly created income in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'budget_id' => 'nullable|exists:budgets,id',
            'income_code' => 'required|unique:incomes,income_code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:registration_fee,sponsorship,donation,infaq,merchandise,grant,other',
            'source_name' => 'required|string|max:255',
            'source_contact' => 'nullable|string|max:50',
            'source_email' => 'nullable|email|max:255',
            'registration_id' => 'nullable|exists:event_registrations,id',
            'sponsorship_id' => 'nullable|exists:sponsorships,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet,credit_card,check,other',
            'payment_reference' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'received_date' => 'nullable|date',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('incomes/receipts', 'public');
        }

        // Set received_date to payment_date if not provided
        if (!$validated['received_date']) {
            $validated['received_date'] = $validated['payment_date'];
        }

        $validated['recorded_by'] = Auth::id();

        $income = Income::create($validated);

        // Generate receipt number if verified
        if ($income->status === 'verified') {
            $income->generateReceiptNumber();
        }

        return redirect()->route('admin.incomes.index')
            ->with('success', 'Income berhasil ditambahkan!');
    }

    /**
     * Display the specified income.
     */
    public function show(Income $income)
    {
        $income->load(['event', 'budget', 'registration', 'sponsorship', 'verifier', 'recorder']);
        
        return view('admin.finance.incomes.show', compact('income'));
    }

    /**
     * Show the form for editing the specified income.
     */
    public function edit(Income $income)
    {
        $events = Event::orderBy('title')->get();
        $budgets = Budget::where('status', 'active')->orderBy('budget_code')->get();
        $registrations = EventRegistration::where('status', 'confirmed')->orderBy('registration_code')->get();
        $sponsorships = Sponsorship::whereIn('status', ['confirmed', 'delivered'])->orderBy('sponsor_code')->get();

        return view('admin.finance.incomes.edit', compact('income', 'events', 'budgets', 'registrations', 'sponsorships'));
    }

    /**
     * Update the specified income in storage.
     */
    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'budget_id' => 'nullable|exists:budgets,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:registration_fee,sponsorship,donation,infaq,merchandise,grant,other',
            'source_name' => 'required|string|max:255',
            'source_contact' => 'nullable|string|max:50',
            'source_email' => 'nullable|email|max:255',
            'registration_id' => 'nullable|exists:event_registrations,id',
            'sponsorship_id' => 'nullable|exists:sponsorships,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet,credit_card,check,other',
            'payment_reference' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'received_date' => 'nullable|date',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('receipt_file')) {
            if ($income->receipt_file) {
                Storage::disk('public')->delete($income->receipt_file);
            }
            $validated['receipt_file'] = $request->file('receipt_file')->store('incomes/receipts', 'public');
        }

        // Set received_date to payment_date if not provided
        if (!$validated['received_date']) {
            $validated['received_date'] = $validated['payment_date'];
        }

        $income->update($validated);

        return redirect()->route('admin.incomes.index')
            ->with('success', 'Income berhasil diperbarui!');
    }

    /**
     * Remove the specified income from storage.
     */
    public function destroy(Income $income)
    {
        // Delete associated file
        if ($income->receipt_file) {
            Storage::disk('public')->delete($income->receipt_file);
        }

        $income->delete();

        return redirect()->route('admin.incomes.index')
            ->with('success', 'Income berhasil dihapus!');
    }

    /**
     * Verify income.
     */
    public function verify(Request $request, Income $income)
    {
        $request->validate([
            'verification_notes' => 'nullable|string',
        ]);

        $income->verify(Auth::id(), $request->verification_notes);

        // Generate receipt number if not exists
        if (!$income->receipt_number) {
            $income->generateReceiptNumber();
        }

        return back()->with('success', 'Income berhasil diverifikasi!');
    }

    /**
     * Bulk verify incomes.
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'income_ids' => 'required|array',
            'income_ids.*' => 'exists:incomes,id',
            'verification_notes' => 'nullable|string',
        ]);

        $verified = 0;
        foreach ($request->income_ids as $id) {
            $income = Income::find($id);
            if ($income && $income->status === 'pending') {
                $income->verify(Auth::id(), $request->verification_notes);
                
                // Generate receipt number if not exists
                if (!$income->receipt_number) {
                    $income->generateReceiptNumber();
                }
                
                $verified++;
            }
        }

        return back()->with('success', "{$verified} income berhasil diverifikasi!");
    }

    /**
     * Generate receipt for income.
     */
    public function generateReceipt(Income $income)
    {
        // Generate receipt number if not exists
        if (!$income->receipt_number) {
            $income->generateReceiptNumber();
        }

        // This is a placeholder - implement PDF generation logic
        // You can use packages like barryvdh/laravel-dompdf or similar
        
        return back()->with('info', 'Receipt generation feature coming soon! Receipt Number: ' . $income->receipt_number);
    }

    /**
     * Export incomes data.
     */
    public function export(Request $request)
    {
        // This is a placeholder - implement export logic (Excel/CSV)
        // You can use packages like maatwebsite/excel or similar
        
        return back()->with('info', 'Export feature coming soon!');
    }
}