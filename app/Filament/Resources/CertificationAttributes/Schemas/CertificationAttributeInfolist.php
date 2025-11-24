<?php

namespace App\Filament\Resources\CertificationAttributes\Schemas;

use App\Models\CertificationAttribute;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificationAttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('attribute_type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state) => CertificationAttribute::attributeTypes()[$state] ?? $state),
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
