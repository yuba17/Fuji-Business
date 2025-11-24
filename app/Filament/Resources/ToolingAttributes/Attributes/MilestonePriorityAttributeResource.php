<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes;

use App\Filament\Resources\ToolingAttributes\Attributes\Pages\CreateMilestonePriorityAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\EditMilestonePriorityAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ListMilestonePriorityAttributes;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ViewMilestonePriorityAttribute;
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

class MilestonePriorityAttributeResource extends Resource
{
    protected static ?string $model = ToolingAttribute::class;
    
    public static function getModel(): string
    {
        return ToolingAttribute::class;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUp;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de I+D & Tooling';
    
    protected static ?int $navigationSort = 5;

    protected static string $attributeType = 'milestone_priority';

    public static function getModelLabel(): string
    {
        return 'Prioridad de Hito';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Niveles de Prioridad de Hitos';
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
            'index' => ListMilestonePriorityAttributes::route('/'),
            'create' => CreateMilestonePriorityAttribute::route('/create'),
            'view' => ViewMilestonePriorityAttribute::route('/{record}'),
            'edit' => EditMilestonePriorityAttribute::route('/{record}/edit'),
        ];
    }
}

