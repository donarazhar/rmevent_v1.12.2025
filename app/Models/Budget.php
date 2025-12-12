<?php
// app/Models/Budget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'budget_code',
        'title',
        'description',
        'fiscal_year',
        'version',
        'parent_budget_id',
        'total_planned',
        'total_approved',
        'total_allocated',
        'total_spent',
        'total_remaining',
        'valid_from',
        'valid_until',
        'status',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'approved_by',
        'approved_at',
        'approval_notes',
        'revision_reason',
        'rejection_reason',
        'attachments',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_planned' => 'decimal:2',
            'total_approved' => 'decimal:2',
            'total_allocated' => 'decimal:2',
            'total_spent' => 'decimal:2',
            'total_remaining' => 'decimal:2',
            'valid_from' => 'date',
            'valid_until' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'attachments' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function parentBudget()
    {
        return $this->belongsTo(Budget::class, 'parent_budget_id');
    }

    public function revisions()
    {
        return $this->hasMany(Budget::class, 'parent_budget_id');
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function allocations()
    {
        return $this->hasMany(BudgetAllocation::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByFiscalYear($query, $year)
    {
        return $query->where('fiscal_year', $year);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'active']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted', 'under_review']);
    }

    public function scopeLatestVersion($query)
    {
        return $query->whereNull('parent_budget_id')
            ->orWhereNotIn('id', function ($q) {
                $q->select('parent_budget_id')
                    ->from('budgets')
                    ->whereNotNull('parent_budget_id');
            });
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getUtilizationRateAttribute()
    {
        if ($this->total_approved == 0) {
            return 0;
        }
        return round(($this->total_spent / $this->total_approved) * 100, 2);
    }

    public function getVarianceAttribute()
    {
        return $this->total_approved - $this->total_spent;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->total_approved == 0) {
            return 0;
        }
        return round((($this->total_approved - $this->total_spent) / $this->total_approved) * 100, 2);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
            $this->valid_from <= now() &&
            $this->valid_until >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until < now();
    }

    // ========================================
    // METHODS
    // ========================================

    public function calculateTotals()
    {
        $this->total_planned = $this->items()->sum('planned_amount');
        $this->total_approved = $this->items()->sum('approved_amount');
        $this->total_allocated = $this->allocations()->sum('allocated_amount');
        $this->total_spent = $this->expenses()->where('status', 'paid')->sum('paid_amount');
        $this->total_remaining = $this->total_approved - $this->total_spent;

        $this->save();
    }

    public function submit($submitterId)
    {
        $this->update([
            'status' => 'submitted',
            'submitted_by' => $submitterId,
            'submitted_at' => now(),
        ]);
    }

    public function approve($approverId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'total_approved' => $this->total_planned,
            'approved_by' => $approverId,
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);

        // Update all items to approved
        $this->items()->update(['approved_amount' => \DB::raw('planned_amount')]);
    }

    public function reject($reviewerId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    public function requestRevision($reviewerId, $reason)
    {
        $this->update([
            'status' => 'revised',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'revision_reason' => $reason,
        ]);
    }

    public function createRevision()
    {
        $newBudget = $this->replicate();
        $newBudget->parent_budget_id = $this->id;
        $newBudget->version = $this->version + 1;
        $newBudget->status = 'draft';
        $newBudget->submitted_by = null;
        $newBudget->submitted_at = null;
        $newBudget->reviewed_by = null;
        $newBudget->reviewed_at = null;
        $newBudget->approved_by = null;
        $newBudget->approved_at = null;
        $newBudget->save();

        // Copy items
        foreach ($this->items as $item) {
            $newItem = $item->replicate();
            $newItem->budget_id = $newBudget->id;
            $newItem->save();
        }

        return $newBudget;
    }
}
