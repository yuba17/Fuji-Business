<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\ProviderAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProviderAttribute extends CreateRecord
{
    protected static string $resource = ProviderAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'provider';
        return $data;
    }
}

