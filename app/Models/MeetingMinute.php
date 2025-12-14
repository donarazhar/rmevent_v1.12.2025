<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MeetingMinute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'structure_id',
        'minute_code',
        'meeting_title',
        'meeting_type',
        'meeting_date',
        'location',
        'meeting_link',
        'duration_minutes',
        'participants',
        'absent_members',
        'external_participants',
        'chairman',
        'secretary',
        'agenda',
        'discussion_summary',
        'decisions',
        'action_items',
        'next_meeting_agenda',
        'action_items_list',
        'document_file',
        'attachments',
        'next_meeting_date',
        'next_meeting_location',
        'status',
        'created_by',
        'finalized_by',
        'finalized_at',
        'distributed_at',
        'distributed_to',
        'notes',
    ];

    protected $casts = [
        'participants' => 'array',
        'absent_members' => 'array',
        'external_participants' => 'array',
        'action_items_list' => 'array',
        'attachments' => 'array',
        'distributed_to' => 'array',
        'meeting_date' => 'datetime',
        'next_meeting_date' => 'datetime',
        'finalized_at' => 'datetime',
        'distributed_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'document_url',
        'duration_formatted',
        'participant_count',
        'pending_action_items_count',
    ];

    // Constants for enums
    public const TYPE_COORDINATION = 'coordination';
    public const TYPE_PLANNING = 'planning';
    public const TYPE_EVALUATION = 'evaluation';
    public const TYPE_EMERGENCY = 'emergency';
    public const TYPE_GENERAL = 'general';
    public const TYPE_OTHER = 'other';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_FINALIZED = 'finalized';
    public const STATUS_DISTRIBUTED = 'distributed';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Relationships
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(CommitteeStructure::class, 'structure_id');
    }

    public function chairmanUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chairman');
    }

    public function secretaryUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretary');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_FINALIZED => 'Finalisasi',
            self::STATUS_DISTRIBUTED => 'Didistribusikan',
            self::STATUS_ARCHIVED => 'Diarsipkan',
            default => 'Unknown',
        };
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_file ? Storage::url($this->document_file) : null;
    }

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_minutes) {
            return '-';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }

    public function getParticipantCountAttribute(): int
    {
        return count($this->participants ?? []);
    }

    public function getPendingActionItemsCountAttribute(): int
    {
        if (!$this->action_items_list) {
            return 0;
        }

        return collect($this->action_items_list)
            ->where('status', '!=', 'completed')
            ->count();
    }

    /**
     * Scopes
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('meeting_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeFinalized($query)
    {
        return $query->where('status', self::STATUS_FINALIZED);
    }

    public function scopeDistributed($query)
    {
        return $query->where('status', self::STATUS_DISTRIBUTED);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByStructure($query, int $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    public function scopeMeetingBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('meeting_date', [$startDate, $endDate]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>', now())
                    ->orderBy('meeting_date', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('meeting_date', '<=', now())
                    ->orderBy('meeting_date', 'desc');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('meeting_date', now()->month)
                    ->whereYear('meeting_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('meeting_date', now()->year);
    }

    public function scopeWithParticipant($query, int $userId)
    {
        return $query->whereJsonContains('participants', $userId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('meeting_title', 'like', "%{$search}%")
              ->orWhere('minute_code', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    /**
     * Helper Methods
     */
    public function addParticipant(int $userId): void
    {
        $participants = $this->participants ?? [];
        
        if (!in_array($userId, $participants)) {
            $participants[] = $userId;
            $this->update(['participants' => $participants]);
        }
    }

    public function removeParticipant(int $userId): void
    {
        $participants = $this->participants ?? [];
        $participants = array_filter($participants, fn($id) => $id !== $userId);
        $this->update(['participants' => array_values($participants)]);
    }

    public function addAbsentMember(int $userId): void
    {
        $absentMembers = $this->absent_members ?? [];
        
        if (!in_array($userId, $absentMembers)) {
            $absentMembers[] = $userId;
            $this->update(['absent_members' => $absentMembers]);
        }
    }

    public function addExternalParticipant(array $participantData): void
    {
        // $participantData = ['name' => 'John Doe', 'organization' => 'ABC Corp', 'email' => 'john@example.com']
        $externalParticipants = $this->external_participants ?? [];
        $externalParticipants[] = $participantData;
        $this->update(['external_participants' => $externalParticipants]);
    }

    public function addActionItem(array $actionItem): void
    {
        // $actionItem = ['task' => '...', 'assignee' => userId, 'deadline' => date, 'status' => 'pending']
        $actionItems = $this->action_items_list ?? [];
        
        $actionItem['created_at'] = now()->toDateTimeString();
        $actionItem['status'] = $actionItem['status'] ?? 'pending';
        
        $actionItems[] = $actionItem;
        $this->update(['action_items_list' => $actionItems]);
    }

    public function updateActionItemStatus(int $index, string $status): bool
    {
        $actionItems = $this->action_items_list ?? [];
        
        if (!isset($actionItems[$index])) {
            return false;
        }
        
        $actionItems[$index]['status'] = $status;
        
        if ($status === 'completed') {
            $actionItems[$index]['completed_at'] = now()->toDateTimeString();
        }
        
        return $this->update(['action_items_list' => $actionItems]);
    }

    public function addAttachment(string $filePath): void
    {
        $attachments = $this->attachments ?? [];
        $attachments[] = $filePath;
        $this->update(['attachments' => $attachments]);
    }

    public function removeAttachment(string $filePath): void
    {
        $attachments = $this->attachments ?? [];
        $attachments = array_filter($attachments, fn($file) => $file !== $filePath);
        $this->update(['attachments' => array_values($attachments)]);
        
        // Delete file from storage
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    public function finalize(int $userId): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_FINALIZED,
            'finalized_by' => $userId,
            'finalized_at' => now(),
        ]);
    }

    public function distribute(array $userIds): bool
    {
        if ($this->status !== self::STATUS_FINALIZED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_DISTRIBUTED,
            'distributed_at' => now(),
            'distributed_to' => $userIds,
        ]);
    }

    public function archive(): bool
    {
        if (!in_array($this->status, [self::STATUS_FINALIZED, self::STATUS_DISTRIBUTED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_ARCHIVED,
        ]);
    }

    public function revertToDraft(): bool
    {
        if ($this->status !== self::STATUS_FINALIZED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_DRAFT,
            'finalized_by' => null,
            'finalized_at' => null,
        ]);
    }

    public function isParticipant(int $userId): bool
    {
        return in_array($userId, $this->participants ?? []);
    }

    public function isAbsent(int $userId): bool
    {
        return in_array($userId, $this->absent_members ?? []);
    }

    public function hasDistributedTo(int $userId): bool
    {
        return in_array($userId, $this->distributed_to ?? []);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isFinalized(): bool
    {
        return $this->status === self::STATUS_FINALIZED;
    }

    public function isDistributed(): bool
    {
        return $this->status === self::STATUS_DISTRIBUTED;
    }

    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public function canBeEditedBy(User $user): bool
    {
        // Only draft minutes can be edited
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        // Creator or secretary can edit
        if ($this->created_by === $user->id || $this->secretary === $user->id) {
            return true;
        }

        // Admin can always edit
        return $user->hasRole('admin');
    }

    public function canBeFinalizedBy(User $user): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        // Chairman, secretary, or admin can finalize
        return $this->chairman === $user->id || 
               $this->secretary === $user->id || 
               $user->hasRole('admin');
    }

    public function getAttendanceRate(): float
    {
        $totalInvited = count($this->participants ?? []) + count($this->absent_members ?? []);
        
        if ($totalInvited === 0) {
            return 0;
        }

        $attended = count($this->participants ?? []);
        
        return round(($attended / $totalInvited) * 100, 2);
    }

    public function getPendingActionItems(): array
    {
        if (!$this->action_items_list) {
            return [];
        }

        return collect($this->action_items_list)
            ->filter(fn($item) => $item['status'] !== 'completed')
            ->values()
            ->toArray();
    }

    public function getCompletedActionItems(): array
    {
        if (!$this->action_items_list) {
            return [];
        }

        return collect($this->action_items_list)
            ->filter(fn($item) => $item['status'] === 'completed')
            ->values()
            ->toArray();
    }

    public function getOverdueActionItems(): array
    {
        if (!$this->action_items_list) {
            return [];
        }

        $now = now();
        
        return collect($this->action_items_list)
            ->filter(function ($item) use ($now) {
                return isset($item['deadline']) && 
                       Carbon::parse($item['deadline'])->isPast() &&
                       $item['status'] !== 'completed';
            })
            ->values()
            ->toArray();
    }

    public static function generateMinuteCode(): string
    {
        $year = now()->year;
        $latestMinute = static::whereYear('created_at', $year)
                             ->latest('id')
                             ->first();
        
        $number = $latestMinute ? 
                  ((int) substr($latestMinute->minute_code, -3) + 1) : 1;
        
        return 'MM-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($minute) {
            if (empty($minute->minute_code)) {
                $minute->minute_code = static::generateMinuteCode();
            }
        });

        static::deleting(function ($minute) {
            // Delete document file
            if ($minute->document_file && Storage::exists($minute->document_file)) {
                Storage::delete($minute->document_file);
            }

            // Delete attachments
            if ($minute->attachments) {
                foreach ($minute->attachments as $attachment) {
                    if (Storage::exists($attachment)) {
                        Storage::delete($attachment);
                    }
                }
            }
        });
    }
}