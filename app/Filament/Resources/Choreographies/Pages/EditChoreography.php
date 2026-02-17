<?php

namespace App\Filament\Resources\Choreographies\Pages;

use App\Filament\Resources\Choreographies\ChoreographyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChoreography extends EditRecord
{
    protected static string $resource = ChoreographyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
