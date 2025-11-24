<?php

namespace App\Filament\Resources\Infrastructures\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInfrastructures extends ManageRecords
{
    protected static string $resource = InfrastructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

