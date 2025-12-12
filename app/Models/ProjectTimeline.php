<?php
// app/Models/ProjectTimeline.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTimeline extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'parent_id',
        'structure_id',
        'name',
        'description',
        'code',
        'level',
        'order',
        'start_date',
        'end_date',
        'actual_start_date',
        'actual_end_date',
        'duration_days',
        'assigned_to',
        'team_members',
        'progress_percentage',
        'status',
        'priority',
        'dependencies',
        'estimated_budget',
        'actual_budget',
        'estimated_hours',
        'actual_hours',
        'notes',
        'completion_notes',
        'attachments',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'actual_start_date' => 'date',
            'actual_end_date' => 'date',
            'team_members' => 'array',
            'dependencies' => 'array',
            'attachments' => 'array',
            'estimated_budget' => 'decimal:2',
            'actual_budget' => 'decimal:2',
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

    public function parent()
    {
        return $this->belongsTo(ProjectTimeline::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProjectTimeline::class, 'parent_id')->orderBy('order');
    }

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'timeline_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeDelayed($query)
    {
        return $query->where('status', 'delayed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'not_started')
            ->where('start_date', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsOverdueAttribute()
    {
        return $this->end_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->status === 'completed') {
            return 0;
        }
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getBudgetVarianceAttribute()
    {
        if (!$this->estimated_budget) {
            return null;
        }
        return $this->actual_budget - $this->estimated_budget;
    }

    // ========================================
    // METHODS
    // ========================================

    public function updateProgress($percentage)
    {
        $this->progress_percentage = min(100, max(0, $percentage));

        if ($this->progress_percentage == 100 && $this->status != 'completed') {
            $this->status = 'completed';
            $this->actual_end_date = now();
        } elseif ($this->progress_percentage > 0 && $this->status == 'not_started') {
            $this->status = 'in_progress';
            $this->actual_start_date = now();
        }

        $this->save();
    }

    public function checkDelayed()
    {
        if ($this->end_date->isPast() && $this->status == 'in_progress') {
            $this->update(['status' => 'delayed']);
        }
    }
}
