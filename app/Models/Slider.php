<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Button Style Constants
     */
    const BUTTON_PRIMARY = 'primary';
    const BUTTON_SECONDARY = 'secondary';
    const BUTTON_OUTLINE = 'outline';

    /**
     * Text Position Constants
     */
    const POSITION_LEFT = 'left';
    const POSITION_CENTER = 'center';
    const POSITION_RIGHT = 'right';

    /**
     * Placement Constants
     */
    const PLACEMENT_HOMEPAGE = 'homepage';
    const PLACEMENT_EVENTS = 'events';
    const PLACEMENT_ABOUT = 'about';
    const PLACEMENT_ALL = 'all';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'image_mobile',
        'button_text',
        'button_url',
        'button_style',
        'text_position',
        'overlay_color',
        'overlay_opacity',
        'animation_effect',
        'order',
        'is_active',
        'active_from',
        'active_until',
        'placement',
    ];

    protected function casts(): array
    {
        return [
            'overlay_opacity' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
            'active_from' => 'datetime',
            'active_until' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * SCOPES
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('active_from')
                           ->orWhere('active_from', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('active_until')
                           ->orWhere('active_until', '>=', now());
                     });
    }

    public function scopeForPlacement($query, string $placement)
    {
        return $query->where(function ($q) use ($placement) {
            $q->where('placement', $placement)
              ->orWhere('placement', self::PLACEMENT_ALL);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * ACCESSORS
     */

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getImageMobileUrlAttribute(): ?string
    {
        return $this->image_mobile 
            ? asset('storage/' . $this->image_mobile)
            : null;
    }

    /**
     * HELPER METHODS
     */

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->active_from && $this->active_from->isFuture()) {
            return false;
        }

        if ($this->active_until && $this->active_until->isPast()) {
            return false;
        }

        return true;
    }
}