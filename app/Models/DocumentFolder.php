<?php
// app/Models/DocumentFolder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentFolder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'event_id',
        'name',
        'description',
        'path',
        'level',
        'order',
        'visibility',
        'allowed_users',
        'allowed_structures',
        'color',
        'icon',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'allowed_users' => 'array',
            'allowed_structures' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function parent()
    {
        return $this->belongsTo(DocumentFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DocumentFolder::class, 'parent_id')->orderBy('order');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('visibility', 'public')
              ->orWhere('created_by', $userId)
              ->orWhereJsonContains('allowed_users', $userId);
        });
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }

    public function getTotalDocumentsAttribute()
    {
        $count = $this->documents()->count();
        
        foreach ($this->children as $child) {
            $count += $child->total_documents;
        }
        
        return $count;
    }

    // ========================================
    // METHODS
    // ========================================

    public function updatePath()
    {
        if ($this->parent) {
            $this->path = $this->parent->path . '/' . $this->name;
        } else {
            $this->path = '/' . $this->name;
        }
        
        $this->save();
        
        // Update children paths
        foreach ($this->children as $child) {
            $child->updatePath();
        }
    }

    public function canAccess($userId)
    {
        if ($this->visibility === 'public') {
            return true;
        }
        
        if ($this->created_by === $userId) {
            return true;
        }
        
        if (in_array($userId, $this->allowed_users ?? [])) {
            return true;
        }
        
        return false;
    }
}