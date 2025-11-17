<?php

namespace App\Filament\Resources\ServiceLines\Pages;

use App\Filament\Resources\ServiceLines\ServiceLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageServiceLines extends ManageRecords
{
    protected static string $resource = ServiceLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
