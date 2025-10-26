<?php

namespace App\Filament\Resources\Listings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;


class ListingsTable
{
    public static function configure(Table $table): Table
    {
        // return $table
        //     ->columns([
        //         TextColumn::make('name')
        //             ->searchable(),
        //         TextColumn::make('slug')
        //             ->searchable(),
        //         TextColumn::make('license_number')
        //             ->searchable(),
        //         TextColumn::make('address')
        //             ->searchable(),
        //         TextColumn::make('lat')
        //             ->numeric()
        //             ->sortable(),
        //         TextColumn::make('lng')
        //             ->numeric()
        //             ->sortable(),
        //         TextColumn::make('max_guests')
        //             ->numeric()
        //             ->sortable(),
        //         TextColumn::make('checkin_from')
        //             ->time()
        //             ->sortable(),
        //         TextColumn::make('checkout_until')
        //             ->time()
        //             ->sortable(),
        //         TextColumn::make('created_at')
        //             ->dateTime()
        //             ->sortable()
        //             ->toggleable(isToggledHiddenByDefault: true),
        //         TextColumn::make('updated_at')
        //             ->dateTime()
        //             ->sortable()
        //             ->toggleable(isToggledHiddenByDefault: true),
        //     ])
        //     ->filters([
        //         //
        //     ])
        //     ->recordActions([
        //         EditAction::make(),
        //     ])
        //     ->toolbarActions([
        //         BulkActionGroup::make([
        //             DeleteBulkAction::make(),
        //         ]),
        //     ]);

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_number')
                    ->label('VUT')
                    ->toggleable(),
                TextColumn::make('address')
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('max_guests')
                    ->label('Cap.')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->since()
                    ->label('Creado'),
            ])
            ->filters([
                // por ahora ninguno
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
