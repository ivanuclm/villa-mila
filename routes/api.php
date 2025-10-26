<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicListingController;

Route::get('/listings/{listing:slug}/unavailable-dates', [PublicListingController::class, 'unavailableDates']);
Route::post('/listings/{listing:slug}/quote', [PublicListingController::class, 'quote']);
