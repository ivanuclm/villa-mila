<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class PublicBookingHoldOwnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public array $quote,
        public ?string $adminUrl = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud de reserva (HOLD)',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking.hold-owner',
            with: [
                'booking'  => $this->booking,
                'quote'    => $this->quote,
                'adminUrl' => $this->adminUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
