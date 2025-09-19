<?php

// Session Configuration for Hosting Environment
// This file contains optimized session settings for shared hosting
// Copy relevant settings to your main session.php file

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver - HOSTING OPTIMIZED
    |--------------------------------------------------------------------------
    |
    | For shared hosting, database driver is more reliable than file driver
    | as it doesn't depend on file system permissions and storage location
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Increased lifetime for better user experience on hosting
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 180), // 3 hours instead of 2

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | Keep encryption disabled for hosting compatibility
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | For file driver, use storage/framework/sessions which is more secure
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | Use default database connection for sessions
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | Default sessions table name
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Store
    |--------------------------------------------------------------------------
    |
    | Redis store configuration (if using Redis)
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Reduced lottery chances for hosting performance
    |
    */

    'lottery' => [2, 100], // 2% chance instead of default

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Use app-specific cookie name to avoid conflicts
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | Root path for hosting compatibility
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Set to null for hosting compatibility, or specify your domain
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies - HOSTING IMPORTANT
    |--------------------------------------------------------------------------
    |
    | Enable secure cookies for HTTPS hosting
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', true), // Force HTTPS

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Prevent JavaScript access to session cookie for security
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Lax setting for hosting compatibility
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Disable for hosting compatibility
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];