<?php

namespace App\Filament\Resources\ToolingAttributes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ToolingAttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('slug')
                    ->label('Slug'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextEntry::make('order')
                    ->label('Orden'),
                IconEntry::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime(),
            ]);
    }
}
