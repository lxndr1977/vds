<?php

namespace Tests\Feature;

use App\Filament\Resources\Registrations\Pages\CreateRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_registration_with_school_and_user_via_wizard(): void
    {
        $user = User::factory()->create(['role' => \App\Enums\UserRoleEnum::Admin->value]);
        $responsibleUser = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateRegistration::class)
            // Step 1: Instituição e Responsável
            ->fillForm([
                'school.name' => 'Test School',
                'school.user_id' => $responsibleUser->id,
                'school.responsible_name' => 'Responsible Person',
                'school.responsible_email' => 'resp@example.com',
                'school.responsible_phone' => '123456789',
            ])
            ->call('callSchemaComponentMethod', 'wizard', 'nextStep', ['currentStepIndex' => 0])
            // Step 2: Dados da Inscrição (Status should be Draft by default)
            ->assertFormSet([
                'status_registration' => \App\Enums\RegistrationStatusEnum::Draft,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('schools', [
            'name' => 'Test School',
            'user_id' => $responsibleUser->id,
        ]);

        $this->assertDatabaseHas('registrations', [
            'status_registration' => \App\Enums\RegistrationStatusEnum::Draft->value,
        ]);
    }
}
