<?php

namespace App\Filament\Resources\UserCompetencies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserCompetencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('competency_id')
                    ->label('Competencia')
                    ->relationship('competency', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('current_level')
                    ->label('Nivel Actual')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required()
                    ->default(1)
                    ->helperText('Nivel actual de la competencia (1-5)'),
                TextInput::make('target_level')
                    ->label('Nivel Objetivo')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->helperText('Nivel objetivo deseado (1-5)'),
                DatePicker::make('last_assessed_at')
                    ->label('Última Evaluación')
                    ->default(now())
                    ->displayFormat('d/m/Y'),
                Select::make('assessed_by')
                    ->label('Evaluado Por')
                    ->relationship('assessor', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->id()),
                Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Notas adicionales sobre la evaluación'),
            ]);
    }
}
