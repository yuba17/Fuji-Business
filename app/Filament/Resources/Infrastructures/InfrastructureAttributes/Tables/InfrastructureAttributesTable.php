<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InfrastructureAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute_type')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable()
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
                        'class' => 'Clase',
                        'acquisition_status' => 'Adquisición',
                        'type' => 'Tipo',
                        'category' => 'Categoría',
                        'operational_status' => 'Estado Operativo',
                        'provider' => 'Proveedor',
                        default => $state,
                    }),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('attribute_type')
                    ->label('Tipo de Atributo')
                    ->options([
                        'class' => 'Clase',
                        'acquisition_status' => 'Estado de Adquisición',
                        'type' => 'Tipo',
                        'category' => 'Categoría',
                        'operational_status' => 'Estado Operativo',
                        'provider' => 'Proveedor',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
            ])
            ->defaultSort('attribute_type')
            ->defaultSort('order')
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
