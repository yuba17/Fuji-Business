<?php

namespace App\Filament\Resources\CertificationAttributes;

use App\Filament\Resources\CertificationAttributes\Pages\CreateCertificationAttribute;
use App\Filament\Resources\CertificationAttributes\Pages\EditCertificationAttribute;
use App\Filament\Resources\CertificationAttributes\Pages\ListCertificationAttributes;
use App\Filament\Resources\CertificationAttributes\Pages\ViewCertificationAttribute;
use App\Filament\Resources\CertificationAttributes\Schemas\CertificationAttributeForm;
use App\Filament\Resources\CertificationAttributes\Schemas\CertificationAttributeInfolist;
use App\Filament\Resources\CertificationAttributes\Tables\CertificationAttributesTable;
use App\Models\CertificationAttribute;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CertificationAttributeResource extends Resource
{
    protected static ?string $model = CertificationAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Certificaciones';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CertificationAttributeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificationAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificationAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificationAttributes::route('/'),
            'create' => CreateCertificationAttribute::route('/create'),
            'view' => ViewCertificationAttribute::route('/{record}'),
            'edit' => EditCertificationAttribute::route('/{record}/edit'),
        ];
    }
}
