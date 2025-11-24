<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestoneTypeAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditMilestoneTypeAttribute extends EditRecord
{
    protected static string $resource = MilestoneTypeAttributeResource::class;
}

