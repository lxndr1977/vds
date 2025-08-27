<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\DanceStyle;
use Filament\Tables\Table;
use App\Models\Choreography;
use Filament\Resources\Resource;
use App\Models\ChoreographyCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChoreographyResource\Pages;
use App\Filament\Resources\ChoreographyResource\RelationManagers;
use App\Filament\Resources\ChoreographyResource\RelationManagers\DancersRelationManager;
use App\Filament\Resources\ChoreographyResource\RelationManagers\ChoreographersRelationManager;

class ChoreographyResource extends Resource
{
   protected static ?string $model = Choreography::class;

   protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static ?string $recordTitleAttribute = 'name';

   protected static ?string $label = 'Coreografia';

   protected static ?string $pluralLabel = 'Coreografias';

   protected static ?string $pluralModelLabel = 'Coreografias';

   public static function form(Form $form): Form
   {
      return $form
         ->schema([
            Forms\Components\Section::make('Informações da Escola')
               ->schema([
                  Forms\Components\Select::make('school_id')
                     ->label('Escola')
                     ->relationship('school', 'name')
                     ->placeholder('Selecione uma escola')
                     ->required()
                     ->columnSpan(2)
                     ->disabled(fn(string $operation): bool => $operation === 'edit'),
               ])
               ->columns(2)
               ->collapsible(),

            Forms\Components\Section::make('Informações da Apresentação')
               ->schema([
                  Forms\Components\TextInput::make('name')
                     ->label('Nome da Coreografia')
                     ->placeholder('Digite o nome da coreografia')
                     ->required()
                     ->maxLength(255)
                     ->columnSpan(2),

                  Forms\Components\TextInput::make('music')
                     ->label('Música')
                     ->placeholder('Digite o nome da música')
                     ->required()
                     ->maxLength(255),

                  Forms\Components\TextInput::make('music_composer')
                     ->label('Compositor')
                     ->placeholder('Digite o nome do compositor')
                     ->required()
                     ->maxLength(255),

                  Forms\Components\TextInput::make('duration')
                     ->label('Duração')
                     ->placeholder('00:00')
                     ->helperText('Formato: MM:SS')
                     ->required()
                     ->maxLength(5)
                     ->mask('99:99'),
               ])
               ->columns(2)
               ->collapsible(),

            Forms\Components\Section::make('Detalhes da Coreografia')
               ->schema([
                  Forms\Components\Select::make('choreography_type_id')
                     ->label('Formação')
                     ->relationship('choreographyType', 'name')
                     ->placeholder('Selecione o tipo')
                     ->required(),

                  Forms\Components\Select::make('choreography_category_id')
                     ->label('Categoria')
                     ->placeholder('Selecione a categoria')
                     ->required()
                     ->options(ChoreographyCategory::pluck('name', 'id')),

                  Forms\Components\Select::make('dance_style_id')
                     ->label('Modalidade')
                     ->placeholder('Selecione o estilo')
                     ->required()
                     ->options(DanceStyle::pluck('name', 'id'))
               ])
               ->columns(3)
               ->collapsible(),

            Forms\Components\Section::make('Classificação do Projeto')
               ->schema([
                  Forms\Components\Toggle::make('is_social_project')
                     ->label('Projeto Social')
                     ->helperText('Marque se este é um projeto social')
                     ->inline(false),

                  Forms\Components\Toggle::make('is_university_project')
                     ->label('Projeto Universitário')
                     ->helperText('Marque se este é um projeto universitário')
                     ->inline(false),
               ])
               ->columns(2)
               ->collapsible(),
         ]);
   }

   public static function table(Table $table): Table
   {
      return $table
         ->columns([
            Tables\Columns\TextColumn::make('school.name')
               ->label('Grupo/Escola/Cia')
               ->searchable()
               ->sortable(),

            Tables\Columns\TextColumn::make('choreographyType.name')
               ->label('Formação')
               ->searchable()
               ->sortable()
               ->badge(),

            Tables\Columns\TextColumn::make('name')
               ->label('Nome da Coreografia')
               ->searchable()
               ->sortable()
               ->weight('medium')
               ->limit(30),


            Tables\Columns\TextColumn::make('created_at')
               ->label('Criado em')
               ->dateTime('d/m/Y H:i')
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true)
               ->icon('heroicon-o-calendar')
               ->tooltip('Data de criação do registro'),

            Tables\Columns\TextColumn::make('updated_at')
               ->label('Atualizado em')
               ->dateTime('d/m/Y H:i')
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true)
               ->icon('heroicon-o-pencil')
               ->tooltip('Data da última atualização'),
         ])
         ->filters([
            Tables\Filters\SelectFilter::make('school_id')
               ->label('Escola')
               ->relationship('school', 'name')
               ->placeholder('Filtrar por escola')
               ->multiple(),

            Tables\Filters\SelectFilter::make('choreography_type_id')
               ->label('Tipo de Coreografia')
               ->relationship('choreographyType', 'name')
               ->placeholder('Filtrar por tipo'),

            Tables\Filters\TernaryFilter::make('is_social_project')
               ->label('Projeto Social')
               ->placeholder('Todos os projetos')
               ->trueLabel('Apenas projetos sociais')
               ->falseLabel('Apenas não-sociais'),

            Tables\Filters\TernaryFilter::make('is_university_project')
               ->label('Projeto Universitário')
               ->placeholder('Todos os projetos')
               ->trueLabel('Apenas projetos universitários')
               ->falseLabel('Apenas não-universitários'),

            Tables\Filters\Filter::make('created_at')
               ->label('Período de Criação')
               ->form([
                  Forms\Components\DatePicker::make('created_from')
                     ->label('Criado a partir de'),
                  Forms\Components\DatePicker::make('created_until')
                     ->label('Criado até'),
               ])
               ->query(function (Builder $query, array $data): Builder {
                  return $query
                     ->when(
                        $data['created_from'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                     )
                     ->when(
                        $data['created_until'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                     );
               })
         ])
         ->actions([
            Tables\Actions\ViewAction::make()
               ->label('Visualizar'),
            Tables\Actions\EditAction::make()
               ->label('Editar'),
            Tables\Actions\DeleteAction::make()
               ->label('Excluir')
               ->requiresConfirmation()
               ->modalHeading('Excluir Coreografia')
               ->modalDescription('Tem certeza que deseja excluir esta coreografia? Esta ação não pode ser desfeita.')
               ->modalSubmitActionLabel('Sim, excluir'),
         ])
         ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
               Tables\Actions\DeleteBulkAction::make()
                  ->label('Excluir selecionados')
                  ->requiresConfirmation()
                  ->modalHeading('Excluir Coreografias')
                  ->modalDescription('Tem certeza que deseja excluir as coreografias selecionadas? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, excluir todas'),
            ]),
         ])
         ->emptyStateHeading('Nenhuma coreografia encontrada')
         ->emptyStateDescription('Comece criando sua primeira coreografia.')
         ->emptyStateIcon('heroicon-o-musical-note')
         ->defaultSort('created_at', 'desc');
   }

   public static function getRelations(): array
   {
      return [
         //
         DancersRelationManager::class,
         ChoreographersRelationManager::class,
      ];
   }

   public static function getPages(): array
   {
      return [
         'index' => Pages\ListChoreographies::route('/'),
         'create' => Pages\CreateChoreography::route('/create'),
         'edit' => Pages\EditChoreography::route('/{record}/edit'),
      ];
   }
}
