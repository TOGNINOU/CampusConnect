<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed demo users, rooms and equipments
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            EquipmentSeeder::class,
            \Database\Seeders\ProjectSeeder::class,
        ]);
    }
}
