<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes;

use App\Filament\Resources\ToolingAttributes\Attributes\Pages\CreateMilestoneStatusAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\EditMilestoneStatusAttribute;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ListMilestoneStatusAttributes;
use App\Filament\Resources\ToolingAttributes\Attributes\Pages\ViewMilestoneStatusAttribute;
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

class MilestoneStatusAttributeResource extends Resource
{
    protected static ?string $model = ToolingAttribute::class;
    
    public static function getModel(): string
    {
        return ToolingAttribute::class;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de I+D & Tooling';
    
    protected static ?int $navigationSort = 6;

    protected static string $attributeType = 'milestone_status';

    public static function getModelLabel(): string
    {
        return 'Estado de Hito';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Estados de Hitos';
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
            'index' => ListMilestoneStatusAttributes::route('/'),
            'create' => CreateMilestoneStatusAttribute::route('/create'),
            'view' => ViewMilestoneStatusAttribute::route('/{record}'),
            'edit' => EditMilestoneStatusAttribute::route('/{record}/edit'),
        ];
    }
}

