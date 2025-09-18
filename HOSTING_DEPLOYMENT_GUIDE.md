# Panduan Deployment ke Hosting cPanel

## Masalah yang Ditemukan

### **Localhost vs Hosting**
- ✅ **Localhost (127.0.0.1:8000)**: Payment update berhasil
- ❌ **Hosting (lark.today)**: Masih error 405 Method Not Allowed

**Root Cause**: Perubahan kode belum di-deploy ke hosting environment

## File yang Perlu Di-Upload ke Hosting

### **1. File Middleware yang Sudah Diperbaiki**
```
app/Http/Middleware/AdminMiddleware.php
```

### **2. File View dengan Session Keep-Alive**
```
resources/views/admin/registrations/show.blade.php
```

### **3. File Routes dengan Endpoint Ping**
```
routes/web.php
```

### **4. File Konfigurasi (jika diperlukan)**
```
config/session.php
.env (update konfigurasi session)
```

## Langkah Deployment ke cPanel

### **Step 1: Backup File Lama**
1. Login ke cPanel hosting
2. Buka File Manager
3. Navigate ke folder aplikasi (biasanya `public_html/event/`)
4. Backup file-file yang akan diubah:
   - `app/Http/Middleware/AdminMiddleware.php`
   - `resources/views/admin/registrations/show.blade.php`
   - `routes/web.php`

### **Step 2: Upload File Baru**
1. **Upload AdminMiddleware.php**:
   - Path: `app/Http/Middleware/AdminMiddleware.php`
   - Pastikan permission 644

2. **Upload show.blade.php**:
   - Path: `resources/views/admin/registrations/show.blade.php`
   - Pastikan permission 644

3. **Upload web.php**:
   - Path: `routes/web.php`
   - Pastikan permission 644

### **Step 3: Update Konfigurasi .env**
1. Edit file `.env` di hosting
2. Pastikan konfigurasi session benar:

```env
# Session Configuration untuk Hosting
SESSION_DRIVER=database
SESSION_LIFETIME=43200
SESSION_ENCRYPT=false
SESSION_PATH=/event/public/
SESSION_DOMAIN=.lark.today
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_EXPIRE_ON_CLOSE=false

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# App Configuration
APP_URL=https://lark.today/event/public
APP_ENV=production
APP_DEBUG=false
```

### **Step 4: Clear Cache di Hosting**
1. Akses terminal/SSH atau gunakan cPanel Terminal
2. Navigate ke folder aplikasi
3. Jalankan perintah:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Step 5: Set Permission yang Benar**
```bash
# Set permission untuk folder storage
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Set permission untuk file
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

## Verifikasi Deployment

### **1. Test Route Ping**
Buka browser dan akses:
```
https://lark.today/event/public/admin/ping
```
Harus return JSON: `{"status":"ok","time":"..."}`

### **2. Test Login Admin**
1. Login sebagai admin di: `https://lark.today/event/public/login`
2. Navigate ke: `https://lark.today/event/public/admin/registrations/1`
3. Coba update payment status
4. Harus berhasil tanpa error 405

### **3. Test Session Keep-Alive**
1. Buka Developer Tools (F12)
2. Go to Console tab
3. Setelah 5 menit, harus muncul log: "Session keep-alive successful"

## Troubleshooting

### **Jika Masih Error 405:**
1. **Cek .htaccess**:
   ```apache
   # Pastikan ada di public/.htaccess
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule ^ index.php [L]
   ```

2. **Cek Route Cache**:
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

3. **Cek Database Connection**:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

### **Jika Session Masih Logout:**
1. **Cek Tabel Sessions**:
   ```sql
   SELECT * FROM sessions LIMIT 5;
   ```

2. **Cek Permission Storage**:
   ```bash
   ls -la storage/framework/sessions/
   ```

3. **Cek Log Error**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Checklist Deployment

- [ ] Backup file lama
- [ ] Upload AdminMiddleware.php baru
- [ ] Upload show.blade.php baru
- [ ] Upload web.php baru
- [ ] Update .env configuration
- [ ] Clear semua cache
- [ ] Set permission yang benar
- [ ] Test route /admin/ping
- [ ] Test login admin
- [ ] Test update payment status
- [ ] Test session keep-alive
- [ ] Monitor log untuk error

## Kontak Support

Jika masih ada masalah setelah deployment:
1. Cek log error di `storage/logs/laravel.log`
2. Screenshot error message
3. Berikan informasi hosting environment

---

**Catatan Penting**: 
- Pastikan backup sebelum deployment
- Test di staging environment dulu jika ada
- Monitor aplikasi setelah deployment
- Siapkan rollback plan jika ada masalah