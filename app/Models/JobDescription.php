<?php
// app/Models/JobDescription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'structure_id',
        'event_id',
        'title',
        'code',
        'description',
        'responsibilities',
        'requirements',
        'skills_required',
        'estimated_hours',
        'workload_level',
        'priority',
        'required_members',
        'assigned_members',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'requirements' => 'array',
            'skills_required' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'datetime',
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignments()
    {
        return $this->hasMany(JobAssignment::class, 'job_description_id');
    }

    public function activeAssignments()
    {
        return $this->assignments()->whereIn('status', ['assigned', 'in_progress']);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStructure($query, $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->whereColumn('assigned_members', '<', 'required_members');
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsFulfilledAttribute()
    {
        return $this->assigned_members >= $this->required_members;
    }

    public function getRemainingPositionsAttribute()
    {
        return max(0, $this->required_members - $this->assigned_members);
    }

    public function getFulfillmentRateAttribute()
    {
        if ($this->required_members == 0) {
            return 100;
        }
        return round(($this->assigned_members / $this->required_members) * 100, 2);
    }
}