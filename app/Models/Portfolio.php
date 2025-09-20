<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'images',
        'status'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    /**
     * Get the event that owns the portfolio.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the first image from the images array.
     */
    public function getFirstImageAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        return $images && is_array($images) && count($images) > 0 ? $images[0] : null;
    }

    /**
     * Get the count of images.
     */
    public function getImageCountAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        return $images && is_array($images) ? count($images) : 0;
    }

    /**
     * Scope to get published portfolios.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get draft portfolios.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
