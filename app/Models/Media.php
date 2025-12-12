<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'disk',
        'width',
        'height',
        'thumbnail_path',
        'metadata',
        'alt_text',
        'mediable_type',
        'mediable_id',
        'uploaded_by',
        'collection',
        'order',
    ];

    /**
     * The attributes that should have default values.
     */
    protected $attributes = [
        'order' => 0,
        'collection' => 'general',
        'disk' => 'public',
        'mediable_type' => null,
        'mediable_id' => null,
        'title' => null,
        'description' => null,
        'alt_text' => null,
        'thumbnail_path' => null,
        'metadata' => null,
        'width' => null,
        'height' => null,
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function mediable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * SCOPES
     */

    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }

    public function scopeInCollection($query, string $collection)
    {
        return $query->where('collection', $collection);
    }

    /**
     * ACCESSORS
     */

    public function getUrlAttribute(): string
    {
        return \Storage::disk($this->disk)->url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path
            ? \Storage::disk($this->disk)->url($this->thumbnail_path)
            : null;
    }

    public function getFileSizeHumanAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * HELPER METHODS
     */

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isDocument(): bool
    {
        return $this->file_type === 'document';
    }
}
