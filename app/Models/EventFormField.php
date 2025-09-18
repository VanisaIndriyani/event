<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventFormField extends Model
{
    protected $fillable = [
        'event_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function registrationData(): HasMany
    {
        return $this->hasMany(EventRegistrationData::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get field types yang tersedia
     */
    public static function getFieldTypes()
    {
        return [
            'text' => 'Text Input',
            'email' => 'Email Input',
            'number' => 'Number Input',
            'textarea' => 'Textarea',
            'select' => 'Select Dropdown',
            'file' => 'File Upload',
            'date' => 'Date Input'
        ];
    }
}
