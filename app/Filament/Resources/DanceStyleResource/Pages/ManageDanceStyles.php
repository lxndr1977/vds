<?php

namespace App\Filament\Resources\DanceStyleResource\Pages;

use App\Filament\Resources\DanceStyleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDanceStyles extends ManageRecords
{
    protected static string $resource = DanceStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
