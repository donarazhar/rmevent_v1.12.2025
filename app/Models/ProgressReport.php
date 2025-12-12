<?php
// app/Models/ProgressReport.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'structure_id',
        'timeline_id',
        'report_code',
        'title',
        'report_type',
        'period_start',
        'period_end',
        'report_date',
        'executive_summary',
        'activities_completed',
        'ongoing_activities',
        'planned_activities',
        'issues_challenges',
        'solutions_recommendations',
        'overall_progress',
        'tasks_planned',
        'tasks_completed',
        'tasks_delayed',
        'budget_allocated',
        'budget_used',
        'budget_variance',
        'team_members_involved',
        'hours_spent',
        'attachments',
        'status',
        'created_by',
        'submitted_to',
        'approved_by',
        'submitted_at',
        'approved_at',
        'reviewer_feedback',
        'approval_notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'report_date' => 'date',
            'budget_allocated' => 'decimal:2',
            'budget_used' => 'decimal:2',
            'budget_variance' => 'decimal:2',
            'attachments' => 'array',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
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

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function timeline()
    {
        return $this->belongsTo(ProjectTimeline::class, 'timeline_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submittedTo()
    {
        return $this->belongsTo(User::class, 'submitted_to');
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

    public function scopeByStructure($query, $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted']);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('report_date', '>=', now()->subDays($days));
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getTaskCompletionRateAttribute()
    {
        if ($this->tasks_planned == 0) {
            return 0;
        }
        return round(($this->tasks_completed / $this->tasks_planned) * 100, 2);
    }

    public function getBudgetUtilizationAttribute()
    {
        if (!$this->budget_allocated || $this->budget_allocated == 0) {
            return 0;
        }
        return round(($this->budget_used / $this->budget_allocated) * 100, 2);
    }

    // ========================================
    // METHODS
    // ========================================

    public function submit($submittedToId)
    {
        $this->update([
            'status' => 'submitted',
            'submitted_to' => $submittedToId,
            'submitted_at' => now(),
        ]);
    }

    public function approve($approverId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);
    }

    public function reject($feedback)
    {
        $this->update([
            'status' => 'draft',
            'reviewer_feedback' => $feedback,
        ]);
    }
}