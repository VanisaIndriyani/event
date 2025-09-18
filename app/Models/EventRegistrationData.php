<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistrationData extends Model
{
    protected $fillable = [
        'event_registration_id',
        'event_form_field_id',
        'field_value',
        'file_path'
    ];

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function eventFormField(): BelongsTo
    {
        return $this->belongsTo(EventFormField::class);
    }
    
    public function formField(): BelongsTo
    {
        return $this->eventFormField();
    }

    public function getDisplayValueAttribute()
    {
        if ($this->eventFormField->field_type === 'file' && $this->file_path) {
            return basename($this->file_path);
        }
        return $this->field_value;
    }
}
