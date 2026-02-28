<?php

namespace Tests\Unit;

use App\Models\SystemConfiguration;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SystemConfigurationDateTest extends TestCase
{
    public function test_registrations_open_on_day_before_and_on_end_date_and_closed_after()
    {
        $config = SystemConfiguration::factory()->make([
            'registration_start_date' => Carbon::parse('2026-02-01'),
            'registration_end_date' => Carbon::parse('2026-02-28'),
        ]);

        Carbon::setTestNow(Carbon::parse('2026-02-27 10:00:00'));
        $this->assertTrue($config->registrations_open_to_public, 'Should be open on 2026-02-27');

        Carbon::setTestNow(Carbon::parse('2026-02-28 23:59:59'));
        $this->assertTrue($config->registrations_open_to_public, 'Should still be open on the end date 2026-02-28');

        Carbon::setTestNow(Carbon::parse('2026-03-01 00:00:00'));
        $this->assertFalse($config->registrations_open_to_public, 'Should be closed after 2026-02-28');

        Carbon::setTestNow();
    }
}
