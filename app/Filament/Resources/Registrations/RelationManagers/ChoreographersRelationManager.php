<?php

namespace App\Filament\Resources\Registrations\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Choreographer;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconSize;

class ChoreographersRelationManager extends RelationManager
{
   protected static string $relationship = 'Choreographers';

   protected static ?string $title = "Coreógrafos";

   public function form(Schema $form): Schema
   {
      return $form
         ->schema([
            Grid::make(3)
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
               ->size(IconSize::Medium),

            Tables\Columns\IconColumn::make('is_public_domain')
               ->label('Domínio Público')
               ->boolean()
               ->size(IconSize::Medium),

            Tables\Columns\IconColumn::make('is_adaptation')
               ->label('Adaptação')
               ->boolean()
               ->size(IconSize::Medium),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            CreateAction::make()
               ->using(function (array $data) {
                  $data['school_id'] = $this->getOwnerRecord()->school_id;

                  return Choreographer::create($data);
               }),
         ])
         ->actions([
            EditAction::make()
               ->using(function (Choreographer $record, array $data) {
                  $record->update($data);

                  return $record;
               }),

            DeleteAction::make(),
         ])
         ->bulkActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ])
         ->defaultSort('name', 'asc');
   }
}
