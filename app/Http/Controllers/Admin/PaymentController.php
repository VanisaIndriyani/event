<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, EventPayment $payment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,verified,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status provided.'
            ], 400);
        }

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
            } catch (\Exception $e) {
                \Log::error('Failed to send email notification: ' . $e->getMessage());
            }
        } elseif ($request->status === 'rejected') {
            $payment->eventRegistration->update(['status' => 'pending']);
        }

        // Handle both AJAX and form submission
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully.'
            ]);
        } else {
            // For form submission, redirect back with success message
            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
        }
    }
}