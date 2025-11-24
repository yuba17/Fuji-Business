<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateOperationalStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditOperationalStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListOperationalStatusAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewOperationalStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\OperationalStatusAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\OperationalStatusAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\OperationalStatusAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OperationalStatusAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 5;

    protected static string $attributeType = 'operational_status';

    public static function getModelLabel(): string
    {
        return 'Estado Operativo';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Estados Operativos';
    }

    public static function form(Schema $schema): Schema
    {
        return OperationalStatusAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OperationalStatusAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationalStatusAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperationalStatusAttributes::route('/'),
            'create' => CreateOperationalStatusAttribute::route('/create'),
            'view' => ViewOperationalStatusAttribute::route('/{record}'),
            'edit' => EditOperationalStatusAttribute::route('/{record}/edit'),
        ];
    }
}

