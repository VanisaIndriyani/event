<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = [
        'event_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'field_placeholder',
        'field_description',
        'is_required',
        'field_order',
        'is_active'
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'field_order' => 'integer'
    ];

    /**
     * Get the event that owns the form field.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Scope untuk field yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan field_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('field_order');
    }

    /**
     * Get field types yang tersedia
     */
    public static function getFieldTypes()
    {
        return [
            'text' => 'Text Input',
            'number' => 'Number Input',
            'file' => 'File Upload (Image)'
        ];
    }
}
