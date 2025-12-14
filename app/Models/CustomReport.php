<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CustomReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'report_code',
        'title',
        'description',
        'report_type',
        'data_sources',
        'filters',
        'metrics',
        'dimensions',
        'chart_config',
        'period_start',
        'period_end',
        'report_data',
        'last_generated_at',
        'is_scheduled',
        'schedule_frequency',
        'schedule_config',
        'visibility',
        'shared_with',
        'status',
        'created_by',
        'view_count',
        'export_count',
    ];

    protected $casts = [
        'data_sources' => 'array',
        'filters' => 'array',
        'metrics' => 'array',
        'dimensions' => 'array',
        'chart_config' => 'array',
        'report_data' => 'array',
        'schedule_config' => 'array',
        'shared_with' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'last_generated_at' => 'datetime',
        'is_scheduled' => 'boolean',
        'view_count' => 'integer',
        'export_count' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'is_stale',
        'period_duration_days',
        'next_scheduled_run',
    ];

    // Constants for enums
    public const TYPE_FINANCIAL = 'financial';
    public const TYPE_PERFORMANCE = 'performance';
    public const TYPE_EVENT = 'event';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_CUSTOM = 'custom';

    public const FREQUENCY_DAILY = 'daily';
    public const FREQUENCY_WEEKLY = 'weekly';
    public const FREQUENCY_MONTHLY = 'monthly';
    public const FREQUENCY_QUARTERLY = 'quarterly';

    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_TEAM = 'team';
    public const VISIBILITY_PUBLIC = 'public';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SAVED = 'saved';
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

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SAVED => 'Tersimpan',
            self::STATUS_PUBLISHED => 'Dipublikasikan',
            default => 'Unknown',
        };
    }

    public function getIsStaleAttribute(): bool
    {
        if (!$this->last_generated_at) {
            return true;
        }

        // Consider report stale if not generated in last 24 hours
        return $this->last_generated_at->diffInHours(now()) > 24;
    }

    public function getPeriodDurationDaysAttribute(): ?int
    {
        if (!$this->period_start || !$this->period_end) {
            return null;
        }

        return $this->period_start->diffInDays($this->period_end);
    }

    public function getNextScheduledRunAttribute(): ?Carbon
    {
        if (!$this->is_scheduled || !$this->last_generated_at || !$this->schedule_frequency) {
            return null;
        }

        return match($this->schedule_frequency) {
            self::FREQUENCY_DAILY => $this->last_generated_at->addDay(),
            self::FREQUENCY_WEEKLY => $this->last_generated_at->addWeek(),
            self::FREQUENCY_MONTHLY => $this->last_generated_at->addMonth(),
            self::FREQUENCY_QUARTERLY => $this->last_generated_at->addMonths(3),
            default => null,
        };
    }

    /**
     * Scopes
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    public function scopeDueForGeneration($query)
    {
        return $query->where('is_scheduled', true)
                    ->where(function ($q) {
                        $q->whereNull('last_generated_at')
                          ->orWhere(function ($q2) {
                              $q2->where('schedule_frequency', self::FREQUENCY_DAILY)
                                 ->where('last_generated_at', '<', now()->subDay());
                          })
                          ->orWhere(function ($q2) {
                              $q2->where('schedule_frequency', self::FREQUENCY_WEEKLY)
                                 ->where('last_generated_at', '<', now()->subWeek());
                          })
                          ->orWhere(function ($q2) {
                              $q2->where('schedule_frequency', self::FREQUENCY_MONTHLY)
                                 ->where('last_generated_at', '<', now()->subMonth());
                          })
                          ->orWhere(function ($q2) {
                              $q2->where('schedule_frequency', self::FREQUENCY_QUARTERLY)
                                 ->where('last_generated_at', '<', now()->subMonths(3));
                          });
                    });
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByVisibility($query, string $visibility)
    {
        return $query->where('visibility', $visibility);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function scopeVisibleTo($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('visibility', self::VISIBILITY_PUBLIC)
              ->orWhere('created_by', $userId)
              ->orWhereJsonContains('shared_with', $userId);
        });
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
              ->orWhere('report_code', 'like', "%{$search}%");
        });
    }

    public function scopePeriodBetween($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('period_start', [$startDate, $endDate])
              ->orWhereBetween('period_end', [$startDate, $endDate]);
        });
    }

    public function scopeStale($query)
    {
        return $query->where('last_generated_at', '<', now()->subDay());
    }

    /**
     * Helper Methods
     */
    public function generate(): bool
    {
        try {
            // This is where you would implement the actual report generation logic
            // based on data_sources, filters, metrics, and dimensions
            
            $data = $this->executeReportQuery();
            
            return $this->update([
                'report_data' => $data,
                'last_generated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function executeReportQuery(): array
    {
        // Placeholder for actual query execution
        // You would implement logic here to:
        // 1. Query data from data_sources
        // 2. Apply filters
        // 3. Calculate metrics
        // 4. Group by dimensions
        // 5. Return formatted data
        
        return [];
    }

    public function publish(): bool
    {
        if ($this->status === self::STATUS_PUBLISHED) {
            return false;
        }

        // Generate report before publishing if not already generated
        if (!$this->report_data || $this->is_stale) {
            $this->generate();
        }

        return $this->update([
            'status' => self::STATUS_PUBLISHED,
        ]);
    }

    public function unpublish(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_SAVED,
        ]);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementExportCount(): void
    {
        $this->increment('export_count');
    }

    public function shareWith(int $userId): void
    {
        $sharedWith = $this->shared_with ?? [];
        
        if (!in_array($userId, $sharedWith)) {
            $sharedWith[] = $userId;
            $this->update(['shared_with' => $sharedWith]);
        }
    }

    public function unshareWith(int $userId): void
    {
        $sharedWith = $this->shared_with ?? [];
        $sharedWith = array_filter($sharedWith, fn($id) => $id !== $userId);
        $this->update(['shared_with' => array_values($sharedWith)]);
    }

    public function shareWithMultiple(array $userIds): void
    {
        $sharedWith = $this->shared_with ?? [];
        
        foreach ($userIds as $userId) {
            if (!in_array($userId, $sharedWith)) {
                $sharedWith[] = $userId;
            }
        }
        
        $this->update(['shared_with' => $sharedWith]);
    }

    public function enableSchedule(string $frequency, ?array $config = null): bool
    {
        return $this->update([
            'is_scheduled' => true,
            'schedule_frequency' => $frequency,
            'schedule_config' => $config,
        ]);
    }

    public function disableSchedule(): bool
    {
        return $this->update([
            'is_scheduled' => false,
            'schedule_frequency' => null,
            'schedule_config' => null,
        ]);
    }

    public function addDataSource(string $source): void
    {
        $dataSources = $this->data_sources ?? [];
        
        if (!in_array($source, $dataSources)) {
            $dataSources[] = $source;
            $this->update(['data_sources' => $dataSources]);
        }
    }

    public function addFilter(string $field, string $operator, $value): void
    {
        $filters = $this->filters ?? [];
        
        $filters[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
        ];
        
        $this->update(['filters' => $filters]);
    }

    public function addMetric(string $name, string $aggregation, string $field): void
    {
        $metrics = $this->metrics ?? [];
        
        $metrics[] = [
            'name' => $name,
            'aggregation' => $aggregation, // sum, avg, count, max, min
            'field' => $field,
        ];
        
        $this->update(['metrics' => $metrics]);
    }

    public function addDimension(string $field, ?string $label = null): void
    {
        $dimensions = $this->dimensions ?? [];
        
        $dimensions[] = [
            'field' => $field,
            'label' => $label ?? $field,
        ];
        
        $this->update(['dimensions' => $dimensions]);
    }

    public function setChartConfig(string $type, array $config): void
    {
        $chartConfig = [
            'type' => $type, // line, bar, pie, etc.
            'config' => $config,
        ];
        
        $this->update(['chart_config' => $chartConfig]);
    }

    public function clearFilters(): bool
    {
        return $this->update(['filters' => []]);
    }

    public function clearMetrics(): bool
    {
        return $this->update(['metrics' => []]);
    }

    public function clearDimensions(): bool
    {
        return $this->update(['dimensions' => []]);
    }

    public function canBeViewedBy(User $user): bool
    {
        if ($this->visibility === self::VISIBILITY_PUBLIC) {
            return true;
        }

        if ($this->created_by === $user->id) {
            return true;
        }

        if ($this->shared_with && in_array($user->id, $this->shared_with)) {
            return true;
        }

        if ($this->visibility === self::VISIBILITY_TEAM && $user->team_id === $this->createdBy->team_id) {
            return true;
        }

        return false;
    }

    public function canBeEditedBy(User $user): bool
    {
        // Only creator can edit
        if ($this->created_by === $user->id) {
            return true;
        }

        // Admin can edit
        return $user->hasRole('admin');
    }

    public function canBeDeletedBy(User $user): bool
    {
        // Only creator can delete
        if ($this->created_by === $user->id) {
            return true;
        }

        // Admin can delete
        return $user->hasRole('admin');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSaved(): bool
    {
        return $this->status === self::STATUS_SAVED;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isScheduled(): bool
    {
        return $this->is_scheduled;
    }

    public function needsRegeneration(): bool
    {
        return $this->is_stale || !$this->report_data;
    }

    public function export(string $format = 'pdf'): string
    {
        // Placeholder for export logic
        // You would implement logic here to export the report to PDF, Excel, CSV, etc.
        
        $this->incrementExportCount();
        
        return ''; // Return file path
    }

    public function duplicate(): CustomReport
    {
        $newReport = $this->replicate();
        $newReport->report_code = static::generateReportCode();
        $newReport->title = $this->title . ' (Copy)';
        $newReport->status = self::STATUS_DRAFT;
        $newReport->report_data = null;
        $newReport->last_generated_at = null;
        $newReport->view_count = 0;
        $newReport->export_count = 0;
        $newReport->save();
        
        return $newReport;
    }

    public static function generateReportCode(): string
    {
        $year = now()->year;
        $latestReport = static::whereYear('created_at', $year)
                             ->latest('id')
                             ->first();
        
        $number = $latestReport ? 
                  ((int) substr($latestReport->report_code, -3) + 1) : 1;
        
        return 'CR-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
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
        });
    }
}