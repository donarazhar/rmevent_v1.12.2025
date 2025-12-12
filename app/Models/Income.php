<?php
// app/Models/Income.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'budget_id',
        'income_code',
        'title',
        'description',
        'category',
        'source_name',
        'source_contact',
        'source_email',
        'registration_id',
        'sponsorship_id',
        'amount',
        'payment_method',
        'payment_reference',
        'bank_account',
        'payment_date',
        'received_date',
        'receipt_number',
        'receipt_file',
        'status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'attachments',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'received_date' => 'date',
            'verified_at' => 'datetime',
            'attachments' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }

    public function sponsorship()
    {
        return $this->belongsTo(Sponsorship::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereBetween('received_date', [$start, $end]);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getIsVerifiedAttribute()
    {
        return $this->status === 'verified';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // ========================================
    // METHODS
    // ========================================

    public function verify($userId, $notes = null)
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => $userId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);

        // Update budget totals
        if ($this->budget) {
            $this->budget->calculateTotals();
        }

        // Update sponsorship if applicable
        if ($this->sponsorship) {
            $this->sponsorship->recordPayment($this->amount, $this->id);
        }
    }

    public function reject($userId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $userId,
            'verified_at' => now(),
            'verification_notes' => $reason,
        ]);
    }

    public function generateReceiptNumber()
    {
        $date = $this->received_date ?? now();
        $prefix = 'RCP';
        $year = $date->format('Y');
        $month = $date->format('m');
        
        $lastReceipt = self::where('receipt_number', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('receipt_number', 'desc')
            ->first();
        
        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        $this->receipt_number = "{$prefix}-{$year}{$month}-{$newNumber}";
        $this->save();
        
        return $this->receipt_number;
    }
}