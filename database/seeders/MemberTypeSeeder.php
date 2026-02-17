<?php

namespace Database\Seeders;

use App\Models\MemberFee;
use App\Models\MemberType;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Coordenador',
                'description' => 'Coordenador de atividades',
                'fee' => 50.00,
                'max_limit' => 3,
            ],
            [
                'name' => 'Diretor',
                'description' => 'Diretor da escola',
                'fee' => 30.00,
                'max_limit' => 1,
            ],
        ];

        foreach ($types as $typeData) {
            $type = MemberType::firstOrCreate([
                'name' => $typeData['name'],
                'description' => $typeData['description'],
                'max_limit' => $typeData['max_limit'],
            ]);

            MemberFee::create([
                'member_type_id' => $type->id,
                'amount' => $typeData['fee'],
                'valid_until' => now()->addYear(),
            ]);
        }
    }
}
