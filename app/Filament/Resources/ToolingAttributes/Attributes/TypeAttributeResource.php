<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes;

use App\Filament\Resources\ToolingAttributes\Attributes\Pages\CreateTypeAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\EditTypeAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ListTypeAttributes;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ViewTypeAttribute;
use App\Filament\Resources\ToolingAttributes\Schemas\ToolingAttributeForm;
use App\Filament\Resources\ToolingAttributes\Schemas\ToolingAttributeInfolist;
use App\Filament\Resources\ToolingAttributes\Tables\ToolingAttributesTable;
use App\Models\ToolingAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeAttributeResource extends Resource
{
    protected static ?string $model = ToolingAttribute::class;
    
    public static function getModel(): string
    {
        return ToolingAttribute::class;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de I+D & Tooling';
    
    protected static ?int $navigationSort = 1;

    protected static string $attributeType = 'type';

    public static function getModelLabel(): string
    {
        return 'Tipo de Herramienta';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Tipos de Herramientas';
    }

    public static function form(Schema $schema): Schema
    {
        return ToolingAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ToolingAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ToolingAttributesTable::configure($table, static::$attributeType);
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

