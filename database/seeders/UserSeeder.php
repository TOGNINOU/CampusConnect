<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // admin (existing demo)
        User::firstOrCreate([
            'email' => 'admin@campus.local',
        ], [
            'name' => 'Admin Demo',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // teacher
        User::firstOrCreate([
            'email' => 'teacher@campus.local',
        ], [
            'name' => 'Enseignant Demo',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // student
        User::firstOrCreate([
            'email' => 'student@campus.local',
        ], [
            'name' => 'Etudiant Demo',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // developer admin (stable dev credentials) - useful after migrate:fresh --seed
        User::firstOrCreate([
            'email' => 'admin@example.test',
        ], [
            'name' => 'Dev Admin',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
    }
}
