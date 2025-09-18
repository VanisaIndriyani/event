<?php

/**
 * Contoh Controller Laravel yang Benar untuk Mengembalikan JSON Response
 * 
 * File ini berisi contoh implementasi controller yang memastikan:
 * 1. Selalu mengembalikan JSON response untuk AJAX requests
 * 2. Proper error handling
 * 3. Consistent response format
 * 4. CSRF token validation
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\EventPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class ExampleController extends Controller
{
    /**
     * Update registration status
     * 
     * Route: PUT /admin/registrations/{id}/status
     */
    public function updateRegistrationStatus(Request $request, $id)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find registration
            $registration = EventRegistration::with(['user', 'event'])->find($id);
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi tidak ditemukan'
                ], 404);
            }

            $oldStatus = $registration->status;
            
            // Update status
            $registration->update([
                'status' => $request->status
            ]);
            
            // Send email notification if status changed to confirmed
            if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
                try {
                    \Mail::to($registration->user->email)->send(new \App\Mail\RegistrationApproved($registration));
                    Log::info('Email approval notification sent successfully - Registration ID: ' . $registration->id);
                } catch (Exception $e) {
                    Log::error('Failed to send email approval notification: ' . $e->getMessage());
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Status registrasi berhasil diupdate!',
                'data' => [
                    'id' => $registration->id,
                    'status' => $registration->status
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Error updating registration status: ' . $e->getMessage(), [
                'registration_id' => $id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Delete registration
     * 
     * Route: DELETE /admin/registrations/{id}
     */
    public function deleteRegistration($id)
    {
        try {
            $registration = EventRegistration::find($id);
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi tidak ditemukan'
                ], 404);
            }
            
            $registration->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil dihapus!'
            ]);
            
        } catch (Exception $e) {
            Log::error('Error deleting registration: ' . $e->getMessage(), [
                'registration_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Update payment status
     * 
     * Route: PUT /admin/payments/{id}/status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,verified,rejected',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find payment
            $payment = EventPayment::with('eventRegistration.user')->find($id);
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment tidak ditemukan'
                ], 404);
            }

            // Update payment status
            $payment->update([
                'payment_status' => $request->status,
                'verified_at' => $request->status === 'verified' ? now() : null,
            ]);

            // Update registration status based on payment status
            if ($request->status === 'verified') {
                $payment->eventRegistration->update(['status' => 'confirmed']);
                
                // Send email notification
                try {
                    \Mail::to($payment->eventRegistration->user->email)->send(new \App\Mail\RegistrationApproved($payment->eventRegistration));
                } catch (Exception $e) {
                    Log::error('Failed to send email notification: ' . $e->getMessage());
                }
            } elseif ($request->status === 'rejected') {
                $payment->eventRegistration->update(['status' => 'pending']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status payment berhasil diupdate!',
                'data' => [
                    'id' => $payment->id,
                    'status' => $payment->payment_status
                ]
            ]);
            
        } catch (Exception $e) {
            Log::error('Error updating payment status: ' . $e->getMessage(), [
                'payment_id' => $id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Send email notification
     * 
     * Route: POST /admin/email/send-notification
     */
    public function sendEmailNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'registration_id' => 'required|exists:event_registrations,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $registration = EventRegistration::with(['user', 'event'])->find($request->registration_id);
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi tidak ditemukan'
                ], 404);
            }
            
            // Send email based on current status
            \Mail::to($registration->user->email)->send(new \App\Mail\RegistrationStatusUpdate($registration));
            
            Log::info('Email notification sent successfully', [
                'registration_id' => $registration->id,
                'email' => $registration->user->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . $registration->user->email
            ]);
            
        } catch (Exception $e) {
            Log::error('Error sending email notification: ' . $e->getMessage(), [
                'registration_id' => $request->registration_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

/**
 * MIDDLEWARE YANG DIPERLUKAN
 * 
 * Pastikan route menggunakan middleware berikut:
 * 
 * Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
 *     Route::put('/registrations/{id}/status', [ExampleController::class, 'updateRegistrationStatus']);
 *     Route::delete('/registrations/{id}', [ExampleController::class, 'deleteRegistration']);
 *     Route::put('/payments/{id}/status', [ExampleController::class, 'updatePaymentStatus']);
 *     Route::post('/email/send-notification', [ExampleController::class, 'sendEmailNotification']);
 * });
 * 
 * CSRF PROTECTION
 * 
 * Pastikan semua request POST/PUT/DELETE menyertakan CSRF token:
 * 
 * headers: {
 *     'Content-Type': 'application/json',
 *     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
 * }
 * 
 * ERROR HANDLING DI JAVASCRIPT
 * 
 * fetch('/admin/registrations/1/status', {
 *     method: 'PUT',
 *     headers: {
 *         'Content-Type': 'application/json',
 *         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
 *     },
 *     body: JSON.stringify({ status: 'confirmed' })
 * })
 * .then(response => {
 *     if (!response.ok) {
 *         throw new Error(`HTTP error! Status: ${response.status}`);
 *     }
 *     const contentType = response.headers.get('content-type');
 *     if (contentType && contentType.includes('application/json')) {
 *         return response.json();
 *     } else {
 *         throw new Error('Response is not JSON');
 *     }
 * })
 * .then(data => {
 *     if (data.success) {
 *         alert(data.message);
 *         location.reload();
 *     } else {
 *         alert('Error: ' + data.message);
 *     }
 * })
 * .catch(error => {
 *     console.error('Error:', error);
 *     alert('Terjadi kesalahan sistem. Silakan coba lagi.');
 * });
 */