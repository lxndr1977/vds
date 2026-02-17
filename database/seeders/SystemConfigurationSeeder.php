<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemConfiguration::create([
            'festival_name' => 'Festival de Dança',
            'registration_start_date' => now(),
            'registration_end_date' => now()->addDays(30),
            'logo_path' => null,
            'primary_color' => '#3B82F6',
            'secondary_color' => '#8B5CF6',
            'text_color' => '#1F2937',
            'allow_edit_after_submit' => false,
        ]);
    }
}
