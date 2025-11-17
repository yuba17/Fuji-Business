<?php

namespace App\Filament\Resources\Competencies\Pages;

use App\Filament\Resources\Competencies\CompetencyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCompetency extends ViewRecord
{
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
