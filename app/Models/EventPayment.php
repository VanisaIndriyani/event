<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPayment extends Model
{
    protected $fillable = [
        'event_registration_id',
        'amount',
        'payment_method',
        'payment_proof',
        'payment_status',
        'admin_notes',
        'paid_at',
        'verified_at',
        'verified_by',
        'email_sent',
        'email_sent_at',
        'email_sent_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime'
    ];

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function emailSentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email_sent_by');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('payment_status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('payment_status', 'rejected');
    }

    public function scopeLunas($query)
    {
        return $query->where('payment_status', 'lunas');
    }

    public function scopeEmailSent($query)
    {
        return $query->where('email_sent', true);
    }

    public function scopeEmailNotSent($query)
    {
        return $query->where('email_sent', false);
    }
}
