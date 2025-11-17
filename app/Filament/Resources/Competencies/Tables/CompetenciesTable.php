<?php

namespace App\Filament\Resources\Competencies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Técnica' => 'info',
                        'Metodología' => 'success',
                        'Soft Skills' => 'warning',
                        'Certificación' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('area.name')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('internalRole.name')
                    ->label('Rol Interno')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_critical')
                    ->label('Crítica')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
                TextColumn::make('userCompetencies_count')
                    ->label('Evaluaciones')
                    ->counts('userCompetencies')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'Técnica' => 'Técnica',
                        'Metodología' => 'Metodología',
                        'Soft Skills' => 'Soft Skills',
                        'Certificación' => 'Certificación',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\TernaryFilter::make('is_critical')
                    ->label('Competencia Crítica')
                    ->placeholder('Todas')
                    ->trueLabel('Solo críticas')
                    ->falseLabel('Solo no críticas'),
                \Filament\Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todas')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
