<?php

namespace App\Filament\Resources\Risks;

use App\Filament\Resources\Risks\Pages\CreateRisk;
use App\Filament\Resources\Risks\Pages\EditRisk;
use App\Filament\Resources\Risks\Pages\ListRisks;
use App\Filament\Resources\Risks\Schemas\RiskForm;
use App\Filament\Resources\Risks\Tables\RisksTable;
use App\Models\Risk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class RiskResource extends Resource
{
    protected static ?string $model = Risk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Riesgos';
    
    protected static string|UnitEnum|null $navigationGroup = 'Gestión Estratégica';
    
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RiskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RisksTable::configure($table);
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
            'index' => ListRisks::route('/'),
            'create' => CreateRisk::route('/create'),
            'edit' => EditRisk::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
