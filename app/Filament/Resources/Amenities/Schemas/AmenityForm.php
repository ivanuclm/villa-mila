<?php

namespace App\Filament\Resources\Amenities\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class AmenityForm
{
    public static function configure(Schema $schema): Schema
    {
        // return $schema
        //     ->components([
        //         TextInput::make('name')
        //             ->required(),
        //     ]);
    
        return $schema->schema([
            TextInput::make('name')
                ->label('Nombre del servicio')
                ->required()
                ->maxLength(100),
        ]);
    }
}
