<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('sector_economico'),
                TextInput::make('tamaño_empresa'),
                TextInput::make('ubicacion'),
                TextInput::make('contacto_principal'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('telefono')
                    ->tel(),
                TextInput::make('sitio_web'),
                Textarea::make('notas')
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
