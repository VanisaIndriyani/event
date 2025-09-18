<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization',
        'email',
        'phone',
        'collaboration_type',
        'budget',
        'timeline',
        'services',
        'event_description',
        'additional_info',
        'status'
    ];

    protected $casts = [
        'services' => 'array'
    ];

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'reviewing' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'completed' => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getFormattedServicesAttribute()
    {
        if (!$this->services) {
            return 'Tidak ada layanan dipilih';
        }

        $serviceLabels = [
            'planning' => 'Event Planning',
            'marketing' => 'Marketing & Promotion',
            'venue' => 'Venue Management',
            'catering' => 'Catering Services',
            'entertainment' => 'Entertainment',
            'photography' => 'Photography/Videography'
        ];

        $services = is_string($this->services) ? json_decode($this->services, true) : $this->services;
        $services = $services ?? [];
        
        $formatted = [];
        foreach ($services as $service) {
            $formatted[] = $serviceLabels[$service] ?? $service;
        }

        return implode(', ', $formatted);
    }
}