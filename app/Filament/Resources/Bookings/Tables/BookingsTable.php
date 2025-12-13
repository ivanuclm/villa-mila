<?php

namespace App\Filament\Resources\Bookings\Tables;

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
            ->columns([
                TextColumn::make('listing.name')->label('Alojamiento')->searchable()->sortable(),
                TextColumn::make('customer_name')->label('Cliente')->searchable(),
                TextColumn::make('customer_email')->label('Email')->searchable(),
                TextColumn::make('customer_phone')->label('Teléfono')->searchable(),
                TextColumn::make('arrival')->date()->label('Llegada')->sortable(),
                TextColumn::make('departure')->date()->label('Salida')->sortable(),
                TextColumn::make('guests')->label('Viajeros'),
                TextColumn::make('status')
                ->label('Estado')
                ->badge()
                ->formatStateUsing(fn (string $state) => [
                    'pending' => 'Pendiente',
                    'hold' => 'Bloqueo',
                    'confirmed' => 'Confirmada',
                    'cancelled' => 'Cancelada',
                ][$state] ?? $state)
                ->colors([
                    'warning' => 'pending',
                    'info'    => 'hold',
                    'success' => 'confirmed',
                    'danger'  => 'cancelled',
                ]),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('arrival', 'asc');
    }
}
