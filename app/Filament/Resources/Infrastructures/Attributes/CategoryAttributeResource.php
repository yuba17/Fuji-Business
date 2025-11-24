<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateCategoryAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditCategoryAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListCategoryAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewCategoryAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\CategoryAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\CategoryAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\CategoryAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoryAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 4;

    protected static string $attributeType = 'category';

    public static function getModelLabel(): string
    {
        return 'Categoría de Infraestructura';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Categorías de Infraestructura';
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryAttributes::route('/'),
            'create' => CreateCategoryAttribute::route('/create'),
            'view' => ViewCategoryAttribute::route('/{record}'),
            'edit' => EditCategoryAttribute::route('/{record}/edit'),
        ];
    }
}

