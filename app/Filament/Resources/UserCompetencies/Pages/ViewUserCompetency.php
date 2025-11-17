<?php

namespace App\Filament\Resources\UserCompetencies\Pages;

use App\Filament\Resources\UserCompetencies\UserCompetencyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserCompetency extends ViewRecord
{
    protected static string $resource = UserCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
