<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureAttributes\InfrastructureAttributeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInfrastructureAttribute extends ViewRecord
{
    protected static string $resource = InfrastructureAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
