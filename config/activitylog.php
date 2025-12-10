<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Activity Log Settings
    |--------------------------------------------------------------------------
    |
    | Configure which actions should be logged automatically
    |
    */

    'log_created' => env('ACTIVITYLOG_CREATED', true),
    'log_updated' => env('ACTIVITYLOG_UPDATED', true),
    'log_deleted' => env('ACTIVITYLOG_DELETED', true),

    /*
    |--------------------------------------------------------------------------
    | Excluded Attributes
    |--------------------------------------------------------------------------
    |
    | Attributes that should not be logged in activity changes
    |
    */

    'excluded_attributes' => [
        'password',
        'remember_token',
        'updated_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention Period
    |--------------------------------------------------------------------------
    |
    | Number of days to keep activity logs (null = forever)
    |
    */

    'retention_days' => env('ACTIVITYLOG_RETENTION_DAYS', 90),
];