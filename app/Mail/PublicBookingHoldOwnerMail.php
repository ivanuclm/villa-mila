<?php

namespace App\Mail;

use App\Models\Booking;
use App\Support\BookingFlow;
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
    ) {
        $this->booking->loadMissing('listing');
        $this->coverUrl = $this->booking->listing?->cover_url;
        $this->flow = BookingFlow::context($this->booking);
        $this->portalUrl = $this->booking->public_access_token
            ? route('guest.portal.show', $this->booking->public_access_token)
            : null;
    }

    public ?string $coverUrl = null;
    public array $flow = [];
    public ?string $portalUrl = null;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud: ' . ($this->booking->customer_name ?? 'Invitado'),
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
                'coverUrl' => $this->coverUrl,
                'flow' => $this->flow,
                'portalUrl' => $this->portalUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
