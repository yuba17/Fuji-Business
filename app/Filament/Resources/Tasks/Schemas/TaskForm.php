<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('plan_id')
                    ->relationship('plan', 'name'),
                Select::make('area_id')
                    ->relationship('area', 'name'),
                Select::make('milestone_id')
                    ->relationship('milestone', 'name'),
                Select::make('project_id')
                    ->relationship('project', 'name'),
                Select::make('parent_task_id')
                    ->relationship('parentTask', 'title'),
                TextInput::make('assigned_to')
                    ->numeric(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('todo'),
                TextInput::make('priority')
                    ->required()
                    ->default('medium'),
                DatePicker::make('due_date'),
                TextInput::make('estimated_hours')
                    ->numeric(),
                TextInput::make('actual_hours')
                    ->numeric(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
