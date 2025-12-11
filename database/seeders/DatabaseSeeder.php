<?php

namespace Database\Seeders;

use App\Models\Fuvarozo;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fuvarozo::factory(10)->create();

        Fuvarozo::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nev' => 'Admin User',
                'jelszo' => 'password',
                'szerepkor' => 'admin',
            ]
        );

        Fuvarozo::firstOrCreate(
            ['email' => 'fuvarozo@example.com'],
            [
                'nev' => 'Fuvarozó User',
                'jelszo' => 'password',
                'szerepkor' => 'fuvarozo',
            ]
        );

        $this->call([
            MunkaSeeder::class,
        ]);
    }
}
