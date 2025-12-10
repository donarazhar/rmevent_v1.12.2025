<?php

namespace App\Models;

use App\Models\Traits\HasMedia;
use App\Models\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasMedia, HasTags;

    /**
     * Status Constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'full_description',
        'featured_image',
        'category_id',
        'location',
        'location_maps_url',
        'start_datetime',
        'end_datetime',
        'timezone',
        'is_registration_open',
        'registration_start',
        'registration_end',
        'max_participants',
        'current_participants',
        'is_free',
        'price',
        'registration_fields',
        'requirements',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'views_count', // ← ADD THIS
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'registration_fields' => 'array',
            'is_registration_open' => 'boolean',
            'is_free' => 'boolean',
            'is_featured' => 'boolean',
            'max_participants' => 'integer',
            'current_participants' => 'integer',
            'views_count' => 'integer', // ← ADD THIS
            'price' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function confirmedRegistrations()
    {
        return $this->hasMany(EventRegistration::class)
                    ->where('status', EventRegistration::STATUS_CONFIRMED);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * SCOPES
     */

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
                     ->orderBy('start_datetime');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_datetime', '<=', now())
                     ->where('end_datetime', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now())
                     ->orderBy('end_datetime', 'desc');
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('is_registration_open', true)
                     ->where(function ($q) {
                         $q->whereNull('registration_end')
                           ->orWhere('registration_end', '>', now());
                     });
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_participants')
              ->orWhereRaw('current_participants < max_participants');
        });
    }

    /**
     * ACCESSORS
     */

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image 
            ? asset('storage/' . $this->featured_image) 
            : asset('images/default-event.jpg');
    }

    public function getAvailableSlotsAttribute()
    {
        if (!$this->max_participants) {
            return null; // Unlimited
        }
        return max(0, $this->max_participants - $this->current_participants);
    }

    public function getIsFullAttribute(): bool
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * HELPER METHODS
     */

    public function isUpcoming(): bool
    {
        return $this->start_datetime->isFuture();
    }

    public function isPast(): bool
    {
        return $this->end_datetime->isPast();
    }

    public function isOngoing(): bool
    {
        return $this->start_datetime->isPast() && $this->end_datetime->isFuture();
    }

    public function canRegister(): bool
    {
        if (!$this->is_registration_open) {
            return false;
        }

        if ($this->registration_end && $this->registration_end->isPast()) {
            return false;
        }

        if ($this->is_full) {
            return false;
        }

        return true;
    }

    public function incrementParticipants(): void
    {
        $this->increment('current_participants');
    }

    public function decrementParticipants(): void
    {
        $this->decrement('current_participants');
    }
}