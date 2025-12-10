<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * PENTING: Nama tabel harus 'feedbacks' (plural)
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'registration_id',
        'type',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'overall_rating',
        'content_rating',
        'speaker_rating',
        'venue_rating',
        'organization_rating',
        'recommendation_score',
        'suggestions',
        'is_published',
        'is_featured',
        'display_order',
        'status',
        'admin_response',
        'responded_by',
        'responded_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'overall_rating' => 'integer',
        'content_rating' => 'integer',
        'speaker_rating' => 'integer',
        'venue_rating' => 'integer',
        'organization_rating' => 'integer',
        'recommendation_score' => 'integer',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Constants
     */
    const TYPE_GENERAL = 'general';
    const TYPE_EVENT = 'event';
    const TYPE_TESTIMONIAL = 'testimonial';
    const TYPE_COMPLAINT = 'complaint';
    const TYPE_SUGGESTION = 'suggestion';

    const STATUS_NEW = 'new';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_RESPONDED = 'responded';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('overall_rating', '>=', $minRating);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Methods
     */
    public function respond($response, $userId = null)
    {
        $this->update([
            'admin_response' => $response,
            'responded_by' => $userId ?? auth()->id(),
            'responded_at' => now(),
            'status' => self::STATUS_RESPONDED,
        ]);
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'type' => self::TYPE_TESTIMONIAL,
        ]);
    }

    public function unpublish()
    {
        $this->update(['is_published' => false]);
    }

    public function logActivity($action, $description = null)
    {
        // Implement activity logging if needed
        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => self::class,
            'subject_id' => $this->id,
            'action' => $action,
            'description' => $description ?? "Feedback {$action}",
        ]);
    }
}