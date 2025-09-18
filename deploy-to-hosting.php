<?php
/**
 * Script Deployment Otomatis ke Hosting
 * 
 * Script ini membantu mengidentifikasi file yang perlu di-upload ke hosting
 * dan memberikan panduan deployment yang tepat.
 */

echo "\n=== DEPLOYMENT CHECKER UNTUK HOSTING ===\n\n";

// File yang perlu di-deploy
$filesToDeploy = [
    'app/Http/Middleware/AdminMiddleware.php' => 'Middleware yang sudah diperbaiki',
    'resources/views/admin/registrations/show.blade.php' => 'View dengan session keep-alive',
    'routes/web.php' => 'Routes dengan endpoint ping',
    '.env' => 'Konfigurasi environment (perlu update manual)'
];

echo "📁 FILE YANG PERLU DI-UPLOAD KE HOSTING:\n";
echo "=" . str_repeat("=", 50) . "\n";

foreach ($filesToDeploy as $file => $description) {
    $fullPath = __DIR__ . '/' . $file;
    $exists = file_exists($fullPath);
    $status = $exists ? '✅' : '❌';
    $size = $exists ? formatBytes(filesize($fullPath)) : 'N/A';
    $modified = $exists ? date('Y-m-d H:i:s', filemtime($fullPath)) : 'N/A';
    
    echo "$status $file\n";
    echo "   📝 $description\n";
    echo "   📊 Size: $size | Modified: $modified\n\n";
}

echo "\n🔧 LANGKAH DEPLOYMENT:\n";
echo "=" . str_repeat("=", 30) . "\n";

echo "1. 📋 BACKUP FILE LAMA DI HOSTING\n";
echo "   - Login ke cPanel File Manager\n";
echo "   - Backup file yang akan diubah\n\n";

echo "2. 📤 UPLOAD FILE BARU\n";
foreach ($filesToDeploy as $file => $description) {
    if ($file !== '.env') {
        echo "   - Upload: $file\n";
    }
}
echo "\n";

echo "3. ⚙️ UPDATE KONFIGURASI .env\n";
echo "   - SESSION_DOMAIN=.lark.today\n";
echo "   - SESSION_SECURE_COOKIE=true\n";
echo "   - SESSION_PATH=/event/public/\n";
echo "   - APP_URL=https://lark.today/event/public\n\n";

echo "4. 🧹 CLEAR CACHE DI HOSTING\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan view:clear\n";
echo "   php artisan config:cache\n\n";

echo "5. 🔍 TEST DEPLOYMENT\n";
echo "   - Test: https://lark.today/event/public/admin/ping\n";
echo "   - Login admin dan test payment update\n\n";

// Cek konfigurasi saat ini
echo "\n📋 KONFIGURASI SAAT INI:\n";
echo "=" . str_repeat("=", 25) . "\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    $configs = [
        'APP_URL' => 'URL aplikasi',
        'APP_ENV' => 'Environment',
        'SESSION_DRIVER' => 'Driver session',
        'SESSION_LIFETIME' => 'Lifetime session',
        'SESSION_DOMAIN' => 'Domain session',
        'SESSION_SECURE_COOKIE' => 'Secure cookie',
        'DB_HOST' => 'Database host',
        'DB_DATABASE' => 'Database name'
    ];
    
    foreach ($configs as $key => $desc) {
        if (preg_match("/^$key=(.*)$/m", $envContent, $matches)) {
            $value = trim($matches[1]);
            echo "✅ $key = $value\n";
        } else {
            echo "❌ $key = (tidak ditemukan)\n";
        }
    }
} else {
    echo "❌ File .env tidak ditemukan\n";
}

echo "\n\n🚀 READY FOR DEPLOYMENT!\n";
echo "Ikuti panduan di HOSTING_DEPLOYMENT_GUIDE.md untuk detail lengkap.\n\n";

// Helper function
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

// Generate deployment checklist
echo "\n📝 DEPLOYMENT CHECKLIST:\n";
echo "=" . str_repeat("=", 25) . "\n";

$checklist = [
    'Backup file lama di hosting',
    'Upload AdminMiddleware.php',
    'Upload show.blade.php', 
    'Upload web.php',
    'Update .env configuration',
    'Clear cache (config, route, view)',
    'Set permission storage folder',
    'Test /admin/ping endpoint',
    'Test login admin',
    'Test payment update function',
    'Monitor log untuk error'
];

foreach ($checklist as $index => $item) {
    echo "[ ] " . ($index + 1) . ". $item\n";
}

echo "\n💡 TIP: Simpan checklist ini dan centang setiap langkah yang sudah selesai!\n";
?>