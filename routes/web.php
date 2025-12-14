<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestPortalController;
use App\Http\Controllers\PublicListingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReservationController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Home “bonita” de Villa Mila
Route::get('/', [LandingController::class, 'home'])
    ->name('home');

Route::get('/reservar', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservar', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservar/confirmada/{token}', [ReservationController::class, 'success'])->name('reservations.success');

// (Opcional) index multi-inmueble para tu futuro proyecto
Route::get('/demo/listings', [PublicListingController::class, 'index'])
    ->name('public.listings.index');


Route::get('/villa/{listing:slug}', [PublicListingController::class, 'show'])
    ->name('public.listing.show');

// Portal del huésped
Route::get('/mi-reserva/{token}', [GuestPortalController::class, 'show'])->name('guest.portal.show');
Route::post('/mi-reserva/{token}/viajeros', [GuestPortalController::class, 'storeGuest'])->name('guest.portal.guests.store');
Route::patch('/mi-reserva/{token}/viajeros/{guest}', [GuestPortalController::class, 'updateGuest'])->name('guest.portal.guests.update');
Route::delete('/mi-reserva/{token}/viajeros/{guest}', [GuestPortalController::class, 'destroyGuest'])->name('guest.portal.guests.destroy');
