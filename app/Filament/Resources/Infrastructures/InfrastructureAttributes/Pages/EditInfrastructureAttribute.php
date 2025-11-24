<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureAttributes\InfrastructureAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInfrastructureAttribute extends EditRecord
{
    protected static string $resource = InfrastructureAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
