<?php

namespace App\Filament\Resources\InternalRoles;

use App\Filament\Resources\InternalRoles\Pages\ManageInternalRoles;
use App\Models\InternalRole;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InternalRoleResource extends Resource
{
    protected static ?string $model = InternalRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre del rol interno')
                    ->required()
                    ->maxLength(255),
                TextInput::make('track')
                    ->label('Track / familia')
                    ->maxLength(255),
                TextInput::make('level')
                    ->label('Nivel')
                    ->maxLength(255),
                TextInput::make('role_type')
                    ->label('Tipo de rol (IC / Manager / Director)')
                    ->maxLength(255),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('track')
                    ->label('Track'),
                TextEntry::make('level')
                    ->label('Nivel'),
                TextEntry::make('role_type')
                    ->label('Tipo de rol'),
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
                TextColumn::make('track')
                    ->label('Track')
                    ->searchable(),
                TextColumn::make('level')
                    ->label('Nivel')
                    ->searchable(),
                TextColumn::make('role_type')
                    ->label('Tipo')
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
            'index' => ManageInternalRoles::route('/'),
        ];
    }
}
