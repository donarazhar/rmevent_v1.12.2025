<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class OfficialLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'letter_number',
        'letter_type',
        'direction',
        'subject',
        'content',
        'sender_id',
        'sender_name',
        'sender_organization',
        'recipient_name',
        'recipient_organization',
        'recipient_address',
        'recipient_email',
        'cc_recipients',
        'attachment_count',
        'attachment_list',
        'reference_number',
        'replied_to_letter',
        'letter_date',
        'received_date',
        'sent_date',
        'due_date',
        'priority',
        'classification',
        'status',
        'approved_by',
        'approved_at',
        'letter_file',
        'signed_file',
        'supporting_files',
        'signatory',
        'signatory_name',
        'signatory_position',
        'signature_file',
        'created_by',
        'notes',
        'internal_notes',
    ];

    protected $casts = [
        'cc_recipients' => 'array',
        'attachment_list' => 'array',
        'supporting_files' => 'array',
        'letter_date' => 'date',
        'received_date' => 'date',
        'sent_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'attachment_count' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'priority_label',
        'letter_file_url',
        'signed_file_url',
        'is_overdue',
        'days_until_due',
    ];

    // Constants for enums
    public const TYPE_INVITATION = 'invitation';
    public const TYPE_ANNOUNCEMENT = 'announcement';
    public const TYPE_NOTIFICATION = 'notification';
    public const TYPE_REQUEST = 'request';
    public const TYPE_RESPONSE = 'response';
    public const TYPE_THANK_YOU = 'thank_you';
    public const TYPE_COOPERATION = 'cooperation';
    public const TYPE_RECOMMENDATION = 'recommendation';
    public const TYPE_OTHER = 'other';

    public const DIRECTION_INCOMING = 'incoming';
    public const DIRECTION_OUTGOING = 'outgoing';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public const CLASSIFICATION_PUBLIC = 'public';
    public const CLASSIFICATION_INTERNAL = 'internal';
    public const CLASSIFICATION_CONFIDENTIAL = 'confidential';
    public const CLASSIFICATION_SECRET = 'secret';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_SENT = 'sent';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Relationships
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signatory(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signatory');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function repliedToLetter(): BelongsTo
    {
        return $this->belongsTo(OfficialLetter::class, 'replied_to_letter');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(OfficialLetter::class, 'replied_to_letter');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_APPROVAL => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_SENT => 'Terkirim',
            self::STATUS_RECEIVED => 'Diterima',
            self::STATUS_ARCHIVED => 'Diarsipkan',
            default => 'Unknown',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_URGENT => 'Mendesak',
            default => 'Unknown',
        };
    }

    public function getLetterFileUrlAttribute(): ?string
    {
        return $this->letter_file ? Storage::url($this->letter_file) : null;
    }

    public function getSignedFileUrlAttribute(): ?string
    {
        return $this->signed_file ? Storage::url($this->signed_file) : null;
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->due_date->isPast() &&
            !in_array($this->status, [self::STATUS_SENT, self::STATUS_ARCHIVED]);
    }

    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date) {
            return null;
        }

        if ($this->due_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Scopes
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('letter_type', $type);
    }

    public function scopeByDirection($query, string $direction)
    {
        return $query->where('direction', $direction);
    }

    public function scopeIncoming($query)
    {
        return $query->where('direction', self::DIRECTION_INCOMING);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', self::DIRECTION_OUTGOING);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeSent($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', self::PRIORITY_URGENT);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function scopeByClassification($query, string $classification)
    {
        return $query->where('classification', $classification);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', [self::STATUS_SENT, self::STATUS_ARCHIVED]);
    }

    public function scopeDueSoon($query, int $days = 7)
    {
        return $query->whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays($days)])
            ->whereNotIn('status', [self::STATUS_SENT, self::STATUS_ARCHIVED]);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeLetterDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('letter_date', [$startDate, $endDate]);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhere('letter_number', 'like', "%{$search}%")
                ->orWhere('recipient_name', 'like', "%{$search}%")
                ->orWhere('sender_name', 'like', "%{$search}%");
        });
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('letter_date', now()->month)
            ->whereYear('letter_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('letter_date', now()->year);
    }

    /**
     * Helper Methods
     */
    public function submitForApproval(): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        if ($this->direction !== self::DIRECTION_OUTGOING) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_PENDING_APPROVAL,
        ]);
    }

    public function approve(int $userId): bool
    {
        if ($this->status !== self::STATUS_PENDING_APPROVAL) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function reject(): bool
    {
        if ($this->status !== self::STATUS_PENDING_APPROVAL) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_DRAFT,
        ]);
    }

    public function send(): bool
    {
        if ($this->direction === self::DIRECTION_OUTGOING) {
            if (!in_array($this->status, [self::STATUS_APPROVED, self::STATUS_DRAFT])) {
                return false;
            }
        }

        $status = $this->direction === self::DIRECTION_OUTGOING ?
            self::STATUS_SENT : self::STATUS_RECEIVED;

        $data = ['status' => $status];

        if ($this->direction === self::DIRECTION_OUTGOING) {
            $data['sent_date'] = now()->toDateString();
        } else {
            $data['received_date'] = now()->toDateString();
        }

        return $this->update($data);
    }

    public function archive(): bool
    {
        if (!in_array($this->status, [self::STATUS_SENT, self::STATUS_RECEIVED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_ARCHIVED,
        ]);
    }

    public function addCcRecipient(array $recipient): void
    {
        // $recipient = ['name' => '...', 'email' => '...', 'organization' => '...']
        $ccRecipients = $this->cc_recipients ?? [];
        $ccRecipients[] = $recipient;
        $this->update(['cc_recipients' => $ccRecipients]);
    }

    public function removeCcRecipient(int $index): void
    {
        $ccRecipients = $this->cc_recipients ?? [];

        if (isset($ccRecipients[$index])) {
            unset($ccRecipients[$index]);
            $this->update(['cc_recipients' => array_values($ccRecipients)]);
        }
    }

    public function addAttachment(string $fileName): void
    {
        $attachments = $this->attachment_list ?? [];
        $attachments[] = $fileName;

        $this->update([
            'attachment_list' => $attachments,
            'attachment_count' => count($attachments),
        ]);
    }

    public function removeAttachment(string $fileName): void
    {
        $attachments = $this->attachment_list ?? [];
        $attachments = array_filter($attachments, fn($file) => $file !== $fileName);

        $this->update([
            'attachment_list' => array_values($attachments),
            'attachment_count' => count($attachments),
        ]);
    }

    public function addSupportingFile(string $filePath): void
    {
        $files = $this->supporting_files ?? [];
        $files[] = $filePath;
        $this->update(['supporting_files' => $files]);
    }

    public function removeSupportingFile(string $filePath): void
    {
        $files = $this->supporting_files ?? [];
        $files = array_filter($files, fn($file) => $file !== $filePath);
        $this->update(['supporting_files' => array_values($files)]);

        // Delete file from storage
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    public function createReply(array $data): OfficialLetter
    {
        $reply = new OfficialLetter($data);
        $reply->replied_to_letter = $this->id;
        $reply->letter_type = self::TYPE_RESPONSE;
        $reply->reference_number = $this->letter_number;

        // Swap sender and recipient for reply
        if ($this->direction === self::DIRECTION_INCOMING) {
            $reply->direction = self::DIRECTION_OUTGOING;
            $reply->recipient_name = $this->sender_name ?? $this->sender?->name;
            $reply->recipient_organization = $this->sender_organization;
        } else {
            $reply->direction = self::DIRECTION_INCOMING;
        }

        $reply->save();

        return $reply;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public function isIncoming(): bool
    {
        return $this->direction === self::DIRECTION_INCOMING;
    }

    public function isOutgoing(): bool
    {
        return $this->direction === self::DIRECTION_OUTGOING;
    }

    public function isUrgent(): bool
    {
        return $this->priority === self::PRIORITY_URGENT;
    }

    public function isHighPriority(): bool
    {
        return in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function isConfidential(): bool
    {
        return in_array($this->classification, [
            self::CLASSIFICATION_CONFIDENTIAL,
            self::CLASSIFICATION_SECRET
        ]);
    }

    public function canBeEditedBy(User $user): bool
    {
        // Only draft letters can be edited
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        // Creator can edit
        if ($this->created_by === $user->id) {
            return true;
        }

        // Admin can edit
        return $user->hasRole('admin');
    }

    public function canBeApprovedBy(User $user): bool
    {
        if ($this->status !== self::STATUS_PENDING_APPROVAL) {
            return false;
        }

        // Add your permission logic here
        return $user->hasRole(['admin', 'approver', 'chairman']);
    }

    public static function generateLetterNumber(string $direction = self::DIRECTION_OUTGOING): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $romanMonth = self::getRomanMonth($month);

        // Get latest letter number for this year and direction
        $latestLetter = static::where('direction', $direction)
            ->whereYear('letter_date', $year)
            ->latest('id')
            ->first();

        $number = $latestLetter ?
            ((int) explode('/', $latestLetter->letter_number)[0] + 1) : 1;

        // Format: 001/RM-1447H/XII/2024
        return str_pad($number, 3, '0', STR_PAD_LEFT) .
            '/RM-1447H/' .
            $romanMonth . '/' .
            $year;
    }

    protected static function getRomanMonth(string $month): string
    {
        $romans = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];

        return $romans[$month] ?? 'I';
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($letter) {
            if (empty($letter->letter_number)) {
                $letter->letter_number = static::generateLetterNumber($letter->direction);
            }

            // Set letter_date to today if not set
            if (empty($letter->letter_date)) {
                $letter->letter_date = now()->toDateString();
            }
        });

        static::deleting(function ($letter) {
            // Delete letter file
            if ($letter->letter_file && Storage::exists($letter->letter_file)) {
                Storage::delete($letter->letter_file);
            }

            // Delete signed file
            if ($letter->signed_file && Storage::exists($letter->signed_file)) {
                Storage::delete($letter->signed_file);
            }

            // Delete signature file
            if ($letter->signature_file && Storage::exists($letter->signature_file)) {
                Storage::delete($letter->signature_file);
            }

            // Delete supporting files
            if ($letter->supporting_files) {
                foreach ($letter->supporting_files as $file) {
                    if (Storage::exists($file)) {
                        Storage::delete($file);
                    }
                }
            }
        });
    }
}
