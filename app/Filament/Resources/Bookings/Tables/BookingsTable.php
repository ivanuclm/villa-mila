<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enums\BookingStatus;
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
            ->recordClasses(fn (Booking $record) => match ($record->status?->value ?? (string) $record->status) {
                BookingStatus::Pending->value => 'bg-amber-50/70 dark:bg-amber-500/10',
                BookingStatus::Hold->value => 'bg-sky-50/70 dark:bg-sky-500/10',
                BookingStatus::Confirmed->value => 'bg-emerald-50/70 dark:bg-emerald-500/10',
                BookingStatus::InStay->value => 'bg-indigo-50/70 dark:bg-indigo-500/10',
                BookingStatus::Completed->value => 'bg-slate-50/70 dark:bg-slate-500/10',
                BookingStatus::Cancelled->value => 'bg-rose-50/70 dark:bg-rose-500/10',
                default => '',
            })
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
                        'warning' => BookingStatus::Pending->value,
                        'info' => BookingStatus::Hold->value,
                        'success' => BookingStatus::Confirmed->value,
                        'primary' => BookingStatus::InStay->value,
                        'gray' => BookingStatus::Completed->value,
                        'danger' => BookingStatus::Cancelled->value,
                    ])
                    ->formatStateUsing(function ($state) {
                        $status = $state instanceof BookingStatus ? $state : BookingStatus::tryFrom($state);

                        return $status?->label() ?? ucfirst((string) $state);
                    })
                    ->label('Estado'),
                TextColumn::make('total')->money('eur')->label('Total'),
            ])
            ->filters([
                Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(BookingStatus::labels()),
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
                    Action::make('markPending')
                        ->label('Volver a pendiente')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status?->value !== BookingStatus::Pending->value)
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::Pending->value])),
                    Action::make('markHold')
                        ->label('Bloquear fechas')
                        ->icon('heroicon-o-pause-circle')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status?->value !== BookingStatus::Hold->value)
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::Hold->value])),
                    Action::make('markConfirmed')
                        ->label('Marcar confirmada')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => ! in_array($record->status?->value, [
                            BookingStatus::Confirmed->value,
                            BookingStatus::InStay->value,
                            BookingStatus::Completed->value,
                        ], true))
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::Confirmed->value])),
                    Action::make('markInStay')
                        ->label('Registrar check-in')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status?->value === BookingStatus::Confirmed->value)
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::InStay->value])),
                    Action::make('markCompleted')
                        ->label('Finalizar estancia')
                        ->icon('heroicon-o-flag')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status?->value === BookingStatus::InStay->value)
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::Completed->value])),
                    Action::make('cancel')
                        ->label('Cancelar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Booking $record) => $record->status?->value !== BookingStatus::Cancelled->value)
                        ->action(fn (Booking $record) => $record->update(['status' => BookingStatus::Cancelled->value])),
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
