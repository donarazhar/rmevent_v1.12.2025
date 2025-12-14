<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'budget_id',
        'budget_item_id',
        'budget_allocation_id',
        'structure_id',
        'expense_code',
        'title',
        'description',
        'category',
        'vendor_name',
        'vendor_contact',
        'vendor_address',
        'vendor_tax_id',
        'requested_amount',
        'approved_amount',
        'paid_amount',
        'request_date',
        'needed_by_date',
        'approved_date',
        'payment_date',
        'payment_method',
        'payment_reference',
        'bank_account',
        'status',
        'requested_by',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'approved_by',
        'approved_at',
        'approval_notes',
        'paid_by',
        'paid_at',
        'receipt_file',
        'invoice_file',
        'supporting_documents',
        'tax_amount',
        'tax_type',
        'has_tax_invoice',
        'notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'requested_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'request_date' => 'date',
            'needed_by_date' => 'date',
            'approved_date' => 'date',
            'payment_date' => 'date',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
            'supporting_documents' => 'array',
            'has_tax_invoice' => 'boolean',
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

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function budgetItem()
    {
        return $this->belongsTo(BudgetItem::class);
    }

    public function budgetAllocation()
    {
        return $this->belongsTo(BudgetAllocation::class);
    }

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted', 'under_review']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('payment_date', [$start, $end]);
    }

    public function scopeByRequester($query, $userId)
    {
        return $query->where('requested_by', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('needed_by_date', '<', now())
            ->whereNotIn('status', ['paid', 'cancelled']);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsOverdueAttribute()
    {
        return $this->needed_by_date && 
               $this->needed_by_date->isPast() && 
               !in_array($this->status, ['paid', 'cancelled']);
    }

    public function getVarianceAttribute()
    {
        return $this->approved_amount - $this->paid_amount;
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->paid_amount ?? $this->approved_amount ?? $this->requested_amount, 0, ',', '.');
    }

    public function getTotalAmountWithTaxAttribute()
    {
        return $this->requested_amount + ($this->tax_amount ?? 0);
    }

    // ========================================
    // METHODS
    // ========================================

    public function submit()
    {
        $this->update(['status' => 'submitted']);
    }

    public function review($reviewerId, $notes = null)
    {
        $this->update([
            'status' => 'under_review',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);
    }

    public function approve($approverId, $approvedAmount = null, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'approved_date' => now(),
            'approved_amount' => $approvedAmount ?? $this->requested_amount,
            'approval_notes' => $notes,
        ]);
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

    public function markAsPaid($payerId, $paidAmount = null, $paymentDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_by' => $payerId,
            'paid_at' => now(),
            'payment_date' => $paymentDate ?? now(),
            'paid_amount' => $paidAmount ?? $this->approved_amount,
        ]);

        // Update related records
        if ($this->budget) {
            $this->budget->calculateTotals();
        }

        if ($this->budgetItem) {
            $this->budgetItem->updateSpentAmount();
        }

        if ($this->budgetAllocation) {
            $this->budgetAllocation->updateSpentAmount();
        }
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => ($this->notes ? $this->notes . "\n" : '') . "Cancelled: {$reason}",
        ]);
    }

    public static function generateExpenseCode()
    {
        $date = now();
        $prefix = 'EXP';
        $year = $date->format('Y');
        $month = $date->format('m');
        
        $lastExpense = self::where('expense_code', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('expense_code', 'desc')
            ->first();
        
        if ($lastExpense) {
            $lastNumber = (int) substr($lastExpense->expense_code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }
}