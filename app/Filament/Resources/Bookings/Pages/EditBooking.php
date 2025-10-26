<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\BookingPriceService;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recalculate')
                ->label('Recalcular precio')
                ->icon('heroicon-o-calculator')
                ->action(function () {
                    $booking = $this->record;
                    $svc = app(BookingPriceService::class);
                    $q = $svc->quote($booking->listing, $booking->arrival, $booking->departure, $booking->guests);

                    $booking->update(['total' => $q['total']]);
                    $this->record->refresh(); // PARA REFRESCAR EL FORMULARIO
                    $this->fillForm(); // PARA REFRESCAR EL FORMULARIO

                    Notification::make()
                        ->title("Total actualizado: €{$q['total']}")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(),

            DeleteAction::make(),
        ];
    }
}
