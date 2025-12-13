<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class PublicBookingHoldCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public array $quote
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu solicitud de reserva está pendiente de revisión',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking.hold-customer',
            with: [
                'booking' => $this->booking,
                'quote'   => $this->quote,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
