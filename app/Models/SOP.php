<?php
// app/Models/SOP.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SOP extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sops';

    protected $fillable = [
        'sop_code',
        'title',
        'purpose',
        'scope',
        'category',
        'content',
        'procedures',
        'responsibilities',
        'related_forms',
        'related_templates',
        'attachments',
        'version',
        'parent_sop_id',
        'version_notes',
        'effective_date',
        'review_date',
        'expiry_date',
        'status',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'notes',
        'view_count',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'procedures' => 'array',
            'responsibilities' => 'array',
            'related_forms' => 'array',
            'related_templates' => 'array',
            'attachments' => 'array',
            'effective_date' => 'date',
            'review_date' => 'date',
            'expiry_date' => 'date',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function parentSOP()
    {
        return $this->belongsTo(SOP::class, 'parent_sop_id');
    }

    public function versions()
    {
        return $this->hasMany(SOP::class, 'parent_sop_id')->orderBy('version', 'desc');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function workInstructions()
    {
        return $this->hasMany(WorkInstruction::class, 'sop_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeNeedingReview($query)
    {
        return $query->where('status', 'published')
            ->where('review_date', '<=', now());
    }

    public function scopeLatestVersions($query)
    {
        return $query->whereNull('parent_sop_id')
            ->orWhereNotIn('id', function ($q) {
                $q->select('parent_sop_id')
                    ->from('sops')
                    ->whereNotNull('parent_sop_id');
            });
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsActiveAttribute()
    {
        return $this->status === 'published' &&
            $this->effective_date <= now() &&
            (!$this->expiry_date || $this->expiry_date >= now());
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getNeedsReviewAttribute()
    {
        return $this->review_date && $this->review_date->isPast();
    }

    // ========================================
    // METHODS
    // ========================================

    public function publish($approverId)
    {
        $this->update([
            'status' => 'published',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function createNewVersion($userId, $versionNotes = null)
    {
        $newSOP = $this->replicate();
        $newSOP->parent_sop_id = $this->id;
        $newSOP->version = $this->getNextVersion();
        $newSOP->version_notes = $versionNotes;
        $newSOP->status = 'draft';
        $newSOP->created_by = $userId;
        $newSOP->approved_by = null;
        $newSOP->approved_at = null;
        $newSOP->save();

        return $newSOP;
    }

    private function getNextVersion()
    {
        $parts = explode('.', $this->version);
        $parts[count($parts) - 1]++;
        return implode('.', $parts);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
