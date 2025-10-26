<?php

namespace App\Filament\Resources\PriceRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PriceRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('listing.name')->label('Alojamiento')->sortable()->searchable(),
                TextColumn::make('season.name')->label('Temporada')->placeholder('Global'),
                TextColumn::make('dow')->label('Día')->formatStateUsing(fn($d)=>[
                    1=>'Lun',2=>'Mar',3=>'Mié',4=>'Jue',5=>'Vie',6=>'Sáb',7=>'Dom'
                ][$d] ?? 'Todos'),
                TextColumn::make('price_per_night')->money('eur')->label('€/noche'),
                TextColumn::make('min_nights')->label('Mín'),
                TextColumn::make('cleaning_fee')->money('eur')->label('Limpieza'),
                TextColumn::make('included_guests')->label('Incl.'),
                TextColumn::make('extra_guest_fee')->money('eur')->label('€/extra'),
                IconColumn::make('is_override')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
