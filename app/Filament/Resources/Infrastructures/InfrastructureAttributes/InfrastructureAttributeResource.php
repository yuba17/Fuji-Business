<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes;

use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages\CreateInfrastructureAttribute;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages\EditInfrastructureAttribute;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages\ListInfrastructureAttributes;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages\ViewInfrastructureAttribute;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Schemas\InfrastructureAttributeForm;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Schemas\InfrastructureAttributeInfolist;
use App\Filament\Resources\Infrastructures\InfrastructureAttributes\Tables\InfrastructureAttributesTable;
use App\Models\InfrastructureAttribute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
class InfrastructureAttributeResource extends Resource
{
    protected static ?string $model = InfrastructureAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Desarrollo Interno';
    
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Oculto porque ahora tenemos recursos separados
    }

    public static function form(Schema $schema): Schema
    {
        return InfrastructureAttributeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InfrastructureAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfrastructureAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInfrastructureAttributes::route('/'),
            'create' => CreateInfrastructureAttribute::route('/create'),
            'view' => ViewInfrastructureAttribute::route('/{record}'),
            'edit' => EditInfrastructureAttribute::route('/{record}/edit'),
        ];
    }
}
