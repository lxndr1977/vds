<?php

namespace Tests\Unit;

use App\Models\SystemConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registrations_are_open_when_no_dates_defined()
    {
        $config = SystemConfiguration::factory()->create([
            'registration_start_date' => null,
            'registration_end_date' => null,
        ]);

        $this->assertTrue($config->registrations_open_to_public);
    }

    public function test_registrations_are_closed_before_start_date()
    {
        $config = SystemConfiguration::factory()->create([
            'registration_start_date' => now()->addDay(),
            'registration_end_date' => null,
        ]);

        $this->assertFalse($config->registrations_open_to_public);
    }

    public function test_registrations_are_closed_after_end_date()
    {
        $config = SystemConfiguration::factory()->create([
            'registration_start_date' => null,
            'registration_end_date' => now()->subDay(),
        ]);

        $this->assertFalse($config->registrations_open_to_public);
    }

    public function test_registrations_are_open_within_range()
    {
        $config = SystemConfiguration::factory()->create([
            'registration_start_date' => now()->subDay(),
            'registration_end_date' => now()->addDay(),
        ]);

        $this->assertTrue($config->registrations_open_to_public);
    }
}
