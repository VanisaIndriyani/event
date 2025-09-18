# Production Deployment Guide - Payment Update Fix

## Current Issue
User is experiencing HTTP 405 Method Not Allowed error when trying to update payment status on the production environment (lark.today).

## Root Cause
The issue occurs because:
1. **Authentication Required**: User must be logged in as admin to access payment update functionality
2. **Production vs Development**: The fixes were implemented locally but need to be deployed to production
3. **Session Management**: Production environment may have different session configuration

## Immediate Solution for User

### Step 1: Login as Admin
1. Go to: `https://lark.today/event/public/login`
2. Login with admin credentials
3. Navigate back to the registration details page
4. Try updating payment status again

### Step 2: If Still Getting 405 Error
The enhanced error handling will now:
- Show clear error messages
- Automatically redirect to login page
- Provide specific instructions for the issue

## Files Modified (Need Deployment)

### 1. AdminMiddleware Enhancement
**File**: `app/Http/Middleware/AdminMiddleware.php`
- Added JSON response handling for AJAX requests
- Proper HTTP status codes (401, 403)
- Better error messages

### 2. JavaScript Error Handling
**File**: `resources/views/admin/registrations/show.blade.php`
- Enhanced authentication checking
- Production-aware login URL detection
- Specific 405 error handling
- Better user feedback

## Deployment Steps

### For Production Server (lark.today)
1. **Upload Modified Files**:
   ```bash
   # Upload these files to production:
   app/Http/Middleware/AdminMiddleware.php
   resources/views/admin/registrations/show.blade.php
   ```

2. **Clear Cache** (if applicable):
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Restart Web Server** (if needed)

## Testing After Deployment

### Test Case 1: Unauthenticated User
1. Access registration details page without login
2. Try to update payment status
3. Should see: "Authentication required" message
4. Should redirect to login page

### Test Case 2: Authenticated Admin
1. Login as admin
2. Access registration details page
3. Try to update payment status
4. Should work without 405 error

## Debug Information

The enhanced JavaScript now logs:
- Request URL
- HTTP method
- CSRF token
- Current environment
- User agent

Check browser console (F12) for detailed debug information.

## Error Messages Guide

### "Authentication required"
- **Cause**: User not logged in
- **Solution**: Login as admin first

### "Session expired"
- **Cause**: Login session has expired
- **Solution**: Login again

### "Method Not Allowed (405)"
- **Cause**: Usually authentication issue or route problem
- **Solution**: Login as admin, if persists check route configuration

### "Access denied (403)"
- **Cause**: User logged in but not as admin
- **Solution**: Login with admin account

## Production Environment Considerations

### Session Configuration
Ensure production has proper session configuration:
- Session driver (database/redis recommended for production)
- Session lifetime
- CSRF token handling

### HTTPS Configuration
Ensure HTTPS is properly configured for:
- Secure cookies
- CSRF token transmission
- Session security

### Route Configuration
Verify that admin routes are properly configured:
```php
// Should exist in routes/web.php
Route::put('/admin/payments/{payment}/status', [PaymentController::class, 'updateStatus'])
    ->name('payments.status')
    ->middleware(['admin']);
```

## Monitoring

After deployment, monitor:
1. Laravel logs for authentication errors
2. Web server logs for 405 errors
3. User feedback on payment update functionality

## Rollback Plan

If issues persist after deployment:
1. Revert to previous version of modified files
2. Clear caches
3. Investigate production-specific configuration issues

## Contact Information

For deployment assistance or if issues persist:
- Check Laravel logs: `storage/logs/laravel.log`
- Check web server error logs
- Verify database connectivity and admin user permissions