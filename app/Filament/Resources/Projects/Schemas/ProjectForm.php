<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
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
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                Select::make('plan_comercial_id')
                    ->relationship('planComercial', 'name'),
                TextInput::make('sector_economico'),
                TextInput::make('status')
                    ->required()
                    ->default('prospecto'),
                DatePicker::make('fecha_inicio'),
                DatePicker::make('fecha_fin'),
                TextInput::make('presupuesto')
                    ->numeric(),
                TextInput::make('moneda')
                    ->required()
                    ->default('EUR'),
                Select::make('manager_id')
                    ->relationship('manager', 'name'),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
