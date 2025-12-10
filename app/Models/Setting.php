<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * Type Constants
     */
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_IMAGE = 'image';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_COLOR = 'color';
    const TYPE_DATE = 'date';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * BOOT
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when setting is saved or deleted
        static::saved(function () {
            Cache::forget('settings');
        });

        static::deleted(function () {
            Cache::forget('settings');
        });
    }

    /**
     * SCOPES
     */

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('group')->orderBy('order')->orderBy('key');
    }

    /**
     * ACCESSORS
     */

    public function getFormattedValueAttribute()
    {
        return match($this->type) {
            self::TYPE_BOOLEAN => (bool) $this->value,
            self::TYPE_JSON => json_decode($this->value, true),
            self::TYPE_IMAGE => $this->value ? asset('storage/' . $this->value) : null,
            default => $this->value,
        };
    }

    /**
     * STATIC HELPER METHODS
     */

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::firstOrCreate(['key' => $key]);
        
        // Convert value based on type
        if ($setting->type === self::TYPE_BOOLEAN) {
            $value = $value ? '1' : '0';
        } elseif ($setting->type === self::TYPE_JSON) {
            $value = is_array($value) ? json_encode($value) : $value;
        }

        $setting->value = $value;
        return $setting->save();
    }

    /**
     * Get all settings as key-value array with caching
     */
    public static function getAllCached(): array
    {
        return Cache::remember('settings', now()->addDay(), function () {
            return self::all()->pluck('formatted_value', 'key')->toArray();
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        return self::byGroup($group)->get()->pluck('formatted_value', 'key')->toArray();
    }

    /**
     * Check if setting exists
     */
    public static function has(string $key): bool
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Remove setting by key
     */
    public static function remove(string $key): bool
    {
        return self::where('key', $key)->delete();
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('settings');
    }

    /**
     * Bulk update settings
     */
    public static function bulkUpdate(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
        return true;
    }
}