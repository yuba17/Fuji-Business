<?php

namespace App\Filament\Resources\Certifications\Schemas;

use App\Models\CertificationAttribute;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code'),
                TextInput::make('official_url')
                    ->label('URL Oficial')
                    ->url()
                    ->maxLength(500),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('provider')
                    ->required()
                    ->options(fn () => CertificationAttribute::optionsFor('provider'))
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre del Proveedor'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $slug = \Illuminate\Support\Str::slug($data['name']);
                        CertificationAttribute::create([
                            'attribute_type' => 'provider',
                            'name' => $data['name'],
                            'slug' => $slug,
                            'is_active' => true,
                        ]);
                        return $slug;
                    }),
                Select::make('category')
                    ->options(fn () => CertificationAttribute::optionsFor('category'))
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Categoría'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $slug = \Illuminate\Support\Str::slug($data['name']);
                        CertificationAttribute::create([
                            'attribute_type' => 'category',
                            'name' => $data['name'],
                            'slug' => $slug,
                            'is_active' => true,
                        ]);
                        return $slug;
                    }),
                Select::make('level')
                    ->options(fn () => CertificationAttribute::optionsFor('level'))
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre del Nivel'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $slug = \Illuminate\Support\Str::slug($data['name']);
                        CertificationAttribute::create([
                            'attribute_type' => 'level',
                            'name' => $data['name'],
                            'slug' => $slug,
                            'is_active' => true,
                        ]);
                        return $slug;
                    }),
                TextInput::make('validity_months')
                    ->numeric(),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('€'),
                TextInput::make('currency')
                    ->required()
                    ->default('EUR'),
                TextInput::make('difficulty_score')
                    ->numeric(),
                TextInput::make('value_score')
                    ->numeric(),
                Toggle::make('is_critical')
                    ->required(),
                Toggle::make('is_internal')
                    ->required(),
                Textarea::make('requirements')
                    ->columnSpanFull(),
                Textarea::make('exam_details')
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
