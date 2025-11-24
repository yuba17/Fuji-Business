<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InfrastructureAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('attribute_type')
                    ->label('Tipo de Atributo')
                    ->options([
                        'class' => 'Clase (Hardware/Licencia)',
                        'acquisition_status' => 'Estado de Adquisición',
                        'type' => 'Tipo',
                        'category' => 'Categoría',
                        'operational_status' => 'Estado Operativo',
                        'provider' => 'Proveedor',
                    ])
                    ->required()
                    ->searchable()
                    ->helperText('Selecciona el tipo de atributo que estás configurando'),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Nombre que se mostrará en los formularios'),
                TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->helperText('Se genera automáticamente si lo dejas vacío')
                    ->dehydrated(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0)
                    ->helperText('Orden de visualización (menor = primero)'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->helperText('Solo los atributos activos aparecerán en los formularios'),
            ]);
    }
}
