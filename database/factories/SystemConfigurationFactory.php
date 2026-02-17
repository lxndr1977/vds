<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemConfiguration>
 */
class SystemConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'festival_name' => fake()->words(3, true).' Festival',
            'registration_start_date' => now(),
            'registration_end_date' => now()->addDays(30),
            'logo_path' => null,
            'primary_color' => fake()->hexColor(),
            'secondary_color' => fake()->hexColor(),
            'text_color' => fake()->hexColor(),
            'allow_edit_after_submit' => fake()->boolean(),
        ];
    }
}
