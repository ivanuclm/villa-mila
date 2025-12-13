<?php

namespace App\Filament\Resources\PriceRules;

use App\Filament\Resources\PriceRules\Pages\CreatePriceRule;
use App\Filament\Resources\PriceRules\Pages\EditPriceRule;
use App\Filament\Resources\PriceRules\Pages\ListPriceRules;
use App\Filament\Resources\PriceRules\Schemas\PriceRuleForm;
use App\Filament\Resources\PriceRules\Tables\PriceRulesTable;
use App\Models\PriceRule;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PriceRuleResource extends Resource
{
    protected static ?string $model = PriceRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Reglas de Precio';
    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    public static function form(Schema $schema): Schema
    {
        return PriceRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PriceRulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPriceRules::route('/'),
            'create' => CreatePriceRule::route('/create'),
            'edit' => EditPriceRule::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Regla de Precio';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Reglas de Precio';
    }
}
