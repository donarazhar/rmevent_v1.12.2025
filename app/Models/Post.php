<?php

namespace App\Models;

use App\Models\Traits\HasMedia;
use App\Models\Traits\HasTags;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasMedia, HasTags, LogsActivity;

    /**
     * Status Constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'scheduled_at',
        'views_count',
        'reading_time',
        'is_featured',
        'allow_comments',
        'is_sticky',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'views_count' => 'integer',
            'reading_time' => 'integer',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'is_sticky' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * SCOPES
     */

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    public function scopeRecent($query, int $limit = 5)
    {
        return $query->published()
                     ->orderBy('published_at', 'desc')
                     ->limit($limit);
    }

    public function scopePopular($query, int $limit = 5)
    {
        return $query->published()
                     ->orderBy('views_count', 'desc')
                     ->limit($limit);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /**
     * ACCESSORS
     */

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image 
            ? asset('storage/' . $this->featured_image) 
            : asset('images/default-post.jpg');
    }

    public function getExcerptOrContentAttribute()
    {
        return $this->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($this->content), 200);
    }

    /**
     * HELPER METHODS
     */

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED 
            && $this->published_at 
            && $this->published_at->isPast();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function publish(): bool
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->published_at = now();
        return $this->save();
    }

    public function unpublish(): bool
    {
        $this->status = self::STATUS_DRAFT;
        return $this->save();
    }
}