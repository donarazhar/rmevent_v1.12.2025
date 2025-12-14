<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ExecutiveSummary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'summary_code',
        'title',
        'summary_type',
        'period_start',
        'period_end',
        'report_date',
        'executive_overview',
        'key_highlights',
        'achievements',
        'challenges',
        'recommendations',
        'financial_summary',
        'performance_metrics',
        'event_statistics',
        'team_performance',
        'total_income',
        'total_expenses',
        'net_result',
        'budget_utilization_percentage',
        'events_conducted',
        'total_participants',
        'satisfaction_score',
        'charts_data',
        'document_file',
        'supporting_documents',
        'status',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'financial_summary' => 'array',
        'performance_metrics' => 'array',
        'event_statistics' => 'array',
        'team_performance' => 'array',
        'charts_data' => 'array',
        'supporting_documents' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'report_date' => 'date',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_result' => 'decimal:2',
        'budget_utilization_percentage' => 'decimal:2',
        'satisfaction_score' => 'decimal:2',
        'events_conducted' => 'integer',
        'total_participants' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'document_url',
        'period_duration_days',
        'is_profitable',
        'profit_margin_percentage',
    ];

    // Constants for enums
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_QUARTERLY = 'quarterly';
    public const TYPE_EVENT = 'event';
    public const TYPE_ANNUAL = 'annual';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PUBLISHED = 'published';

    /**
     * Relationships
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_PUBLISHED => 'Dipublikasikan',
            default => 'Unknown',
        };
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_file ? Storage::url($this->document_file) : null;
    }

    public function getPeriodDurationDaysAttribute(): int
    {
        return $this->period_start->diffInDays($this->period_end);
    }

    public function getIsProfitableAttribute(): bool
    {
        if (!$this->net_result) {
            return false;
        }

        return $this->net_result > 0;
    }

    public function getProfitMarginPercentageAttribute(): ?float
    {
        if (!$this->total_income || $this->total_income == 0) {
            return null;
        }

        return round(($this->net_result / $this->total_income) * 100, 2);
    }

    /**
     * Scopes
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('summary_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopePeriodBetween($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('period_start', [$startDate, $endDate])
              ->orWhereBetween('period_end', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('period_start', '<=', $startDate)
                     ->where('period_end', '>=', $endDate);
              });
        });
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('period_start', now()->month)
                    ->whereYear('period_start', now()->year);
    }

    public function scopeThisQuarter($query)
    {
        $quarter = now()->quarter;
        $year = now()->year;
        
        return $query->whereRaw('QUARTER(period_start) = ?', [$quarter])
                    ->whereYear('period_start', $year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('period_start', now()->year);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('summary_code', 'like', "%{$search}%")
              ->orWhere('executive_overview', 'like', "%{$search}%");
        });
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeProfitable($query)
    {
        return $query->where('net_result', '>', 0);
    }

    public function scopeByReportDate($query, $date)
    {
        return $query->whereDate('report_date', $date);
    }

    /**
     * Helper Methods
     */
    public function submitForReview(): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
        ]);
    }

    public function review(int $userId): bool
    {
        if ($this->status !== self::STATUS_UNDER_REVIEW) {
            return false;
        }

        return $this->update([
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
        ]);
    }

    public function approve(int $userId): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_DRAFT])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function publish(): bool
    {
        if ($this->status !== self::STATUS_APPROVED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_PUBLISHED,
        ]);
    }

    public function rejectToReview(): bool
    {
        if ($this->status !== self::STATUS_APPROVED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    public function rejectToDraft(): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_APPROVED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_DRAFT,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    public function calculateFinancialMetrics(): void
    {
        // Calculate net result
        $netResult = ($this->total_income ?? 0) - ($this->total_expenses ?? 0);
        
        // Calculate budget utilization if total_income is budget
        $budgetUtilization = null;
        if ($this->total_income && $this->total_income > 0) {
            $budgetUtilization = ($this->total_expenses / $this->total_income) * 100;
        }

        $this->update([
            'net_result' => $netResult,
            'budget_utilization_percentage' => $budgetUtilization,
        ]);
    }

    public function addFinancialSummaryItem(string $key, $value): void
    {
        $financialSummary = $this->financial_summary ?? [];
        $financialSummary[$key] = $value;
        $this->update(['financial_summary' => $financialSummary]);
    }

    public function addPerformanceMetric(string $metric, $value, ?string $unit = null): void
    {
        $performanceMetrics = $this->performance_metrics ?? [];
        
        $performanceMetrics[] = [
            'metric' => $metric,
            'value' => $value,
            'unit' => $unit,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        $this->update(['performance_metrics' => $performanceMetrics]);
    }

    public function addEventStatistic(string $key, $value): void
    {
        $eventStatistics = $this->event_statistics ?? [];
        $eventStatistics[$key] = $value;
        $this->update(['event_statistics' => $eventStatistics]);
    }

    public function addTeamPerformanceData(string $teamName, array $data): void
    {
        $teamPerformance = $this->team_performance ?? [];
        
        $teamPerformance[] = array_merge([
            'team_name' => $teamName,
        ], $data);
        
        $this->update(['team_performance' => $teamPerformance]);
    }

    public function addChartData(string $chartType, array $data): void
    {
        $chartsData = $this->charts_data ?? [];
        
        $chartsData[] = [
            'type' => $chartType,
            'data' => $data,
            'created_at' => now()->toDateTimeString(),
        ];
        
        $this->update(['charts_data' => $chartsData]);
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

    public function generateDocument(): string
    {
        // Placeholder for document generation logic
        // You would implement logic here to generate a PDF document
        // containing the executive summary with all data and charts
        
        // Return the path to the generated document
        return '';
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function canBeEditedBy(User $user): bool
    {
        // Only draft summaries can be edited
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

    public function canBeReviewedBy(User $user): bool
    {
        if ($this->status !== self::STATUS_UNDER_REVIEW) {
            return false;
        }

        // Add your permission logic here
        return $user->hasRole(['admin', 'reviewer', 'manager']);
    }

    public function canBeApprovedBy(User $user): bool
    {
        if (!in_array($this->status, [self::STATUS_UNDER_REVIEW, self::STATUS_DRAFT])) {
            return false;
        }

        // Add your permission logic here
        return $user->hasRole(['admin', 'approver', 'director']);
    }

    public function getFinancialHealth(): string
    {
        if (!$this->net_result) {
            return 'neutral';
        }

        if ($this->net_result > 0) {
            return 'positive';
        }

        return 'negative';
    }

    public function getBudgetStatus(): string
    {
        if (!$this->budget_utilization_percentage) {
            return 'unknown';
        }

        if ($this->budget_utilization_percentage <= 80) {
            return 'under_budget';
        } elseif ($this->budget_utilization_percentage <= 100) {
            return 'on_budget';
        } else {
            return 'over_budget';
        }
    }

    public function getPerformanceRating(): ?string
    {
        if (!$this->satisfaction_score) {
            return null;
        }

        if ($this->satisfaction_score >= 4.5) {
            return 'excellent';
        } elseif ($this->satisfaction_score >= 4.0) {
            return 'very_good';
        } elseif ($this->satisfaction_score >= 3.5) {
            return 'good';
        } elseif ($this->satisfaction_score >= 3.0) {
            return 'satisfactory';
        } else {
            return 'needs_improvement';
        }
    }

    public function getAverageParticipantsPerEvent(): ?float
    {
        if (!$this->events_conducted || $this->events_conducted == 0) {
            return null;
        }

        return round($this->total_participants / $this->events_conducted, 2);
    }

    public static function generateSummaryCode(): string
    {
        $year = now()->year;
        $latestSummary = static::whereYear('created_at', $year)
                              ->latest('id')
                              ->first();
        
        $number = $latestSummary ? 
                  ((int) substr($latestSummary->summary_code, -3) + 1) : 1;
        
        return 'ES-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($summary) {
            if (empty($summary->summary_code)) {
                $summary->summary_code = static::generateSummaryCode();
            }
            
            // Set report_date to today if not set
            if (empty($summary->report_date)) {
                $summary->report_date = now()->toDateString();
            }
        });

        static::updating(function ($summary) {
            // Auto-calculate financial metrics when income or expenses change
            if ($summary->isDirty(['total_income', 'total_expenses'])) {
                $netResult = ($summary->total_income ?? 0) - ($summary->total_expenses ?? 0);
                $summary->net_result = $netResult;
                
                if ($summary->total_income && $summary->total_income > 0) {
                    $summary->budget_utilization_percentage = 
                        round(($summary->total_expenses / $summary->total_income) * 100, 2);
                }
            }
        });

        static::deleting(function ($summary) {
            // Delete document file
            if ($summary->document_file && Storage::exists($summary->document_file)) {
                Storage::delete($summary->document_file);
            }

            // Delete supporting documents
            if ($summary->supporting_documents) {
                foreach ($summary->supporting_documents as $document) {
                    if (Storage::exists($document)) {
                        Storage::delete($document);
                    }
                }
            }
        });
    }
}