<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\StatusAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStatusAttributes extends ListRecords
{
    protected static string $resource = StatusAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

