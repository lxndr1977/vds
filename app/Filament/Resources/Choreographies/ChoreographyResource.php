<?php

namespace App\Filament\Resources\Choreographies;

use App\Filament\Resources\Choreographies\Pages\CreateChoreography;
use App\Filament\Resources\Choreographies\Pages\EditChoreography;
use App\Filament\Resources\Choreographies\Pages\ListChoreographies;
use App\Filament\Resources\Choreographies\Schemas\ChoreographyForm;
use App\Filament\Resources\Choreographies\Tables\ChoreographiesTable;
use App\Models\Choreography;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChoreographyResource extends Resource
{
    protected static ?string $model = Choreography::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ChoreographyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChoreographiesTable::configure($table);
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
            'index' => ListChoreographies::route('/'),
            'create' => CreateChoreography::route('/create'),
            'edit' => EditChoreography::route('/{record}/edit'),
        ];
    }
}
