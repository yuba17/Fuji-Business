<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateTypeAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditTypeAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListTypeAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewTypeAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\TypeAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\TypeAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\TypeAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 3;

    protected static string $attributeType = 'type';

    public static function getModelLabel(): string
    {
        return 'Tipo de Infraestructura';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Tipos de Infraestructura';
    }

    public static function form(Schema $schema): Schema
    {
        return TypeAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TypeAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypeAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypeAttributes::route('/'),
            'create' => CreateTypeAttribute::route('/create'),
            'view' => ViewTypeAttribute::route('/{record}'),
            'edit' => EditTypeAttribute::route('/{record}/edit'),
        ];
    }
}

