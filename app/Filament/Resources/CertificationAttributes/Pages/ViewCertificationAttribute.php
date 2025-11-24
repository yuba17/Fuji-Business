<?php

namespace App\Filament\Resources\CertificationAttributes\Pages;

use App\Filament\Resources\CertificationAttributes\CertificationAttributeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificationAttribute extends ViewRecord
{
    protected static string $resource = CertificationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
