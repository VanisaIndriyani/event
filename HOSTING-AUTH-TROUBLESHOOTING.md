# Panduan Troubleshooting Authentication di Hosting

## Masalah: Login dan Register Tidak Berfungsi di Hosting

Jika fitur login dan register tidak berfungsi setelah deploy ke hosting, ikuti langkah-langkah troubleshooting berikut:

## 🔍 Identifikasi Masalah

### Gejala Umum:
- Form login/register tidak merespon saat diklik
- Error 419 (CSRF Token Mismatch)
- Error 500 (Internal Server Error)
- Session tidak tersimpan
- Redirect loop setelah login
- Cookie tidak ter-set dengan benar

## 🛠️ Solusi Step-by-Step

### 1. Konfigurasi Environment (.env)

**Salin file `.env.hosting-fixed` ke `.env` dan sesuaikan:**

```bash
# Pastikan konfigurasi ini benar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com  # Sesuaikan dengan domain Anda
APP_KEY=base64:YOUR_GENERATED_KEY  # Generate dengan: php artisan key:generate

# Session Configuration - PENTING!
SESSION_DRIVER=database  # Lebih stabil untuk hosting
SESSION_DOMAIN=null      # Atau .yourdomain.com
SESSION_SECURE_COOKIE=true   # Untuk HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Database - Sesuaikan dengan hosting
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 2. Update Konfigurasi Session

**Salin pengaturan dari `config/session-hosting.php` ke `config/session.php`:**

```php
// Di config/session.php, update bagian ini:
'driver' => env('SESSION_DRIVER', 'database'),
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => env('SESSION_HTTP_ONLY', true),
'same_site' => env('SESSION_SAME_SITE', 'lax'),
```

### 3. Setup Database Session Table

**Jalankan migration untuk session table:**

```bash
php artisan session:table
php artisan migrate
```

**Atau buat manual di database:**

```sql
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    PRIMARY KEY (id),
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);
```

### 4. Update .htaccess

**Ganti file `.htaccess` di folder `public/` dengan `.htaccess-auth-fix`:**

```bash
cp .htaccess-auth-fix public/.htaccess
```

### 5. Clear Cache dan Optimize

**Jalankan perintah berikut di hosting:**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:flush

# Untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set File Permissions

**Pastikan permission folder berikut:**

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## 🔧 Troubleshooting Spesifik

### Error 419 - CSRF Token Mismatch

**Penyebab:**
- Session tidak tersimpan
- Domain/subdomain tidak match
- HTTPS/HTTP mixed content

**Solusi:**
1. Pastikan `SESSION_DOMAIN` di .env benar
2. Gunakan `SESSION_DRIVER=database`
3. Clear browser cache dan cookies
4. Pastikan semua request menggunakan HTTPS

### Session Tidak Tersimpan

**Penyebab:**
- Permission folder storage salah
- Session driver tidak kompatibel
- Cookie settings salah

**Solusi:**
1. Gunakan database session driver
2. Set permission storage/ ke 755
3. Pastikan `SESSION_SECURE_COOKIE=true` untuk HTTPS

### Form Login/Register Tidak Merespon

**Penyebab:**
- JavaScript error
- CSRF token tidak ter-load
- Network/CORS issue

**Solusi:**
1. Check browser console untuk error
2. Pastikan `@csrf` ada di form
3. Verify AJAX headers include CSRF token

### Redirect Loop Setelah Login

**Penyebab:**
- Middleware authentication error
- Route configuration salah
- Session tidak persistent

**Solusi:**
1. Check middleware di `routes/web.php`
2. Pastikan user role tersimpan dengan benar
3. Verify redirect logic di AuthController

## 🧪 Testing dan Verifikasi

### 1. Test Session

**Buat route test di `routes/web.php`:**

```php
Route::get('/test-session', function() {
    session(['test' => 'working']);
    return 'Session set: ' . session('test');
});

Route::get('/check-session', function() {
    return 'Session value: ' . session('test', 'not found');
});
```

### 2. Test CSRF

**Buat form test:**

```html
<form method="POST" action="/test-csrf">
    @csrf
    <button type="submit">Test CSRF</button>
</form>
```

```php
Route::post('/test-csrf', function() {
    return 'CSRF working!';
});
```

### 3. Test Database Connection

```php
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return 'Database connected!';
    } catch (Exception $e) {
        return 'Database error: ' . $e->getMessage();
    }
});
```

## 📋 Checklist Deployment

- [ ] File .env sudah dikonfigurasi dengan benar
- [ ] APP_KEY sudah di-generate
- [ ] Database connection berfungsi
- [ ] Session table sudah dibuat
- [ ] File permissions sudah benar (755 untuk storage/)
- [ ] .htaccess sudah di-update
- [ ] Cache sudah di-clear
- [ ] HTTPS sudah aktif
- [ ] Domain di .env sudah benar
- [ ] Session driver menggunakan database
- [ ] CSRF protection berfungsi

## 🆘 Jika Masih Bermasalah

### 1. Enable Debug Mode Sementara

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**⚠️ JANGAN LUPA DISABLE SETELAH SELESAI!**

### 2. Check Log Files

- `storage/logs/laravel.log`
- cPanel Error Logs
- Browser Developer Tools Console

### 3. Contact Hosting Support

Jika semua langkah sudah dilakukan tapi masih error, contact hosting support dengan informasi:

- PHP version yang digunakan
- Error message lengkap
- Konfigurasi .env (tanpa password)
- Log error dari hosting

## 📞 Bantuan Tambahan

Jika masih mengalami kesulitan, silakan hubungi developer dengan menyertakan:

1. Screenshot error yang muncul
2. File .env (hapus password/sensitive data)
3. Log error dari `storage/logs/laravel.log`
4. Informasi hosting provider dan PHP version

---

**Catatan:** Panduan ini dibuat khusus untuk mengatasi masalah authentication di shared hosting. Untuk VPS/dedicated server, beberapa langkah mungkin berbeda.