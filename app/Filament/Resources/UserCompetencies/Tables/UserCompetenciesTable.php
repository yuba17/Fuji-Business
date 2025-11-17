<?php

namespace App\Filament\Resources\UserCompetencies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserCompetenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('competency.name')
                    ->label('Competencia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('competency.category')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Técnica' => 'info',
                        'Metodología' => 'success',
                        'Soft Skills' => 'warning',
                        'Certificación' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('current_level')
                    ->label('Nivel Actual')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'info',
                        $state >= 2 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('target_level')
                    ->label('Nivel Objetivo')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('gap')
                    ->label('Gap')
                    ->state(fn ($record) => $record->target_level && $record->current_level 
                        ? max(0, $record->target_level - $record->current_level) 
                        : null)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state) => $state !== null ? "+{$state}" : '-'),
                TextColumn::make('last_assessed_at')
                    ->label('Última Evaluación')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('assessor.name')
                    ->label('Evaluado Por')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('competency_id')
                    ->label('Competencia')
                    ->relationship('competency', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('competency.category')
                    ->label('Categoría')
                    ->options([
                        'Técnica' => 'Técnica',
                        'Metodología' => 'Metodología',
                        'Soft Skills' => 'Soft Skills',
                        'Certificación' => 'Certificación',
                    ]),
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
