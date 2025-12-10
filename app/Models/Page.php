<?php

namespace App\Models;

use App\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes, HasMedia;

    /**
     * Status Constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'sections',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'parent_id',
        'order',
        'status',
        'show_in_menu',
        'is_homepage',
        'custom_css',
        'custom_js',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'custom_css' => 'array',
            'custom_js' => 'array',
            'order' => 'integer',
            'show_in_menu' => 'boolean',
            'is_homepage' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('order');
    }

    /**
     * SCOPES
     */

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    /**
     * HELPER METHODS
     */

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isHomepage(): bool
    {
        return $this->is_homepage;
    }
}