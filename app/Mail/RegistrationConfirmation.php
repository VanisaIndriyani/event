<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventRegistration;

class RegistrationConfirmation extends Mailable
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
        return $this->subject('Konfirmasi Pendaftaran Event - ' . $this->registration->event->name)
                    ->view('emails.registration-confirmation')
                    ->with([
                        'userName' => $this->registration->user->name,
                        'eventName' => $this->registration->event->name,
                        'eventDate' => $this->registration->event->event_date,
                        'eventLocation' => $this->registration->event->location,
                        'eventPrice' => $this->registration->event->price,
                        'registrationId' => $this->registration->id,
                        'registration' => $this->registration
                    ]);
    }
}