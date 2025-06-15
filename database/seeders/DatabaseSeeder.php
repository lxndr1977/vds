<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'ale@ale.com',
            'password' => Hash::make('alessandra'),
            'role' => UserRoleEnum::User
        ]);

         User::factory()->create([
            'name' => 'Alexandre',
            'email' => 'alexandre@alexandre.com.br',
            'password' => Hash::make('09111977'),
            'role' => UserRoleEnum::Admin
        ]);

       $this->call([
            ChoreographyTypeSeeder::class,
            MemberTypeSeeder::class,
            DanceStyleSeeder::class,
            ChoreographyCategorySeeder::class,
            ChoreographyExtraFeeSeeder::class,        
        ]);
    }
}
