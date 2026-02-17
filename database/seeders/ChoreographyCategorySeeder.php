<?php

namespace Database\Seeders;

use App\Models\ChoreographyCategory;
use Illuminate\Database\Seeder;

class ChoreographyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pré-infantil'],
            ['name' => 'Infantil'],
            ['name' => 'Infanto'],
            ['name' => 'Juvenil'],
            ['name' => 'Adulto'],
            ['name' => 'Composição Livre'],
            ['name' => 'Avançado'],
            ['name' => 'Master'],
            ['name' => 'Profissional'],
        ];

        foreach ($categories as $categoryData) {
            ChoreographyCategory::create([
                'name' => $categoryData['name'],
            ]);
        }
    }
}
