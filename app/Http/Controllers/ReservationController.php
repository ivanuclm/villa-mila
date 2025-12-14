<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicBookingRequest;
use App\Models\Listing;
use App\Services\BookingPriceService;
use App\Services\PublicBookingCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    protected function villa(): Listing
    {
        return Listing::where('slug', 'villa-mila')->firstOrFail();
    }

    public function create(Request $request, BookingPriceService $priceService)
    {
        $listing = $this->villa();

        $arrivalInput = old('arrival', $request->query('arrival'));
        $departureInput = old('departure', $request->query('departure'));
        $guests = (int) old('guests', $request->query('guests', 2));

        $arrival = $this->parseDate($arrivalInput);
        $departure = $this->parseDate($departureInput);

        $quote = null;
        if ($arrival && $departure) {
            try {
                $quote = $priceService->quote(
                    $listing,
                    $arrival->toDateString(),
                    $departure->toDateString(),
                    $guests
                );
            } catch (\Throwable) {
                $quote = null;
            }
        }

        return view('reservations.create', [
            'listing' => $listing,
            'initial' => [
                'arrival' => $arrival?->toDateString(),
                'departure' => $departure?->toDateString(),
                'guests' => $guests,
            ],
            'quote' => $quote,
        ]);
    }

    public function store(StorePublicBookingRequest $request, PublicBookingCreator $creator)
    {
        $listing = $this->villa();

        try {
            [$booking, $quote] = $creator->create($listing, $request->validated());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('reservations.success', $booking->public_access_token);
    }

    public function success(string $token)
    {
        $booking = $this->villa()
            ->bookings()
            ->where('public_access_token', $token)
            ->with('listing')
            ->firstOrFail();

        return view('reservations.confirmation', [
            'booking' => $booking,
        ]);
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
