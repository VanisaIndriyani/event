<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'account_number',
        'account_name',
        'bank_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Scope untuk payment methods yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
