<?php

namespace App\Filament\Resources\SystemConfigurations\Pages;

use App\Filament\Resources\SystemConfigurations\SystemConfigurationResource;
use App\Models\SystemConfiguration;
use Filament\Resources\Pages\EditRecord;

class ManageSystemConfiguration extends EditRecord
{
    protected static string $resource = SystemConfigurationResource::class;

    public function mount(int|string|null $record = null): void
    {
        // Get or create the singleton configuration record
        $config = SystemConfiguration::firstOrCreate(
            [],
            [
                'festival_name' => 'Festival de Dança',
                'allow_edit_after_submit' => false,
            ]
        );

        // Call parent mount with the config ID
        parent::mount($config->id);
    }

    protected function getHeaderActions(): array
    {
        // Remove delete action for singleton
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        // Stay on the same page after save
        return null;
    }
}
