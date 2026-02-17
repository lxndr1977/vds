<?php

namespace App\Filament\Resources\ChoreographyCategories\Pages;

use App\Filament\Resources\ChoreographyCategories\ChoreographyCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageChoreographyCategories extends ManageRecords
{
    protected static string $resource = ChoreographyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
