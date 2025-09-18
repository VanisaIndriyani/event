# 🚨 Solusi Error 405 Method Not Allowed di Hosting

## Status Masalah
❌ **Error masih terjadi di hosting meskipun file sudah di-upload**  
✅ **Localhost berfungsi normal**  

---

## 🔍 DIAGNOSIS MASALAH

### Kemungkinan Penyebab:
1. **File belum ter-upload dengan benar**
2. **Cache hosting belum di-clear**
3. **Konfigurasi .htaccess bermasalah**
4. **PHP version compatibility**
5. **Hosting provider restrictions**
6. **Database connection issue**

---

## 🛠️ SOLUSI ALTERNATIF

### **Solusi 1: Verifikasi Upload File**

#### Cek apakah file benar-benar ter-upload:
1. **Login ke cPanel File Manager**
2. **Navigate ke folder aplikasi**
3. **Cek timestamp file** - pastikan baru saja di-update:
   - `app/Http/Middleware/AdminMiddleware.php`
   - `resources/views/admin/registrations/show.blade.php`
   - `routes/web.php`

#### Cara memastikan:
```bash
# Di cPanel Terminal (jika tersedia)
ls -la app/Http/Middleware/AdminMiddleware.php
ls -la resources/views/admin/registrations/show.blade.php
ls -la routes/web.php
```

### **Solusi 2: Clear All Cache**

#### Via cPanel Terminal:
```bash
cd /path/to/your/laravel/app
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

#### Via File Manager (jika tidak ada terminal):
1. **Hapus folder cache**:
   - `bootstrap/cache/config.php`
   - `bootstrap/cache/routes.php`
   - `storage/framework/cache/data/*`
   - `storage/framework/views/*`

### **Solusi 3: Update .htaccess**

#### Tambahkan rules khusus untuk method PUT:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Allow PUT, PATCH, DELETE methods
    RewriteCond %{REQUEST_METHOD} ^(PUT|PATCH|DELETE)$
    RewriteRule ^(.*)$ index.php [L,QSA]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### **Solusi 4: Cek PHP Version**

#### Di cPanel:
1. **Cari "PHP Selector" atau "PHP Version"**
2. **Pastikan menggunakan PHP 8.1 atau 8.2**
3. **Enable extensions yang diperlukan**:
   - `mbstring`
   - `openssl`
   - `pdo`
   - `tokenizer`
   - `xml`

### **Solusi 5: Database Connection Test**

#### Buat file test sederhana:
**File**: `test-db.php` (upload ke root folder)
```php
<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=your_database_name',
        'your_username',
        'your_password'
    );
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
```

### **Solusi 6: Hosting Provider Specific**

#### Untuk cPanel/Shared Hosting:
1. **Pastikan folder permissions**:
   - Folders: 755
   - Files: 644
   - storage/ dan bootstrap/cache/: 775

2. **Cek mod_rewrite enabled**:
   - Tanyakan ke hosting provider
   - Atau cek via phpinfo()

#### Untuk VPS/Dedicated:
```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Set proper permissions
sudo chown -R www-data:www-data /path/to/laravel
sudo chmod -R 755 /path/to/laravel
sudo chmod -R 775 /path/to/laravel/storage
sudo chmod -R 775 /path/to/laravel/bootstrap/cache
```

---

## 🧪 TESTING STEP BY STEP

### **Test 1: Basic Route Test**
1. **Akses**: `https://lark.today/event/public/admin/ping`
2. **Expected**: `{"status":"ok","time":"..."}`
3. **Jika gagal**: Route tidak ter-load

### **Test 2: Authentication Test**
1. **Login**: `https://lark.today/event/public/login`
2. **Navigate**: ke halaman admin
3. **Jika redirect loop**: Session issue

### **Test 3: AJAX Test**
1. **Buka browser console** (F12)
2. **Coba update payment**
3. **Lihat error message** di console

---

## 🚨 EMERGENCY WORKAROUND

### **Jika semua solusi gagal, gunakan GET method sementara:**

#### Update JavaScript (temporary fix):
```javascript
// Ganti method PUT dengan GET + parameter
fetch(`/admin/payments/${paymentId}/status?status=${newStatus}&_method=PUT`, {
    method: 'GET',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
```

#### Update Route (temporary):
```php
// Tambahkan route GET sementara
Route::get('/payments/{payment}/status', [PaymentController::class, 'updateStatusGet'])
    ->name('payments.status.get');
```

---

## 📞 CONTACT HOSTING SUPPORT

### **Jika masih error, hubungi hosting provider dengan info:**

```
Subject: Laravel Application - HTTP 405 Method Not Allowed Error

Hi Support Team,

I'm experiencing HTTP 405 Method Not Allowed errors on my Laravel application.

Domain: lark.today
Error: PUT requests to /admin/payments/{id}/status returning 405
PHP Version: [check your version]
Framework: Laravel 11

Can you please check:
1. Is mod_rewrite enabled?
2. Are PUT/PATCH/DELETE methods allowed?
3. Any server-level restrictions on HTTP methods?
4. .htaccess file processing correctly?

Thank you!
```

---

## 📋 CHECKLIST TROUBLESHOOTING

- [ ] File AdminMiddleware.php ter-upload dengan timestamp terbaru
- [ ] File show.blade.php ter-upload dengan timestamp terbaru  
- [ ] File web.php ter-upload dengan timestamp terbaru
- [ ] Cache Laravel sudah di-clear
- [ ] .htaccess sudah di-update
- [ ] PHP version 8.1+
- [ ] Database connection working
- [ ] File permissions correct (755/644)
- [ ] mod_rewrite enabled
- [ ] CSRF token working
- [ ] Session configuration correct
- [ ] Hosting provider contacted (if needed)

---

## 🎯 EXPECTED RESULTS

Setelah menerapkan solusi:
✅ **Payment update berhasil**  
✅ **No more 405 errors**  
✅ **Admin authentication working**  
✅ **Session keep-alive active**  

---

**💡 TIP**: Mulai dari Solusi 1 dan 2 dulu (verifikasi upload + clear cache), karena 80% masalah biasanya di situ!