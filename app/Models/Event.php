<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'image',
        'price',
        'max_participants',
        'status',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function formFields()
    {
        return $this->hasMany(FormField::class);
    }

    public function activeFormFields()
    {
        return $this->formFields()->active()->ordered();
    }

    public function getRegisteredCountAttribute()
    {
        return $this->registrations()->count();
    }

    public function getAvailableSlotsAttribute()
    {
        if (!$this->max_participants) {
            return null;
        }
        return $this->max_participants - $this->registered_count;
    }
}
