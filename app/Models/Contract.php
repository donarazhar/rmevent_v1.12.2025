<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'sponsorship_id',
        'contract_code',
        'title',
        'description',
        'type',
        'party_a_name',
        'party_a_address',
        'party_a_representative',
        'party_b_name',
        'party_b_address',
        'party_b_representative',
        'party_b_contact',
        'party_b_email',
        'contract_value',
        'currency',
        'terms_and_conditions',
        'scope_of_work',
        'deliverables',
        'payment_terms',
        'start_date',
        'end_date',
        'duration_days',
        'auto_renewal',
        'status',
        'signed_by_party_a',
        'signed_at_party_a',
        'signature_file_party_a',
        'signed_at_party_b',
        'signature_file_party_b',
        'contract_file',
        'supporting_documents',
        'renewal_notice_date',
        'termination_date',
        'termination_reason',
        'pic_internal',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'deliverables' => 'array',
        'payment_terms' => 'array',
        'supporting_documents' => 'array',
        'contract_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at_party_a' => 'datetime',
        'signed_at_party_b' => 'date',
        'renewal_notice_date' => 'date',
        'termination_date' => 'date',
        'auto_renewal' => 'boolean',
        'duration_days' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'contract_file_url',
        'is_active',
        'is_expired',
        'is_expiring_soon',
        'days_remaining',
        'contract_value_formatted',
    ];

    // Constants for enums
    public const TYPE_SPONSORSHIP = 'sponsorship';
    public const TYPE_VENDOR = 'vendor';
    public const TYPE_VENUE = 'venue';
    public const TYPE_PARTNERSHIP = 'partnership';
    public const TYPE_SERVICE = 'service';
    public const TYPE_EMPLOYMENT = 'employment';
    public const TYPE_OTHER = 'other';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_SIGNATURE = 'pending_signature';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_TERMINATED = 'terminated';
    public const STATUS_EXPIRED = 'expired';

    /**
     * Relationships
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sponsorship(): BelongsTo
    {
        return $this->belongsTo(Sponsorship::class);
    }

    public function signedByPartyA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_party_a');
    }

    public function picInternal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_internal');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_SIGNATURE => 'Menunggu Tanda Tangan',
            self::STATUS_SIGNED => 'Ditandatangani',
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_TERMINATED => 'Diakhiri',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            default => 'Unknown',
        };
    }

    public function getContractFileUrlAttribute(): ?string
    {
        return $this->contract_file ? Storage::url($this->contract_file) : null;
    }

    public function getIsActiveAttribute(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $now = now()->toDateString();
        return $now >= $this->start_date->toDateString() && 
               $now <= $this->end_date->toDateString();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        // Expiring within 30 days
        $daysRemaining = $this->days_remaining;
        return $daysRemaining > 0 && $daysRemaining <= 30;
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->end_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->end_date, false);
    }

    public function getContractValueFormattedAttribute(): string
    {
        if (!$this->contract_value) {
            return '-';
        }

        return $this->currency . ' ' . number_format($this->contract_value, 2, ',', '.');
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

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeSigned($query)
    {
        return $query->where('status', self::STATUS_SIGNED);
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now())
                    ->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_SIGNED]);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByPartyB($query, string $partyBName)
    {
        return $query->where('party_b_name', 'like', "%{$partyBName}%");
    }

    public function scopeValueGreaterThan($query, float $amount)
    {
        return $query->where('contract_value', '>', $amount);
    }

    public function scopePeriodBetween($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('contract_code', 'like', "%{$search}%")
              ->orWhere('party_b_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Helper Methods
     */
    public function sendForSignature(int $userId): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_PENDING_SIGNATURE,
        ]);
    }

    public function signByPartyA(int $userId, ?string $signatureFile = null): bool
    {
        if (!in_array($this->status, [self::STATUS_PENDING_SIGNATURE, self::STATUS_DRAFT])) {
            return false;
        }

        $data = [
            'signed_by_party_a' => $userId,
            'signed_at_party_a' => now(),
        ];

        if ($signatureFile) {
            $data['signature_file_party_a'] = $signatureFile;
        }

        // Check if both parties have signed
        if ($this->signed_at_party_b) {
            $data['status'] = self::STATUS_SIGNED;
        }

        return $this->update($data);
    }

    public function signByPartyB(?string $signatureFile = null): bool
    {
        if (!in_array($this->status, [self::STATUS_PENDING_SIGNATURE, self::STATUS_DRAFT])) {
            return false;
        }

        $data = [
            'signed_at_party_b' => now()->toDateString(),
        ];

        if ($signatureFile) {
            $data['signature_file_party_b'] = $signatureFile;
        }

        // Check if both parties have signed
        if ($this->signed_at_party_a) {
            $data['status'] = self::STATUS_SIGNED;
        }

        return $this->update($data);
    }

    public function activate(): bool
    {
        if ($this->status !== self::STATUS_SIGNED) {
            return false;
        }

        // Can only activate if both parties have signed
        if (!$this->isBothPartiesSigned()) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function complete(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_COMPLETED,
        ]);
    }

    public function terminate(string $reason): bool
    {
        if (!in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_SIGNED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_TERMINATED,
            'termination_date' => now()->toDateString(),
            'termination_reason' => $reason,
        ]);
    }

    public function markAsExpired(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    public function renew(array $newTerms): Contract
    {
        $newContract = $this->replicate();
        $newContract->contract_code = static::generateContractCode();
        $newContract->status = self::STATUS_DRAFT;
        
        // Update dates
        $newContract->start_date = $newTerms['start_date'] ?? now()->addDay();
        $newContract->end_date = $newTerms['end_date'];
        
        // Update values if provided
        if (isset($newTerms['contract_value'])) {
            $newContract->contract_value = $newTerms['contract_value'];
        }
        
        // Clear signatures
        $newContract->signed_by_party_a = null;
        $newContract->signed_at_party_a = null;
        $newContract->signature_file_party_a = null;
        $newContract->signed_at_party_b = null;
        $newContract->signature_file_party_b = null;
        
        $newContract->save();
        
        return $newContract;
    }

    public function addDeliverable(array $deliverable): void
    {
        // $deliverable = ['item' => '...', 'deadline' => date, 'status' => 'pending']
        $deliverables = $this->deliverables ?? [];
        
        $deliverable['created_at'] = now()->toDateTimeString();
        $deliverable['status'] = $deliverable['status'] ?? 'pending';
        
        $deliverables[] = $deliverable;
        $this->update(['deliverables' => $deliverables]);
    }

    public function updateDeliverableStatus(int $index, string $status): bool
    {
        $deliverables = $this->deliverables ?? [];
        
        if (!isset($deliverables[$index])) {
            return false;
        }
        
        $deliverables[$index]['status'] = $status;
        
        if ($status === 'completed') {
            $deliverables[$index]['completed_at'] = now()->toDateTimeString();
        }
        
        return $this->update(['deliverables' => $deliverables]);
    }

    public function addPaymentTerm(array $paymentTerm): void
    {
        // $paymentTerm = ['description' => '...', 'amount' => 0, 'due_date' => date, 'status' => 'pending']
        $paymentTerms = $this->payment_terms ?? [];
        
        $paymentTerm['created_at'] = now()->toDateTimeString();
        $paymentTerm['status'] = $paymentTerm['status'] ?? 'pending';
        
        $paymentTerms[] = $paymentTerm;
        $this->update(['payment_terms' => $paymentTerms]);
    }

    public function updatePaymentTermStatus(int $index, string $status): bool
    {
        $paymentTerms = $this->payment_terms ?? [];
        
        if (!isset($paymentTerms[$index])) {
            return false;
        }
        
        $paymentTerms[$index]['status'] = $status;
        
        if ($status === 'paid') {
            $paymentTerms[$index]['paid_at'] = now()->toDateTimeString();
        }
        
        return $this->update(['payment_terms' => $paymentTerms]);
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

    public function isBothPartiesSigned(): bool
    {
        return $this->signed_at_party_a && $this->signed_at_party_b;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingSignature(): bool
    {
        return $this->status === self::STATUS_PENDING_SIGNATURE;
    }

    public function isSigned(): bool
    {
        return $this->status === self::STATUS_SIGNED;
    }

    public function isActiveContract(): bool
    {
        return $this->is_active;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isTerminated(): bool
    {
        return $this->status === self::STATUS_TERMINATED;
    }

    public function isExpiredContract(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function calculateDuration(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getPendingDeliverables(): array
    {
        if (!$this->deliverables) {
            return [];
        }

        return collect($this->deliverables)
            ->filter(fn($item) => $item['status'] !== 'completed')
            ->values()
            ->toArray();
    }

    public function getPendingPayments(): array
    {
        if (!$this->payment_terms) {
            return [];
        }

        return collect($this->payment_terms)
            ->filter(fn($item) => $item['status'] !== 'paid')
            ->values()
            ->toArray();
    }

    public function getTotalPaidAmount(): float
    {
        if (!$this->payment_terms) {
            return 0;
        }

        return collect($this->payment_terms)
            ->filter(fn($item) => $item['status'] === 'paid')
            ->sum('amount');
    }

    public function getTotalOutstandingAmount(): float
    {
        if (!$this->payment_terms) {
            return 0;
        }

        return collect($this->payment_terms)
            ->filter(fn($item) => $item['status'] !== 'paid')
            ->sum('amount');
    }

    public static function generateContractCode(): string
    {
        $year = now()->year;
        $latestContract = static::whereYear('created_at', $year)
                               ->latest('id')
                               ->first();
        
        $number = $latestContract ? 
                  ((int) substr($latestContract->contract_code, -3) + 1) : 1;
        
        return 'CTR-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (empty($contract->contract_code)) {
                $contract->contract_code = static::generateContractCode();
            }
            
            // Calculate duration if not set
            if (empty($contract->duration_days) && $contract->start_date && $contract->end_date) {
                $contract->duration_days = $contract->start_date->diffInDays($contract->end_date);
            }
        });

        static::updating(function ($contract) {
            // Recalculate duration if dates changed
            if ($contract->isDirty(['start_date', 'end_date'])) {
                $contract->duration_days = $contract->start_date->diffInDays($contract->end_date);
            }
        });

        static::deleting(function ($contract) {
            // Delete contract file
            if ($contract->contract_file && Storage::exists($contract->contract_file)) {
                Storage::delete($contract->contract_file);
            }

            // Delete signature files
            if ($contract->signature_file_party_a && Storage::exists($contract->signature_file_party_a)) {
                Storage::delete($contract->signature_file_party_a);
            }

            if ($contract->signature_file_party_b && Storage::exists($contract->signature_file_party_b)) {
                Storage::delete($contract->signature_file_party_b);
            }

            // Delete supporting documents
            if ($contract->supporting_documents) {
                foreach ($contract->supporting_documents as $document) {
                    if (Storage::exists($document)) {
                        Storage::delete($document);
                    }
                }
            }
        });
    }
}