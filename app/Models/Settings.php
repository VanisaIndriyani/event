<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    protected $casts = [
        'value' => 'json'
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        if ($setting->type === 'json') {
            return json_decode($setting->value, true);
        }

        return $setting->value;
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value, $type = 'text', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $type === 'json' ? json_encode($value) : $value,
                'type' => $type,
                'description' => $description
            ]
        );

        return $setting;
    }

    /**
     * Get QR code image URL
     */
    public static function getQrCodeUrl()
    {
        $qrPath = self::get('qr_code_path');
        
        if ($qrPath && Storage::disk('public')->exists($qrPath)) {
            return Storage::disk('public')->url($qrPath);
        }

        return null;
    }
}
