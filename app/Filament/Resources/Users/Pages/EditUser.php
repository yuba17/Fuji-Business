<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si no se proporciona contraseña, eliminarla del array para no actualizarla
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Si se proporciona, hashearla
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }
}
