<?php

namespace App\Filament\Resources\Registrations\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Dancer;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DancersRelationManager extends RelationManager
{
   protected static string $relationship = 'Dancers';

   protected static ?string $title = "Bailarinos";

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
         ->modelLabel('Bailarino')
         ->pluralModelLabel('Bailarinos')
         ->columns([
            Tables\Columns\TextColumn::make('name')
               ->label('Nome')
               ->sortable()
               ->searchable(),

            Tables\Columns\TextColumn::make('birth_date')
               ->label('Nascimento')
         ])
         ->filters([
            //
         ])
         ->headerActions([
            CreateAction::make()
               ->using(function (array $data) {
                  $data['school_id'] = $this->getOwnerRecord()->school_id;

                  return Dancer::create($data);
               })
         ])
         ->actions([
            EditAction::make()
               ->using(function (Dancer $record, array $data) {
                  $record->update($data);

                  return $record;
               }),

            DeleteAction::make()
               ->using(function ($record, $livewire) {
                  try {
                     $record->delete();
                  } catch (\Exception $e) {
                     Notification::make()
                        ->title('Erro ao excluir')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();

                     return false;
                  }
               }),
         ])
         ->bulkActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ])
         ->defaultSort('name', 'asc');
   }
}
