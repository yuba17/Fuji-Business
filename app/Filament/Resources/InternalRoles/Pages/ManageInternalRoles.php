<?php

namespace App\Filament\Resources\InternalRoles\Pages;

use App\Filament\Resources\InternalRoles\InternalRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInternalRoles extends ManageRecords
{
    protected static string $resource = InternalRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
