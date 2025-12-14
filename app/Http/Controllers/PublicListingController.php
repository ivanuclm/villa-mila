<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\{Listing, Booking};
use App\Services\BookingPriceService;
use App\Services\PublicBookingCreator;
use App\Http\Requests\StorePublicBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

class PublicListingController extends Controller
{
    public function show(Listing $listing)
    {
        // Datos básicos para la vista
        return view('listings.show', [
            'listing' => $listing->only([
                'id',
                'name',
                'slug',
                'address',
                'license_number',
                'max_guests',
                'description',
                'lat',
                'lng',
                'cover_image_path',
            ]),
        ]);
    }

    public function index()
    {
        // Más adelante podrás filtrar por "publicado", orden por prioridad, etc.
        $listings = Listing::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'address',
                'license_number',
                'max_guests',
                'description',
            ]);

        return view('listings.index', compact('listings'));
    }

    public function unavailableDates(Listing $listing)
    {
        // Ocupar: estados que bloquean calendario
        $bookings = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', BookingStatus::blocking())
            ->get(['arrival','departure']);

        $dates = [];
        foreach ($bookings as $b) {
            $start = Carbon::parse($b->arrival);
            $end   = Carbon::parse($b->departure)->subDay(); // noches
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $dates[] = $d->toDateString();
            }
        }

        return response()->json(['unavailable' => array_values(array_unique($dates))]);
    }

    public function quote(Request $request, Listing $listing, BookingPriceService $svc)
    {
        $data = $request->validate([
            'arrival'   => ['required','date'],
            'departure' => ['required','date','after:arrival'],
            'guests'    => ['required','integer','min:1'],
        ]);

        $quote = $svc->quote($listing, $data['arrival'], $data['departure'], (int)$data['guests']);

        return response()->json($quote);
    }

    public function monthPrices(Listing $listing, Request $request, BookingPriceService $svc)
    {
        $data = $request->validate([
            'month'  => ['required','date_format:Y-m'], // ej: 2025-11
            'guests' => ['nullable','integer','min:1'],
        ]);

        $guests = (int)($data['guests'] ?? 2);

        $start = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Fechas bloqueadas (confirmed/hold/in_stay)
        $bookings = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', BookingStatus::blocking())
            ->get(['arrival','departure']);

        $locked = [];
        foreach ($bookings as $b) {
            $a = Carbon::parse($b->arrival);
            $d = Carbon::parse($b->departure)->subDay();
            foreach (CarbonPeriod::create($a, $d) as $day) {
                $locked[$day->toDateString()] = true;
            }
        }

        $prices = [];
        foreach (CarbonPeriod::create($start, $end) as $day) {
            $date = $day->toDateString();

            // Bloquea pasado
            if ($day->isBefore(today())) {
                $prices[$date] = null;
                continue;
            }

            if (isset($locked[$date])) {
                $prices[$date] = null;
                continue;
            }

            // Precio por 1 noche [date, date+1)
            $q = $svc->quote($listing, $date, $day->copy()->addDay()->toDateString(), $guests);
            // $prices[$date] = $q['total']; // total (incluye limpieza)
            // $prices[$date] = $q['price_per_night']; // precio por noche
            $prices[$date] = $q['subtotal']; // subtotal (sin limpieza)
        }

        return response()->json(['prices' => $prices]);
    }

    public function book(StorePublicBookingRequest $request, Listing $listing, PublicBookingCreator $creator)
    {
        try {
            [$booking, $quote] = $creator->create($listing, $request->validated());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Las fechas seleccionadas ya no están disponibles. Por favor, selecciona otro rango.',
                'messages' => $e->errors(),
            ], 422);
        }

        return response()->json([
            'ok'      => true,
            'booking' => [
                'id'        => $booking->id,
                'status'    => $booking->status?->value,
                'arrival'   => $booking->arrival->toDateString(),
                'departure' => $booking->departure->toDateString(),
                'guests'    => $booking->guests,
                'total'     => $booking->total,
            ],
            'quote'   => $quote,
        ]);
    }
}
