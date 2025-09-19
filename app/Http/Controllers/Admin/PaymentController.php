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
            'status' => 'required|in:pending,lunas,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status provided.'
            ], 400);
        }

        $payment->update([
            'payment_status' => $request->status,
        ]);

        // Update registration status based on payment status
        if ($request->status === 'lunas') {
            $payment->eventRegistration->update(['status' => 'confirmed']);
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

    /**
     * Send email approval to user.
     */
    public function sendEmail(Request $request, EventPayment $payment)
    {
        // Check if payment is lunas
        if ($payment->payment_status !== 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Email can only be sent for paid payments.'
            ], 400);
        }

        // Check if email already sent
        if ($payment->email_sent) {
            return response()->json([
                'success' => false,
                'message' => 'Email has already been sent for this payment.'
            ], 400);
        }

        try {
            // Send email notification
            \Mail::to($payment->eventRegistration->user->email)->send(new \App\Mail\RegistrationApproved($payment->eventRegistration));
            
            // Update email tracking
            $payment->update([
                'email_sent' => true,
                'email_sent_at' => now(),
                'email_sent_by' => auth()->id()
            ]);
            
            // Update registration status to confirmed
            $payment->eventRegistration->update(['status' => 'confirmed']);
            
            \Log::info('Email notification sent successfully - Payment ID: ' . $payment->id);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send email notification: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email notification.'
            ], 500);
        }

        // Handle both AJAX and form submission
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Email notification sent successfully.'
            ]);
        } else {
            // For form submission, redirect back with success message
            return redirect()->back()->with('success', 'Email notifikasi berhasil dikirim!');
        }
    }
}