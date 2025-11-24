<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\CriticalityAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCriticalityAttribute extends CreateRecord
{
    protected static string $resource = CriticalityAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'criticality';
        return $data;
    }
}

