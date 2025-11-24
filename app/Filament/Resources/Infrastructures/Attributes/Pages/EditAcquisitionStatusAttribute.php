<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\AcquisitionStatusAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAcquisitionStatusAttribute extends EditRecord
{
    protected static string $resource = AcquisitionStatusAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

