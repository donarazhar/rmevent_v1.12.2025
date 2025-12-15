<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetAllocation;
use App\Models\CommitteeStructure;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetAllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = BudgetAllocation::with(['budget', 'structure', 'event', 'allocatedTo', 'creator'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('budget_id')) {
            $query->where('budget_id', $request->budget_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('structure_id')) {
            $query->where('structure_id', $request->structure_id);
        }

        if ($request->filled('allocation_type')) {
            $query->where('allocation_type', $request->allocation_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('allocation_code', 'like', '%' . $request->search . '%');
            });
        }

        $allocations = $query->paginate(15)->withQueryString();

        $budgets = Budget::where('status', 'approved')->get();
        $events = Event::all();
        $structures = CommitteeStructure::all();

        return view('admin.finance.allocations.index', compact(
            'allocations',
            'budgets',
            'events',
            'structures'
        ));
    }

    public function create()
    {
        $budgets = Budget::where('status', 'approved')->get();
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();

        return view('admin.finance.allocations.create', compact(
            'budgets',
            'events',
            'structures',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allocation_type' => 'required|in:operational,program,project,reserve,contingency',
            'allocated_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'allocated_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Check budget availability
            $budget = Budget::find($validated['budget_id']);
            $totalAllocated = $budget->allocations()->sum('allocated_amount');
            $available = $budget->total_approved - $totalAllocated;

            if ($validated['allocated_amount'] > $available) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah alokasi melebihi budget yang tersedia! Available: Rp ' . number_format($available, 0, ',', '.'));
            }

            // Generate allocation code
            $validated['allocation_code'] = $this->generateAllocationCode();
            $validated['created_by'] = Auth::id();
            $validated['status'] = 'active';
            $validated['spent_amount'] = 0;
            $validated['remaining_amount'] = $validated['allocated_amount'];
            $validated['committed_amount'] = 0;

            // Create allocation
            $allocation = BudgetAllocation::create($validated);

            // Update budget total allocated
            $budget->total_allocated = $budget->allocations()->sum('allocated_amount');
            $budget->save();

            DB::commit();

            return redirect()->route('admin.budget-allocations.show', $allocation)
                ->with('success', 'Alokasi budget berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat alokasi: ' . $e->getMessage());
        }
    }

    public function show(BudgetAllocation $budgetAllocation)
    {
        $budgetAllocation->load([
            'budget',
            'structure',
            'event',
            'allocatedTo',
            'approver',
            'creator',
            'expenses'
        ]);

        return view('admin.finance.allocations.show', compact('budgetAllocation'));
    }

    public function edit(BudgetAllocation $budgetAllocation)
    {
        if (!in_array($budgetAllocation->status, ['active', 'pending'])) {
            return redirect()->route('admin.budget-allocations.show', $budgetAllocation)
                ->with('error', 'Alokasi yang sudah depleted tidak dapat diedit!');
        }

        $budgets = Budget::where('status', 'approved')->get();
        $events = Event::all();
        $structures = CommitteeStructure::all();
        $users = User::all();

        return view('admin.finance.allocations.edit', compact(
            'budgetAllocation',
            'budgets',
            'events',
            'structures',
            'users'
        ));
    }

    public function update(Request $request, BudgetAllocation $budgetAllocation)
    {
        $validated = $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'structure_id' => 'nullable|exists:committee_structures,id',
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allocation_type' => 'required|in:operational,program,project,reserve,contingency',
            'allocated_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'allocated_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Check if allocated_amount changed
            if ($validated['allocated_amount'] != $budgetAllocation->allocated_amount) {
                $budget = Budget::find($validated['budget_id']);
                $totalAllocated = $budget->allocations()
                    ->where('id', '!=', $budgetAllocation->id)
                    ->sum('allocated_amount');
                $available = $budget->total_approved - $totalAllocated;

                if ($validated['allocated_amount'] > $available) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Jumlah alokasi melebihi budget yang tersedia! Available: Rp ' . number_format($available, 0, ',', '.'));
                }

                // Update remaining amount
                $difference = $validated['allocated_amount'] - $budgetAllocation->allocated_amount;
                $validated['remaining_amount'] = $budgetAllocation->remaining_amount + $difference;
            }

            // Update allocation
            $budgetAllocation->update($validated);

            // Update budget total allocated
            $budget = Budget::find($validated['budget_id']);
            $budget->total_allocated = $budget->allocations()->sum('allocated_amount');
            $budget->save();

            DB::commit();

            return redirect()->route('admin.budget-allocations.show', $budgetAllocation)
                ->with('success', 'Alokasi budget berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate alokasi: ' . $e->getMessage());
        }
    }

    public function destroy(BudgetAllocation $budgetAllocation)
    {
        // Check if has expenses
        if ($budgetAllocation->expenses()->exists()) {
            return redirect()->route('admin.budget-allocations.index')
                ->with('error', 'Alokasi dengan transaksi tidak dapat dihapus!');
        }

        DB::beginTransaction();
        try {
            $budget = $budgetAllocation->budget;

            $budgetAllocation->delete();

            // Update budget total allocated
            $budget->total_allocated = $budget->allocations()->sum('allocated_amount');
            $budget->save();

            DB::commit();

            return redirect()->route('admin.budget-allocations.index')
                ->with('success', 'Alokasi budget berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus alokasi: ' . $e->getMessage());
        }
    }

    public function transfer(Request $request, BudgetAllocation $budgetAllocation)
    {
        $request->validate([
            'target_allocation_id' => 'required|exists:budget_allocations,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $targetAllocation = BudgetAllocation::findOrFail($request->target_allocation_id);

            if ($request->amount > $budgetAllocation->available_amount) {
                return redirect()->back()
                    ->with('error', 'Jumlah transfer melebihi saldo yang tersedia!');
            }

            $budgetAllocation->transfer($targetAllocation, $request->amount, $request->notes);

            DB::commit();

            return redirect()->route('admin.budget-allocations.show', $budgetAllocation)
                ->with('success', 'Transfer budget berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal transfer budget: ' . $e->getMessage());
        }
    }

    public function adjust(Request $request, BudgetAllocation $budgetAllocation)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'reason' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $budgetAllocation->adjust($request->amount, $request->reason);

            DB::commit();

            return redirect()->route('admin.budget-allocations.show', $budgetAllocation)
                ->with('success', 'Adjustment budget berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal adjust budget: ' . $e->getMessage());
        }
    }

    public function byDivision($divisionId)
    {
        $allocations = BudgetAllocation::where('structure_id', $divisionId)
            ->with(['budget', 'event', 'allocatedTo'])
            ->get();

        return response()->json($allocations);
    }

    public function byEvent($eventId)
    {
        $allocations = BudgetAllocation::where('event_id', $eventId)
            ->with(['budget', 'structure', 'allocatedTo'])
            ->get();

        return response()->json($allocations);
    }

    private function generateAllocationCode()
    {
        $year = date('Y');
        $month = date('m');

        $lastAllocation = BudgetAllocation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastAllocation ? intval(substr($lastAllocation->allocation_code, -4)) + 1 : 1;

        return 'ALLOC-' . $year . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
