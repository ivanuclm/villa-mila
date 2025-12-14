<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use App\Models\BookingGuest;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Schema;

class GuestEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'guestEntries';
    protected static ?string $title = 'Personas viajeras';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('full_name')->required()->maxLength(255)->label('Nombre completo'),
            Forms\Components\TextInput::make('document_number')->maxLength(100)->label('Documento'),
            Forms\Components\TextInput::make('nationality')->maxLength(100)->label('Nacionalidad'),
            Forms\Components\DatePicker::make('birthdate')->label('Nacimiento'),
            Forms\Components\TextInput::make('email')->email()->maxLength(255),
            Forms\Components\TextInput::make('phone')->maxLength(50)->label('Teléfono'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('document_number')->label('Documento')->toggleable(),
                Tables\Columns\TextColumn::make('nationality')->label('Nacionalidad')->toggleable(),
                Tables\Columns\TextColumn::make('email')->toggleable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->label('Añadido'),
                Tables\Columns\IconColumn::make('signature_path')
                    ->label('Firma')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir viajero')
                    ->visible(fn () => $this->ownerRecord->guestEntries()->count() < $this->ownerRecord->guests),
            ])
            ->actions([
                Action::make('ver_firma')
                    ->label('Ver firma')
                    ->icon('heroicon-o-eye')
                    ->visible(fn (BookingGuest $record) => filled($record->signature_path))
                    ->url(fn (BookingGuest $record) => $record->signature_path ? Storage::disk('public')->url($record->signature_path) : '#', shouldOpenInNewTab: true),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
