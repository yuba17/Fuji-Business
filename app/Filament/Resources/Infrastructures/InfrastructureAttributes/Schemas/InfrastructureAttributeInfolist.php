<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InfrastructureAttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('attribute_type')
                    ->label('Tipo de Atributo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'class' => 'info',
                        'acquisition_status' => 'success',
                        'type' => 'warning',
                        'category' => 'danger',
                        'operational_status' => 'primary',
                        'provider' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'class' => 'Clase (Hardware/Licencia)',
                        'acquisition_status' => 'Estado de Adquisición',
                        'type' => 'Tipo',
                        'category' => 'Categoría',
                        'operational_status' => 'Estado Operativo',
                        'provider' => 'Proveedor',
                        default => $state,
                    }),
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('slug')
                    ->label('Slug'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextEntry::make('order')
                    ->label('Orden')
                    ->numeric(),
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
