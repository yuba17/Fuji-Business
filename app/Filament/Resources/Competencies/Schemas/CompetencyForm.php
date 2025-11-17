<?php

namespace App\Filament\Resources\Competencies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompetencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('category')
                    ->label('Categoría')
                    ->options([
                        'Técnica' => 'Técnica',
                        'Metodología' => 'Metodología',
                        'Soft Skills' => 'Soft Skills',
                        'Certificación' => 'Certificación',
                        'Otros' => 'Otros',
                    ])
                    ->searchable()
                    ->preload(),
                Select::make('level_type')
                    ->label('Tipo de Nivel')
                    ->options([
                        'numeric' => 'Numérico (1-5)',
                        'named' => 'Nombrado (beginner/intermediate/advanced/expert)',
                    ])
                    ->required()
                    ->default('numeric'),
                Select::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('internal_role_id')
                    ->label('Rol Interno')
                    ->relationship('internalRole', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_critical')
                    ->label('Competencia Crítica')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true)
                    ->required(),
            ]);
    }
}
