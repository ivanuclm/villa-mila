<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enums\BookingStatus;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('booking-tabs')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Datos de la reserva')
                        ->schema([
                            Section::make('Alojamiento y estado')
                                ->columns(2)
                                ->schema([
                                    Select::make('listing_id')
                                        ->relationship('listing', 'name')
                                        ->required()
                                        ->label('Alojamiento'),
                                    Select::make('status')
                                        ->options(BookingStatus::labels())
                                        ->default(BookingStatus::Pending->value)
                                        ->required()
                                        ->native(false)
                                        ->label('Estado'),
                                    TextInput::make('source')
                                        ->default('web')
                                        ->label('Origen')
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Contacto principal')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('customer_name')
                                        ->required()
                                        ->label('Nombre cliente'),
                                    TextInput::make('customer_email')
                                        ->email()
                                        ->required()
                                        ->label('Email'),
                                    TextInput::make('customer_phone')
                                        ->label('Teléfono cliente')
                                        ->tel()
                                        ->maxLength(30)
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Datos del representante / firmante')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('customer_first_name')->label('Nombre')->columnSpan(1),
                                    TextInput::make('customer_first_surname')->label('Primer apellido')->columnSpan(1),
                                    TextInput::make('customer_second_surname')->label('Segundo apellido')->columnSpan(1),
                                    Select::make('customer_document_type')
                                        ->label('Tipo documento')
                                        ->options([
                                            'dni' => 'DNI',
                                            'nie' => 'NIE',
                                            'passport' => 'Pasaporte',
                                            'other' => 'Otro',
                                        ])
                                        ->searchable()
                                        ->native(false),
                                    TextInput::make('customer_document_number')->label('Nº documento'),
                                    TextInput::make('customer_document_support_number')->label('Nº de soporte'),
                                    DatePicker::make('customer_birthdate')->label('Fecha de nacimiento'),
                                    TextInput::make('customer_birth_country')->label('País de nacimiento'),
                                    TextInput::make('customer_address_country')->label('País de residencia'),
                                    TextInput::make('customer_address_street')->label('Calle y vía')->columnSpan(2),
                                    TextInput::make('customer_address_number')->label('Número')->columnSpan(1),
                                    TextInput::make('customer_address_city')->label('Ciudad')->columnSpan(1),
                                    TextInput::make('customer_address_province')->label('Provincia / Estado')->columnSpan(1),
                                ]),
                        ]),
                    Tab::make('Estancia y precio')
                        ->schema([
                            Section::make('Fechas y viajeros')
                                ->columns(2)
                                ->schema([
                                    DatePicker::make('arrival')->required()->label('Llegada'),
                                    DatePicker::make('departure')->required()->label('Salida'),
                                    TextInput::make('guests')
                                        ->numeric()
                                        ->minValue(1)
                                        ->label('Viajeros')
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Importes')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('total')->numeric()->prefix('€')->label('Total'),
                                ]),
                        ]),
                    Tab::make('Operativa y pagos')
                        ->schema([
                            Section::make('Pagos')
                                ->columns(2)
                                ->schema([
                                    Select::make('payment_method')
                                        ->options([
                                            'bank_transfer' => 'Transferencia bancaria',
                                            'cash' => 'Pago en mano',
                                            'card' => 'TPV / tarjeta',
                                            'other' => 'Otro método',
                                        ])
                                        ->label('Método de pago')
                                        ->native(false),
                                    DateTimePicker::make('payment_received_at')
                                        ->label('Pago recibido')
                                        ->seconds(false),
                                    Textarea::make('payment_notes')
                                        ->label('Notas internas sobre el pago')
                                        ->rows(2)
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Checklist interno')
                                ->schema([
                                    CheckboxList::make('operations_checklist')
                                        ->label('Hitos completados')
                                        ->options([
                                            'payment_confirmed' => 'Pago confirmado',
                                            'traveler_forms' => 'Fichas de viajeros completas',
                                            'contract_signed' => 'Contrato firmado',
                                            'cleaning_notified' => 'Limpieza / mantenimiento avisado',
                                        ])
                                        ->columns(2)
                                        ->helperText('Marca los hitos completados para habilitar las siguientes fases.'),
                                ]),
                        ]),
                    Tab::make('Notas y documentación')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Textarea::make('notes')
                                        ->label('Notas del huésped / comentarios')
                                        ->rows(5),
                                ]),
                        ]),
                ]),
        ]);
    }
}
