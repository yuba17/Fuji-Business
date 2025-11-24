<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\OperationalStatusAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOperationalStatusAttribute extends CreateRecord
{
    protected static string $resource = OperationalStatusAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'operational_status';
        return $data;
    }
}

