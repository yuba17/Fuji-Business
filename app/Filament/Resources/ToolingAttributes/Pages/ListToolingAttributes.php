<?php

namespace App\Filament\Resources\ToolingAttributes\Pages;

use App\Filament\Resources\ToolingAttributes\ToolingAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListToolingAttributes extends ListRecords
{
    protected static string $resource = ToolingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
