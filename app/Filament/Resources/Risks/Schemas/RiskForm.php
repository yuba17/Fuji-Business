<?php

namespace App\Filament\Resources\Risks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RiskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('plan_id')
                    ->relationship('plan', 'name'),
                Select::make('area_id')
                    ->relationship('area', 'name'),
                Select::make('project_id')
                    ->relationship('project', 'name'),
                TextInput::make('probability')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('impact')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('risk_level')
                    ->numeric(),
                TextInput::make('category'),
                TextInput::make('strategy'),
                Select::make('owner_id')
                    ->relationship('owner', 'name'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                DatePicker::make('identified_at')
                    ->required(),
                DatePicker::make('target_mitigation_date'),
                DatePicker::make('mitigated_at'),
                Textarea::make('mitigation_plan')
                    ->columnSpanFull(),
                TextInput::make('estimated_cost')
                    ->numeric(),
                TextInput::make('trend')
                    ->required()
                    ->default('stable'),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
