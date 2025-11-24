<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\StatusAttributeResource;
use Filament\Resources\Pages\EditRecord;

class EditStatusAttribute extends EditRecord
{
    protected static string $resource = StatusAttributeResource::class;
}

