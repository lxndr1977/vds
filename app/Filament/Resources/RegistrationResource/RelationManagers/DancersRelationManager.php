<?php

namespace App\Filament\Resources\RegistrationResource\RelationManagers;

use App\Models\Dancer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DancersRelationManager extends RelationManager
{
   protected static string $relationship = 'Dancers';

   protected static ?string $title = "Bailarinos";

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
            Tables\Actions\CreateAction::make()
               ->using(function (array $data) {
                  $data['school_id'] = $this->getOwnerRecord()->school_id;

                  return Dancer::create($data);
               })
         ])
         ->actions([
            Tables\Actions\EditAction::make()
               ->using(function (Dancer $record, array $data) {
                  $record->update($data);

                  return $record;
               }),
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
