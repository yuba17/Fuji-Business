<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestoneStatusAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditMilestoneStatusAttribute extends EditRecord
{
    protected static string $resource = MilestoneStatusAttributeResource::class;
}

