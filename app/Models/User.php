<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role Constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_PANITIA = 'panitia';
    const ROLE_JAMAAH = 'jamaah';

    /**
     * Status Constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * Relationships
     */
    
    // Posts authored by this user
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    // Event registrations
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    // Feedbacks given by this user
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    // Activity logs
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Scopes
     */
    
    // Filter by role
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopePanitia($query)
    {
        return $query->where('role', self::ROLE_PANITIA);
    }

    public function scopeJamaah($query)
    {
        return $query->where('role', self::ROLE_JAMAAH);
    }

    // Filter by status
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    /**
     * Helper Methods
     */
    
    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Check if user is panitia
    public function isPanitia()
    {
        return $this->role === self::ROLE_PANITIA;
    }

    // Check if user is jamaah
    public function isJamaah()
    {
        return $this->role === self::ROLE_JAMAAH;
    }

    // Check if user is active
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Get avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return \Storage::url($this->avatar);
        }

        // Default avatar with initial
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=0053C5&background=E3F2FD';
    }

    // Get display name with role
    public function getDisplayNameAttribute()
    {
        $roleBadge = match($this->role) {
            self::ROLE_ADMIN => '👨‍💼',
            self::ROLE_PANITIA => '🎯',
            self::ROLE_JAMAAH => '🕌',
            default => ''
        };

        return $roleBadge . ' ' . $this->name;
    }
}