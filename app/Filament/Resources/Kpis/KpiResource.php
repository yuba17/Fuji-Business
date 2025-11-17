<?php

namespace App\Filament\Resources\Kpis;

use App\Filament\Resources\Kpis\Pages\CreateKpi;
use App\Filament\Resources\Kpis\Pages\EditKpi;
use App\Filament\Resources\Kpis\Pages\ListKpis;
use App\Filament\Resources\Kpis\Schemas\KpiForm;
use App\Filament\Resources\Kpis\Tables\KpisTable;
use App\Models\Kpi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KpiResource extends Resource
{
    protected static ?string $model = Kpi::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'KPIs';
    
    protected static string|UnitEnum|null $navigationGroup = 'Gestión Estratégica';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return KpiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KpisTable::configure($table);
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
            'index' => ListKpis::route('/'),
            'create' => CreateKpi::route('/create'),
            'edit' => EditKpi::route('/{record}/edit'),
        ];
    }
}
