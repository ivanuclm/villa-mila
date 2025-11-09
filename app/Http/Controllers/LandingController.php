<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class LandingController extends Controller
{
    protected function villa(): Listing
    {
        // De momento asumimos que sólo hay una villa
        // y se llama "villa-mila". Si cambia, solo tocas aquí.
        return Listing::where('slug', 'villa-mila')->firstOrFail();
    }

    // Landing page
    public function home()
    {
        $listing = $this->villa();
        $heroImage = asset('images/PORTADA.jpg');

        return view('landing.villa-mila', [
            'listing' => $listing,
            'heroImage' => $heroImage
        ]);
    }

    // Página/sección de reserva reutilizando el widget actual
    public function booking()
    {
        $listing = $this->villa();

        // Reutilizamos el mismo formato que usabas en show.blade.php
        return view('listings.show', [
            'listing' => $listing->only([
                'id','name','slug','address','license_number','max_guests','description','lat','lng'
            ]),
        ]);
    }
}
