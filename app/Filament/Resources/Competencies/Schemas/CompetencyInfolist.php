<?php

namespace App\Filament\Resources\Competencies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CompetencyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre')
                    ->size('lg')
                    ->weight('bold'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                TextEntry::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Técnica' => 'info',
                        'Metodología' => 'success',
                        'Soft Skills' => 'warning',
                        'Certificación' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('level_type')
                    ->label('Tipo de Nivel')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'numeric' => 'Numérico (1-5)',
                        'named' => 'Nombrado',
                        default => $state,
                    }),
                IconEntry::make('is_critical')
                    ->label('Competencia Crítica')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
                TextEntry::make('area.name')
                    ->label('Área'),
                TextEntry::make('internalRole.name')
                    ->label('Rol Interno'),
                TextEntry::make('order')
                    ->label('Orden')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                TextEntry::make('userCompetencies_count')
                    ->label('Número de Evaluaciones')
                    ->state(fn ($record) => $record->userCompetencies()->count()),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime(),
            ]);
    }
}
