<?php

namespace App\Filament\Resources\RegistrationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ChoreographyResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ChoreographiesRelationManager extends RelationManager
{
   protected static string $relationship = 'Choreographies';

   protected static ?string $title = "Coreografias";

   public function form(Form $form): Form
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
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true)
               ->icon('heroicon-o-calendar')
               ->tooltip('Data de criação do registro'),

            Tables\Columns\TextColumn::make('updated_at')
               ->label('Atualizado em')
               ->dateTime('d/m/Y H:i')
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true)
               ->icon('heroicon-o-pencil')
               ->tooltip('Data da última atualização'),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            Tables\Actions\CreateAction::make(),
         ])
         ->actions([
            Tables\Actions\Action::make('edit')
               ->label('Editar')
               ->icon('heroicon-o-pencil')
               ->url(fn($record) => ChoreographyResource::getUrl('edit', ['record' => $record])),
            Tables\Actions\DeleteAction::make(),
         ])
         ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
               Tables\Actions\DeleteBulkAction::make(),
            ]),
         ])
         ->defaultSort('name', 'asc');
   }
}
