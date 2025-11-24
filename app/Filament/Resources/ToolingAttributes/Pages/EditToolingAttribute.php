<?php

namespace App\Filament\Resources\ToolingAttributes\Pages;

use App\Filament\Resources\ToolingAttributes\ToolingAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditToolingAttribute extends EditRecord
{
    protected static string $resource = ToolingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
