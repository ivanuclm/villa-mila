<?php

namespace App\Http\Controllers;

use App\Models\{Listing, Booking};
use App\Services\BookingPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicListingController extends Controller
{
    public function show(Listing $listing)
    {
        // Datos básicos para la vista
        return view('listings.show', [
            'listing' => $listing->only([
                'id','name','slug','address','license_number','max_guests','description','lat','lng'
            ]),
        ]);
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
}
