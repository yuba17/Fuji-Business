<?php

namespace App\Filament\Resources\CertificationAttributes\Pages;

use App\Filament\Resources\CertificationAttributes\CertificationAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificationAttributes extends ListRecords
{
    protected static string $resource = CertificationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
