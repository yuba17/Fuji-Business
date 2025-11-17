<?php

namespace App\Filament\Resources\Risks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RisksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('plan.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('area.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('probability')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('impact')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('risk_level')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('strategy')
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('identified_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('target_mitigation_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('mitigated_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('estimated_cost')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trend')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
