<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\StatusAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStatusAttribute extends CreateRecord
{
    protected static string $resource = StatusAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'status';
        return $data;
    }
}

