<?php

namespace App\Filament\Resources\Kpis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KpiForm
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
                TextInput::make('type')
                    ->required()
                    ->default('numeric'),
                TextInput::make('unit'),
                TextInput::make('target_value')
                    ->numeric(),
                TextInput::make('current_value')
                    ->numeric(),
                TextInput::make('calculation_method'),
                Textarea::make('formula')
                    ->columnSpanFull(),
                TextInput::make('update_frequency')
                    ->required()
                    ->default('monthly'),
                DatePicker::make('last_updated_at'),
                TextInput::make('status')
                    ->required()
                    ->default('green'),
                TextInput::make('threshold_green')
                    ->required()
                    ->numeric()
                    ->default(80),
                TextInput::make('threshold_yellow')
                    ->required()
                    ->numeric()
                    ->default(50),
                Select::make('responsible_id')
                    ->relationship('responsible', 'name'),
                TextInput::make('kpi_type')
                    ->required()
                    ->default('lagging'),
                Textarea::make('metadata')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
