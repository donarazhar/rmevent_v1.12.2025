<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'structure_id',
        'proposal_code',
        'title',
        'description',
        'type',
        'executive_summary',
        'background',
        'objectives',
        'methodology',
        'timeline',
        'budget_overview',
        'expected_outcomes',
        'submitted_to',
        'recipient_contact',
        'recipient_email',
        'requested_amount',
        'approved_amount',
        'submission_date',
        'response_deadline',
        'approved_date',
        'status',
        'created_by',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_feedback',
        'approved_by',
        'approved_at',
        'approval_notes',
        'document_file',
        'supporting_documents',
        'notes',
        'revision_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'supporting_documents' => 'array',
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'submission_date' => 'date',
        'response_deadline' => 'date',
        'approved_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'document_url',
        'approval_percentage',
        'is_overdue',
    ];

    // Constants for enums
    public const TYPE_EVENT = 'event';
    public const TYPE_SPONSORSHIP = 'sponsorship';
    public const TYPE_PARTNERSHIP = 'partnership';
    public const TYPE_FUNDING = 'funding';
    public const TYPE_PROJECT = 'project';
    public const TYPE_OTHER = 'other';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVISION_NEEDED = 'revision_needed';
    public const STATUS_WITHDRAWN = 'withdrawn';

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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_UNDER_REVIEW => 'Sedang Ditinjau',
            self::STATUS_SUBMITTED => 'Diajukan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_REVISION_NEEDED => 'Perlu Revisi',
            self::STATUS_WITHDRAWN => 'Ditarik',
            default => 'Unknown',
        };
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_file ? Storage::url($this->document_file) : null;
    }

    public function getApprovalPercentageAttribute(): ?float
    {
        if (!$this->requested_amount || $this->requested_amount == 0) {
            return null;
        }

        if (!$this->approved_amount) {
            return 0;
        }

        return round(($this->approved_amount / $this->requested_amount) * 100, 2);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->response_deadline) {
            return false;
        }

        return $this->response_deadline->isPast() && 
               !in_array($this->status, [self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_WITHDRAWN]);
    }

    /**
     * Scopes
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            self::STATUS_SUBMITTED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_REVISION_NEEDED
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('response_deadline')
                    ->where('response_deadline', '<', now())
                    ->whereNotIn('status', [
                        self::STATUS_APPROVED,
                        self::STATUS_REJECTED,
                        self::STATUS_WITHDRAWN
                    ]);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByStructure($query, int $structureId)
    {
        return $query->where('structure_id', $structureId);
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('proposal_code', 'like', "%{$search}%")
              ->orWhere('submitted_to', 'like', "%{$search}%");
        });
    }

    public function scopeSubmittedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('submission_date', [$startDate, $endDate]);
    }

    public function scopeAmountGreaterThan($query, float $amount)
    {
        return $query->where('requested_amount', '>', $amount);
    }

    /**
     * Helper Methods
     */
    public function submit(int $userId): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_SUBMITTED,
            'submitted_by' => $userId,
            'submitted_at' => now(),
            'submission_date' => now()->toDateString(),
        ]);
    }

    public function startReview(int $userId): bool
    {
        if (!in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_REVISION_NEEDED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
        ]);
    }

    public function approve(int $userId, ?float $approvedAmount = null, ?string $notes = null): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_SUBMITTED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
            'approved_date' => now()->toDateString(),
            'approved_amount' => $approvedAmount ?? $this->requested_amount,
            'approval_notes' => $notes,
        ]);
    }

    public function reject(int $userId, string $reason): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_SUBMITTED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    public function requestRevision(int $userId, string $feedback): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_SUBMITTED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_REVISION_NEEDED,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_feedback' => $feedback,
        ]);
    }

    public function withdraw(): bool
    {
        if (in_array($this->status, [self::STATUS_APPROVED, self::STATUS_REJECTED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_WITHDRAWN,
        ]);
    }

    public function addSupportingDocument(string $documentPath): void
    {
        $documents = $this->supporting_documents ?? [];
        $documents[] = $documentPath;
        $this->update(['supporting_documents' => $documents]);
    }

    public function removeSupportingDocument(string $documentPath): void
    {
        $documents = $this->supporting_documents ?? [];
        $documents = array_filter($documents, fn($doc) => $doc !== $documentPath);
        $this->update(['supporting_documents' => array_values($documents)]);
        
        // Delete file from storage
        if (Storage::exists($documentPath)) {
            Storage::delete($documentPath);
        }
    }

    public function canBeEditedBy(User $user): bool
    {
        // Only draft proposals can be edited
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        // Creator can always edit
        if ($this->created_by === $user->id) {
            return true;
        }

        // Check if user has permission (admin, etc.)
        // Add your permission logic here
        return false;
    }

    public function canBeSubmittedBy(User $user): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->created_by === $user->id || $user->hasRole('admin');
    }

    public function canBeReviewedBy(User $user): bool
    {
        if (!in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_UNDER_REVIEW])) {
            return false;
        }

        // Add your permission logic here (e.g., reviewer role)
        return $user->hasRole(['admin', 'reviewer']);
    }

    public function canBeApprovedBy(User $user): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_SUBMITTED])) {
            return false;
        }

        // Add your permission logic here (e.g., approver role)
        return $user->hasRole(['admin', 'approver']);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function needsRevision(): bool
    {
        return $this->status === self::STATUS_REVISION_NEEDED;
    }

    public function isWithdrawn(): bool
    {
        return $this->status === self::STATUS_WITHDRAWN;
    }

    public static function generateProposalCode(): string
    {
        $year = now()->year;
        $latestProposal = static::whereYear('created_at', $year)
                                ->latest('id')
                                ->first();
        
        $number = $latestProposal ? 
                  ((int) substr($latestProposal->proposal_code, -3) + 1) : 1;
        
        return 'PROP-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proposal) {
            if (empty($proposal->proposal_code)) {
                $proposal->proposal_code = static::generateProposalCode();
            }
        });

        static::deleting(function ($proposal) {
            // Delete main document file
            if ($proposal->document_file && Storage::exists($proposal->document_file)) {
                Storage::delete($proposal->document_file);
            }

            // Delete supporting documents
            if ($proposal->supporting_documents) {
                foreach ($proposal->supporting_documents as $document) {
                    if (Storage::exists($document)) {
                        Storage::delete($document);
                    }
                }
            }
        });
    }
}