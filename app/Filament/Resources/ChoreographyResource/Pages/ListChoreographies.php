<?php

namespace App\Filament\Resources\ChoreographyResource\Pages;

use App\Filament\Resources\ChoreographyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChoreographies extends ListRecords
{
   protected static string $resource = ChoreographyResource::class;

   protected function getHeaderActions(): array
   {
      return [
         Actions\CreateAction::make(),
      ];
   }

   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }
}
