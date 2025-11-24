<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProviderAttributeForm
{
    public static function configure(Schema $schema, string $attributeType): Schema
    {
        return $schema
            ->components([
                Hidden::make('attribute_type')
                    ->default($attributeType),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ejemplo: Microsoft, AWS, Dell, HP, etc.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        if (empty($state)) return;
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->helperText('Se genera automáticamente desde el nombre')
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

