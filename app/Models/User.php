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
        'position',        // Jabatan: Ketua, Sekretaris, Bendahara
        'unit',            // Unit koordinasi
        'seksi',           // Nama seksi kegiatan
        'is_coordinator',  // Koordinator seksi atau tidak
        'bio',
        'address',
        'city',
        'province',
        'postal_code',
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
            'is_coordinator' => 'boolean',
        ];
    }

    /**
     * Role Constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_PENASEHAT = 'penasehat';
    const ROLE_PENGARAH = 'pengarah';
    const ROLE_PELAKSANA = 'pelaksana';
    const ROLE_KOORDINATOR = 'koordinator';
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

    public function scopePenasehat($query)
    {
        return $query->where('role', self::ROLE_PENASEHAT);
    }

    public function scopePengarah($query)
    {
        return $query->where('role', self::ROLE_PENGARAH);
    }

    public function scopePelaksana($query)
    {
        return $query->where('role', self::ROLE_PELAKSANA);
    }

    public function scopeKoordinator($query)
    {
        return $query->where('role', self::ROLE_KOORDINATOR);
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

    // Check if user is penasehat
    public function isPenasehat()
    {
        return $this->role === self::ROLE_PENASEHAT;
    }

    // Check if user is pengarah
    public function isPengarah()
    {
        return $this->role === self::ROLE_PENGARAH;
    }

    // Check if user is pelaksana
    public function isPelaksana()
    {
        return $this->role === self::ROLE_PELAKSANA;
    }

    // Check if user is koordinator
    public function isKoordinator()
    {
        return $this->role === self::ROLE_KOORDINATOR;
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
        $roleBadge = match ($this->role) {
            self::ROLE_ADMIN => '👨‍💼',
            self::ROLE_PENASEHAT => '🎓',
            self::ROLE_PENGARAH => '👔',
            self::ROLE_PELAKSANA => '⭐',
            self::ROLE_KOORDINATOR => '🎯',
            self::ROLE_PANITIA => '👥',
            self::ROLE_JAMAAH => '🕌',
            default => ''
        };

        return $roleBadge . ' ' . $this->name;
    }

    // Get role label
    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_PENASEHAT => 'Penasehat',
            self::ROLE_PENGARAH => 'Pengarah (SC)',
            self::ROLE_PELAKSANA => 'Pelaksana (OC)',
            self::ROLE_KOORDINATOR => 'Koordinator Unit',
            self::ROLE_PANITIA => 'Panitia Seksi',
            self::ROLE_JAMAAH => 'Jamaah',
            default => 'Unknown'
        };
    }

    // Get full position info
    public function getFullPositionAttribute()
    {
        $parts = [];

        if ($this->position) {
            $parts[] = $this->position;
        }

        if ($this->unit) {
            $parts[] = $this->unit;
        }

        if ($this->seksi) {
            $parts[] = $this->seksi;
            if ($this->is_coordinator) {
                $parts[] = '(Koordinator)';
            }
        }

        return !empty($parts) ? implode(' - ', $parts) : null;
    }
}
