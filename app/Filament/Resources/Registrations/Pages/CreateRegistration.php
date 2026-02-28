<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Wizard\Step;

class CreateRegistration extends CreateRecord
{
    use HasWizard;

    protected static string $resource = RegistrationResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Instituição e Responsável')
                ->description('Dados da escola e usuário responsável')
                ->schema([
                    RegistrationResource::getSchoolFormSchema(),
                ]),
            Step::make('Dados da Inscrição')
                ->description('Status e finalização')
                ->schema([
                    RegistrationResource::getRegistrationStatusFormSchema(),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['school'])) {
            $schoolData = $data['school'];
            unset($data['school']);

            $school = \App\Models\School::create($schoolData);
            $data['school_id'] = $school->id;
        }

        return $data;
    }
}
