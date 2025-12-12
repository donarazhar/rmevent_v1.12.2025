<?php
// app/Models/BudgetAllocation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAllocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'budget_id',
        'structure_id',
        'event_id',
        'allocation_code',
        'title',
        'description',
        'allocation_type',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
        'committed_amount',
        'valid_from',
        'valid_until',
        'status',
        'allocated_to',
        'approved_by',
        'approved_at',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'allocated_amount' => 'decimal:2',
            'spent_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'committed_amount' => 'decimal:2',
            'valid_from' => 'date',
            'valid_until' => 'date',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function allocatedTo()
    {
        return $this->belongsTo(User::class, 'allocated_to');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'budget_allocation_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeByBudget($query, $budgetId)
    {
        return $query->where('budget_id', $budgetId);
    }

    public function scopeByStructure($query, $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('allocation_type', $type);
    }

    public function scopeDepleted($query)
    {
        return $query->where('remaining_amount', '<=', 0);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getUtilizationRateAttribute()
    {
        if ($this->allocated_amount == 0) {
            return 0;
        }
        return round(($this->spent_amount / $this->allocated_amount) * 100, 2);
    }

    public function getAvailableAmountAttribute()
    {
        return $this->remaining_amount - $this->committed_amount;
    }

    public function getIsDepletedAttribute()
    {
        return $this->available_amount <= 0;
    }

    // ========================================
    // METHODS
    // ========================================

    public function updateSpentAmount()
    {
        $this->spent_amount = $this->expenses()
            ->where('status', 'paid')
            ->sum('paid_amount');
        
        $this->remaining_amount = $this->allocated_amount - $this->spent_amount;
        
        if ($this->remaining_amount <= 0 && $this->status === 'active') {
            $this->status = 'depleted';
        }
        
        $this->save();
    }

    public function transfer($targetAllocation, $amount, $notes = null)
    {
        if ($amount > $this->available_amount) {
            throw new \Exception('Insufficient available amount for transfer');
        }

        \DB::transaction(function () use ($targetAllocation, $amount, $notes) {
            // Reduce from source
            $this->allocated_amount -= $amount;
            $this->remaining_amount -= $amount;
            $this->notes = ($this->notes ? $this->notes . "\n" : '') . 
                          "Transferred {$amount} to {$targetAllocation->allocation_code}: {$notes}";
            $this->save();

            // Add to target
            $targetAllocation->allocated_amount += $amount;
            $targetAllocation->remaining_amount += $amount;
            $targetAllocation->notes = ($targetAllocation->notes ? $targetAllocation->notes . "\n" : '') . 
                                      "Received {$amount} from {$this->allocation_code}: {$notes}";
            $targetAllocation->save();
        });
    }

    public function adjust($amount, $reason)
    {
        $this->allocated_amount += $amount;
        $this->remaining_amount += $amount;
        $this->notes = ($this->notes ? $this->notes . "\n" : '') . 
                      "Adjusted by {$amount}: {$reason}";
        $this->save();
    }
}