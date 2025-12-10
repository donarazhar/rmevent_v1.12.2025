<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    /**
     * Get all tags
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Attach tags to model
     * 
     * @param array|string $tags Can be array of tag names/IDs or comma-separated string
     */
    public function attachTags($tags): void
    {
        $tagIds = $this->parseTags($tags);
        $this->tags()->syncWithoutDetaching($tagIds);
    }

    /**
     * Sync tags (remove old, add new)
     */
    public function syncTags($tags): void
    {
        $tagIds = $this->parseTags($tags);
        $this->tags()->sync($tagIds);
    }

    /**
     * Detach tags from model
     */
    public function detachTags($tags = null): void
    {
        if ($tags === null) {
            $this->tags()->detach();
            return;
        }

        $tagIds = $this->parseTags($tags);
        $this->tags()->detach($tagIds);
    }

    /**
     * Check if model has specific tag
     */
    public function hasTag($tag): bool
    {
        if (is_string($tag)) {
            return $this->tags()->where('name', $tag)->exists();
        }

        if ($tag instanceof Tag) {
            return $this->tags()->where('id', $tag->id)->exists();
        }

        return false;
    }

    /**
     * Check if model has any of the given tags
     */
    public function hasAnyTag($tags): bool
    {
        $tagIds = $this->parseTags($tags);
        return $this->tags()->whereIn('id', $tagIds)->exists();
    }

    /**
     * Check if model has all of the given tags
     */
    public function hasAllTags($tags): bool
    {
        $tagIds = $this->parseTags($tags);
        return $this->tags()->whereIn('id', $tagIds)->count() === count($tagIds);
    }

    /**
     * Get tag names as array
     */
    public function getTagNamesAttribute(): array
    {
        return $this->tags->pluck('name')->toArray();
    }

    /**
     * Get tag names as comma-separated string
     */
    public function getTagListAttribute(): string
    {
        return $this->tags->pluck('name')->implode(', ');
    }

    /**
     * Scope to filter by tags
     */
    public function scopeWithAllTags($query, $tags)
    {
        $tagIds = $this->parseTags($tags);
        
        foreach ($tagIds as $tagId) {
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }

        return $query;
    }

    /**
     * Scope to filter by any tags
     */
    public function scopeWithAnyTags($query, $tags)
    {
        $tagIds = $this->parseTags($tags);

        return $query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });
    }

    /**
     * Parse tags input to array of tag IDs
     */
    private function parseTags($tags): array
    {
        if (is_null($tags)) {
            return [];
        }

        // If already array of IDs
        if (is_array($tags) && is_numeric($tags[0] ?? null)) {
            return $tags;
        }

        // If string, split by comma
        if (is_string($tags)) {
            $tags = array_map('trim', explode(',', $tags));
        }

        // Convert tag names to IDs
        $tagIds = [];
        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = $tag;
            } elseif (is_string($tag)) {
                $tagModel = Tag::findOrCreateByName($tag);
                $tagIds[] = $tagModel->id;
            } elseif ($tag instanceof Tag) {
                $tagIds[] = $tag->id;
            }
        }

        return array_unique($tagIds);
    }
}