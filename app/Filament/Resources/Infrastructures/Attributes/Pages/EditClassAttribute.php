<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\ClassAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClassAttribute extends EditRecord
{
    protected static string $resource = ClassAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

