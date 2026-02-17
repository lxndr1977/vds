<?php

namespace App\Filament\Resources\Registrations\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Choreographies\ChoreographyResource;
use App\Filament\Resources\Chogreographies\ChogreographyResource;

class ChoreographiesRelationManager extends RelationManager
{
   protected static string $relationship = 'Choreographies';

   protected static ?string $title = "Coreografias";

   public function form(Schema $form): Schema
   {
      return $form
         ->schema([
            Forms\Components\TextInput::make('name')
               ->label('Nome')
               ->required()
               ->maxLength(255),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('name')
         ->modelLabel('Coreografia')
         ->pluralModelLabel('Coreografias')
         ->columns([
            Tables\Columns\TextColumn::make('name')
               ->label('Nome')
               ->sortable()
               ->searchable(),

            Tables\Columns\TextColumn::make('choreographyType.name')
               ->label('Formação')
               ->searchable()
               ->sortable()
               ->badge(),

            Tables\Columns\TextColumn::make('name')
               ->label('Nome da Coreografia')
               ->searchable()
               ->sortable()
               ->weight('medium')
               ->limit(30),

            Tables\Columns\TextColumn::make('created_at')
               ->label('Criado em')
               ->dateTime('d/m/Y H:i')
               ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
               ->label('Atualizado em')
               ->dateTime('d/m/Y H:i')
               ->sortable(),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            CreateAction::make(),
         ])
         ->actions([
            Action::make('edit')
               ->label('Editar')
               ->icon('heroicon-o-pencil')
               ->url(fn($record) => ChoreographyResource::getUrl('edit', ['record' => $record])),
            DeleteAction::make(),
         ])
         ->bulkActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ])
         ->defaultSort('created_at', 'asc');
   }
}
