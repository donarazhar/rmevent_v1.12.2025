<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'taggable');
    }

    /**
     * SCOPES
     */

    public function scopePopular($query, int $limit = 10)
    {
        return $query->withCount(['posts', 'events'])
                     ->orderByDesc('posts_count')
                     ->orderByDesc('events_count')
                     ->limit($limit);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * ACCESSORS
     */

    public function getUrlAttribute(): string
    {
        return route('tags.show', $this->slug);
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->posts()->count() + $this->events()->count();
    }

    /**
     * HELPER METHODS
     */

    public static function findOrCreateByName(string $name): self
    {
        $slug = \Illuminate\Support\Str::slug($name);
        
        return self::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'color' => self::randomColor(),
            ]
        );
    }

    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Generate random color for tag
     */
    private static function randomColor(): string
    {
        $colors = [
            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', 
            '#6366F1', '#8B5CF6', '#EC4899', '#14B8A6',
        ];
        return $colors[array_rand($colors)];
    }
}