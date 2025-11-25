<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('code'),
                TextEntry::make('provider'),
                TextEntry::make('category'),
                TextEntry::make('level'),
                TextEntry::make('validity_months')
                    ->numeric(),
                TextEntry::make('cost')
                    ->money(),
                TextEntry::make('currency'),
                TextEntry::make('difficulty_score')
                    ->numeric(),
                TextEntry::make('value_score')
                    ->numeric(),
                IconEntry::make('is_critical')
                    ->boolean(),
                IconEntry::make('is_internal')
                    ->boolean(),
                TextEntry::make('order')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
