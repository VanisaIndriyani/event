# Solusi Masalah Logout Otomatis

## Masalah yang Ditemukan

### 1. **Middleware AdminMiddleware Bermasalah**
- Logika session recovery yang kompleks menyebabkan konflik
- Mencoba melakukan manual session restoration yang tidak stabil
- Menyebabkan user logout secara tidak terduga

### 2. **Konfigurasi Session**
- SESSION_LIFETIME: 43200 menit (30 hari) - sudah benar
- SESSION_DRIVER: database - sudah benar
- Tabel sessions sudah ada di database

## Solusi yang Diterapkan

### 1. **Penyederhanaan AdminMiddleware**
```php
// Menghapus logika session recovery yang kompleks
// Menambahkan update last_activity untuk mencegah timeout
$request->session()->put('last_activity', time());
```

### 2. **Konfigurasi Session yang Direkomendasikan**
Untuk production environment, pastikan konfigurasi berikut di `.env`:

```env
# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=43200  # 30 hari
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true  # Untuk HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_EXPIRE_ON_CLOSE=false

# Untuk production dengan domain khusus
SESSION_DOMAIN=.lark.today  # Dengan titik untuk subdomain
```

### 3. **Langkah Deployment**

1. **Update konfigurasi di production:**
   ```bash
   # Edit file .env di server production
   nano /path/to/project/.env
   
   # Tambahkan/update konfigurasi session
   SESSION_SECURE_COOKIE=true
   SESSION_DOMAIN=.lark.today
   ```

2. **Clear cache dan restart:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan session:clear
   php artisan config:cache
   ```

3. **Restart web server:**
   ```bash
   # Untuk Apache
   sudo systemctl restart apache2
   
   # Untuk Nginx + PHP-FPM
   sudo systemctl restart nginx
   sudo systemctl restart php8.1-fpm
   ```

## Testing

### 1. **Test Login Stability**
- Login sebagai admin
- Navigasi ke berbagai halaman admin
- Tunggu beberapa menit tanpa aktivitas
- Coba akses halaman admin lagi
- Pastikan tidak logout otomatis

### 2. **Test Session Persistence**
- Login di satu tab
- Buka tab baru dengan halaman admin
- Pastikan tetap login di kedua tab

### 3. **Test AJAX Requests**
- Test update payment status
- Pastikan tidak ada error 401/405
- Pastikan response JSON benar

## Monitoring

### 1. **Log Session Issues**
Tambahkan logging untuk debug session:

```php
// Di AdminMiddleware
if (!auth()->check()) {
    \Log::warning('User not authenticated', [
        'session_id' => $request->session()->getId(),
        'url' => $request->fullUrl(),
        'user_agent' => $request->userAgent()
    ]);
}
```

### 2. **Monitor Database Sessions**
```sql
-- Cek session aktif
SELECT COUNT(*) as active_sessions FROM sessions 
WHERE last_activity > UNIX_TIMESTAMP(NOW() - INTERVAL 1 HOUR);

-- Cek session untuk user tertentu
SELECT * FROM sessions 
WHERE payload LIKE '%user_id%' 
ORDER BY last_activity DESC;
```

## File yang Dimodifikasi

1. **app/Http/Middleware/AdminMiddleware.php**
   - Disederhanakan logika authentication
   - Dihapus session recovery manual
   - Ditambahkan update last_activity

## Catatan Penting

1. **Untuk Production:**
   - Pastikan HTTPS aktif sebelum set SESSION_SECURE_COOKIE=true
   - Set SESSION_DOMAIN sesuai dengan domain production
   - Monitor log untuk error session

2. **Untuk Development:**
   - Gunakan SESSION_SECURE_COOKIE=false
   - SESSION_DOMAIN=null sudah benar

3. **Backup:**
   - Backup database sebelum clear session
   - Backup konfigurasi .env lama

## Troubleshooting

### Jika Masih Logout Otomatis:
1. Cek log Laravel: `tail -f storage/logs/laravel.log`
2. Cek session di database: `SELECT * FROM sessions LIMIT 10;`
3. Cek konfigurasi web server (Apache/Nginx)
4. Pastikan permission folder storage/framework/sessions

### Jika Error 500:
1. Clear semua cache
2. Regenerate app key jika perlu
3. Cek permission folder storage

Dengan solusi ini, masalah logout otomatis seharusnya teratasi dan session akan lebih stabil.