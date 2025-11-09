<?php

namespace App\Filament\Resources\PriceRules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
class PriceRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->schema([
            Section::make('Regla de precio')->columns(3)->schema([
                Select::make('listing_id')
                    ->relationship('listing','name')
                    ->required()->label('Alojamiento')->columnSpan(1),

                Select::make('season_id')
                    ->relationship('season','name')
                    ->label('Temporada')
                    ->helperText('Vacío = aplica todo el año')
                    ->searchable()->preload()->columnSpan(1),

                Select::make('dow')
                    ->label('Día de semana')
                    ->options([
                        1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',
                        5=>'Viernes',6=>'Sábado',7=>'Domingo',
                    ])
                    ->nullable()
                    ->helperText('Vacío = todos los días')
                    ->columnSpan(1),

                TextInput::make('price_per_night')->numeric()->required()->prefix('€')->label('Precio/noche')->columnSpan(1),
                TextInput::make('min_nights')->numeric()->minValue(1)->default(1)->label('Mín. noches')->columnSpan(1),
                TextInput::make('cleaning_fee')->numeric()->default(0)->prefix('€')->label('Limpieza')->columnSpan(1),

                TextInput::make('included_guests')->numeric()->minValue(1)->default(2)->label('Viajeros incluidos')->columnSpan(1),
                TextInput::make('extra_guest_fee')->numeric()->default(0)->prefix('€')->label('Extra por viajero')->columnSpan(1),

                Toggle::make('is_override')->label('Prioritaria (override)')->inline(false)->columnSpanFull(),
            ]),
        ]);
    }
}
