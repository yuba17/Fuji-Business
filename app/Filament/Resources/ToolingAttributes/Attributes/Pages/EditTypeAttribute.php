<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\TypeAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditTypeAttribute extends EditRecord
{
    protected static string $resource = TypeAttributeResource::class;
}

