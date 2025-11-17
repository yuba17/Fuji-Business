<?php

namespace App\Filament\Resources\UserCompetencies\Pages;

use App\Filament\Resources\UserCompetencies\UserCompetencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserCompetencies extends ListRecords
{
    protected static string $resource = UserCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
