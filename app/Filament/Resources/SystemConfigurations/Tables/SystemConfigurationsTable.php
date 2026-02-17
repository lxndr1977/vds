<?php

namespace App\Filament\Resources\SystemConfigurations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SystemConfigurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modelLabel('Configuração do Sistema')
            ->pluralModelLabel('Configurações do Sistema')
            ->columns([
                TextColumn::make('festival_name')
                    ->label('Nome do Festival')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->square()
                    ->defaultImageUrl(null),

                TextColumn::make('registration_start_date')
                    ->label('Início das Inscrições')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('registration_end_date')
                    ->label('Fim das Inscrições')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                IconColumn::make('allow_edit_after_submit')
                    ->label('Permite Edição')
                    ->boolean()
                    ->trueIcon(Heroicon::Check)
                    ->falseIcon(Heroicon::XMark),

                TextColumn::make('updated_at')
                    ->label('Atualizada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
