<?php
// app/Models/PerformanceEvaluation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'evaluator_id',
        'structure_id',
        'event_id',
        'evaluation_code',
        'period_type',
        'period_start',
        'period_end',
        'task_completion_score',
        'quality_score',
        'teamwork_score',
        'initiative_score',
        'leadership_score',
        'discipline_score',
        'overall_score',
        'strengths',
        'weaknesses',
        'recommendations',
        'achievements',
        'improvement_areas',
        'tasks_completed',
        'tasks_assigned',
        'attendance_days',
        'total_days',
        'status',
        'approved_by',
        'submitted_at',
        'approved_at',
        'evaluator_comments',
        'employee_feedback',
        'employee_acknowledged_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'task_completion_score' => 'decimal:2',
            'quality_score' => 'decimal:2',
            'teamwork_score' => 'decimal:2',
            'initiative_score' => 'decimal:2',
            'leadership_score' => 'decimal:2',
            'discipline_score' => 'decimal:2',
            'overall_score' => 'decimal:2',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'employee_acknowledged_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEvaluator($query, $evaluatorId)
    {
        return $query->where('evaluator_id', $evaluatorId);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end]);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted']);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getAttendanceRateAttribute()
    {
        if ($this->total_days == 0) {
            return 0;
        }
        return round(($this->attendance_days / $this->total_days) * 100, 2);
    }

    public function getTaskCompletionRateAttribute()
    {
        if ($this->tasks_assigned == 0) {
            return 0;
        }
        return round(($this->tasks_completed / $this->tasks_assigned) * 100, 2);
    }

    public function getPerformanceLevelAttribute()
    {
        if (!$this->overall_score) {
            return 'Not Evaluated';
        }

        if ($this->overall_score >= 4.5) return 'Outstanding';
        if ($this->overall_score >= 4.0) return 'Excellent';
        if ($this->overall_score >= 3.5) return 'Very Good';
        if ($this->overall_score >= 3.0) return 'Good';
        if ($this->overall_score >= 2.5) return 'Satisfactory';
        return 'Needs Improvement';
    }

    // ========================================
    // METHODS
    // ========================================

    public function calculateOverallScore()
    {
        $scores = [
            $this->task_completion_score,
            $this->quality_score,
            $this->teamwork_score,
            $this->initiative_score,
            $this->leadership_score,
            $this->discipline_score,
        ];

        $validScores = array_filter($scores, fn($score) => $score !== null);

        if (count($validScores) === 0) {
            return null;
        }

        $average = array_sum($validScores) / count($validScores);

        $this->update(['overall_score' => round($average, 2)]);

        return $this->overall_score;
    }

    public function submit()
    {
        $this->calculateOverallScore();

        $this->update([
            'status' => 'submitted',
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
}
