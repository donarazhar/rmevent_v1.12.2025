<?php
// app/Models/JobAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_description_id',
        'user_id',
        'assigned_date',
        'expected_completion_date',
        'actual_completion_date',
        'status',
        'progress_percentage',
        'notes',
        'completion_notes',
        'rating',
        'assigned_by',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'expected_completion_date' => 'date',
            'actual_completion_date' => 'date',
            'rating' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class);
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
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_completion_date', '<', now())
            ->whereIn('status', ['assigned', 'in_progress']);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsOverdueAttribute()
    {
        return $this->expected_completion_date &&
            $this->expected_completion_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->expected_completion_date || $this->status === 'completed') {
            return null;
        }
        return now()->diffInDays($this->expected_completion_date, false);
    }

    // ========================================
    // METHODS
    // ========================================

    public function markAsStarted()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted($notes = null)
    {
        $this->update([
            'status' => 'completed',
            'actual_completion_date' => now(),
            'completed_at' => now(),
            'progress_percentage' => 100,
            'completion_notes' => $notes,
        ]);
    }
}
