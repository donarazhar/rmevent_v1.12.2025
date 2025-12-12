<?php
// app/Models/CommitteeStructure.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommitteeStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'parent_id',
        'name',
        'code',
        'description',
        'level',
        'order',
        'leader_id',
        'vice_leader_id',
        'email',
        'phone',
        'status',
        'responsibilities',
        'authorities',
    ];

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'authorities' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Event that this structure belongs to
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Parent structure (for hierarchy)
     */
    public function parent()
    {
        return $this->belongsTo(CommitteeStructure::class, 'parent_id');
    }

    /**
     * Child structures (subordinates)
     */
    public function children()
    {
        return $this->hasMany(CommitteeStructure::class, 'parent_id')->orderBy('order');
    }

    /**
     * All descendants (recursive)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Leader of this structure
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Vice leader of this structure
     */
    public function viceLeader()
    {
        return $this->belongsTo(User::class, 'vice_leader_id');
    }

    /**
     * Members of this structure
     */
    public function members()
    {
        return $this->hasMany(CommitteeMember::class, 'structure_id');
    }

    /**
     * Active members only
     */
    public function activeMembers()
    {
        return $this->members()->where('status', 'active');
    }

    /**
     * Job descriptions for this structure
     */
    public function jobDescriptions()
    {
        return $this->hasMany(JobDescription::class, 'structure_id');
    }

    /**
     * Budget allocations for this structure
     */
    public function budgetAllocations()
    {
        return $this->hasMany(BudgetAllocation::class, 'structure_id');
    }

    /**
     * Progress reports from this structure
     */
    public function progressReports()
    {
        return $this->hasMany(ProgressReport::class, 'structure_id');
    }

    /**
     * Project timelines assigned to this structure
     */
    public function timelines()
    {
        return $this->hasMany(ProjectTimeline::class, 'structure_id');
    }

    /**
     * Milestones assigned to this structure
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'structure_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->orWhere('level', 1);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->full_name . ' > ' . $this->name;
        }
        return $this->name;
    }

    public function getTotalMembersAttribute()
    {
        return $this->members()->count();
    }

    public function getActiveMembersCountAttribute()
    {
        return $this->activeMembers()->count();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if this structure has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get all ancestors (parent chain)
     */
    public function getAncestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Get hierarchy tree
     */
    public static function getTree($eventId = null)
    {
        $query = self::with('children', 'leader', 'viceLeader')
            ->whereNull('parent_id')
            ->ordered();

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        return $query->get();
    }
}