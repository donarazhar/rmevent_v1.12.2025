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
        // Log when model is created
        static::created(function ($model) {
            if (method_exists($model, 'shouldLogActivity') && !$model->shouldLogActivity()) {
                return;
            }

            ActivityLog::logCreated(
                $model,
                static::getActivityDescription($model, 'created')
            );
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (method_exists($model, 'shouldLogActivity') && !$model->shouldLogActivity()) {
                return;
            }

            $old = $model->getOriginal();

            ActivityLog::logUpdated(
                $model,
                $old,
                static::getActivityDescription($model, 'updated')
            );
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (method_exists($model, 'shouldLogActivity') && !$model->shouldLogActivity()) {
                return;
            }

            ActivityLog::logDeleted(
                $model,
                static::getActivityDescription($model, 'deleted')
            );
        });
    }

    /**
     * Get activity description
     */
    protected static function getActivityDescription($model, string $action): string
    {
        $modelName = class_basename($model);
        $userName = auth()->check() ? auth()->user()->name : 'System';

        // Check if model has custom description method
        if (method_exists($model, 'getActivityDescription')) {
            return $model->getActivityDescription($action);
        }

        // Check if model has name or title attribute
        $identifier = $model->name ?? $model->title ?? "#{$model->id}";

        return match ($action) {
            'created' => "{$userName} membuat {$modelName}: {$identifier}",
            'updated' => "{$userName} mengupdate {$modelName}: {$identifier}",
            'deleted' => "{$userName} menghapus {$modelName}: {$identifier}",
            default => "{$userName} melakukan {$action} pada {$modelName}: {$identifier}",
        };
    }

    /**
     * Log custom activity
     */
    public function logActivity(string $action, ?string $description = null, array $properties = []): ActivityLog
    {
        return ActivityLog::log(
            $action,
            $this,
            $description ?? static::getActivityDescription($this, $action),
            $properties
        );
    }

    /**
     * Get all activity logs for this model
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get recent activity logs
     */
    public function recentActivityLogs(int $limit = 10)
    {
        return $this->activityLogs()->limit($limit)->get();
    }
}
