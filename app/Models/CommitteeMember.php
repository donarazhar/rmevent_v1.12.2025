<?php
// app/Models/CommitteeMember.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommitteeMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'structure_id',
        'user_id',
        'position',
        'specific_role',
        'start_date',
        'end_date',
        'status',
        'tasks_completed',
        'tasks_assigned',
        'performance_score',
        'notes',
        'assigned_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'performance_score' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStructure($query, $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getCompletionRateAttribute()
    {
        if ($this->tasks_assigned == 0) {
            return 0;
        }
        return round(($this->tasks_completed / $this->tasks_assigned) * 100, 2);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               (!$this->end_date || $this->end_date->isFuture());
    }
}