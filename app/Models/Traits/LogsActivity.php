<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            if (config('activitylog.log_created', true)) {
                $model->logActivity('created', 'Created ' . class_basename($model));
            }
        });

        static::updated(function ($model) {
            if (config('activitylog.log_updated', true)) {
                $old = $model->getOriginal();
                $model->logActivity('updated', 'Updated ' . class_basename($model), [
                    'old' => $old,
                    'new' => $model->getAttributes(),
                ]);
            }
        });

        static::deleted(function ($model) {
            if (config('activitylog.log_deleted', true)) {
                $model->logActivity('deleted', 'Deleted ' . class_basename($model));
            }
        });
    }

    /**
     * Get all activity logs for this model
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Log activity for this model
     */
    public function logActivity(
        string $action,
        ?string $description = null,
        array $properties = []
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description ?? ucfirst($action) . ' ' . class_basename($this),
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get recent activity logs
     */
    public function recentActivity(int $limit = 10)
    {
        return $this->activityLogs()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get activity by action
     */
    public function getActivityByAction(string $action)
    {
        return $this->activityLogs()
                    ->where('action', $action)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}