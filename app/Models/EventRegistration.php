<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'notes',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payment()
    {
        return $this->hasOne(EventPayment::class);
    }

    public function registrationData()
    {
        return $this->hasMany(EventRegistrationData::class);
    }

    public function getFormDataAttribute()
    {
        $data = [];
        foreach ($this->registrationData as $regData) {
            $data[$regData->eventFormField->field_name] = $regData->field_value;
        }
        return $data;
    }
}
