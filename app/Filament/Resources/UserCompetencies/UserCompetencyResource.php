<?php

namespace App\Filament\Resources\UserCompetencies;

use App\Filament\Resources\UserCompetencies\Pages\CreateUserCompetency;
use App\Filament\Resources\UserCompetencies\Pages\EditUserCompetency;
use App\Filament\Resources\UserCompetencies\Pages\ListUserCompetencies;
use App\Filament\Resources\UserCompetencies\Pages\ViewUserCompetency;
use App\Filament\Resources\UserCompetencies\Schemas\UserCompetencyForm;
use App\Filament\Resources\UserCompetencies\Schemas\UserCompetencyInfolist;
use App\Filament\Resources\UserCompetencies\Tables\UserCompetenciesTable;
use App\Models\UserCompetency;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserCompetencyResource extends Resource
{
    protected static ?string $model = UserCompetency::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static UnitEnum|string|null $navigationGroup = 'Competencias';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = null;
    
    public static function getModelLabel(): string
    {
        return 'Evaluación de Competencia';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Evaluaciones de Competencias';
    }

    public static function form(Schema $schema): Schema
    {
        return UserCompetencyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserCompetencyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserCompetenciesTable::configure($table);
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
            'index' => ListUserCompetencies::route('/'),
            'create' => CreateUserCompetency::route('/create'),
            'view' => ViewUserCompetency::route('/{record}'),
            'edit' => EditUserCompetency::route('/{record}/edit'),
        ];
    }
}
