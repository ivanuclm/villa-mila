<?php

namespace App\Observers;

use App\Enums\BookingStatus;
use App\Mail\BookingStatusChangedMail;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    public function updated(Booking $booking): void
    {
        if (! $booking->wasChanged('status')) {
            return;
        }

        $previous = $booking->getOriginal('status');
        $previous = $previous instanceof BookingStatus ? $previous->value : (string) $previous;

        if ($booking->customer_email) {
            Mail::to($booking->customer_email)->send(
                new BookingStatusChangedMail($booking, $previous, false),
            );
        }

        if ($ownerEmail = config('villa.owner_email')) {
            Mail::to($ownerEmail)->send(
                new BookingStatusChangedMail($booking, $previous, true),
            );
        }
    }
}
