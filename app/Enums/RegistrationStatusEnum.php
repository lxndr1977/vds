<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RegistrationStatusEnum: string implements HasLabel
{
    case Draft = 'draft';
    case Finished = 'finished';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Finished => 'Finalizada',
            self::Cancelled => 'Cancelada',
        };
    }
}
