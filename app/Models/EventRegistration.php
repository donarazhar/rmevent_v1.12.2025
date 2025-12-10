<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status Constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ATTENDED = 'attended';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    /**
     * Payment Status Constants
     */
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * Gender Constants
     */
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    protected $fillable = [
        'registration_code',
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'address',
        'city',
        'province',
        'custom_data',
        'status',
        'payment_status',
        'payment_amount',
        'payment_proof',
        'payment_date',
        'checked_in_at',
        'checked_in_by',
        'notes',
        'admin_notes',
        'confirmation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'custom_data' => 'array',
            'payment_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'checked_in_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * BOOT - Generate registration code automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = self::generateRegistrationCode();
            }
        });
    }

    /**
     * RELATIONSHIPS
     */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'registration_id');
    }

    /**
     * SCOPES
     */

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeAttended($query)
    {
        return $query->where('status', self::STATUS_ATTENDED);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * HELPER METHODS
     */

    public function confirm(): bool
    {
        $this->status = self::STATUS_CONFIRMED;
        $this->confirmation_sent_at = now();
        return $this->save();
    }

    public function cancel(): bool
    {
        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }

    public function checkIn(string $checkedBy = null): bool
    {
        $this->status = self::STATUS_ATTENDED;
        $this->checked_in_at = now();
        $this->checked_in_by = $checkedBy;
        return $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isAttended(): bool
    {
        return $this->status === self::STATUS_ATTENDED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Generate unique registration code
     */
    public static function generateRegistrationCode(): string
    {
        do {
            // Format: REG-RMB-2025-XXXX
            $code = 'REG-RMB-' . date('Y') . '-' . strtoupper(Str::random(4));
        } while (self::where('registration_code', $code)->exists());

        return $code;
    }
}
