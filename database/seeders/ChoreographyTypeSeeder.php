<?php

namespace Database\Seeders;

use App\Models\ChoreographyFee;
use Illuminate\Database\Seeder;
use App\Models\ChoreographyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChoreographyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Solo',
                'description' => 'Apresentação individual',
                'min_dancers' => 1,
                'max_dancers' => 1,
                'fee' => 50.00,
            ], 
            [
                'name' => 'Duo',
                'description' => 'Apresentação em dupla',
                'min_dancers' => 2,
                'max_dancers' => 2,
                'fee' => 40.00,
            ],
            [
                'name' => 'Trio',
                'description' => 'Apresentação em trio',
                'min_dancers' => 3,
                'max_dancers' => 3,
                'fee' => 35.00,
            ],
            [
                'name' => 'Grupo - 4 a 7 bailarinos(as)',
                'description' => 'De 4 a 7 bailarinos(as)',
                'min_dancers' => 4,
                'max_dancers' => 7,
                'fee' => 30.00,
            ],
            [
                'name' => 'Conjunto - Acima de 7 bailarinos(as)',
                'min_dancers' => 8,
                'max_dancers' => 100,
                'description' => 'Acima de 7 bailarinos(as)',
                'fee' => 25.00,
            ],
        ];

        foreach ($types as $typeData) {
            $type = ChoreographyType::firstOrCreate([
                'name' => $typeData['name'],
                'min_dancers' => $typeData['min_dancers'],
                'max_dancers' => $typeData['max_dancers'],

            ], [
                'description' => $typeData['description'] ?? null,
            ]);

            ChoreographyFee::create([
                'choreography_type_id' => $type->id,
                'amount' => $typeData['fee'],
                'valid_until' => now()->addYear(),
            ]);
        }
    }
}
