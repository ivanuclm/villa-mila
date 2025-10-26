<?php

namespace App\Filament\Resources\Seasons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class SeasonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Temporada')->columns(2)->schema([
                Select::make('listing_id')
                    ->relationship('listing','name')
                    ->required()->label('Alojamiento'),
                TextInput::make('name')->label('Nombre')->maxLength(100),
                DatePicker::make('start_date')->required()->label('Desde'),
                DatePicker::make('end_date')->required()->label('Hasta'),
            ]),
        ]);
    }
}
