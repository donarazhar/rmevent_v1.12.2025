<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FinalEventReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'report_code',
        'title',
        'report_date',
        'executive_summary',
        'event_overview',
        'objectives_achievement',
        'implementation_process',
        'participant_analysis',
        'financial_report',
        'challenges_solutions',
        'lessons_learned',
        'recommendations',
        'conclusion',
        'total_participants',
        'registered_participants',
        'attended_participants',
        'attendance_rate',
        'total_budget',
        'total_income',
        'total_expenses',
        'surplus_deficit',
        'overall_satisfaction',
        'content_rating',
        'organization_rating',
        'venue_rating',
        'committee_members',
        'team_performance_score',
        'photo_gallery',
        'video_links',
        'supporting_documents',
        'report_file',
        'presentation_file',
        'status',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'published_at',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'photo_gallery' => 'array',
        'video_links' => 'array',
        'supporting_documents' => 'array',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
        'total_participants' => 'integer',
        'registered_participants' => 'integer',
        'attended_participants' => 'integer',
        'committee_members' => 'integer',
        'attendance_rate' => 'decimal:2',
        'total_budget' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'surplus_deficit' => 'decimal:2',
        'overall_satisfaction' => 'decimal:2',
        'content_rating' => 'decimal:2',
        'organization_rating' => 'decimal:2',
        'venue_rating' => 'decimal:2',
        'team_performance_score' => 'decimal:2',
    ];

    protected $appends = [
        'status_label',
        'report_file_url',
        'presentation_file_url',
        'budget_utilization_percentage',
        'is_surplus',
        'average_rating',
        'completion_percentage',
    ];

    // Constants for enums
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

    public function getReportFileUrlAttribute(): ?string
    {
        return $this->report_file ? Storage::url($this->report_file) : null;
    }

    public function getPresentationFileUrlAttribute(): ?string
    {
        return $this->presentation_file ? Storage::url($this->presentation_file) : null;
    }

    public function getBudgetUtilizationPercentageAttribute(): ?float
    {
        if (!$this->total_budget || $this->total_budget == 0) {
            return null;
        }

        return round(($this->total_expenses / $this->total_budget) * 100, 2);
    }

    public function getIsSurplusAttribute(): bool
    {
        if (!$this->surplus_deficit) {
            return false;
        }

        return $this->surplus_deficit > 0;
    }

    public function getAverageRatingAttribute(): ?float
    {
        $ratings = array_filter([
            $this->content_rating,
            $this->organization_rating,
            $this->venue_rating,
        ]);

        if (empty($ratings)) {
            return null;
        }

        return round(array_sum($ratings) / count($ratings), 2);
    }

    public function getCompletionPercentageAttribute(): float
    {
        $sections = [
            'executive_summary',
            'event_overview',
            'objectives_achievement',
            'implementation_process',
            'participant_analysis',
            'financial_report',
            'challenges_solutions',
            'lessons_learned',
            'recommendations',
            'conclusion',
        ];

        $completed = 0;
        foreach ($sections as $section) {
            if (!empty($this->$section)) {
                $completed++;
            }
        }

        return round(($completed / count($sections)) * 100, 2);
    }

    /**
     * Scopes
     */
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

    public function scopeReportDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('report_date', now()->month)
                    ->whereYear('report_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('report_date', now()->year);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('report_code', 'like', "%{$search}%")
              ->orWhere('executive_summary', 'like', "%{$search}%")
              ->orWhereHas('event', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeWithSurplus($query)
    {
        return $query->where('surplus_deficit', '>', 0);
    }

    public function scopeWithDeficit($query)
    {
        return $query->where('surplus_deficit', '<', 0);
    }

    public function scopeHighRated($query, float $minRating = 4.0)
    {
        return $query->where('overall_satisfaction', '>=', $minRating);
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
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_APPROVED,
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

    public function calculateAttendanceRate(): void
    {
        if (!$this->registered_participants || $this->registered_participants == 0) {
            return;
        }

        $attendanceRate = ($this->attended_participants / $this->registered_participants) * 100;
        
        $this->update([
            'attendance_rate' => round($attendanceRate, 2),
        ]);
    }

    public function calculateFinancialSummary(): void
    {
        $surplusDeficit = ($this->total_income ?? 0) - ($this->total_expenses ?? 0);
        
        $this->update([
            'surplus_deficit' => $surplusDeficit,
        ]);
    }

    public function addPhoto(string $photoPath, ?string $caption = null): void
    {
        $photos = $this->photo_gallery ?? [];
        
        $photos[] = [
            'path' => $photoPath,
            'caption' => $caption,
            'uploaded_at' => now()->toDateTimeString(),
        ];
        
        $this->update(['photo_gallery' => $photos]);
    }

    public function removePhoto(string $photoPath): void
    {
        $photos = $this->photo_gallery ?? [];
        $photos = array_filter($photos, fn($photo) => $photo['path'] !== $photoPath);
        $this->update(['photo_gallery' => array_values($photos)]);
        
        // Delete file from storage
        if (Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }
    }

    public function addVideoLink(string $url, ?string $title = null, ?string $platform = null): void
    {
        $videos = $this->video_links ?? [];
        
        $videos[] = [
            'url' => $url,
            'title' => $title,
            'platform' => $platform, // youtube, vimeo, etc.
            'added_at' => now()->toDateTimeString(),
        ];
        
        $this->update(['video_links' => $videos]);
    }

    public function removeVideoLink(string $url): void
    {
        $videos = $this->video_links ?? [];
        $videos = array_filter($videos, fn($video) => $video['url'] !== $url);
        $this->update(['video_links' => array_values($videos)]);
    }

    public function addSupportingDocument(string $documentPath, ?string $description = null): void
    {
        $documents = $this->supporting_documents ?? [];
        
        $documents[] = [
            'path' => $documentPath,
            'description' => $description,
            'uploaded_at' => now()->toDateTimeString(),
        ];
        
        $this->update(['supporting_documents' => $documents]);
    }

    public function removeSupportingDocument(string $documentPath): void
    {
        $documents = $this->supporting_documents ?? [];
        $documents = array_filter($documents, fn($doc) => $doc['path'] !== $documentPath);
        $this->update(['supporting_documents' => array_values($documents)]);
        
        // Delete file from storage
        if (Storage::exists($documentPath)) {
            Storage::delete($documentPath);
        }
    }

    public function generateReport(): string
    {
        // Placeholder for report generation logic
        // You would implement logic here to generate a comprehensive PDF report
        // with all sections, statistics, charts, and documentation
        
        return '';
    }

    public function generatePresentation(): string
    {
        // Placeholder for presentation generation logic
        // You would implement logic here to generate a PowerPoint presentation
        // summarizing the key points of the report
        
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
        // Only draft reports can be edited
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

    public function getFinancialStatus(): string
    {
        if (!$this->surplus_deficit) {
            return 'balanced';
        }

        if ($this->surplus_deficit > 0) {
            return 'surplus';
        }

        return 'deficit';
    }

    public function getBudgetHealthStatus(): string
    {
        $utilization = $this->budget_utilization_percentage;

        if (!$utilization) {
            return 'unknown';
        }

        if ($utilization <= 80) {
            return 'excellent';
        } elseif ($utilization <= 95) {
            return 'good';
        } elseif ($utilization <= 100) {
            return 'on_target';
        } else {
            return 'over_budget';
        }
    }

    public function getPerformanceLevel(): ?string
    {
        if (!$this->overall_satisfaction) {
            return null;
        }

        if ($this->overall_satisfaction >= 4.5) {
            return 'excellent';
        } elseif ($this->overall_satisfaction >= 4.0) {
            return 'very_good';
        } elseif ($this->overall_satisfaction >= 3.5) {
            return 'good';
        } elseif ($this->overall_satisfaction >= 3.0) {
            return 'satisfactory';
        } else {
            return 'needs_improvement';
        }
    }

    public function getAttendanceStatus(): ?string
    {
        if (!$this->attendance_rate) {
            return null;
        }

        if ($this->attendance_rate >= 90) {
            return 'excellent';
        } elseif ($this->attendance_rate >= 80) {
            return 'very_good';
        } elseif ($this->attendance_rate >= 70) {
            return 'good';
        } elseif ($this->attendance_rate >= 60) {
            return 'satisfactory';
        } else {
            return 'poor';
        }
    }

    public function isComplete(): bool
    {
        return $this->completion_percentage >= 100;
    }

    public function getMissingSections(): array
    {
        $sections = [
            'executive_summary' => 'Ringkasan Eksekutif',
            'event_overview' => 'Gambaran Umum Acara',
            'objectives_achievement' => 'Pencapaian Tujuan',
            'implementation_process' => 'Proses Pelaksanaan',
            'participant_analysis' => 'Analisis Peserta',
            'financial_report' => 'Laporan Keuangan',
            'challenges_solutions' => 'Tantangan & Solusi',
            'lessons_learned' => 'Pelajaran yang Dipetik',
            'recommendations' => 'Rekomendasi',
            'conclusion' => 'Kesimpulan',
        ];

        $missing = [];
        foreach ($sections as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    public static function generateReportCode(): string
    {
        $year = now()->year;
        $latestReport = static::whereYear('created_at', $year)
                             ->latest('id')
                             ->first();
        
        $number = $latestReport ? 
                  ((int) substr($latestReport->report_code, -3) + 1) : 1;
        
        return 'FER-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (empty($report->report_code)) {
                $report->report_code = static::generateReportCode();
            }
            
            // Set report_date to today if not set
            if (empty($report->report_date)) {
                $report->report_date = now()->toDateString();
            }
        });

        static::updating(function ($report) {
            // Auto-calculate attendance rate
            if ($report->isDirty(['registered_participants', 'attended_participants'])) {
                if ($report->registered_participants && $report->registered_participants > 0) {
                    $report->attendance_rate = round(
                        ($report->attended_participants / $report->registered_participants) * 100, 
                        2
                    );
                }
            }

            // Auto-calculate surplus/deficit
            if ($report->isDirty(['total_income', 'total_expenses'])) {
                $report->surplus_deficit = ($report->total_income ?? 0) - ($report->total_expenses ?? 0);
            }
        });

        static::deleting(function ($report) {
            // Delete report file
            if ($report->report_file && Storage::exists($report->report_file)) {
                Storage::delete($report->report_file);
            }

            // Delete presentation file
            if ($report->presentation_file && Storage::exists($report->presentation_file)) {
                Storage::delete($report->presentation_file);
            }

            // Delete photos
            if ($report->photo_gallery) {
                foreach ($report->photo_gallery as $photo) {
                    if (isset($photo['path']) && Storage::exists($photo['path'])) {
                        Storage::delete($photo['path']);
                    }
                }
            }

            // Delete supporting documents
            if ($report->supporting_documents) {
                foreach ($report->supporting_documents as $document) {
                    if (isset($document['path']) && Storage::exists($document['path'])) {
                        Storage::delete($document['path']);
                    }
                }
            }
        });
    }
}