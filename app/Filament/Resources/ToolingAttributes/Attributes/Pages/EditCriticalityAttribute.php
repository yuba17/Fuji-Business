<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\CriticalityAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditCriticalityAttribute extends EditRecord
{
    protected static string $resource = CriticalityAttributeResource::class;
}

