<?php

namespace App\Filament\Resources\Registrations;

use App\Enums\RegistrationStatusEnum;
use App\Filament\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Resources\Registrations\Pages\ManageChoreographies;
use App\Filament\Resources\Registrations\Pages\ViewRegistrationDetails;
use App\Filament\Resources\Registrations\RelationManagers\ChoreographersRelationManager;
use App\Filament\Resources\Registrations\RelationManagers\ChoreographiesRelationManager;
use App\Filament\Resources\Registrations\RelationManagers\DancersRelationManager;
use App\Filament\Resources\Registrations\RelationManagers\MembersRelationManager;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    // protected static ?string $navigationIcon = 'heroicon-o-identification';

    // protected static ?string $label = 'Inscrição';

    // protected static ?string $pluralLabel = 'Inscrições';

    // protected static ?string $pluralModelLabel = 'Inscrições';

    protected static ?string $recordTitleAttribute = 'school.name';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informações da Inscrição')
                    ->schema([
                        Fieldset::make('Informações de contato do Grupo/Escola/Cia')
                            ->relationship('school')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('name')
                                        ->label('Nome do Grupo/Escola/Cia')
                                        ->required()
                                        ->columnSpanFull(),

                                    TextInput::make('street')
                                        ->label('Rua')
                                        ->columnSpan(2),

                                    TextInput::make('number')
                                        ->label('Número')
                                        ->maxLength(10),

                                    TextInput::make('complement')
                                        ->label('Complemento')
                                        ->columnSpan(1),

                                    TextInput::make('district')
                                        ->label('Bairro')
                                        ->columnSpan(1),

                                    TextInput::make('city')
                                        ->label('Cidade')
                                        ->columnSpan(1),

                                    TextInput::make('state')
                                        ->label('Estado')
                                        ->maxLength(2)
                                        ->columnSpan(1),

                                    TextInput::make('zip_code')
                                        ->label('CEP')
                                        ->mask('99999-999')
                                        ->maxLength(9)
                                        ->columnSpan(1),

                                    TextInput::make('responsible_name')
                                        ->label('Responsável')
                                        ->columnSpanFull(),

                                    TextInput::make('responsible_email')
                                        ->label('E-mail do Responsável')
                                        ->email()
                                        ->columnSpan(2),

                                    TextInput::make('responsible_phone')
                                        ->label('Telefone do Responsável')
                                        ->tel()
                                        ->columnSpan(1),
                                ]),
                            ]),

                        Forms\Components\Select::make('status_registration')
                            ->label('Status da Inscrição')
                            ->options(RegistrationStatusEnum::class)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modelLabel('Inscrição')
            ->pluralModelLabel('Inscrições')
            ->columns([
                TextColumn::make('school.name')
                    ->label('Nome do Grupo/Escola/Cia')
                    ->label('Grupo/Escola/Cia')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_registration')
                    ->label('Status'),

                TextColumn::make('created_at')
                    ->label('Criada em')->label('Criada em')
                    ->dateTime()
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Atualizada em')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                //
                SelectFilter::make('status_registration')
                    ->label('Status')
                    ->options(RegistrationStatusEnum::class),
            ])
            ->actions([
                EditAction::make(),
                Action::make('view_details')
                    ->label('Ver Detalhes')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Registration $record): string => RegistrationResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class,
            ChoreographersRelationManager::class,
            DancersRelationManager::class,
            ChoreographiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'edit' => EditRegistration::route('/{record}/edit'),
            'view' => ViewRegistrationDetails::route('/{record}/view'),
            'choreographies' => ManageChoreographies::route('/{record}/choreographies'),
        ];
    }
}
