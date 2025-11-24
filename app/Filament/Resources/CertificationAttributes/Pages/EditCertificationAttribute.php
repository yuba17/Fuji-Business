<?php

namespace App\Filament\Resources\CertificationAttributes\Pages;

use App\Filament\Resources\CertificationAttributes\CertificationAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCertificationAttribute extends EditRecord
{
    protected static string $resource = CertificationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
