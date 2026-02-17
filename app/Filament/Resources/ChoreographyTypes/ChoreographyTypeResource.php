<?php

namespace App\Filament\Resources\ChoreographyTypes;

use App\Filament\Resources\ChoreographyTypes\Pages\ManageChoreographyTypes;
use App\Models\ChoreographyType;
use BackedEnum;
use Faker\Core\Color;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChoreographyTypeResource extends Resource
{
    protected static ?string $model = ChoreographyType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Tipo de Coreografia';

    protected static ?string $pluralModelLabel = 'Tipos de Coreografias';

    protected static bool $hasTitleCaseModelLabel = false;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('description')
                    ->label('Descrição'),
                TextInput::make('min_dancers')
                    ->label('Mínimo de Dançarinos')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->live()
                    ->rules([
                        fn(Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $maxDancers = $get('max_dancers');
                            if ($maxDancers && $value > $maxDancers) {
                                $fail('O mínimo de dançarinos deve ser menor ou igual ao máximo de dançarinos.');
                            }
                        },
                    ]),
                TextInput::make('max_dancers')
                    ->label('Máximo de Dançarinos')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->live()
                    ->rules([
                        fn(Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $minDancers = $get('min_dancers');
                            if ($minDancers && $value < $minDancers) {
                                $fail('O máximo de dançarinos deve ser maior ou igual ao mínimo de dançarinos.');
                            }
                        },
                    ]),
            ]);    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
                TextColumn::make('min_dancers')
                    ->label('Mín. Dançarinos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_dancers')
                    ->label('Máx. Dançarinos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('gray'),
                    DeleteAction::make()
                ])
                    ->label('Ações')
                    ->button()
                    ->color('gray')
                    ->size(Size::Small)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageChoreographyTypes::route('/'),
        ];
    }
}
