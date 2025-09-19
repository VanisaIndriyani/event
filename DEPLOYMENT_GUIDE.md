# Panduan Deployment Laravel ke Hosting cPanel

## Persiapan Sebelum Upload

### 1. Optimasi Aplikasi
```bash
# Jalankan perintah berikut di local development
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

### 2. File yang Perlu Disiapkan
- Semua file aplikasi Laravel
- File `.env` yang sudah dikonfigurasi untuk production
- Database backup (jika ada)

## Langkah-langkah Deployment

### 1. Upload File ke Hosting
1. **Compress** seluruh folder Laravel menjadi ZIP
2. **Upload** file ZIP ke cPanel File Manager
3. **Extract** di folder `public_html` atau subdomain folder
4. **Pindahkan** isi folder `public` Laravel ke root `public_html`
5. **Edit** file `index.php` di `public_html`:
   ```php
   // Ubah path ini sesuai struktur folder Anda
   require __DIR__.'/bootstrap/app.php';
   // atau
   require __DIR__.'/../bootstrap/app.php';
   ```

### 2. Konfigurasi Database
1. **Buat database** baru di cPanel MySQL Databases
2. **Buat user** database dan berikan privileges
3. **Update file `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username_database
   DB_PASSWORD=password_database
   ```

### 3. Konfigurasi Environment
```env
# File .env untuk production
APP_NAME="Event Manager"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Event Manager"
```

### 4. Jalankan Migration
```bash
# Via SSH atau Terminal di cPanel
php artisan migrate --force
php artisan db:seed --force
```

### 5. Set Permissions
```bash
# Set permission untuk storage dan bootstrap/cache
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Troubleshooting Masalah Umum

### 1. Error 405 Method Not Allowed
**Penyebab:** Server hosting tidak mendukung HTTP methods tertentu (PUT, PATCH, DELETE)

**Solusi:**
- Gunakan method spoofing dengan POST request
- Pastikan form menggunakan `@method('PUT')` directive
- JavaScript sudah diupdate untuk menggunakan FormData dengan `_method`

### 2. Error 500 Internal Server Error
**Penyebab:** 
- File permission salah
- Missing APP_KEY
- Database connection error

**Solusi:**
```bash
# Generate APP_KEY
php artisan key:generate

# Set permission
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. CSRF Token Mismatch
**Penyebab:** Session configuration atau domain mismatch

**Solusi:**
```env
# Update .env
SESSION_DOMAIN=.yourdomain.com
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
```

### 4. File Upload Issues
**Penyebab:** PHP upload limits

**Solusi:**
1. Buat file `.htaccess` di root:
   ```apache
   php_value upload_max_filesize 64M
   php_value post_max_size 64M
   php_value max_execution_time 300
   php_value max_input_time 300
   ```

2. Atau update via cPanel PHP Selector

### 5. Email Not Working
**Penyebab:** SMTP configuration atau hosting restrictions

**Solusi:**
1. Gunakan hosting email SMTP
2. Atau gunakan service seperti Mailgun, SendGrid
3. Test dengan:
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

## Optimasi Performance

### 1. Enable OPcache
```ini
; Tambahkan di php.ini atau .htaccess
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
```

### 2. Compress Assets
```bash
# Sebelum upload
npm run production
```

### 3. Database Optimization
```sql
-- Tambahkan index untuk query yang sering digunakan
ALTER TABLE event_registrations ADD INDEX idx_user_id (user_id);
ALTER TABLE event_registrations ADD INDEX idx_event_id (event_id);
ALTER TABLE event_payments ADD INDEX idx_registration_id (event_registration_id);
```

## Monitoring dan Maintenance

### 1. Log Monitoring
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Check server error logs
tail -f /path/to/error_log
```

### 2. Backup Strategy
1. **Database backup** mingguan
2. **File backup** bulanan
3. **Automated backup** via cPanel atau script

### 3. Security Checklist
- [ ] APP_DEBUG=false di production
- [ ] Strong APP_KEY
- [ ] Database credentials aman
- [ ] File permissions correct (755 untuk folder, 644 untuk file)
- [ ] .env file tidak accessible dari web
- [ ] SSL certificate installed
- [ ] Regular updates

## Kontak Support
Jika mengalami masalah yang tidak bisa diselesaikan:
1. Check error logs terlebih dahulu
2. Hubungi hosting provider untuk server-specific issues
3. Dokumentasikan error message dan langkah reproduksi

---
*Panduan ini dibuat untuk deployment Event Manager Laravel Application*