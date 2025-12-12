<?php
// app/Models/Milestone.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'timeline_id',
        'name',
        'code',
        'description',
        'target_date',
        'actual_date',
        'success_criteria',
        'deliverables',
        'progress_percentage',
        'status',
        'priority',
        'responsible_person',
        'structure_id',
        'completion_notes',
        'completion_proof',
        'completed_by',
        'completed_at',
        'is_verified',
        'verified_by',
        'verified_at',
        'verification_notes',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'actual_date' => 'date',
            'success_criteria' => 'array',
            'deliverables' => 'array',
            'completion_proof' => 'array',
            'is_verified' => 'boolean',
            'completed_at' => 'datetime',
            'verified_at' => 'datetime',
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

    public function timeline()
    {
        return $this->belongsTo(ProjectTimeline::class, 'timeline_id');
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsible_person');
    }

    public function structure()
    {
        return $this->belongsTo(CommitteeStructure::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('target_date', '>', now())
            ->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('target_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('target_date');
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsOverdueAttribute()
    {
        return $this->target_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDaysUntilDueAttribute()
    {
        if ($this->status === 'completed') {
            return null;
        }
        return now()->diffInDays($this->target_date, false);
    }

    // ========================================
    // METHODS
    // ========================================

    public function complete($userId, $notes = null, $proof = [])
    {
        $this->update([
            'status' => 'completed',
            'actual_date' => now(),
            'progress_percentage' => 100,
            'completed_by' => $userId,
            'completed_at' => now(),
            'completion_notes' => $notes,
            'completion_proof' => $proof,
        ]);
    }

    public function verify($userId, $notes = null)
    {
        $this->update([
            'is_verified' => true,
            'verified_by' => $userId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    public function reopen()
    {
        $this->update([
            'status' => 'in_progress',
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }
}
