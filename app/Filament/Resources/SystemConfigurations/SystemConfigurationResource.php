<?php

namespace App\Filament\Resources\SystemConfigurations;

use App\Filament\Resources\SystemConfigurations\Pages\ManageSystemConfiguration;
use App\Filament\Resources\SystemConfigurations\Schemas\SystemConfigurationForm;
use App\Models\SystemConfiguration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SystemConfigurationResource extends Resource
{
    protected static ?string $model = SystemConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Configurações do Sistema';

    protected static ?string $modelLabel = 'Configuração do Sistema';

    protected static ?string $pluralModelLabel = 'Configurações do Sistema';

    public static function form(Schema $schema): Schema
    {
        return SystemConfigurationForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSystemConfiguration::route('/'),
        ];
    }
}
