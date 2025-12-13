<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker as FilterDatePicker;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('arrival')
            ->columns([
                TextColumn::make('listing.name')
                    ->label('Alojamiento')
                    ->description(fn (Booking $record) => $record->source ? 'Canal: ' . $record->source : null)
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->description(fn (Booking $record) => $record->customer_phone ?: $record->customer_email)
                    ->searchable()
                    ->wrap(),
                TextColumn::make('arrival')
                    ->date('d M')
                    ->label('Llegada')
                    ->sortable()
                    ->description(fn (Booking $record) => 'Salida ' . $record->departure?->format('d M')),
                TextColumn::make('guests')
                    ->label('Viajeros')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'hold',
                        'success' => 'confirmed',
                        'danger'  => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Pendiente',
                        'hold' => 'Bloqueo',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->label('Estado'),
                TextColumn::make('total')->money('eur')->label('Total'),
            ])
            ->filters([
                Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending'   => 'Pendiente',
                        'hold'      => 'Bloqueo',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                    ]),
                Filters\Filter::make('arrival_between')
                    ->form([
                        FilterDatePicker::make('from')->label('Desde'),
                        FilterDatePicker::make('until')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('arrival', '>=', $d))
                            ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('arrival', '<=', $d));
                    })
                    ->label('Llegadas entre'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->label('Abrir ficha'),
                    Action::make('confirm')
                        ->label('Marcar confirmada')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status !== 'confirmed')
                        ->action(fn (Booking $record) => $record->update(['status' => 'confirmed'])),
                    Action::make('pend')
                        ->label('Mover a pendiente')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status !== 'pending')
                        ->action(fn (Booking $record) => $record->update(['status' => 'pending'])),
                    Action::make('cancel')
                        ->label('Cancelar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status !== 'cancelled')
                        ->action(fn (Booking $record) => $record->update(['status' => 'cancelled'])),
                ])->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('arrival', 'asc');
    }
}
