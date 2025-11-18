<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler d'autres seeders si tu en as
        // $this->call(UserSeeder::class);
        // $this->call(SalleSeeder::class);

        // Exemple rapide : créer un utilisateur test
        \App\Models\User::factory()->create([
            'name' => 'Enseignant Test',
            'email' => 'teacher@test.com',
            'role' => 'teacher',
            'password' => bcrypt('password'),
        ]);
    }
}
