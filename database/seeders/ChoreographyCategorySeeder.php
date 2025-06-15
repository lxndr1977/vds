<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChoreographyCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
