<?php

namespace App\Filament\Resources\Infrastructures\InfrastructureAttributes\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureAttributes\InfrastructureAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInfrastructureAttribute extends CreateRecord
{
    protected static string $resource = InfrastructureAttributeResource::class;
}
