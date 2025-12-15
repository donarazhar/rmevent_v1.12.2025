<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['event', 'creator', 'approver'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', $request->fiscal_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('budget_code', 'like', '%' . $request->search . '%');
            });
        }

        $budgets = $query->paginate(15)->withQueryString();
        $events = Event::all();

        // Get available fiscal years
        $fiscalYears = Budget::distinct()->pluck('fiscal_year')->sort()->values();

        return view('admin.finance.budgets.index', compact(
            'budgets',
            'events',
            'fiscalYears'
        ));
    }

    public function create()
    {
        $events = Event::all();
        $users = User::all();

        return view('admin.finance.budgets.create', compact('events', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fiscal_year' => 'required|integer|min:2020|max:2100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',

            // Budget Items
            'items' => 'required|array|min:1',
            'items.*.code' => 'required|string|max:50',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.priority' => 'required|in:low,medium,high,critical',
            'items.*.is_mandatory' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Generate budget code
            $validated['budget_code'] = $this->generateBudgetCode($validated['fiscal_year']);
            $validated['created_by'] = Auth::id();
            $validated['version'] = 1;
            $validated['status'] = 'draft';

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('budgets', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                    ];
                }
                $validated['attachments'] = $attachments;
            }

            // Calculate totals
            $totalPlanned = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalPlanned += $subtotal;
            }
            $validated['total_planned'] = $totalPlanned;
            $validated['total_remaining'] = $totalPlanned;

            // Create budget
            $budget = Budget::create($validated);

            // Create budget items
            foreach ($validated['items'] as $index => $itemData) {
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];

                BudgetItem::create([
                    'budget_id' => $budget->id,
                    'code' => $itemData['code'],
                    'name' => $itemData['name'],
                    'description' => $itemData['description'] ?? null,
                    'category' => $itemData['category'],
                    'level' => 1,
                    'order' => $index + 1,
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                    'planned_amount' => $subtotal,
                    'priority' => $itemData['priority'],
                    'is_mandatory' => $itemData['is_mandatory'] ?? false,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat budget: ' . $e->getMessage());
        }
    }

    public function show(Budget $budget)
    {
        $budget->load(['event', 'items', 'allocations', 'creator', 'submitter', 'reviewer', 'approver']);

        return view('admin.finance.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        // Only allow editing if status is draft or revised
        if (!in_array($budget->status, ['draft', 'revised'])) {
            return redirect()->route('admin.budgets.show', $budget)
                ->with('error', 'Budget yang sudah disubmit tidak dapat diedit!');
        }

        $budget->load('items');
        $events = Event::all();
        $users = User::all();

        return view('admin.finance.budgets.edit', compact('budget', 'events', 'users'));
    }

    public function update(Request $request, Budget $budget)
    {
        // Only allow updating if status is draft or revised
        if (!in_array($budget->status, ['draft', 'revised'])) {
            return redirect()->route('admin.budgets.show', $budget)
                ->with('error', 'Budget yang sudah disubmit tidak dapat diupdate!');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fiscal_year' => 'required|integer|min:2020|max:2100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',

            // Budget Items
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:budget_items,id',
            'items.*.code' => 'required|string|max:50',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.category' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.priority' => 'required|in:low,medium,high,critical',
            'items.*.is_mandatory' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Handle new file uploads
            if ($request->hasFile('attachments')) {
                $existingAttachments = $budget->attachments ?? [];
                $newAttachments = [];

                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('budgets', 'public');
                    $newAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                    ];
                }

                $validated['attachments'] = array_merge($existingAttachments, $newAttachments);
            }

            // Calculate totals
            $totalPlanned = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalPlanned += $subtotal;
            }
            $validated['total_planned'] = $totalPlanned;
            $validated['total_remaining'] = $totalPlanned - ($budget->total_spent ?? 0);

            // Update budget
            $budget->update($validated);

            // Sync budget items
            $existingItemIds = [];
            foreach ($validated['items'] as $index => $itemData) {
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];

                $itemAttributes = [
                    'budget_id' => $budget->id,
                    'code' => $itemData['code'],
                    'name' => $itemData['name'],
                    'description' => $itemData['description'] ?? null,
                    'category' => $itemData['category'],
                    'level' => 1,
                    'order' => $index + 1,
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                    'planned_amount' => $subtotal,
                    'priority' => $itemData['priority'],
                    'is_mandatory' => $itemData['is_mandatory'] ?? false,
                ];

                if (isset($itemData['id']) && $itemData['id']) {
                    // Update existing item
                    $item = BudgetItem::find($itemData['id']);
                    if ($item && $item->budget_id == $budget->id) {
                        $item->update($itemAttributes);
                        $existingItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = BudgetItem::create($itemAttributes);
                    $existingItemIds[] = $newItem->id;
                }
            }

            // Delete removed items
            $budget->items()->whereNotIn('id', $existingItemIds)->delete();

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate budget: ' . $e->getMessage());
        }
    }

    public function destroy(Budget $budget)
    {
        // Only allow deleting if status is draft
        if ($budget->status !== 'draft') {
            return redirect()->route('admin.budgets.index')
                ->with('error', 'Hanya budget dengan status draft yang dapat dihapus!');
        }

        // Delete attachments
        if ($budget->attachments) {
            foreach ($budget->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        // Delete items
        $budget->items()->delete();

        // Delete budget
        $budget->delete();

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Budget berhasil dihapus!');
    }

    public function approve(Request $request, Budget $budget)
    {
        $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $budget->approve(Auth::id(), $request->approval_notes);

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget berhasil diapprove!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal approve budget: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Budget $budget)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $budget->reject(Auth::id(), $request->rejection_reason);

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal reject budget: ' . $e->getMessage());
        }
    }

    public function revise(Request $request, Budget $budget)
    {
        $request->validate([
            'revision_reason' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $budget->requestRevision(Auth::id(), $request->revision_reason);

            DB::commit();

            return redirect()->route('admin.budgets.show', $budget)
                ->with('success', 'Budget dikembalikan untuk revisi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal request revisi: ' . $e->getMessage());
        }
    }

    public function duplicate(Budget $budget)
    {
        DB::beginTransaction();
        try {
            $newBudget = $budget->createRevision();

            DB::commit();

            return redirect()->route('admin.budgets.edit', $newBudget)
                ->with('success', 'Budget berhasil diduplikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal duplikasi budget: ' . $e->getMessage());
        }
    }

    public function export(Budget $budget)
    {
        // Placeholder for export functionality
        return redirect()->back()->with('info', 'Export feature coming soon!');
    }

    public function print(Budget $budget)
    {
        $budget->load(['event', 'items', 'creator', 'approver']);

        return view('admin.finance.budgets.print', compact('budget'));
    }

    private function generateBudgetCode($fiscalYear)
    {
        $lastBudget = Budget::whereYear('created_at', date('Y'))
            ->where('fiscal_year', $fiscalYear)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastBudget ? intval(substr($lastBudget->budget_code, -4)) + 1 : 1;

        return 'BDG-' . $fiscalYear . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
