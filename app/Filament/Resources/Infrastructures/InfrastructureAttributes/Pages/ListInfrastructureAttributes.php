<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureAttributes\InfrastructureAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfrastructureAttributes extends ListRecords
{
    protected static string $resource = InfrastructureAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
