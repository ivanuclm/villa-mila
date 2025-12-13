<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public ?string $previousStatus = null,
        public bool $isOwner = false,
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->isOwner
            ? "Estado actualizado: {$this->booking->status->label()}"
            : "Tu reserva ahora está {$this->booking->status->label()}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking.status-changed',
            with: [
                'booking' => $this->booking,
                'previousStatus' => $this->previousStatus,
                'isOwner' => $this->isOwner,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
