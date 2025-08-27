<?php

namespace App\Filament\Resources;

use App\Enums\UserRoleEnum;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
   protected static ?string $model = User::class;

   protected static ?string $navigationIcon = 'heroicon-o-users';

   protected static ?string $label = 'Usuários';


   public static function form(Form $form): Form
   {
      return $form
         ->schema([
            Forms\Components\TextInput::make('name')
               ->label('Nome do usuário')
               ->required()
               ->maxLength(255),

            Forms\Components\TextInput::make('email')
               ->label('Email')
               ->email()
               ->required()
               ->maxLength(255),

            Forms\Components\DateTimePicker::make('email_verified_at'),

            Forms\Components\TextInput::make('password')
               ->label('Senha')
               ->password()
               ->required()
               ->maxLength(255)
               ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
               ->dehydrated(fn(?string $state): bool => filled($state))
               ->required(fn(string $operation): bool => $operation === 'create'),

            Forms\Components\Select::make('role')
               ->label('Função')
               ->options(UserRoleEnum::class)
               ->required(),
         ]);
   }

   public static function table(Table $table): Table
   {
      return $table
         ->modelLabel('Usuário')
         ->pluralModelLabel('Usuários')
         ->columns([
            Tables\Columns\TextColumn::make('name')
               ->searchable(),
            Tables\Columns\TextColumn::make('email')
               ->searchable(),
            Tables\Columns\TextColumn::make('email_verified_at')
               ->dateTime()
               ->sortable(),
            Tables\Columns\TextColumn::make('role'),
            Tables\Columns\TextColumn::make('created_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
            //
         ])
         ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
         ])
         ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
               Tables\Actions\DeleteBulkAction::make(),
            ]),
         ]);
   }

   public static function getRelations(): array
   {
      return [
         //
      ];
   }

   public static function getPages(): array
   {
      return [
         'index' => Pages\ListUsers::route('/'),
         'create' => Pages\CreateUser::route('/create'),
         'view' => Pages\ViewUser::route('/{record}'),
         'edit' => Pages\EditUser::route('/{record}/edit'),
      ];
   }
}
