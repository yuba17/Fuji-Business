<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('plan_type_id')
                    ->relationship('planType', 'name')
                    ->required(),
                Select::make('area_id')
                    ->relationship('area', 'name'),
                Select::make('manager_id')
                    ->relationship('manager', 'name'),
                Select::make('director_id')
                    ->relationship('director', 'name'),
                Select::make('parent_plan_id')
                    ->relationship('parentPlan', 'name'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DatePicker::make('start_date'),
                DatePicker::make('target_date'),
                DatePicker::make('review_date'),
                DatePicker::make('end_date'),
                TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('is_current_version')
                    ->required(),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
