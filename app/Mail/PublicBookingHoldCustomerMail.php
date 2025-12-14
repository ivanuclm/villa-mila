<?php

namespace App\Mail;

use App\Models\Booking;
use App\Support\BookingFlow;
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
    ) {
        $this->booking->loadMissing('listing');
        $this->coverUrl = $this->booking->listing?->cover_url;
        $this->portalUrl = $this->booking->public_access_token
            ? route('guest.portal.show', $this->booking->public_access_token)
            : null;
        $this->flow = BookingFlow::context($this->booking);
    }

    public ?string $coverUrl = null;
    public ?string $portalUrl = null;
    public array $flow = [];

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu solicitud para ' . ($this->booking->listing->name ?? 'Villa Mila'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking.hold-customer',
            with: [
                'booking' => $this->booking,
                'quote'   => $this->quote,
                'coverUrl' => $this->coverUrl,
                'portalUrl' => $this->portalUrl,
                'flow' => $this->flow,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
