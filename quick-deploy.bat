@echo off
echo ========================================
echo     QUICK DEPLOYMENT TO HOSTING
echo ========================================
echo.

echo [1/4] Committing changes to Git...
git add .
set /p commit_msg="Enter commit message: "
git commit -m "%commit_msg%"

echo.
echo [2/4] Pushing to repository...
git push origin main

echo.
echo [3/4] Files that need to be uploaded to hosting:
echo ✓ app/Http/Middleware/AdminMiddleware.php
echo ✓ resources/views/admin/registrations/show.blade.php  
echo ✓ routes/web.php
echo ✓ .env (update configuration)
echo.

echo [4/4] Next steps for hosting:
echo 1. Login to cPanel File Manager
echo 2. Upload the 3 files above
echo 3. Update .env with production settings
echo 4. Clear cache: php artisan config:clear
echo 5. Test: https://lark.today/event/public/admin/ping
echo.

echo ========================================
echo     DEPLOYMENT PREPARATION COMPLETE!
echo ========================================
echo.
echo Manual upload required to hosting.
echo Follow HOSTING_DEPLOYMENT_GUIDE.md for details.
echo.
pause