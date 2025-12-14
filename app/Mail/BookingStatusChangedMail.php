<?php

namespace App\Mail;

use App\Models\Booking;
use App\Support\BookingFlow;
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
        $this->booking->loadMissing('listing');
        $this->coverUrl = $this->booking->listing?->cover_url;
        $this->portalUrl = $this->booking->public_access_token
            ? route('guest.portal.show', $this->booking->public_access_token)
            : null;
        $this->flow = BookingFlow::context($this->booking);
        $this->adminUrl = url("/admin/bookings/{$this->booking->id}/edit");
    }

    public ?string $coverUrl = null;
    public ?string $portalUrl = null;
    public array $flow = [];
    public ?string $adminUrl = null;

    public function envelope(): Envelope
    {
        $listingName = $this->booking->listing->name ?? 'Villa Mila';
        $statusLabel = $this->booking->status->label();

        $subject = $this->isOwner
            ? "[$listingName] {$this->booking->customer_name} ahora está {$statusLabel}"
            : "Tu reserva en {$listingName} está {$statusLabel}";

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
                'coverUrl' => $this->coverUrl,
                'portalUrl' => $this->portalUrl,
                'flow' => $this->flow,
                'adminUrl' => $this->adminUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
