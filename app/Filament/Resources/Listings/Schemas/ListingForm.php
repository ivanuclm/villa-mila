<?php

namespace App\Filament\Resources\Listings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

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

                        FileUpload::make('cover_image_path')
                            ->label('Imagen de portada')
                            ->disk('public')
                            ->directory('listings/covers')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->helperText('Se usará en correos, landing y portal del huésped.')
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

                Section::make('Contrato y documentación legal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contract_owner_name')
                            ->label('Nombre completo del propietario para el contrato')
                            ->maxLength(255),
                        TextInput::make('contract_owner_document')
                            ->label('Documento del propietario (DNI, NIE, etc.)')
                            ->maxLength(255),
                        TextInput::make('contract_owner_address')
                            ->label('Domicilio a efectos de notificación')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('contract_full_text')
                            ->label('Texto completo del contrato')
                            ->rows(20)
                            ->helperText('Puedes personalizar la plantilla oficial de Milagros. Si lo dejas vacío se usará la definida en config/villa.php.')
                            ->default(config('villa.contract_template'))
                            ->columnSpanFull(),
                        TextInput::make('cadastral_reference')
                            ->label('Referencia catastral')
                            ->maxLength(255),
                        TextInput::make('municipal_registry_code')
                            ->label('Código seguro verificación municipal')
                            ->maxLength(255),
                        TextInput::make('clm_registry_number')
                            ->label('Registro CLM (Empresas y Establecimientos)')
                            ->maxLength(255),
                        TextInput::make('nra_registry_number')
                            ->label('NRA registro único de arrendamiento')
                            ->maxLength(255),
                        TextInput::make('ccaa_license_code')
                            ->label('Nº de licencia CCAA (TURO)')
                            ->maxLength(255),
                        TextInput::make('extra_beds')
                            ->numeric()
                            ->minValue(0)
                            ->label('Camas supletorias disponibles'),
                        TextInput::make('cribs')
                            ->numeric()
                            ->minValue(0)
                            ->label('Cunas disponibles'),
                    ]),
            ]);
    }
}
