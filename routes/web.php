<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicListingController;
use App\Http\Controllers\LandingController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Home “bonita” de Villa Mila
Route::get('/', [LandingController::class, 'home'])
    ->name('home');

// Página de reserva (usa el widget que ya tienes)
Route::get('/reserva', [LandingController::class, 'booking'])
    ->name('public.booking');

// (Opcional) index multi-inmueble para tu futuro proyecto
Route::get('/demo/listings', [PublicListingController::class, 'index'])
    ->name('public.listings.index');


Route::get('/villa/{listing:slug}', [PublicListingController::class, 'show'])
    ->name('public.listing.show');