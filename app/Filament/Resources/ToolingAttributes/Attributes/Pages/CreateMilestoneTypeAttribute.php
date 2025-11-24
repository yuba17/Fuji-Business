<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestoneTypeAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMilestoneTypeAttribute extends CreateRecord
{
    protected static string $resource = MilestoneTypeAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'milestone_type';
        return $data;
    }
}

