<?php

namespace Database\Seeders;

use App\Models\DanceStyle;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DanceStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $styles = [
            ['name' => 'Jazz'],
            ['name' => 'Ballet de Repertório'],
            ['name' => 'Ballet'],
            ['name' => 'Dança Contemporânea'],
            ['name' => 'Dança Urbana'],
            ['name' => 'Composição Livre'],
            ['name' => 'Dança Oriental'],
            ['name' => 'Folclore e Dança Popular'],
            ['name' => 'Dança de Salão'],
            ['name' => 'Dança Gospel'],
            ['name' => 'Performance'],
            ['name' => 'Gin Dance'],
        ];

        foreach ($styles as $styleData) {
            DanceStyle::create([
                'name' => $styleData['name'],
            ]);
        }
    }
}
