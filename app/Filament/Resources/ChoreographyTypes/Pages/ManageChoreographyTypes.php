<?php

namespace App\Filament\Resources\ChoreographyTypes\Pages;

use App\Filament\Resources\ChoreographyTypes\ChoreographyTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageChoreographyTypes extends ManageRecords
{
    protected static string $resource = ChoreographyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
