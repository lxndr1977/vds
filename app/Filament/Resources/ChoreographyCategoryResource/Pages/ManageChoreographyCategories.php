<?php

namespace App\Filament\Resources\ChoreographyCategoryResource\Pages;

use App\Filament\Resources\ChoreographyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageChoreographyCategories extends ManageRecords
{
    protected static string $resource = ChoreographyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
