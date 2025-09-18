# Panduan Auto Push/Pull Deployment

## Metode 1: Git Hooks (Recommended)

### **Setup di Hosting (cPanel)**

#### **1. Buat Git Repository di Hosting**
```bash
# Login ke cPanel Terminal atau SSH
cd /home/username/public_html/event/
git init
git remote add origin https://github.com/username/event-manager.git
```

#### **2. Buat Post-Receive Hook**
```bash
# Buat file hook
cd .git/hooks/
nano post-receive
```

**Isi file post-receive:**
```bash
#!/bin/bash
echo "Starting auto deployment..."

# Navigate to project directory
cd /home/username/public_html/event/

# Pull latest changes
git --git-dir=/home/username/public_html/event/.git --work-tree=/home/username/public_html/event/ checkout -f

# Install/Update dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Set permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "Deployment completed successfully!"
```

```bash
# Make executable
chmod +x post-receive
```

### **3. Setup di Local Development**

#### **Push ke Repository:**
```bash
# Add remote jika belum ada
git remote add production username@yourhost.com:/home/username/public_html/event/.git

# Push untuk auto deploy
git push production main
```

---

## Metode 2: Webhook Deployment

### **1. Buat Script Deployment di Hosting**

**File: `/public_html/event/deploy.php`**
```php
<?php
// Webhook deployment script
$secret = 'your_secret_key_here';
$payload = file_get_contents('php://input');
$signature = hash_hmac('sha256', $payload, $secret);

// Verify webhook signature
if (!hash_equals('sha256=' . $signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
    http_response_code(403);
    die('Unauthorized');
}

// Log deployment
file_put_contents('deployment.log', date('Y-m-d H:i:s') . " - Deployment started\n", FILE_APPEND);

// Execute deployment
exec('cd /home/username/public_html/event && ./deploy.sh 2>&1', $output, $return_code);

// Log result
if ($return_code === 0) {
    file_put_contents('deployment.log', date('Y-m-d H:i:s') . " - Deployment successful\n", FILE_APPEND);
    echo "Deployment successful";
} else {
    file_put_contents('deployment.log', date('Y-m-d H:i:s') . " - Deployment failed: " . implode("\n", $output) . "\n", FILE_APPEND);
    http_response_code(500);
    echo "Deployment failed";
}
?>
```

### **2. Buat Script Deployment**

**File: `/public_html/event/deploy.sh`**
```bash
#!/bin/bash
set -e

echo "Starting deployment..."

# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Laravel optimizations
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Set permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "Deployment completed!"
```

```bash
# Make executable
chmod +x deploy.sh
```

### **3. Setup GitHub Webhook**
1. Go to GitHub Repository → Settings → Webhooks
2. Add webhook:
   - **Payload URL**: `https://lark.today/event/public/deploy.php`
   - **Content type**: `application/json`
   - **Secret**: `your_secret_key_here`
   - **Events**: Just the push event

---

## Metode 3: Automated Script (Simple)

### **1. Buat Script Auto Deploy**

**File: `auto-deploy.php`**
```php
<?php
/**
 * Simple Auto Deployment Script
 * Jalankan script ini setelah push ke repository
 */

echo "\n=== AUTO DEPLOYMENT SCRIPT ===\n\n";

// Configuration
$remoteHost = 'your-hosting-server.com';
$remoteUser = 'your-username';
$remotePath = '/home/username/public_html/event/';
$localPath = __DIR__ . '/';

// Files to upload
$filesToUpload = [
    'app/Http/Middleware/AdminMiddleware.php',
    'resources/views/admin/registrations/show.blade.php',
    'routes/web.php',
    '.env' // Will be uploaded as .env.new
];

echo "📤 UPLOADING FILES TO HOSTING...\n";
echo "=" . str_repeat("=", 40) . "\n";

foreach ($filesToUpload as $file) {
    $localFile = $localPath . $file;
    $remoteFile = $remotePath . $file;
    
    if (file_exists($localFile)) {
        echo "✅ Uploading: $file\n";
        
        // Using SCP (requires SSH key setup)
        $command = "scp \"$localFile\" $remoteUser@$remoteHost:\"$remoteFile\"";
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ Success\n";
        } else {
            echo "   ❌ Failed\n";
        }
    } else {
        echo "❌ File not found: $file\n";
    }
}

echo "\n🧹 CLEARING CACHE ON HOSTING...\n";
echo "=" . str_repeat("=", 35) . "\n";

$cacheCommands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan config:cache'
];

foreach ($cacheCommands as $cmd) {
    echo "🔄 Running: $cmd\n";
    $sshCommand = "ssh $remoteUser@$remoteHost 'cd $remotePath && $cmd'";
    exec($sshCommand, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Success\n";
    } else {
        echo "   ❌ Failed\n";
    }
}

echo "\n🎉 AUTO DEPLOYMENT COMPLETED!\n";
echo "\n🔍 NEXT STEPS:\n";
echo "1. Test: https://lark.today/event/public/admin/ping\n";
echo "2. Login admin and test payment update\n";
echo "3. Monitor logs for any errors\n\n";
?>
```

### **2. Setup SSH Key (One-time)**
```bash
# Generate SSH key
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"

# Copy public key to hosting
ssh-copy-id username@your-hosting-server.com
```

### **3. Usage**
```bash
# After making changes and committing
git add .
git commit -m "Update payment system"
git push origin main

# Run auto deployment
php auto-deploy.php
```

---

## Metode 4: GitHub Actions (Advanced)

### **1. Buat Workflow File**

**File: `.github/workflows/deploy.yml`**
```yaml
name: Deploy to Hosting

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /home/username/public_html/event/
          git pull origin main
          composer install --no-dev --optimize-autoloader
          php artisan config:clear
          php artisan cache:clear
          php artisan route:clear
          php artisan view:clear
          php artisan config:cache
          php artisan route:cache
          chmod -R 775 storage/
          chmod -R 775 bootstrap/cache/
```

### **2. Setup GitHub Secrets**
1. Go to Repository → Settings → Secrets
2. Add secrets:
   - `HOST`: your-hosting-server.com
   - `USERNAME`: your-username
   - `SSH_KEY`: your-private-ssh-key

---

## Rekomendasi

### **Untuk Pemula: Metode 3 (Automated Script)**
- ✅ Simple dan mudah dipahami
- ✅ Tidak perlu setup webhook
- ✅ Bisa dijalankan manual

### **Untuk Advanced: Metode 1 (Git Hooks)**
- ✅ Otomatis setiap push
- ✅ Native Git integration
- ✅ Reliable dan cepat

### **Untuk Team: Metode 4 (GitHub Actions)**
- ✅ CI/CD pipeline
- ✅ Testing sebelum deploy
- ✅ Professional workflow

---

## Troubleshooting

### **Permission Issues**
```bash
# Fix permission after deployment
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
```

### **Cache Issues**
```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### **Database Migration**
```bash
# Run migration after deployment
php artisan migrate --force
```

Pilih metode yang sesuai dengan kebutuhan dan tingkat keahlian Anda! 🚀