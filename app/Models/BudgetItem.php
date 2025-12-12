<?php
// app/Models/BudgetItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'budget_id',
        'parent_id',
        'code',
        'name',
        'description',
        'category',
        'level',
        'order',
        'quantity',
        'unit',
        'unit_price',
        'subtotal',
        'planned_amount',
        'approved_amount',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
        'priority',
        'is_mandatory',
        'notes',
        'justification',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'planned_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'allocated_amount' => 'decimal:2',
            'spent_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'is_mandatory' => 'boolean',
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

    public function parent()
    {
        return $this->belongsTo(BudgetItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BudgetItem::class, 'parent_id')->orderBy('order');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'budget_item_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByBudget($query, $budgetId)
    {
        return $query->where('budget_id', $budgetId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getUtilizationRateAttribute()
    {
        if ($this->approved_amount == 0) {
            return 0;
        }
        return round(($this->spent_amount / $this->approved_amount) * 100, 2);
    }

    public function getVarianceAttribute()
    {
        return $this->approved_amount - $this->spent_amount;
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->spent_amount > $this->approved_amount;
    }

    // ========================================
    // METHODS
    // ========================================

    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->unit_price;
        $this->planned_amount = $this->subtotal;
        $this->save();
    }

    public function updateSpentAmount()
    {
        $this->spent_amount = $this->expenses()
            ->where('status', 'paid')
            ->sum('paid_amount');
        
        $this->remaining_amount = $this->approved_amount - $this->spent_amount;
        $this->save();
    }
}