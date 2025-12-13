<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class UpcomingBookingsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Próximas reservas';
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Booking::query()
            ->whereDate('departure', '>=', now())
            ->orderBy('arrival');
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25];
    }

    protected function getDefaultTableRecordsPerPage(): int
    {
        return 5;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('arrival')
                ->label('Llegada')
                ->date('d M')
                ->sortable()
                ->description(fn (Booking $record) => 'Salida ' . $record->departure?->format('d M')),
            TextColumn::make('customer_name')
                ->label('Nombre')
                ->description(fn (Booking $record) => $record->customer_phone ?: $record->customer_email)
                ->wrap(),
            TextColumn::make('listing.name')
                ->label('Alojamiento')
                ->wrap(),
            BadgeColumn::make('status')
                ->label('Estado')
                ->colors([
                    'warning' => 'pending',
                    'info' => 'hold',
                    'success' => 'confirmed',
                    'danger' => 'cancelled',
                ])
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'pending' => 'Pendiente',
                    'hold' => 'Bloqueo',
                    'confirmed' => 'Confirmada',
                    'cancelled' => 'Cancelada',
                    default => $state,
                }),
            TextColumn::make('guests')
                ->label('Personas')
                ->alignCenter(),
        ];
    }
}