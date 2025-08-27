<?php

namespace App\Filament\Resources\ChoreographyResource\Pages;

use App\Filament\Resources\ChoreographyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChoreography extends EditRecord
{
   protected static string $resource = ChoreographyResource::class;

   protected function getHeaderActions(): array
   {
      return [
         Actions\DeleteAction::make(),
      ];
   }

   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }
}
