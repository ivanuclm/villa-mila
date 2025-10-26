<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PublicListingController;

Route::get('/villa/{listing:slug}', [PublicListingController::class, 'show'])
    ->name('public.listing.show');