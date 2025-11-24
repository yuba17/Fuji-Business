<?php

namespace App\Filament\Resources\Infrastructures\Attributes;

use App\Filament\Resources\Infrastructures\Attributes\Pages\CreateProviderAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\EditProviderAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ListProviderAttributes;
use App\Filament\Resources\Infrastructures\Attributes\Pages\ViewProviderAttribute;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\ProviderAttributeForm;
use App\Filament\Resources\Infrastructures\Attributes\Schemas\ProviderAttributeInfolist;
use App\Filament\Resources\Infrastructures\Attributes\Tables\ProviderAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProviderAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Configuración de Infraestructura';
    
    protected static ?int $navigationSort = 6;

    protected static string $attributeType = 'provider';

    public static function getModelLabel(): string
    {
        return 'Proveedor';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Proveedores';
    }

    public static function form(Schema $schema): Schema
    {
        return ProviderAttributeForm::configure($schema, static::$attributeType);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProviderAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProviderAttributesTable::configure($table, static::$attributeType);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProviderAttributes::route('/'),
            'create' => CreateProviderAttribute::route('/create'),
            'view' => ViewProviderAttribute::route('/{record}'),
            'edit' => EditProviderAttribute::route('/{record}/edit'),
        ];
    }
}

