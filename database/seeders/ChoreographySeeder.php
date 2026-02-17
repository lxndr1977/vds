<?php

namespace Database\Seeders;

use App\Models\ChoreographyExtraFee;
use Illuminate\Database\Seeder;

class ChoreographySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChoreographyExtraFee::create([
            'description' => 'Taxa de inscrição por coreografia',
            'value' => 25.00,
            'active' => true,
        ]);

        ChoreographyExtraFee::create([
            'description' => 'Taxa de julgamento por coreografia',
            'value' => 15.00,
            'active' => true,
        ]);

        ChoreographyExtraFee::create([
            'description' => 'Taxa de certificado por coreografia',
            'value' => 10.00,
            'active' => true,
        ]);
    }
}
