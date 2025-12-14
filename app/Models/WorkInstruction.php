<?php
// app/Models/WorkInstruction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkInstruction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sop_id',
        'instruction_code',
        'title',
        'description',
        'category',
        'content',
        'steps',
        'tools_required',
        'materials_required',
        'safety_notes',
        'precautions',
        'estimated_time',
        'difficulty_level',
        'related_sops',
        'related_templates',
        'attachments',
        'version',
        'effective_date',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'view_count',
        'download_count',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'steps' => 'array',
            'tools_required' => 'array',
            'materials_required' => 'array',
            'precautions' => 'array',
            'related_sops' => 'array',
            'related_templates' => 'array',
            'attachments' => 'array',
            'effective_date' => 'date',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function sop()
    {
        return $this->belongsTo(SOP::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getEstimatedTimeFormattedAttribute()
    {
        if (!$this->estimated_time) {
            return null;
        }

        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;

        if ($hours > 0) {
            return "{$hours} jam " . ($minutes > 0 ? "{$minutes} menit" : '');
        }

        return "{$minutes} menit";
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

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
