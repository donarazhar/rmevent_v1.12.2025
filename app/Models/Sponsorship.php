<?php
// app/Models/Sponsorship.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsorship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'sponsor_code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'website',
        'tier',
        'committed_amount',
        'received_amount',
        'outstanding_amount',
        'type',
        'in_kind_description',
        'in_kind_value',
        'benefits_package',
        'logo_placements',
        'deliverables',
        'status',
        'proposal_sent_date',
        'commitment_date',
        'contract_date',
        'fulfillment_date',
        'payment_schedule',
        'contract_document',
        'proposal_document',
        'attachments',
        'notes',
        'internal_notes',
        'pic_internal',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'committed_amount' => 'decimal:2',
            'received_amount' => 'decimal:2',
            'outstanding_amount' => 'decimal:2',
            'in_kind_value' => 'decimal:2',
            'benefits_package' => 'array',
            'logo_placements' => 'array',
            'deliverables' => 'array',
            'payment_schedule' => 'array',
            'attachments' => 'array',
            'proposal_sent_date' => 'date',
            'commitment_date' => 'date',
            'contract_date' => 'date',
            'fulfillment_date' => 'date',
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

    public function picInternal()
    {
        return $this->belongsTo(User::class, 'pic_internal');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'sponsorship_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'sponsorship_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeByTier($query, $tier)
    {
        return $query->where('tier', $tier);
    }

    public function scopeConfirmed($query)
    {
        return $query->whereIn('status', ['confirmed', 'delivered', 'completed']);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['committed', 'confirmed', 'delivered']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['prospecting', 'negotiating']);
    }

    public function scopeWithOutstanding($query)
    {
        return $query->where('outstanding_amount', '>', 0);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getTotalValueAttribute()
    {
        $cash = $this->type === 'cash' ? $this->committed_amount : 0;
        $inKind = $this->in_kind_value ?? 0;

        if ($this->type === 'mixed') {
            return $this->committed_amount + $inKind;
        }

        return $cash + $inKind;
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->committed_amount == 0) {
            return 0;
        }
        return round(($this->received_amount / $this->committed_amount) * 100, 2);
    }

    public function getIsFulfilledAttribute()
    {
        return $this->outstanding_amount <= 0;
    }

    // ========================================
    // METHODS
    // ========================================

    public function recordPayment($amount, $incomeId = null)
    {
        $this->received_amount += $amount;
        $this->outstanding_amount = $this->committed_amount - $this->received_amount;

        if ($this->outstanding_amount <= 0 && $this->status === 'confirmed') {
            $this->status = 'delivered';
        }

        $this->save();
    }

    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'commitment_date' => now(),
            'outstanding_amount' => $this->committed_amount - $this->received_amount,
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'internal_notes' => ($this->internal_notes ? $this->internal_notes . "\n" : '') .
                "Cancelled: {$reason}",
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'fulfillment_date' => now(),
        ]);
    }
}
