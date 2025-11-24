<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\ClassAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassAttribute extends CreateRecord
{
    protected static string $resource = ClassAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'class';
        return $data;
    }
}

