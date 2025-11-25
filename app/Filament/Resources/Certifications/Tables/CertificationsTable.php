<?php

namespace App\Filament\Resources\Certifications\Tables;

use App\Models\CertificationAttribute;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CertificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('provider')
                    ->label('Proveedor')
                    ->formatStateUsing(fn ($state) => CertificationAttribute::labelFor('provider', $state))
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->formatStateUsing(fn ($state) => CertificationAttribute::labelFor('category', $state))
                    ->searchable(),
                TextColumn::make('level')
                    ->label('Nivel')
                    ->formatStateUsing(fn ($state) => CertificationAttribute::labelFor('level', $state))
                    ->searchable(),
                TextColumn::make('validity_months')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('difficulty_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('value_score')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_critical')
                    ->boolean(),
                IconColumn::make('is_internal')
                    ->boolean(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('provider')
                    ->label('Proveedor')
                    ->options(CertificationAttribute::optionsFor('provider')),
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(CertificationAttribute::optionsFor('category')),
                SelectFilter::make('level')
                    ->label('Nivel')
                    ->options(CertificationAttribute::optionsFor('level')),
                SelectFilter::make('is_critical')
                    ->label('Crítica')
                    ->options([
                        1 => 'Sí',
                        0 => 'No',
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
