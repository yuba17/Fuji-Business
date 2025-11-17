<?php

namespace App\Filament\Resources\UserCompetencies\Pages;

use App\Filament\Resources\UserCompetencies\UserCompetencyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUserCompetency extends EditRecord
{
    protected static string $resource = UserCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
