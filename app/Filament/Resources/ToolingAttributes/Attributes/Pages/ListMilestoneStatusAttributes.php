<?php

namespace App\Filament\Resources\ToolingAttributes\Attributes\Pages;

use App\Filament\Resources\ToolingAttributes\Attributes\MilestoneStatusAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMilestoneStatusAttributes extends ListRecords
{
    protected static string $resource = MilestoneStatusAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

