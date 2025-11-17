<?php

namespace App\Filament\Resources\Kpis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KpisTable
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
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('unit')
                    ->searchable(),
                TextColumn::make('target_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('calculation_method')
                    ->searchable(),
                TextColumn::make('update_frequency')
                    ->searchable(),
                TextColumn::make('last_updated_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('threshold_green')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('threshold_yellow')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('responsible.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kpi_type')
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
