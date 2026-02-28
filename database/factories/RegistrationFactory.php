<?php

namespace Database\Factories;

use App\Enums\RegistrationStatusEnum;
use App\Models\Registration;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'status_registration' => RegistrationStatusEnum::Draft,
            'registration_data' => [],
        ];
    }
}
