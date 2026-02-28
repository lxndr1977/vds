<?php

namespace App\Filament\Resources\Choreographies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

final class ChoreographyForm
{
    public static function configure(Schema $schema, bool $hideSchool = false, ?int $schoolId = null): Schema
    {
        return $schema
            ->components([
                Select::make('school_id')
                    ->label('Instituição')
                    ->relationship('school', 'name')
                    ->required()
                    ->default($schoolId)
                    ->hidden($hideSchool)
                    ->dehydrated(! $hideSchool)
                    ->live(),
                Select::make('choreography_type_id')
                    ->label('Formação')
                    ->relationship('choreographyType', 'name')
                    ->required()
                    ->live(),
                Select::make('choreography_category_id')
                    ->label('Categoria')
                    ->relationship('choreographyCategory', 'name')
                    ->required(),
                Select::make('dance_style_id')
                    ->label('Gênero')
                    ->relationship('danceStyle', 'name')
                    ->required(),
                TextInput::make('name')
                    ->label('Nome da Coreografia')
                    ->required()
                    ->maxLength(255),
                Select::make('choreographers')
                    ->label('Coreógrafos')
                    ->relationship(
                        'choreographers',
                        'name',
                        modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('school_id', $get('school_id') ?? $schoolId)
                    )
                    ->multiple()
                    ->preload()
                    ->required(),
                Select::make('dancers')
                    ->label('Bailarinos')
                    ->relationship(
                        'dancers',
                        'name',
                        modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('school_id', $get('school_id') ?? $schoolId)
                    )
                    ->multiple()
                    ->preload()
                    ->required(),
                TextInput::make('music')
                    ->label('Música')
                    ->required()
                    ->maxLength(255),
                TextInput::make('music_composer')
                    ->label('Compositor')
                    ->required()
                    ->maxLength(255),
                TextInput::make('duration')
                    ->label('Duração')
                    ->required()
                    ->placeholder('00:00')
                    ->maxLength(255),
                Toggle::make('is_social_project')
                    ->label('Projeto Social')
                    ->required(),
                Toggle::make('is_university_project')
                    ->label('Projeto Universitário')
                    ->required(),
            ]);
    }
}
