<?php

namespace Tests\Feature;

use App\Filament\Resources\SystemConfigurations\Pages\ManageSystemConfiguration;
use App\Models\SystemConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SystemConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_system_configuration(): void
    {
        $config = SystemConfiguration::factory()->create([
            'festival_name' => 'Festival de Dança 2026',
            'allow_edit_after_submit' => true,
        ]);

        $this->assertDatabaseHas('system_configurations', [
            'festival_name' => 'Festival de Dança 2026',
            'allow_edit_after_submit' => true,
        ]);

        $this->assertEquals('Festival de Dança 2026', $config->festival_name);
        $this->assertTrue($config->allow_edit_after_submit);
    }

    public function test_can_update_system_configuration(): void
    {
        $config = SystemConfiguration::factory()->create([
            'festival_name' => 'Old Name',
        ]);

        $config->update([
            'festival_name' => 'New Festival Name',
            'primary_color' => '#FF5733',
        ]);

        $this->assertDatabaseHas('system_configurations', [
            'festival_name' => 'New Festival Name',
            'primary_color' => '#FF5733',
        ]);
    }

    public function test_registration_dates_are_cast_to_datetime(): void
    {
        $config = SystemConfiguration::factory()->create([
            'registration_start_date' => '2026-03-01 10:00:00',
            'registration_end_date' => '2026-04-01 18:00:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $config->registration_start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $config->registration_end_date);
    }

    public function test_allow_edit_after_submit_is_cast_to_boolean(): void
    {
        $config = SystemConfiguration::factory()->create([
            'allow_edit_after_submit' => 1,
        ]);

        $this->assertIsBool($config->allow_edit_after_submit);
        $this->assertTrue($config->allow_edit_after_submit);
    }

    public function test_manage_page_creates_config_if_not_exists(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount('system_configurations', 0);

        Livewire::actingAs($user)
            ->test(ManageSystemConfiguration::class);

        $this->assertDatabaseCount('system_configurations', 1);
        $this->assertDatabaseHas('system_configurations', [
            'festival_name' => 'Festival de Dança',
            'allow_edit_after_submit' => false,
        ]);
    }

    public function test_manage_page_loads_existing_config(): void
    {
        $user = User::factory()->create();
        $config = SystemConfiguration::factory()->create([
            'festival_name' => 'Existing Festival',
        ]);

        Livewire::actingAs($user)
            ->test(ManageSystemConfiguration::class)
            ->assertFormSet([
                'festival_name' => 'Existing Festival',
            ]);

        // Should not create a second record
        $this->assertDatabaseCount('system_configurations', 1);
    }

    public function test_authenticated_users_can_update_system_configuration(): void
    {
        $user = User::factory()->create();
        SystemConfiguration::factory()->create([
            'festival_name' => 'Old Festival Name',
            'allow_edit_after_submit' => false,
        ]);

        Livewire::actingAs($user)
            ->test(ManageSystemConfiguration::class)
            ->fillForm([
                'festival_name' => 'Updated Festival Name',
                'primary_color' => '#3B82F6',
                'allow_edit_after_submit' => true,
            ])
            ->call('save')
            ->assertNotified();

        $this->assertDatabaseHas('system_configurations', [
            'festival_name' => 'Updated Festival Name',
            'primary_color' => '#3B82F6',
            'allow_edit_after_submit' => true,
        ]);

        // Should still only have one record
        $this->assertDatabaseCount('system_configurations', 1);
    }

    public function test_festival_name_is_required(): void
    {
        $user = User::factory()->create();
        SystemConfiguration::factory()->create();

        Livewire::actingAs($user)
            ->test(ManageSystemConfiguration::class)
            ->fillForm([
                'festival_name' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'festival_name' => 'required',
            ])
            ->assertNotNotified();
    }
}
