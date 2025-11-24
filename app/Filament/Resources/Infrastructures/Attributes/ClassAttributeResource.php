<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateClassAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditClassAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListClassAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewClassAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\ClassAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\ClassAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\ClassAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClassAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 1;

    protected static string $attributeType = 'class';

    public static function getModelLabel(): string
    {
        return 'Clase de Infraestructura';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Clases de Infraestructura';
    }

    public static function form(Schema $schema): Schema
    {
        return ClassAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClassAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassAttributes::route('/'),
            'create' => CreateClassAttribute::route('/create'),
            'view' => ViewClassAttribute::route('/{record}'),
            'edit' => EditClassAttribute::route('/{record}/edit'),
        ];
    }
}

