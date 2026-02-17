<?php

namespace App\Filament\Resources\Choreographies\Pages;

use App\Filament\Resources\Choreographies\ChoreographyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChoreographies extends ListRecords
{
    protected static string $resource = ChoreographyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
