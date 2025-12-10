<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Action Constants
     */
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';
    const ACTION_VIEWED = 'viewed';
    const ACTION_RESTORED = 'restored';
    const ACTION_PUBLISHED = 'published';
    const ACTION_UNPUBLISHED = 'unpublished';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_REGISTERED = 'registered';
    const ACTION_CONFIRMED = 'confirmed';
    const ACTION_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * RELATIONSHIPS
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * SCOPES
     */

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSubject($query, string $subjectType, int $subjectId)
    {
        return $query->where('subject_type', $subjectType)
                     ->where('subject_id', $subjectId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, int $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * ACCESSORS
     */

    public function getChangesAttribute(): ?array
    {
        if (!isset($this->properties['old']) || !isset($this->properties['new'])) {
            return null;
        }

        $changes = [];
        $old = $this->properties['old'];
        $new = $this->properties['new'];

        foreach ($new as $key => $value) {
            if (isset($old[$key]) && $old[$key] != $value) {
                $changes[$key] = [
                    'old' => $old[$key],
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    /**
     * STATIC HELPER METHODS
     */

    public static function log(
        string $action,
        ?Model $subject = null,
        ?string $description = null,
        array $properties = []
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logCreated(Model $subject, ?string $description = null): self
    {
        return self::log(self::ACTION_CREATED, $subject, $description, [
            'new' => $subject->getAttributes(),
        ]);
    }

    public static function logUpdated(Model $subject, array $old, ?string $description = null): self
    {
        return self::log(self::ACTION_UPDATED, $subject, $description, [
            'old' => $old,
            'new' => $subject->getAttributes(),
        ]);
    }

    public static function logDeleted(Model $subject, ?string $description = null): self
    {
        return self::log(self::ACTION_DELETED, $subject, $description, [
            'old' => $subject->getAttributes(),
        ]);
    }

    public static function logViewed(Model $subject, ?string $description = null): self
    {
        return self::log(self::ACTION_VIEWED, $subject, $description);
    }
}