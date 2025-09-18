# 🚨 URGENT: Langkah Deployment ke Hosting

## Status Saat Ini
❌ **Hosting masih error 405 Method Not Allowed**  
✅ **Localhost sudah berhasil**  

**Root Cause**: File belum di-upload ke hosting!

---

## 🔥 LANGKAH CEPAT (5 Menit)

### **Step 1: Login ke cPanel**
1. Buka: https://lark.today:2083 (atau sesuai hosting provider)
2. Login dengan username/password hosting
3. Cari dan klik **"File Manager"**

### **Step 2: Navigate ke Folder Aplikasi**
1. Di File Manager, navigate ke: `public_html/event/`
2. Atau folder dimana aplikasi Laravel berada

### **Step 3: Upload 3 File Penting**

#### **File 1: AdminMiddleware.php**
- **Local path**: `app/Http/Middleware/AdminMiddleware.php`
- **Upload ke**: `public_html/event/app/Http/Middleware/AdminMiddleware.php`
- **Action**: Replace file yang ada

#### **File 2: show.blade.php**
- **Local path**: `resources/views/admin/registrations/show.blade.php`
- **Upload ke**: `public_html/event/resources/views/admin/registrations/show.blade.php`
- **Action**: Replace file yang ada

#### **File 3: web.php**
- **Local path**: `routes/web.php`
- **Upload ke**: `public_html/event/routes/web.php`
- **Action**: Replace file yang ada

### **Step 4: Update File .env**
1. Edit file `.env` di hosting
2. Ubah konfigurasi berikut:

```env
# UBAH INI:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lark.today/event/public

# SESSION CONFIGURATION:
SESSION_LIFETIME=43200
SESSION_PATH=/event/public/
SESSION_DOMAIN=.lark.today
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_EXPIRE_ON_CLOSE=false
```

### **Step 5: Clear Cache**
1. Di cPanel, cari **"Terminal"** atau **"SSH Access"**
2. Navigate ke folder aplikasi:
   ```bash
   cd public_html/event/
   ```
3. Jalankan perintah:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan config:cache
   ```

### **Step 6: Test**
1. **Test ping endpoint**: https://lark.today/event/public/admin/ping
   - Harus return: `{"status":"ok","time":"..."}`

2. **Test payment update**:
   - Login sebagai admin
   - Buka: https://lark.today/event/public/admin/registrations/1
   - Coba update payment status
   - Harus berhasil tanpa error 405

---

## 📋 CHECKLIST DEPLOYMENT

- [ ] Login ke cPanel File Manager
- [ ] Navigate ke folder `public_html/event/`
- [ ] Upload `AdminMiddleware.php` (replace existing)
- [ ] Upload `show.blade.php` (replace existing)
- [ ] Upload `web.php` (replace existing)
- [ ] Edit `.env` dengan konfigurasi production
- [ ] Clear cache via Terminal/SSH
- [ ] Test endpoint `/admin/ping`
- [ ] Test login admin
- [ ] Test payment update function
- [ ] Verify no more 405 errors

---

## 🆘 JIKA MASIH ERROR

### **Cek 1: Route Cache**
```bash
php artisan route:list | grep ping
```
Harus muncul route `/admin/ping`

### **Cek 2: File Permission**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### **Cek 3: .htaccess**
Pastikan file `public/.htaccess` ada dan berisi:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

### **Cek 4: Database Connection**
```bash
php artisan tinker
DB::connection()->getPdo();
```

---

## 📞 EMERGENCY CONTACT

Jika masih bermasalah:
1. Screenshot error message
2. Cek log: `storage/logs/laravel.log`
3. Pastikan semua file sudah ter-upload
4. Verify database connection

---

## ✅ HASIL YANG DIHARAPKAN

Setelah deployment berhasil:
- ✅ https://lark.today/event/public/admin/ping return JSON
- ✅ Payment update berhasil (tidak error 405)
- ✅ Session tidak logout otomatis
- ✅ Keep-alive mechanism aktif

**INGAT**: File di localhost sudah benar, tinggal upload ke hosting!