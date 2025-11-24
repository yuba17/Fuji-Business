<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\CategoryAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryAttribute extends CreateRecord
{
    protected static string $resource = CategoryAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'category';
        return $data;
    }
}

