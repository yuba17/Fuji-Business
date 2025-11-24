<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestonePriorityAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditMilestonePriorityAttribute extends EditRecord
{
    protected static string $resource = MilestonePriorityAttributeResource::class;
}

