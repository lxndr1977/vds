<?php

namespace App\Filament\Resources\Registrations\RelationManagers;

use App\Models\Member;
use App\Models\MemberType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Equipe Diretiva';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('member_type_id')
                    ->label('Função')
                    ->options(MemberType::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modelLabel('Membro')
            ->pluralModelLabel('Membros')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('memberType.name')
                    ->label('Função')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data) {
                        $data['school_id'] = $this->getOwnerRecord()->school_id;

                        $service = new \App\Services\MemberValidationService;
                        $validation = $service->validateMemberTypeLimit(
                            $data['school_id'],
                            $data['member_type_id']
                        );

                        if (! $validation['valid']) {
                            \Filament\Notifications\Notification::make()
                                ->title('Atenção ao limite de membros')
                                ->body($validation['message'])
                                ->danger()
                                ->duration(5000)
                                ->send();

                            throw new \Filament\Support\Exceptions\Halt;
                        }

                        // Cria o membro
                        return Member::create($data);
                    })
                    ->successNotificationTitle('Membro adicionado com sucesso!')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('member_type_id')
                            ->relationship('memberType', 'name')
                            ->required()
                            ->label('Tipo de Membro'),
                    ]),
            ])
            ->actions([
              EditAction::make()
                    ->using(function (Member $record, array $data) {
                        $data['school_id'] = $this->getOwnerRecord()->school_id;

                        $service = new \App\Services\MemberValidationService;
                        $validation = $service->validateMemberTypeLimit(
                            $data['school_id'],
                            $data['member_type_id'],
                            $record->id
                        );

                        if (! $validation['valid']) {
                            \Filament\Notifications\Notification::make()
                                ->title('Atenção ao limite de membros')
                                ->body($validation['message'])
                                ->danger()
                                ->duration(5000)
                                ->send();

                            throw new \Filament\Support\Exceptions\Halt;
                        }

                        $record->update($data);

                        return $record;
                    })
                    ->successNotificationTitle('Membro atualizado com sucesso!')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('member_type_id')
                            ->relationship('memberType', 'name')
                            ->required()
                            ->label('Tipo de Membro'),
                    ]),

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
