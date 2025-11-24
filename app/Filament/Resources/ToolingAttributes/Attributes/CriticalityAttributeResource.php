<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes;

use App\Filament\Resources\ToolingAttributes\Attributes\Pages\CreateCriticalityAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\EditCriticalityAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ListCriticalityAttributes;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ViewCriticalityAttribute;
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

class CriticalityAttributeResource extends Resource
{
    protected static ?string $model = ToolingAttribute::class;
    
    public static function getModel(): string
    {
        return ToolingAttribute::class;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de I+D & Tooling';
    
    protected static ?int $navigationSort = 3;

    protected static string $attributeType = 'criticality';

    public static function getModelLabel(): string
    {
        return 'Criticidad';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Niveles de Criticidad';
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
            'index' => ListCriticalityAttributes::route('/'),
            'create' => CreateCriticalityAttribute::route('/create'),
            'view' => ViewCriticalityAttribute::route('/{record}'),
            'edit' => EditCriticalityAttribute::route('/{record}/edit'),
        ];
    }
}

