<?php

namespace App\Filament\Resources\ChoreographyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Dancer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DancersRelationManager extends RelationManager
{
   protected static string $relationship = 'dancers';

   protected static ?string $title = "Bailarinos";

   public function form(Form $form): Form
   {
      return $form
         ->schema([
            Forms\Components\TextInput::make('name')
               ->label('Nome')
               ->required()
               ->maxLength(255),

            Forms\Components\TextInput::make('birth_date')
               ->label('Nascimento')
               ->mask('99/99/9999')
               ->placeholder('dd/mm/aaaa')
               ->rules(['regex:/^\d{2}\/\d{2}\/\d{4}$/'])
               ->validationMessages([
                  'regex' => 'A data deve estar no formato dd/mm/aaaa'
               ])
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
            // Tables\Actions\CreateAction::make()
            //    ->using(function (array $data) {
            //       $data['school_id'] = $this->getOwnerRecord()->school_id;
            //       return Dancer::create($data);
            //    }),

            Tables\Actions\AttachAction::make()
               ->color('primary')
               ->recordSelectOptionsQuery(
                  fn(Builder $query) =>
                  $query->where('school_id', $this->getOwnerRecord()->school_id)
                     ->orderBy('name')
               )
               ->preloadRecordSelect()
               ->recordSelectSearchColumns(['name'])
         ])
         ->actions([
            Tables\Actions\EditAction::make()
               ->using(function (Dancer $record, array $data) {
                  $record->update($data);

                  return $record;
               }),

            Tables\Actions\DetachAction::make(),
         ])
         ->bulkActions([
            Tables\Actions\BulkActionGroup::make([]),
         ])
         ->defaultSort('name', 'asc');
   }
}
