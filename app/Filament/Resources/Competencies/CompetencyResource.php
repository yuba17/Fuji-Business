<?php

namespace App\Filament\Resources\Competencies;

use App\Filament\Resources\Competencies\Pages\CreateCompetency;
use App\Filament\Resources\Competencies\Pages\EditCompetency;
use App\Filament\Resources\Competencies\Pages\ListCompetencies;
use App\Filament\Resources\Competencies\Pages\ViewCompetency;
use App\Filament\Resources\Competencies\Schemas\CompetencyForm;
use App\Filament\Resources\Competencies\Schemas\CompetencyInfolist;
use App\Filament\Resources\Competencies\Tables\CompetenciesTable;
use App\Models\Competency;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetencyResource extends Resource
{
    protected static ?string $model = Competency::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static UnitEnum|string|null $navigationGroup = 'Desarrollo Interno';
    
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CompetencyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CompetencyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetenciesTable::configure($table);
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
            'index' => ListCompetencies::route('/'),
            'create' => CreateCompetency::route('/create'),
            'view' => ViewCompetency::route('/{record}'),
            'edit' => EditCompetency::route('/{record}/edit'),
        ];
    }
}
