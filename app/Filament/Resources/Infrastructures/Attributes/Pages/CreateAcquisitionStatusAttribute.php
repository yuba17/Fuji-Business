<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\AcquisitionStatusAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAcquisitionStatusAttribute extends CreateRecord
{
    protected static string $resource = AcquisitionStatusAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'acquisition_status';
        return $data;
    }
}

