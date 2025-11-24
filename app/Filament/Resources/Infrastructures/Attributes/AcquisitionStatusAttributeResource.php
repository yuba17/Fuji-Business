<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateAcquisitionStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditAcquisitionStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListAcquisitionStatusAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewAcquisitionStatusAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\AcquisitionStatusAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\AcquisitionStatusAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\AcquisitionStatusAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcquisitionStatusAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 2;

    protected static string $attributeType = 'acquisition_status';

    public static function getModelLabel(): string
    {
        return 'Estado de Adquisición';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Estados de Adquisición';
    }

    public static function form(Schema $schema): Schema
    {
        return AcquisitionStatusAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AcquisitionStatusAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcquisitionStatusAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcquisitionStatusAttributes::route('/'),
            'create' => CreateAcquisitionStatusAttribute::route('/create'),
            'view' => ViewAcquisitionStatusAttribute::route('/{record}'),
            'edit' => EditAcquisitionStatusAttribute::route('/{record}/edit'),
        ];
    }
}

