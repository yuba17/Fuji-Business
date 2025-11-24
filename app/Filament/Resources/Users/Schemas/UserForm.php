<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Models\Area;
use App\Models\InternalRole;
use App\Models\ServiceLine;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText(fn (string $operation): string => $operation === 'create' 
                        ? 'La contraseña es obligatoria al crear un nuevo usuario.' 
                        : 'Deja en blanco para mantener la contraseña actual.'),
                Textarea::make('two_factor_secret')
                    ->columnSpanFull(),
                Textarea::make('two_factor_recovery_codes')
                    ->columnSpanFull(),
                DateTimePicker::make('two_factor_confirmed_at'),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Selecciona uno o varios roles para este usuario (director, manager, técnico, visualización).'),
                Select::make('manager_id')
                    ->label('Manager / Responsable directo')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Selecciona la persona de la que depende este usuario (director o manager).'),
                Select::make('internal_role_id')
                    ->label('Rol interno')
                    ->relationship('internalRole', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Rol interno dentro de la organización (Senior Red Team, Blue Team Lead, etc.).'),
                Select::make('area_id')
                    ->label('Área principal')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Área/unidad principal a la que pertenece este usuario.'),
                Select::make('service_lines')
                    ->label('Líneas de servicio')
                    ->relationship('serviceLines', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Líneas de servicio en las que trabaja este usuario (Pool, Red Team, CTEM, etc.).'),
            ]);
    }
}
