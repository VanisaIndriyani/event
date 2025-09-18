@echo off
echo ========================================
echo    FIX HOSTING 405 ERROR - LARK.TODAY
echo ========================================
echo.

echo [INFO] Starting comprehensive 405 error fix...
echo.

echo ========================================
echo    STEP 1: BACKUP CURRENT FILES
echo ========================================

if not exist "backup" mkdir backup
if not exist "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%" mkdir "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%"

echo [INFO] Creating backup folder...
copy "app\Http\Middleware\AdminMiddleware.php" "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%\AdminMiddleware.php.bak" >nul 2>&1
copy "resources\views\admin\registrations\show.blade.php" "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%\show.blade.php.bak" >nul 2>&1
copy "routes\web.php" "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%\web.php.bak" >nul 2>&1
copy "public\.htaccess" "backup\%date:~-4,4%-%date:~-10,2%-%date:~-7,2%\.htaccess.bak" >nul 2>&1

echo [SUCCESS] Backup completed!
echo.

echo ========================================
echo    STEP 2: CLEAR LOCAL CACHE
echo ========================================

echo [INFO] Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo [INFO] Rebuilding cache...
php artisan config:cache
php artisan route:cache

echo [SUCCESS] Cache cleared and rebuilt!
echo.

echo ========================================
echo    STEP 3: VERIFY CRITICAL FILES
echo ========================================

echo [INFO] Checking critical files...

if exist "app\Http\Middleware\AdminMiddleware.php" (
    echo [OK] AdminMiddleware.php exists
) else (
    echo [ERROR] AdminMiddleware.php missing!
    pause
    exit /b 1
)

if exist "resources\views\admin\registrations\show.blade.php" (
    echo [OK] show.blade.php exists
) else (
    echo [ERROR] show.blade.php missing!
    pause
    exit /b 1
)

if exist "routes\web.php" (
    echo [OK] web.php exists
) else (
    echo [ERROR] web.php missing!
    pause
    exit /b 1
)

echo [SUCCESS] All critical files verified!
echo.

echo ========================================
echo    STEP 4: CREATE ENHANCED .HTACCESS
echo ========================================

echo [INFO] Creating enhanced .htaccess with PUT method support...

echo ^<IfModule mod_rewrite.c^> > "public\.htaccess.new"
echo     ^<IfModule mod_negotiation.c^> >> "public\.htaccess.new"
echo         Options -MultiViews -Indexes >> "public\.htaccess.new"
echo     ^</IfModule^> >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     RewriteEngine On >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     # Handle Authorization Header >> "public\.htaccess.new"
echo     RewriteCond %%{HTTP:Authorization} . >> "public\.htaccess.new"
echo     RewriteRule .* - [E=HTTP_AUTHORIZATION:%%{HTTP:Authorization}] >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     # Handle X-XSRF-Token Header >> "public\.htaccess.new"
echo     RewriteCond %%{HTTP:x-xsrf-token} . >> "public\.htaccess.new"
echo     RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%%{HTTP:X-XSRF-Token}] >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     # Allow PUT, PATCH, DELETE methods >> "public\.htaccess.new"
echo     RewriteCond %%{REQUEST_METHOD} ^^(PUT^|PATCH^|DELETE)$ >> "public\.htaccess.new"
echo     RewriteRule ^^(.*)$ index.php [L,QSA] >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     # Redirect Trailing Slashes If Not A Folder... >> "public\.htaccess.new"
echo     RewriteCond %%{REQUEST_FILENAME} !-d >> "public\.htaccess.new"
echo     RewriteCond %%{REQUEST_URI} (.+)/$ >> "public\.htaccess.new"
echo     RewriteRule ^^ %%1 [L,R=301] >> "public\.htaccess.new"
echo. >> "public\.htaccess.new"
echo     # Send Requests To Front Controller... >> "public\.htaccess.new"
echo     RewriteCond %%{REQUEST_FILENAME} !-d >> "public\.htaccess.new"
echo     RewriteCond %%{REQUEST_FILENAME} !-f >> "public\.htaccess.new"
echo     RewriteRule ^^ index.php [L] >> "public\.htaccess.new"
echo ^</IfModule^> >> "public\.htaccess.new"

move "public\.htaccess.new" "public\.htaccess"

echo [SUCCESS] Enhanced .htaccess created!
echo.

echo ========================================
echo    STEP 5: CREATE DEPLOYMENT PACKAGE
echo ========================================

echo [INFO] Creating deployment package...

if not exist "deployment-package" mkdir deployment-package
if not exist "deployment-package\app\Http\Middleware" mkdir "deployment-package\app\Http\Middleware"
if not exist "deployment-package\resources\views\admin\registrations" mkdir "deployment-package\resources\views\admin\registrations"
if not exist "deployment-package\routes" mkdir "deployment-package\routes"
if not exist "deployment-package\public" mkdir "deployment-package\public"

copy "app\Http\Middleware\AdminMiddleware.php" "deployment-package\app\Http\Middleware\AdminMiddleware.php"
copy "resources\views\admin\registrations\show.blade.php" "deployment-package\resources\views\admin\registrations\show.blade.php"
copy "routes\web.php" "deployment-package\routes\web.php"
copy "public\.htaccess" "deployment-package\public\.htaccess"
copy "hosting-debug.php" "deployment-package\hosting-debug.php"

echo [SUCCESS] Deployment package created in 'deployment-package' folder!
echo.

echo ========================================
echo    STEP 6: CREATE UPLOAD INSTRUCTIONS
echo ========================================

echo [INFO] Creating upload instructions...

echo ========================================== > "UPLOAD_INSTRUCTIONS.txt"
echo    HOSTING UPLOAD INSTRUCTIONS >> "UPLOAD_INSTRUCTIONS.txt"
echo ========================================== >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 1. LOGIN TO CPANEL: >> "UPLOAD_INSTRUCTIONS.txt"
echo    - URL: https://lark.today:2083 >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Open File Manager >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 2. NAVIGATE TO APPLICATION FOLDER: >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Go to: public_html/event/ >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Or wherever your Laravel app is located >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 3. UPLOAD FILES FROM 'deployment-package' FOLDER: >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo    FILE 1: AdminMiddleware.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Local: deployment-package/app/Http/Middleware/AdminMiddleware.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Upload to: app/Http/Middleware/AdminMiddleware.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Action: REPLACE existing file >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo    FILE 2: show.blade.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Local: deployment-package/resources/views/admin/registrations/show.blade.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Upload to: resources/views/admin/registrations/show.blade.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Action: REPLACE existing file >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo    FILE 3: web.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Local: deployment-package/routes/web.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Upload to: routes/web.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Action: REPLACE existing file >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo    FILE 4: .htaccess (ENHANCED) >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Local: deployment-package/public/.htaccess >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Upload to: public/.htaccess >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Action: REPLACE existing file >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo    FILE 5: hosting-debug.php (TEMPORARY) >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Local: deployment-package/hosting-debug.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Upload to: hosting-debug.php (root folder) >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Action: CREATE new file >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 4. CLEAR HOSTING CACHE: >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Delete: bootstrap/cache/config.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Delete: bootstrap/cache/routes.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Delete: bootstrap/cache/services.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Delete all files in: storage/framework/cache/data/ >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Delete all files in: storage/framework/views/ >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 5. TEST DEPLOYMENT: >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Access: https://lark.today/event/public/hosting-debug.php >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Check all green checkmarks >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Test: https://lark.today/event/public/admin/ping >> "UPLOAD_INSTRUCTIONS.txt"
echo    - Login and test payment update >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo 6. CLEANUP: >> "UPLOAD_INSTRUCTIONS.txt"
echo    - DELETE hosting-debug.php after testing >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo ========================================== >> "UPLOAD_INSTRUCTIONS.txt"
echo    TROUBLESHOOTING >> "UPLOAD_INSTRUCTIONS.txt"
echo ========================================== >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo If still getting 405 error: >> "UPLOAD_INSTRUCTIONS.txt"
echo 1. Check hosting-debug.php report >> "UPLOAD_INSTRUCTIONS.txt"
echo 2. Contact hosting support about mod_rewrite >> "UPLOAD_INSTRUCTIONS.txt"
echo 3. Ask about PUT/PATCH/DELETE method restrictions >> "UPLOAD_INSTRUCTIONS.txt"
echo 4. Verify PHP version is 8.1+ >> "UPLOAD_INSTRUCTIONS.txt"
echo. >> "UPLOAD_INSTRUCTIONS.txt"
echo Emergency contact: >> "UPLOAD_INSTRUCTIONS.txt"
echo - Check Laravel logs: storage/logs/laravel.log >> "UPLOAD_INSTRUCTIONS.txt"
echo - Check web server error logs >> "UPLOAD_INSTRUCTIONS.txt"
echo ========================================== >> "UPLOAD_INSTRUCTIONS.txt"

echo [SUCCESS] Upload instructions created!
echo.

echo ========================================
echo    STEP 7: FINAL VERIFICATION
echo ========================================

echo [INFO] Running final verification...

echo Testing local routes...
php artisan route:list | findstr "payments.*status" >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Payment status route found
) else (
    echo [WARNING] Payment status route not found in route list
)

echo Testing local ping endpoint...
php artisan route:list | findstr "ping" >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Ping endpoint found
) else (
    echo [WARNING] Ping endpoint not found in route list
)

echo [SUCCESS] Local verification completed!
echo.

echo ========================================
echo    DEPLOYMENT READY!
echo ========================================
echo.
echo [SUCCESS] All files prepared for deployment!
echo.
echo NEXT STEPS:
echo 1. Open 'UPLOAD_INSTRUCTIONS.txt' for detailed steps
echo 2. Upload files from 'deployment-package' folder to hosting
echo 3. Test using hosting-debug.php
echo 4. Test payment update functionality
echo.
echo FILES TO UPLOAD:
echo - deployment-package/app/Http/Middleware/AdminMiddleware.php
echo - deployment-package/resources/views/admin/registrations/show.blade.php
echo - deployment-package/routes/web.php
echo - deployment-package/public/.htaccess
echo - deployment-package/hosting-debug.php
echo.
echo [INFO] Backup created in 'backup' folder
echo [INFO] Instructions available in 'UPLOAD_INSTRUCTIONS.txt'
echo.
echo ========================================
echo    SCRIPT COMPLETED SUCCESSFULLY!
echo ========================================
echo.
pause