<?php

namespace App\Filament\Resources\UserCompetencies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserCompetencyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('competency.name')
                    ->numeric(),
                TextEntry::make('current_level')
                    ->numeric(),
                TextEntry::make('target_level')
                    ->numeric(),
                TextEntry::make('last_assessed_at')
                    ->date(),
                TextEntry::make('assessed_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
