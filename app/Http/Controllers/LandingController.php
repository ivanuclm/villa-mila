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
        $heroImage = $listing->cover_url ?? asset('images/PORTADA.jpg');

        return view('landing.villa-mila', [
            'listing' => $listing,
            'heroImage' => $heroImage
        ]);
    }

}
