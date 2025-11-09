<?php

namespace App\Http\Controllers;

use App\Models\{Listing, Booking};
use App\Services\BookingPriceService;
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
                'lng'
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
        // Ocupar: confirmed + hold (bloqueos)
        $bookings = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', ['confirmed','hold'])
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

        // Fechas bloqueadas (confirmed/hold)
        $bookings = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', ['confirmed','hold'])
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

    public function book(StorePublicBookingRequest $request, Listing $listing, BookingPriceService $svc)
    {
        $data = $request->validated();

        // 1) Comprobar disponibilidad de nuevo en servidor
        $arrival   = Carbon::parse($data['arrival']);
        $departure = Carbon::parse($data['departure']);

        $overlapExists = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', ['confirmed', 'hold'])
            ->where(function ($q) use ($arrival, $departure) {
                // solape clásico: NOT (end <= A OR start >= B)
                $q->where('arrival', '<', $departure)
                  ->where('departure', '>', $arrival);
            })
            ->exists();

        if ($overlapExists) {
            return response()->json([
                'ok'    => false,
                'error' => 'Las fechas seleccionadas ya no están disponibles. Por favor, selecciona otro rango.',
            ], 422);
        }

        // 2) Recalcular precio con servicio (no confiamos en el cliente)
        $quote = $svc->quote(
            $listing,
            $arrival->toDateString(),
            $departure->toDateString(),
            (int) $data['guests']
        );

        // 3) Crear reserva en estado "hold"
        $booking = Booking::create([
            'listing_id'        => $listing->id,
            'customer_name'     => $data['customer_name'],
            'customer_email'    => $data['customer_email'],
            'customer_phone'    => $data['customer_phone'] ?? null,
            'arrival'           => $arrival,
            'departure'         => $departure,
            'guests'            => (int) $data['guests'],
            'status'            => 'hold',
            'total'             => $quote['total'],   // total final
            'source'            => 'web',
            'notes'             => $data['notes'] ?? null,
            'terms_accepted_at' => now(),
        ]);

        // 4) TODO (siguiente paso): mails a cliente y propietaria

        return response()->json([
            'ok'      => true,
            'booking' => [
                'id'        => $booking->id,
                'status'    => $booking->status,
                'arrival'   => $booking->arrival->toDateString(),
                'departure' => $booking->departure->toDateString(),
                'guests'    => $booking->guests,
                'total'     => $booking->total,
            ],
            'quote'   => $quote,
        ]);
    }
}
