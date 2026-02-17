<?php

namespace App\Filament\Resources\DanceStyles\Pages;

use App\Filament\Resources\DanceStyles\DanceStyleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDanceStyles extends ManageRecords
{
    protected static string $resource = DanceStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
