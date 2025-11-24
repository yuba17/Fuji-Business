<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\TypeAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTypeAttribute extends CreateRecord
{
    protected static string $resource = TypeAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'type';
        return $data;
    }
}

