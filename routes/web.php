<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EventFormController;
use App\Http\Controllers\Admin\FormFieldController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PortfolioController;


// Public routes
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/events', [UserController::class, 'events'])->name('events');
Route::get('/events/{event}', [UserController::class, 'eventDetail'])->name('event.detail');
Route::get('/portfolio', [UserController::class, 'portfolio'])->name('portfolio');
Route::get('/merchandise', [UserController::class, 'merchandise'])->name('merchandise');
Route::get('/collab', [UserController::class, 'collab'])->name('collab');
Route::post('/collab', [UserController::class, 'submitCollab'])->name('collab.submit');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User dashboard routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/events/{event}/register', [UserController::class, 'showRegistrationForm'])->name('event.registration.form');
    Route::post('/events/{event}/register', [UserController::class, 'registerEvent'])->name('event.register');
    Route::get('/my-events', [UserController::class, 'myEvents'])->name('user.events');
    Route::delete('/events/{registration}/cancel', [UserController::class, 'cancelRegistration'])->name('event.cancel');
});



// Admin routes (requires authentication and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Event management
    Route::get('/events', [AdminController::class, 'events'])->name('events.index');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}', [AdminController::class, 'showEvent'])->name('events.show');
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.destroy');
    
    // Event form fields management
    Route::get('/events/{event}/form-fields', [EventFormController::class, 'index'])->name('events.form-fields.index');
    Route::get('/events/{event}/form-fields/create', [EventFormController::class, 'create'])->name('events.form-fields.create');
    Route::post('/events/{event}/form-fields', [EventFormController::class, 'store'])->name('events.form-fields.store');
    Route::get('/events/{event}/form-fields/{formField}/edit', [EventFormController::class, 'edit'])->name('events.form-fields.edit');
    Route::put('/events/{event}/form-fields/{formField}', [EventFormController::class, 'update'])->name('events.form-fields.update');
    Route::delete('/events/{event}/form-fields/{formField}', [EventFormController::class, 'destroy'])->name('events.form-fields.destroy');
    Route::post('/events/{event}/form-fields/update-order', [EventFormController::class, 'updateOrder'])->name('events.form-fields.update-order');
    
    // Form Fields management (general)
    Route::resource('form-fields', FormFieldController::class);
    Route::post('/form-fields/update-order', [FormFieldController::class, 'updateOrder'])->name('form-fields.update-order');
    
    // Merchandise management
    Route::get('/merchandise', [AdminController::class, 'merchandise'])->name('merchandise.index');
    Route::get('/merchandise/create', [AdminController::class, 'createMerchandise'])->name('merchandise.create');
    Route::post('/merchandise', [AdminController::class, 'storeMerchandise'])->name('merchandise.store');
    Route::get('/merchandise/{merchandise}/edit', [AdminController::class, 'editMerchandise'])->name('merchandise.edit');
    Route::put('/merchandise/{merchandise}', [AdminController::class, 'updateMerchandise'])->name('merchandise.update');
    
    // Collaboration management
    Route::get('/collaborations', [AdminController::class, 'collaborations'])->name('collaborations.index');
    Route::get('/collaborations/{collaboration}', [AdminController::class, 'showCollaboration'])->name('collaborations.show');
    Route::put('/collaborations/{collaboration}/status', [AdminController::class, 'updateCollaborationStatus'])->name('collaborations.update-status');
    Route::delete('/collaborations/{collaboration}', [AdminController::class, 'deleteCollaboration'])->name('collaborations.destroy');
    Route::delete('/merchandise/{merchandise}', [AdminController::class, 'deleteMerchandise'])->name('merchandise.destroy');
    
    // Event registrations management
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations.index');
    Route::get('/registrations/export', [AdminController::class, 'exportRegistrations'])->name('registrations.export');
    Route::get('/registrations/export-excel', [AdminController::class, 'exportRegistrationsExcel'])->name('registrations.export.excel');
    Route::get('/registrations/{registration}', [AdminController::class, 'showRegistration'])->name('registrations.show');
    Route::put('/registrations/{registration}/status', [AdminController::class, 'updateRegistrationStatus'])->name('registrations.status');
    Route::delete('/registrations/{registration}', [AdminController::class, 'deleteRegistration'])->name('registrations.destroy');
    
    // Portfolio management
    Route::resource('portfolios', PortfolioController::class);
    
    // Payment management
    Route::put('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.status');
    Route::post('/payments/{payment}/send-email', [PaymentController::class, 'sendEmail'])->name('payments.send-email');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    
    // Email Notification Management
    Route::post('/email/send-notification', [AdminController::class, 'sendEmailNotification'])->name('email.send-notification');
    
    // Session keep-alive endpoint
    Route::get('/ping', function() {
        return response()->json(['status' => 'ok', 'time' => now()]);
    })->name('session.ping');
});

// Fallback route for hosting compatibility
Route::fallback(function () {
    // Check if it's an admin route
    if (request()->is('admin/*')) {
        return redirect()->route('admin.dashboard');
    }
    
    // Check if it's an API-like request
    if (request()->expectsJson()) {
        return response()->json([
            'error' => 'Route not found',
            'message' => 'The requested route does not exist.'
        ], 404);
    }
    
    // Redirect to home for other requests
    return redirect()->route('home');
});
