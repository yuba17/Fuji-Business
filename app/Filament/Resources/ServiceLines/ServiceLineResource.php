<?php

namespace App\Filament\Resources\ServiceLines;

use App\Filament\Resources\ServiceLines\Pages\ManageServiceLines;
use App\Models\ServiceLine;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceLineResource extends Resource
{
    protected static ?string $model = ServiceLine::class;

    // Icono estándar de Filament para algo tipo flujo/estructura
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de la línea de servicio')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Se genera automáticamente a partir del nombre si lo dejas vacío.')
                    ->maxLength(255),
                Select::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Área a la que pertenece esta línea de servicio.'),
                Select::make('manager_id')
                    ->label('Manager de la línea')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Manager responsable de esta línea de servicio (opcional).'),
                TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('slug')
                    ->label('Slug'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => ManageServiceLines::route('/'),
        ];
    }
}
