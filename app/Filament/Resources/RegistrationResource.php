<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Registration;
use Filament\Resources\Resource;
use App\Enums\RegistrationStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Inscrição';

    protected static ?string $modelLabel = 'Inscrição';

    protected static ?string $pluralLabel = 'Inscrições';

    protected static ?string $pluralModelLabel = 'Inscrições';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('school_id')
                     ->label('Nome do Grupo/Escola/Cia')
                     ->relationship('school', 'name')
                     ->disabled()
                     ->required(),
                Forms\Components\Select::make('status_registration')
                  ->label('Status')
                  ->options(RegistrationStatusEnum::class)
                    ->required(),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school.name')
                  ->label('Nome do Grupo/Escola/Cia')
                  ->label('Grupo/Escola/Cia')
                  ->searchable()
                  ->sortable(),
                Tables\Columns\TextColumn::make('status_registration')
                     ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                     ->label('Criada em')->label('Criada em')
                    ->dateTime()
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                     ->label('Atualizada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_details')
            ->label('Ver Detalhes')
            ->icon('heroicon-o-eye')
            ->url(fn (Registration $record): string => RegistrationResource::getUrl('view', ['record' => $record]))
            ->openUrlInNewTab(false),
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
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
            'view' => Pages\ViewRegistrationDetails::route('/{record}/view'), // Nova página
        ];
    }
}
