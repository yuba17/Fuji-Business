<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestoneStatusAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMilestoneStatusAttribute extends CreateRecord
{
    protected static string $resource = MilestoneStatusAttributeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_type'] = 'milestone_status';
        return $data;
    }
}

