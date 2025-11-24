<?php

namespace App\Filament\Resources\ToolingAttributes\Pages;

use App\Filament\Resources\ToolingAttributes\ToolingAttributeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewToolingAttribute extends ViewRecord
{
    protected static string $resource = ToolingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
