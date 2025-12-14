<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Mail\PublicBookingHoldCustomerMail;
use App\Mail\PublicBookingHoldOwnerMail;
use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Services\BookingPriceService;

class PublicBookingCreator
{
    public function __construct(private readonly BookingPriceService $priceService)
    {
    }

    /**
     * @param  array  $data  validated public booking data
     * @return array{0: \App\Models\Booking, 1: array}
     * @throws ValidationException
     */
    public function create(Listing $listing, array $data): array
    {
        $arrival = Carbon::parse($data['arrival']);
        $departure = Carbon::parse($data['departure']);

        $overlapExists = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', BookingStatus::blocking())
            ->where(function ($q) use ($arrival, $departure) {
                $q->where('arrival', '<', $departure)
                    ->where('departure', '>', $arrival);
            })
            ->exists();

        if ($overlapExists) {
            throw ValidationException::withMessages([
                'arrival' => 'Las fechas seleccionadas ya no están disponibles. Por favor, elige otro rango.',
            ]);
        }

        $quote = $this->priceService->quote(
            $listing,
            $arrival->toDateString(),
            $departure->toDateString(),
            (int) $data['guests']
        );

        $booking = Booking::create([
            'listing_id'                         => $listing->id,
            'customer_name'                      => $data['customer_name'],
            'customer_first_name'                => $data['customer_first_name'],
            'customer_first_surname'             => $data['customer_first_surname'],
            'customer_second_surname'            => $data['customer_second_surname'] ?? null,
            'customer_document_type'             => $data['customer_document_type'],
            'customer_document_number'           => $data['customer_document_number'],
            'customer_document_support_number'   => $data['customer_document_support_number'] ?? null,
            'customer_birthdate'                 => $data['customer_birthdate'],
            'customer_birth_country'             => $data['customer_birth_country'],
            'customer_address_street'            => $data['customer_address_street'],
            'customer_address_number'            => $data['customer_address_number'] ?? null,
            'customer_address_city'              => $data['customer_address_city'],
            'customer_address_province'          => $data['customer_address_province'],
            'customer_address_country'           => $data['customer_address_country'],
            'customer_email'                     => $data['customer_email'],
            'customer_phone'                     => $data['customer_phone'] ?? null,
            'arrival'                            => $arrival,
            'departure'                          => $departure,
            'guests'                             => (int) $data['guests'],
            'status'                             => BookingStatus::Pending->value,
            'total'                              => $quote['total'],
            'source'                             => $data['source'] ?? 'web',
            'notes'                              => $data['notes'] ?? null,
            'terms_accepted_at'                  => now(),
        ]);

        $booking->load('listing');

        Mail::to($booking->customer_email)
            ->send(new PublicBookingHoldCustomerMail($booking, $quote));

        $ownerEmail = config('villa.owner_email');
        if ($ownerEmail) {
            $adminUrl = url("/admin/bookings/{$booking->id}/edit");
            Mail::to($ownerEmail)
                ->send(new PublicBookingHoldOwnerMail($booking, $quote, $adminUrl));
        }

        return [$booking, $quote];
    }
}
