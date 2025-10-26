<?php

namespace App\Filament\Resources\Listings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;

class ListingForm
{
    public static function configure(Schema $schema): Schema
    {
        // return $schema
        //     ->components([
        //         TextInput::make('name')
        //             ->required(),
        //         TextInput::make('slug')
        //             ->required(),
        //         TextInput::make('description'),
        //         TextInput::make('license_number'),
        //         TextInput::make('address'),
        //         TextInput::make('lat')
        //             ->numeric(),
        //         TextInput::make('lng')
        //             ->numeric(),
        //         TextInput::make('max_guests')
        //             ->required()
        //             ->numeric()
        //             ->default(4),
        //         TimePicker::make('checkin_from'),
        //         TimePicker::make('checkout_until'),
        //     ]);
        return $schema
            ->columns(2)
            ->schema([
                Section::make('Datos básicos')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255)->label('Nombre del alojamiento'),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Slug (codigo sin espacios)'),
                        TextInput::make('license_number')->label('Licencia VUT'),

                        Textarea::make('description.es')
                            ->label('Descripción (ESPAÑOL)')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('address')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('lat')->numeric()->label('Latitud'),
                        TextInput::make('lng')->numeric()->label('Longitud'),
                        TextInput::make('max_guests')->numeric()->minValue(1)->label('Capacidad'),

                        TimePicker::make('checkin_from')->label('Check-in desde'),
                        TimePicker::make('checkout_until')->label('Check-out hasta'),
                    ]),

                Select::make('amenities')
                    ->relationship('amenities', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Servicios')
                    ->columnSpanFull(),

                // Placeholder para galería (cambiaremos luego por uploader)
                Textarea::make('gallery_notes')
                    ->helperText('Más adelante cambiaremos esto por un uploader con Media Library.')
                    ->columnSpanFull(),
            ]);
    }
}
