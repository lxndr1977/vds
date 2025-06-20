<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRoleEnum: string implements HasLabel
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case User = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Administrador',
            self::Admin => 'Administrador',
            self::User => 'Usuário',
        };
    }
}
