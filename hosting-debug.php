<?php
/**
 * HOSTING DEBUG SCRIPT
 * Upload file ini ke root folder hosting untuk debug masalah 405
 * Akses via: https://lark.today/event/public/hosting-debug.php
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>";
echo "<html><head><title>Hosting Debug - lark.today</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { color: #28a745; font-weight: bold; }";
echo ".error { color: #dc3545; font-weight: bold; }";
echo ".warning { color: #ffc107; font-weight: bold; }";
echo ".info { color: #17a2b8; font-weight: bold; }";
echo "pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }";
echo "h2 { border-bottom: 2px solid #007bff; padding-bottom: 5px; }";
echo "</style>";
echo "</head><body>";
echo "<div class='container'>";

echo "<h1>🔍 Hosting Debug Report - lark.today</h1>";
echo "<p><strong>Generated:</strong> " . date('Y-m-d H:i:s') . " (Server Time)</p>";
echo "<hr>";

// 1. PHP Information
echo "<h2>📋 PHP Configuration</h2>";
echo "<p><span class='info'>PHP Version:</span> " . phpversion() . "</p>";
echo "<p><span class='info'>Server Software:</span> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><span class='info'>Document Root:</span> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><span class='info'>Current Directory:</span> " . getcwd() . "</p>";

// 2. Laravel Detection
echo "<h2>🚀 Laravel Application Status</h2>";

$laravelPaths = [
    'artisan' => 'artisan',
    'composer.json' => 'composer.json',
    'app directory' => 'app/',
    'routes directory' => 'routes/',
    'AdminMiddleware' => 'app/Http/Middleware/AdminMiddleware.php',
    'web.php routes' => 'routes/web.php',
    'show.blade.php' => 'resources/views/admin/registrations/show.blade.php',
    '.env file' => '.env'
];

foreach ($laravelPaths as $name => $path) {
    if (file_exists($path)) {
        $lastModified = date('Y-m-d H:i:s', filemtime($path));
        echo "<p><span class='success'>✅ {$name}:</span> Found (Modified: {$lastModified})</p>";
    } else {
        echo "<p><span class='error'>❌ {$name}:</span> Not found at {$path}</p>";
    }
}

// 3. File Permissions
echo "<h2>🔐 File Permissions</h2>";

$checkPermissions = [
    'storage/' => 'storage/',
    'bootstrap/cache/' => 'bootstrap/cache/',
    '.env' => '.env',
    'routes/web.php' => 'routes/web.php'
];

foreach ($checkPermissions as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $readable = is_readable($path) ? 'Yes' : 'No';
        $writable = is_writable($path) ? 'Yes' : 'No';
        echo "<p><span class='info'>{$name}:</span> {$perms} (R: {$readable}, W: {$writable})</p>";
    }
}

// 4. Environment Variables
echo "<h2>🌍 Environment Check</h2>";

if (file_exists('.env')) {
    echo "<p><span class='success'>✅ .env file found</span></p>";
    
    $envContent = file_get_contents('.env');
    $envLines = explode("\n", $envContent);
    
    $importantVars = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'SESSION_DRIVER'];
    
    foreach ($importantVars as $var) {
        $found = false;
        foreach ($envLines as $line) {
            if (strpos($line, $var . '=') === 0) {
                $value = substr($line, strlen($var) + 1);
                echo "<p><span class='info'>{$var}:</span> {$value}</p>";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "<p><span class='warning'>⚠️ {$var}:</span> Not set</p>";
        }
    }
} else {
    echo "<p><span class='error'>❌ .env file not found</span></p>";
}

// 5. Database Connection Test
echo "<h2>🗄️ Database Connection</h2>";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    preg_match('/DB_HOST=(.*)/', $envContent, $host);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $database);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $username);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $password);
    
    if (isset($host[1]) && isset($database[1]) && isset($username[1])) {
        try {
            $pdo = new PDO(
                "mysql:host={$host[1]};dbname={$database[1]}",
                $username[1],
                isset($password[1]) ? $password[1] : ''
            );
            echo "<p><span class='success'>✅ Database connection successful</span></p>";
            echo "<p><span class='info'>Host:</span> {$host[1]}</p>";
            echo "<p><span class='info'>Database:</span> {$database[1]}</p>";
        } catch (PDOException $e) {
            echo "<p><span class='error'>❌ Database connection failed:</span> " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p><span class='warning'>⚠️ Database credentials not found in .env</span></p>";
    }
} else {
    echo "<p><span class='error'>❌ Cannot test database - .env file missing</span></p>";
}

// 6. Apache Modules
echo "<h2>🔧 Apache Modules</h2>";

if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $requiredModules = ['mod_rewrite', 'mod_headers', 'mod_ssl'];
    
    foreach ($requiredModules as $module) {
        if (in_array($module, $modules)) {
            echo "<p><span class='success'>✅ {$module}:</span> Enabled</p>";
        } else {
            echo "<p><span class='error'>❌ {$module}:</span> Not found</p>";
        }
    }
} else {
    echo "<p><span class='warning'>⚠️ Cannot check Apache modules (function not available)</span></p>";
}

// 7. HTTP Methods Test
echo "<h2>🌐 HTTP Methods Test</h2>";

echo "<p><span class='info'>Current Request Method:</span> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><span class='info'>Request URI:</span> " . $_SERVER['REQUEST_URI'] . "</p>";

if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    echo "<p><span class='info'>Method Override:</span> " . $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] . "</p>";
}

// 8. Laravel Routes Test
echo "<h2>🛣️ Laravel Routes Test</h2>";

if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $testRoutes = [
        'admin.payments.status' => "Route::put('/payments/{payment}/status'",
        'admin.session.ping' => "Route::get('/ping'",
        'admin middleware' => "middleware(['auth', 'admin'])"
    ];
    
    foreach ($testRoutes as $name => $pattern) {
        if (strpos($routesContent, $pattern) !== false) {
            echo "<p><span class='success'>✅ {$name}:</span> Found in routes</p>";
        } else {
            echo "<p><span class='error'>❌ {$name}:</span> Not found in routes</p>";
        }
    }
} else {
    echo "<p><span class='error'>❌ routes/web.php not found</span></p>";
}

// 9. Cache Status
echo "<h2>💾 Cache Status</h2>";

$cacheFiles = [
    'Config Cache' => 'bootstrap/cache/config.php',
    'Routes Cache' => 'bootstrap/cache/routes.php',
    'Services Cache' => 'bootstrap/cache/services.php'
];

foreach ($cacheFiles as $name => $path) {
    if (file_exists($path)) {
        $lastModified = date('Y-m-d H:i:s', filemtime($path));
        echo "<p><span class='warning'>⚠️ {$name}:</span> Cached (Modified: {$lastModified})</p>";
    } else {
        echo "<p><span class='success'>✅ {$name}:</span> Not cached</p>";
    }
}

// 10. Quick Fix Suggestions
echo "<h2>💡 Quick Fix Suggestions</h2>";

echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff;'>";
echo "<h3>🚀 Immediate Actions:</h3>";
echo "<ol>";
echo "<li><strong>Clear Cache:</strong> Delete files in bootstrap/cache/ folder</li>";
echo "<li><strong>Check File Upload:</strong> Verify AdminMiddleware.php timestamp is recent</li>";
echo "<li><strong>Test Route:</strong> Access https://lark.today/event/public/admin/ping</li>";
echo "<li><strong>Check Permissions:</strong> Ensure storage/ and bootstrap/cache/ are writable</li>";
echo "<li><strong>Verify .htaccess:</strong> Make sure mod_rewrite rules are correct</li>";
echo "</ol>";
echo "</div>";

// 11. Test Links
echo "<h2>🔗 Test Links</h2>";

echo "<p><a href='/admin/ping' target='_blank'>Test Admin Ping Route</a></p>";
echo "<p><a href='/login' target='_blank'>Test Login Page</a></p>";
echo "<p><a href='/admin/dashboard' target='_blank'>Test Admin Dashboard</a></p>";

echo "<hr>";
echo "<p><small>Debug script completed at " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small>⚠️ <strong>SECURITY WARNING:</strong> Delete this file after debugging!</small></p>";

echo "</div>";
echo "</body></html>";
?>