<?php

namespace App\Filament\Resources\Infrastructures;

use App\Filament\Resources\Infrastructures\Pages\ManageInfrastructures;
use App\Models\Infrastructure;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InfrastructureResource extends Resource
{
    protected static ?string $model = Infrastructure::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string|BackedEnum|UnitEnum|null $navigationGroup = 'Infraestructura';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Descripción')
                ->columnSpanFull()
                ->rows(3),
            Select::make('infrastructure_class')
                ->label('Clase')
                ->options([
                    'hardware' => 'Hardware',
                    'license' => 'Licencia',
                ])
                ->required(),
            Select::make('acquisition_status')
                ->label('Estado de adquisición')
                ->options([
                    'purchased' => 'Comprado',
                    'to_purchase' => 'Por comprar',
                    'planned' => 'Planificado',
                ])
                ->required(),
            Select::make('tracking_mode')
                ->label('Modo de seguimiento')
                ->options([
                    'individual' => 'Individual',
                    'group' => 'Grupo',
                ])
                ->default('individual')
                ->required(),
            TextInput::make('type')
                ->label('Tipo')
                ->required()
                ->maxLength(255),
            TextInput::make('category')
                ->label('Categoría')
                ->required()
                ->maxLength(255),
            Select::make('status')
                ->label('Estado operativo')
                ->options([
                    'active' => 'Activo',
                    'maintenance' => 'Mantenimiento',
                    'deprecated' => 'Deprecado',
                    'planned' => 'Planificado',
                ])
                ->required(),
            TextInput::make('provider')
                ->label('Proveedor')
                ->maxLength(255),
            TextInput::make('location')
                ->label('Ubicación / Región')
                ->maxLength(255),
            Select::make('area_id')
                ->label('Área')
                ->relationship('area', 'name')
                ->searchable()
                ->preload(),
            Select::make('plan_id')
                ->label('Plan asociado')
                ->relationship('plan', 'name')
                ->searchable()
                ->preload(),
            Select::make('owner_id')
                ->label('Propietario')
                ->relationship('owner', 'name')
                ->searchable()
                ->preload(),
            TextInput::make('cost_monthly')
                ->label('Coste mensual (€)')
                ->numeric()
                ->step(0.01),
            TextInput::make('cost_yearly')
                ->label('Coste anual (€)')
                ->numeric()
                ->step(0.01),
            TextInput::make('capacity')
                ->label('Capacidad / Límites')
                ->maxLength(255),
            TextInput::make('utilization_percent')
                ->label('Utilización (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100),
            TextInput::make('quantity')
                ->label('Unidades totales')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->visible(fn ($get) => $get('tracking_mode') === 'group'),
            TextInput::make('quantity_in_use')
                ->label('Unidades en uso')
                ->numeric()
                ->minValue(0)
                ->visible(fn ($get) => $get('tracking_mode') === 'group'),
            TextInput::make('quantity_reserved')
                ->label('Unidades reservadas')
                ->numeric()
                ->minValue(0)
                ->visible(fn ($get) => $get('tracking_mode') === 'group'),
            DatePicker::make('roadmap_date')
                ->label('Fecha Roadmap'),
            DatePicker::make('expires_at')
                ->label('Caduca')
                ->visible(fn ($get) => $get('infrastructure_class') === 'license'),
            TextInput::make('renewal_reminder_days')
                ->label('Días aviso renovación')
                ->numeric()
                ->visible(fn ($get) => $get('infrastructure_class') === 'license'),
            Select::make('is_critical')
                ->label('¿Crítico?')
                ->options([
                    1 => 'Sí',
                    0 => 'No',
                ])
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                BadgeColumn::make('infrastructure_class')
                    ->label('Clase')
                    ->colors([
                        'primary' => 'hardware',
                        'purple' => 'license',
                    ])
                    ->formatStateUsing(fn (string $state) => $state === 'hardware' ? 'Hardware' : 'Licencia'),
                BadgeColumn::make('acquisition_status')
                    ->label('Adquisición')
                    ->colors([
                        'success' => 'purchased',
                        'warning' => 'to_purchase',
                        'info' => 'planned',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'purchased' => 'Comprado',
                            'to_purchase' => 'Por comprar',
                            'planned' => 'Planificado',
                            default => ucfirst($state),
                        };
                    }),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'danger' => 'deprecated',
                        'info' => 'planned',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'active' => 'Activo',
                            'maintenance' => 'Mantenimiento',
                            'deprecated' => 'Deprecado',
                            'planned' => 'Planificado',
                            default => ucfirst($state),
                        };
                    }),
                TextColumn::make('provider')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area.name')
                    ->label('Área')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('tracking_mode')
                    ->label('Modo')
                    ->colors([
                        'info' => 'group',
                        'gray' => 'individual',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'group' ? 'Grupo' : 'Individual'),
                TextColumn::make('quantity')
                    ->label('Unidades')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->tracking_mode === 'group') {
                            return $record->quantity_total . ' (Disp. ' . $record->quantity_available . ')';
                        }
                        return '1';
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('owner.name')
                    ->label('Propietario')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cost_monthly')
                    ->label('Coste mensual')
                    ->money('eur')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_critical')
                    ->label('Crítico')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray'),
            ])
            ->filters([
                SelectFilter::make('infrastructure_class')
                    ->label('Clase')
                    ->options([
                        'hardware' => 'Hardware',
                        'license' => 'Licencia',
                    ]),
                SelectFilter::make('acquisition_status')
                    ->label('Adquisición')
                    ->options([
                        'purchased' => 'Comprado',
                        'to_purchase' => 'Por comprar',
                        'planned' => 'Planificado',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(fn () => Infrastructure::query()
                        ->whereNotNull('type')
                        ->orderBy('type')
                        ->pluck('type', 'type')
                        ->toArray()),
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(fn () => Infrastructure::query()
                        ->whereNotNull('category')
                        ->orderBy('category')
                        ->pluck('category', 'category')
                        ->toArray()),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'maintenance' => 'Mantenimiento',
                        'deprecated' => 'Deprecado',
                        'planned' => 'Planificado',
                    ]),
                SelectFilter::make('provider')
                    ->label('Proveedor')
                    ->options(fn () => Infrastructure::query()
                        ->whereNotNull('provider')
                        ->orderBy('provider')
                        ->pluck('provider', 'provider')
                        ->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInfrastructures::route('/'),
        ];
    }
}

