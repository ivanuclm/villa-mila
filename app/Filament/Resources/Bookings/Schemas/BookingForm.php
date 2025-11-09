<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                Select::make('listing_id')
                    ->relationship('listing', 'name')
                    ->required()
                    ->label('Alojamiento'),

                TextInput::make('customer_name')->required()->label('Nombre cliente'),
                TextInput::make('customer_email')->email()->required()->label('Email'),

                DatePicker::make('arrival')->required()->label('Llegada'),
                DatePicker::make('departure')->required()->label('Salida'),

                TextInput::make('guests')->numeric()->minValue(1)->label('Viajeros'),

                Select::make('status')
                    ->options([
                        'pending'   => 'Pendiente',
                        'hold'      => 'Bloqueo',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->required()
                    ->native(false)
                    ->label('Estado'),

                TextInput::make('total')->numeric()->prefix('€')->label('Total'),
                TextInput::make('source')->default('web')->label('Origen'),
            ]);
    }
}
