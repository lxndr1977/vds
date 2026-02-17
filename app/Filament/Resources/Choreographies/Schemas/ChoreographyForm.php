<?php

namespace App\Filament\Resources\Choreographies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ChoreographyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('school_id')
                    ->relationship('school', 'name')
                    ->required(),
                Select::make('choreography_type_id')
                    ->relationship('choreographyType', 'name')
                    ->required(),
                TextInput::make('choreography_category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('dance_style_id')
                    ->required()
                    ->numeric(),
                Toggle::make('is_social_project')
                    ->required(),
                Toggle::make('is_university_project')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('music')
                    ->required(),
                TextInput::make('music_composer')
                    ->required(),
                TextInput::make('duration')
                    ->required(),
            ]);
    }
}
