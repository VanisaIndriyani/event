# 🔧 Panduan Debugging Error "Unexpected token '<'" di Hosting

## 📋 Ringkasan Masalah
Error "Unexpected token '<'" terjadi ketika JavaScript mencoba mem-parsing HTML sebagai JSON. Ini biasanya terjadi ketika:
1. Server mengembalikan halaman error HTML (404, 500, dll) alih-alih JSON
2. Redirect ke halaman login karena masalah autentikasi
3. CSRF token tidak valid atau expired
4. Route tidak ditemukan di hosting environment

## ✅ Perbaikan yang Sudah Dilakukan

### 1. JavaScript Fetch Calls
Semua fetch calls di file berikut sudah diperbaiki dengan pengecekan response:
- `resources/views/admin/registrations/show.blade.php` (lines 1185-1296)
- `resources/views/admin/registrations/index.blade.php` (lines 400-520)
- `resources/views/admin/form-fields/index.blade.php` (line 1110)
- `resources/views/admin/events/form-fields/index.blade.php` (line 130)

**Perbaikan yang diterapkan:**
```javascript
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
        return response.json();
    } else {
        throw new Error('Response is not JSON');
    }
})
```

### 2. Controller Responses
Controller sudah mengembalikan JSON response yang benar:
- `AdminController::updateRegistrationStatus()` - ✅ Sudah benar
- `AdminController::deleteRegistration()` - ✅ Sudah benar
- `AdminController::sendEmailNotification()` - ✅ Sudah benar
- `PaymentController::updateStatus()` - ✅ Sudah benar

## 🔍 Langkah Debugging di Hosting

### 1. Cek Route Registration
```bash
# Di hosting, jalankan command ini untuk melihat semua route
php artisan route:list | grep -E "(payments|registrations)"
```

### 2. Cek CSRF Token
Pastikan meta tag CSRF ada di layout:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### 3. Cek Permission File
```bash
# Pastikan permission folder storage dan bootstrap/cache
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### 4. Cek Log Error
```bash
# Lihat log Laravel untuk error detail
tail -f storage/logs/laravel.log
```

### 5. Test Endpoint Secara Manual
```bash
# Test endpoint dengan curl
curl -X PUT "https://your-domain.com/admin/payments/1/status" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"status":"verified"}'
```

## 🚨 Kemungkinan Masalah di Hosting

### 1. Route Cache Issue
```bash
# Clear dan rebuild route cache
php artisan route:clear
php artisan route:cache
```

### 2. Config Cache Issue
```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. .htaccess Missing atau Salah
Pastikan file `.htaccess` ada di public folder:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

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

### 4. Environment Configuration
Pastikan `.env` di hosting sudah benar:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database settings
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Session settings
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## 🔧 Debugging Steps untuk Admin

1. **Buka Browser Developer Tools** (F12)
2. **Pergi ke Network tab**
3. **Lakukan action yang error** (update status, delete, dll)
4. **Lihat request yang gagal** di Network tab
5. **Klik request tersebut** dan lihat:
   - Status code (404, 500, 302, dll)
   - Response body (apakah HTML atau JSON)
   - Request headers (CSRF token, Content-Type)

## 📞 Jika Masih Error

Jika setelah mengikuti panduan ini masih ada error:

1. **Screenshot error di browser console**
2. **Screenshot Network tab di Developer Tools**
3. **Copy paste isi file `storage/logs/laravel.log`** (bagian yang error)
4. **Berikan informasi hosting environment** (shared hosting, VPS, dll)

## 🎯 Quick Fix Checklist

- [ ] Route cache cleared (`php artisan route:clear`)
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] File permissions correct (775 for storage/)
- [ ] .htaccess file exists in public/
- [ ] CSRF meta tag exists in layout
- [ ] Database connection working
- [ ] APP_URL correct in .env
- [ ] All JavaScript files updated with response checks

---

**Catatan:** Semua perbaikan JavaScript sudah diterapkan. Jika masih ada error, kemungkinan besar masalahnya ada di konfigurasi hosting atau server-side.