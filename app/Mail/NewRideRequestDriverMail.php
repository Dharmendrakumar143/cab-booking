<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRideRequestDriverMail extends Mailable
{
    use Queueable, SerializesModels;

   
    public $ride;
    public $dashboard_type;
    /**
     * Create a new message instance.
     */
    public function __construct($ride, $dashboard_type)
    {
        $this->ride = $ride;
        $this->dashboard_type = $dashboard_type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Ride Request Received – Please Accept to Start the Ride',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_ride_request_driver',
            with: [
                'booking' => $this->ride,
                'dashboard_type'=>$this->dashboard_type
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
