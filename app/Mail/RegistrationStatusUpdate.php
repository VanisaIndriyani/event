<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventRegistration;

class RegistrationStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $statusText = $this->getStatusText($this->registration->status);
        
        return $this->subject('Update Status Pendaftaran - ' . $this->registration->event->name)
                    ->view('emails.registration-status-update')
                    ->with([
                        'userName' => $this->registration->user->name,
                        'eventName' => $this->registration->event->name,
                        'status' => $this->registration->status,
                        'statusText' => $statusText,
                        'eventDate' => $this->registration->event->event_date,
                        'eventLocation' => $this->registration->event->location,
                        'registration' => $this->registration
                    ]);
    }

    /**
     * Get human readable status text
     */
    private function getStatusText($status)
    {
        switch ($status) {
            case 'confirmed':
                return 'Disetujui';
            case 'pending':
                return 'Menunggu Persetujuan';
            case 'cancelled':
                return 'Dibatalkan';
            case 'rejected':
                return 'Ditolak';
            default:
                return ucfirst($status);
        }
    }
}