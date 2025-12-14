<?php
// app/Models/Template.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'template_code',
        'name',
        'description',
        'category',
        'file_type',
        'file_path',
        'file_size',
        'usage_instructions',
        'variables',
        'tags',
        'preview_image',
        'preview_description',
        'status',
        'created_by',
        'download_count',
        'usage_count',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'tags' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByFileType($query, $fileType)
    {
        return $query->where('file_type', $fileType);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('download_count', 'desc')->limit($limit);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // ========================================
    // METHODS
    // ========================================

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public function incrementUsageCount()
    {
        $this->increment('usage_count');
    }

    public function duplicate($userId)
    {
        $newTemplate = $this->replicate();
        $newTemplate->template_code = $this->generateUniqueCode();
        $newTemplate->name = $this->name . ' (Copy)';
        $newTemplate->created_by = $userId;
        $newTemplate->download_count = 0;
        $newTemplate->usage_count = 0;
        $newTemplate->save();

        return $newTemplate;
    }

    private function generateUniqueCode()
    {
        do {
            $code = 'TPL-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('template_code', $code)->exists());

        return $code;
    }
}