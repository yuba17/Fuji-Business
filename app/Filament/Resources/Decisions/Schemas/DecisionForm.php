<?php

namespace App\Filament\Resources\Decisions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DecisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DatePicker::make('decision_date')
                    ->required(),
                Select::make('proponent_id')
                    ->relationship('proponent', 'name'),
                TextInput::make('impact_type'),
                TextInput::make('status')
                    ->required()
                    ->default('proposed'),
                Textarea::make('alternatives_considered')
                    ->columnSpanFull(),
                Textarea::make('rationale')
                    ->columnSpanFull(),
                Textarea::make('expected_impact')
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
