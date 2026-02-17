<?php

namespace App\Filament\Resources\Choreographies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChoreographiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('school.name')
                    ->searchable(),
                TextColumn::make('choreographyType.name')
                    ->searchable(),
                TextColumn::make('choreography_category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dance_style_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_social_project')
                    ->boolean(),
                IconColumn::make('is_university_project')
                    ->boolean(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('music')
                    ->searchable(),
                TextColumn::make('music_composer')
                    ->searchable(),
                TextColumn::make('duration')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
