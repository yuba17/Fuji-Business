<?php

namespace App\Filament\Resources\Infrastructures\Attributes\Pages;

use App\Filament\Resources\Infrastructures\Attributes\OperationalStatusAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationalStatusAttributes extends ListRecords
{
    protected static string $resource = OperationalStatusAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

