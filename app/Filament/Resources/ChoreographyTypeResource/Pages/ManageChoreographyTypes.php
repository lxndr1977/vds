<?php

namespace App\Filament\Resources\ChoreographyTypeResource\Pages;

use App\Filament\Resources\ChoreographyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageChoreographyTypes extends ManageRecords
{
    protected static string $resource = ChoreographyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
