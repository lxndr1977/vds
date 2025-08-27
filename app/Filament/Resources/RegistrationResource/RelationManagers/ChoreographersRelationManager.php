<?php

namespace App\Filament\Resources\RegistrationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Choreographer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Resources\RelationManagers\RelationManager;

class ChoreographersRelationManager extends RelationManager
{
   protected static string $relationship = 'Choreographers';

   protected static ?string $title = "Coreógrafos";

   public function form(Form $form): Form
   {
      return $form
         ->schema([
            Forms\Components\Grid::make(3)
               ->schema([
                  Forms\Components\TextInput::make('name')
                     ->label('Nome')
                     ->required()
                     ->maxLength(255)
                     ->columnSpan(3),

                  Forms\Components\Toggle::make('is_attending')
                     ->label('Participará presencialmente?')
                     ->columnSpan(1),

                  Forms\Components\Toggle::make('is_public_domain')
                     ->label('Domínio público?')
                     ->columnSpan(1),

                  Forms\Components\Toggle::make('is_adaptation')
                     ->label('É adaptação?')
                     ->columnSpan(1),
               ]),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('name')
         ->modelLabel('Coreógrafo')
         ->pluralModelLabel('Coreógrafos')
         ->columns([
            Tables\Columns\TextColumn::make('name')
               ->label('Nome')
               ->sortable()
               ->searchable(),

            Tables\Columns\IconColumn::make('is_attending')
               ->label('Presencialmente')
               ->boolean()
               ->size(IconColumnSize::Medium),

            Tables\Columns\IconColumn::make('is_public_domain')
               ->label('Domínio Público')
               ->boolean()
               ->size(IconColumnSize::Medium),

            Tables\Columns\IconColumn::make('is_adaptation')
               ->label('Adaptação')
               ->boolean()
               ->size(IconColumnSize::Medium),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            Tables\Actions\CreateAction::make()
               ->using(function (array $data) {
                  $data['school_id'] = $this->getOwnerRecord()->school_id;

                  return Choreographer::create($data);
               }),
         ])
         ->actions([
            Tables\Actions\EditAction::make()
               ->using(function (Choreographer $record, array $data) {
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
